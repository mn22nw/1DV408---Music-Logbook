<?php
  namespace helper;

  require_once("src/helper/FileStorage.php");

  class CookieStorage {
    private $fileStorage;

    public function __construct() {
      $this->fileStorage = new \helper\FileStorage();
    }

    /**
      * Checks if the cookie is set.
      *
      * @param string $name - The name of the cookie
      * @return boolval
      */
    public function isCookieSet($name) {
      if (isset($_COOKIE[$name]))
        return true;

      return false;
    }

    /**
      * Get cookie value
      *
      * @param string $name - The name of the cookie
      * @return string - The cookie value
      */
    public function getCookieValue($name) {
      return $_COOKIE[$name];
    }

    /**
      * Checks if a cookie is valid and removes the file if not
      *
      * @param string $name - The name of the cookie
      * @return boolval
      */
    public function isCookieValid($name) {
      if ($this->fileStorage->getFileContent($name) > time()) {
        return true;
      }

      $this->fileStorage->removeFile($name);
      return false;
    }

    /**
      * Save cookie value
      *
      * @param string $name - The name of the cookie
      * @param string $string - The value of the cookie
      * @return boolval
      */
    public function save($name, $string, $uniq = false) {
      $time = time() + 60 * 60 * 24 * 30;

      // If the cookie to save is the uniqid the create a validfile
      if ($uniq)
        $this->fileStorage->setFileContent($string, $time);
	  

      setcookie($name, $string, $time, "/");
	//setcookie($name, $string, $time, "/", $_SERVER["HTTP_HOST"], isset($_SERVER["HTTPS"]), true);
      return true;
    }

    /**
      * Deletes cookie
      *
      * @param string $name - The name of the cookie
      * @return boolval
      */
    public function destroy($name) {
      setcookie($name, "", time() -1, "/");

      return true;
    }
  }
