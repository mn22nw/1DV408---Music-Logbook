<?php
  namespace model;

  require_once("src/helper/FileStorage.php");
  require_once("src/helper/SessionHelper.php");
  require_once('m_UserRepository.php');
  
  class Login {
    private $cookieStorage;
    private $fileStorage;
    private $sessionHelper;
	private $userRepository;
    private static $uniqueID = "Login::UniqueID";
    private static $username = "Login::Username";
	private static $password = "Login::Password";

    public function __construct() {
      $this->cookieStorage = new \helper\CookieStorage();
      $this->fileStorage = new \helper\FileStorage();
	  $this->userRepository = new \model\UserRepository();
      $this->sessionHelper = new \helper\SessionHelper();
    }

 	/**
      * Check if user is logged in with session
	  *   
	  * @return boolval - Either the user is logged in or not
	  */
    public function userIsLoggedIn() {
    	
	 if (isset($_SESSION[self::$uniqueID])) {
        // Check if session is valid
        if ($_SESSION[self::$uniqueID] === $this->sessionHelper->setUniqueID()) {
          return true;
        }

      return false;
     }
      
    }

    /**
      * Log in the user
      *
      * @param string $postUsername
      * @param string $postPassword
      * @param string $postRemember - Whether to remember the user or not
      * @return boolval
      */
    public function logIn($postUsername, $postPassword, $postRemember) {
   
        // Make the inputs safe to use in the code
     	$un = $this->sessionHelper->makeSafe($postUsername);    
    	$pw =  $this->sessionHelper->makeSafe($postPassword);    

	  // If the provided username/password is empty //TODO validate via validation class instead!
      if (empty($postUsername)) {
        $this->sessionHelper->setAlert("Username is missing");
        return false;
      } else if (empty($postPassword)) {
        $this->sessionHelper->setAlert("Password is missing");
        return false;
      }
	  
      // Check against database if the correct username and password is provided
    
		if (!$this->userRepository->find($un, $this->sessionHelper->encryptString($pw))) {
			  $this->sessionHelper->setAlert("Wrong username or/and password");  
			return false;
		}
		
		//sets session for the user
        $_SESSION[self::$uniqueID] = $this->sessionHelper->setUniqueID();
        $_SESSION[self::$username] = $un;
		$_SESSION[self::$password] = $pw;

        // If $postRemember not got a value 
        if (!$postRemember) {
          $this->sessionHelper->setAlert("Inloggning lyckades");
        }
		
	   return true;
    }
    /**
      * Log out the user
      *
      * @return boolval
      */
    public function logOut() {  //TODO (I know this function is against MVC but had no time to fix this more than what I've already done so far)
      // Check if you really are logged in
      if (isset($_SESSION[self::$uniqueID]) OR            // TODO don't use session here 
        $this->cookieStorage->isCookieSet(self::$uniqueID)) {
        unset($_SESSION[self::$uniqueID]);

        if ($this->cookieStorage->isCookieSet(self::$uniqueID)) {  // TODO CHANGE THIS TO VIEW!!!
          // Destroy all cookies
          $this->cookieStorage->destroy(self::$uniqueID);
          $this->cookieStorage->destroy(self::$username);
          $this->cookieStorage->destroy(self::$password);

          // Remove the cookie file
          $this->fileStorage->removeFile($this->cookieStorage->getCookieValue(self::$uniqueID));  
        }

        // Set alert message
        $this->sessionHelper->setAlert("Du har nu loggat ut.");

        return true;
      }
    }
  }
