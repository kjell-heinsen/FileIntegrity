<?php

class FileHash
{

    /**
     * @param string $directory
     * @param string|null $relative
     * @return arrays
     */
  public static function GetAll(string $directory, ?string $relative = NULL):array
    {
        $directory = rtrim($directory, '/\\') . '/';
        $relative = $relative ? $relative : $directory;
        $preg = preg_quote($relative, '#');
        $files = glob($directory . '*');
        $return = array();

        foreach ($files as $file) {
            if (is_dir($file)) {
                $return = array_merge($return, get_all_file_hashes($file, $relative));
            } else {
                $localpath = preg_replace('#^' . $preg . '#', '', $file);
                $return[$localpath] = md5_file($file);
            }
        }
        return $return;

    }

    /**
     * @param string $filepath
     * @return mixed
     * @throws Exception
     */
  public static function GetSingle(string $filepath):mixed{
      $return = md5_file($filepath);
      if($return === false){
          throw new \Exception('Erstellen des Hashes nicht möglich.');
      }
      return $return;
  }

}

