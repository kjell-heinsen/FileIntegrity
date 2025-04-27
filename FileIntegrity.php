<?php
class FileIntegrity
{

    /**
     * @var string|null
     */
    private ?string $_root = NULL;

    /**
     * @var string|null
     */
    private ?string $_dir = NULL;

    /**
     * @var array|null
     */
    private ?array $_hashes = NULL;

    /**
     * @var string|null
     */
    private ?string $_filename = NULL;

    /**
     * @var string|null
     */
    private ?string $_fileversion = NULL;

    /**
     * @var string|null
     */
    private ?string $_errormsg = NULL;

    /**
     * Konstruktor für die FileIntegrity-Klasse
     *
     * @param string|null $root Wurzelverzeichnis
     * @param string|null $directory Unterverzeichnis
     * @param string|null $fileversion Version der Datei
     */
    public function __construct(?string $root = null, ?string $directory = null, ?string $fileversion = null)
    {
        if ($root !== null) {
            $this->_setRoot($root);
        }
        if ($directory !== null) {
            $this->_setDirectory($directory);
        }
        if ($fileversion !== null) {
            $this->_setFileversion($fileversion);
        }
    }

    /**
     * Initialisiert den Prozess der Hashwert-Erstellung
     *
     * @return bool Erfolg der Operation
     */
    public function init(): bool
    {
        try {
            $this->create();
            return true;
        } catch (\Exception $e) {
            $this->_setErrorMessage($e->getMessage());
            return false;
        }
    }

    /**
     * Erstellt die Hashwerte und speichert sie in einer Datei
     *
     * @throws \Exception Wenn die Datei nicht geschrieben werden kann oder bereits existiert
     */
    public function create(): void
    {
        if (!$this->_root) {
            throw new \Exception('Wurzelverzeichnis nicht gesetzt.');
        }

        if (!$this->_directory) {
            throw new \Exception('Verzeichnis nicht gesetzt.');
        }

        $dirname = rtrim($this->_root, '/') . '/' . $this->_directory;

        if (!is_dir($dirname)) {
            throw new \Exception('Verzeichnis existiert nicht: ' . $dirname);
        }

        $this->_filename = $this->createFilename();
        $this->_hashes = FileHash::GetAll($dirname);

        if (!file_exists($this->_filename)) {
            if (file_put_contents($this->_filename, json_encode($this->_hashes)) === false) {
                throw new \Exception('Datei kann nicht geschrieben werden.');
            }
        } else {
            throw new \Exception('Datei existiert bereits.');
        }
    }

    /**
     * Überprüft die Integrität der Dateien durch Vergleich der Hashwerte
     *
     * @return bool Ob die Überprüfung erfolgreich war
     * @throws \Exception Wenn die Hash-Datei nicht gelesen werden kann oder Dateien verändert wurden
     */
    public function verify(): bool
    {
        if (!$this->_filename || !file_exists($this->_filename)) {
            throw new \Exception('Hash-Datei nicht gefunden.');
        }

        $storedHashes = json_decode(file_get_contents($this->_filename), true);
        if ($storedHashes === null) {
            throw new \Exception('Hash-Datei konnte nicht gelesen werden.');
        }

        $dirname = rtrim($this->_root, '/') . '/' . $this->_directory;
        $currentHashes = FileHash::GetAll($dirname);

        $changedFiles = [];
        foreach ($storedHashes as $file => $hash) {
            if (!isset($currentHashes[$file])) {
                $changedFiles[] = $file . ' (gelöscht)';
            } elseif ($currentHashes[$file] !== $hash) {
                $changedFiles[] = $file . ' (verändert)';
            }
        }

        foreach ($currentHashes as $file => $hash) {
            if (!isset($storedHashes[$file])) {
                $changedFiles[] = $file . ' (neu)';
            }
        }

        if (count($changedFiles) > 0) {
            $this->_setErrorMessage('Dateien wurden verändert: ' . implode(', ', $changedFiles));
            return false;
        }

        return true;
    }

    /**
     * Erzeugt einen Dateinamen für die Hash-Datei
     *
     * @return string Der generierte Dateiname
     */
    private function createFilename(): string
    {
        $version = $this->_fileversion ?? date('YmdHis');
        return rtrim($this->_root, '/') . '/hashes_' . $version . '.json';
    }


    public function _getRoot(): ?string
    {
        return $this->_root;
    }

    public function _setRoot(string $root): void
    {
        $this->_root = $root;
    }

    public function _getDirectory(): ?string
    {
        return $this->_directory;
    }

    public function _setDirectory(string $directory): void
    {
        $this->_directory = $directory;
    }

    public function _getHashes(): ?array
    {
        return $this->_hashes;
    }

    public function _setHashes(array $hashes): void
    {
        $this->_hashes = $hashes;
    }

    public function _getFilename(): ?string
    {
        return $this->_filename;
    }

    public function _setFilename(string $filename): void
    {
        $this->_filename = $filename;
    }

    public function _getFileversion(): ?string
    {
        return $this->_fileversion;
    }

    public function _setFileversion(string $version): void
    {
        $this->_fileversion = $version;
    }

    public function _getErrorMessage(): ?string
    {
        return $this->_errorMessage;
    }

    private function _setErrorMessage(string $errorMessage): void
    {
        $this->_errorMessage = $errorMessage;
    }
}
