<?php

class FileIntegrity {


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
     * @return bool
     */
 public function init():bool{
     try {
       $this->create();
       return true;
     } catch(Exception $e) {
       $this->_setErrormsg($e->getMessage());
       return false;
     }

 }

    /**
     * @param string $root
     * @return void
     */
 public function _setroot(string $root):void{
     $this->_root = $root;
 }

    /**
     * @return string
     */
 public function _getroot():?string{
     return $this->_root;
 }

    /**
     * @return string
     */
    public function _getDir():?string
    {
        return $this->_dir;
    }

    /**
     * @param string $file
     * @return void
     */
    public function _setDir(string $dir):void{
        $this->_dir = $dir;
    }

    /**
     * @return array
     */
    public function _getHashes():?array{
        return $this->_hashes;
    }

    /**
     * @param array $hashes
     * @return void
     */
    public function _setHashes(array $hashes):void{
        $this->_hashes = $hashes;
    }

    /**
     * @return string|null
     */
    public function _getFilename():?string{
        return $this->_filename;
    }

    /**
     * @param string $filename
     * @return void
     */
    public function _setFilename(string $filename):void{
        $this->_filename = $filename;
    }


    /**
     * @return string|null
     */
    public function _getFileversion():?string{
        return $this->_fileversion;
    }

    /**
     * @param string $version
     * @return void
     */
    public function _setFileversion(string $version):void{
        $this->_fileversion = $version;
    }

    /**
     * @return string|null
     */
    public function _getErrormsg():?string{
        return $this->_errormsg;
    }

    /**
     * @param string $errormsg
     * @return void
     */
    public function _setErrormsg(string $errormsg):void{
        $this->_errormsg = $errormsg;
    }

    /**
     * @return void
     * @throws Exception
     */
 public function create():void{
    $dirname = $this->_getroot();
    if(substr($this->_getroot(), -1) !== '/'){
        $dirname = $dirname . '/';
    }
    $dirname .= $this->_getDir();

    $this->_setFilename($this->FileNameCreate());
    $this->_setHashes(FileHash::GetAll($dirname));

    if(!file_exists($this->_getFilename())){
       if(file_put_contents($this->_getFilename(),json_encode($this->_getHashes())) === false){
           throw new \Exception('Datei kann nicht geschrieben werden.');
       }
    } else {
        throw new \Exception('Datei existiert bereits.');
    }

 }




 public function verfiy():bool{
     $hashes = file_get_contents($this->_getFilename());
        return true;
 }


}
