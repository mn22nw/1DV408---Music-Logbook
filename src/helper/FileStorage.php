<?php
  namespace helper;

  class FileStorage {
    private static $path = "db/";

    /**
      * Get the content of the file
      *
      * @param string $filename - The name of the file to save
      * @return string - the content of the file
      * @return boolval - if the file is not found
      */
    public function getFileContent($fileName) {
      if (file_exists(self::$path . $fileName)) {
        return file_get_contents(self::$path . $fileName);
      }

      return false;
    }

    /**
      * Save content to a file
      *
      * @param string $filename - The name of the file
      * @param string $value - The value in the file
      * @return boolval
      */
    public function setFileContent($fileName, $value) {
      file_put_contents(self::$path . $fileName, $value);

      return true;
    }

    /**
      * Removes a file
      *
      * @param string $filename - The name of the file
      * @return boolval
      */
    public function removeFile($fileName) {
      unlink(self::$path . $fileName);

      return true;
    }
  }
