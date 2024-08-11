<?php

class FileIntegrity {

    private string $_root;
    private string $_dir;

    private array $_hashes;

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
 public function _getroot():string{
     return $this->_root;
 }

    /**
     * @return string
     */
    public function _getDir(): string
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
    public function _getHashes(): array{
        return $this->_hashes;
    }

    /**
     * @param array $hashes
     * @return void
     */
    public function _setHashes(array $hashes):void{
        $this->_hashes = $hashes;
    }
 public function create():bool{
    $dirname = $this->_getroot();

    if(substr($this->_getroot(), -1) !== '/'){
        $dirname = $dirname . '/';
    }
    $dirname .= $this->_getDir();
    $this->_setHashes(FileHash::GetAll($dirname));
 }


 public function verfiy():bool{
        return true;
 }


}
