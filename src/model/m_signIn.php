<?php
  namespace model;

  require_once("src/helper/SessionHelper.php");
  require_once('m_userRepository.php');
  
  class SignIn {
  	private $cookieStorage;
    private $sessionHelper;
	private $userRepository;
    private static $uniqueID = "SignIn::UniqueID";
    private static $username = "SignIn::Username";
	private static $password = "SignIn::Password";

    public function __construct() {
      $this->cookieStorage = new \helper\CookieStorage();
	  $this->userRepository = new \model\UserRepository();
      $this->sessionHelper = new \helper\SessionHelper();
    }

 	/**
      * Check if user is signed in with session
	  *   
	  * @return boolval - Either the user is signed in in or not
	  */
    public function userIsSignedIn() {
    	
	 if (isset($_SESSION[self::$uniqueID])) {
        // Check if session is valid
        if ($_SESSION[self::$uniqueID] === $this->sessionHelper->setUniqueID()) {
          return true;
        }

      return false;
     }
      
    }

    /**
      * Sign in the user
      *
      * @param string $postUsername
      * @param string $postPassword
      * @param string $postRemember - Whether to remember the user or not
      * @return boolval
      */
    public function signIn($postUsername, $postPassword, $postRemember) { // TODO kolla igenom!!
   
        // Make the inputs safe to use in the code
     	$un = $this->sessionHelper->makeSafe($postUsername);    
    	$pw =  $this->sessionHelper->makeSafe($postPassword);    

	  // If the provided username/password is empty 
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
          $this->sessionHelper->setAlert("Sign in was successfull!");  // TODO maybe remove?
        }
		
	   return true;
    }
    /**
      * Sign out the user
      *
      * @return boolval
      */
    public function signOut() {  
      
      if (isset($_SESSION[self::$uniqueID])) {
      		unset($_SESSION[self::$uniqueID]);
      return true;
      }
      
      return false;
    }
  }
