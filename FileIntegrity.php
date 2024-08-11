<?php

class FileIntegrity {

    private ?string $_root = NULL;
    private ?string $_dir = NULL;

    private ?array $_hashes = NULL;

    private ?string $_filename = NULL;

 public function init():bool{

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
        return true;
 }


}
