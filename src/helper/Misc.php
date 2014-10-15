<?php
  namespace helper;

  class Misc {
    private static $sessionAlert = "sessionAlert";
	public static $instrumentID = "instrumentID";    

    /**
      * Get an alert from the session alert system
      * if there are any messages and the deletes it
      * from the session.
      *
      * @return string - The message
      */
    public function getAlert() {
      if (isset($_SESSION[self::$sessionAlert])) {
        $ret = $_SESSION[self::$sessionAlert];
        unset($_SESSION[self::$sessionAlert]);
      } else {
        $ret = "";
      }

      return $ret;
    }

    /**
      * Set an alert to the session alert system
      *
      * @param string $string - The message to save
      * @return boolval
      */
    public function setAlert($string) {
      $_SESSION[self::$sessionAlert] = $string;
      return true;
    }

	public function getInstrumentID() {
      if (isset($_SESSION[self::$instrumentID])) {
        $ret = $_SESSION[self::$instrumentID];
      } else {
        $ret = "";
      }

      return $ret;
    }


	 public function setInstrumentID($string) {
      $_SESSION[self::$instrumentID] = $string;
      return true;
    }
	 
	 public function unsetSession() {
      if (isset($_SESSION[self::$instrumentID])) 
       unset($_SESSION[self::$instrumentID]);
    }


    /**
      * Makes the param safe from html and stuff...
      *
      * @param string $var - The dirty string
      * @return string - The cleaned up string
      */
    public function makeSafe($var) {
      $var = trim($var);
      $var = stripslashes($var);
      $var = htmlentities($var);
      $var = strip_tags($var);

      return $var;
    }

    /**
      * Generate a unique-ish identifier
      *
      * @return string - The identifier encoded in sha1
      */
    public function setUniqueID() {
      return sha1($_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"]);
    }

    /**
      * Encrypts a given string
      *
      * @return string - The identifier encoded in sha1
      */
    public function encryptString($var) {
      return sha1($var);
    }
  }
