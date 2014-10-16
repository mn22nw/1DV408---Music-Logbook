<?php
  namespace view;

  require_once("src/controller/c_login.php");
  require_once("src/helper/CookieStorage.php");
  require_once("src/helper/SessionHelper.php");

  class Login {
    private $model;
    private $cookieStorage;
    private $sessionHelper;
	private static $getAction = "action"; 

    private static $getLogin  = "login";
    private static $getLogout = "logout";
	private static $uniqueID  = "Login::UniqueID";
	private static $loginBtn  = "Login:loginBtn";
	private static $rememberUser = "Login:Remember";
	private static $username = "Login::Username";
	private static $password = "Login::Password";


    public function __construct(\model\Login $model) {
      $this->model = $model;
      $this->cookieStorage = new \helper\CookieStorage();
      $this->sessionHelper = new \helper\SessionHelper();
    }
	
	
	public function visitorHasChosenLogin() {
		if (isset($_GET[self::$getAction])) {
			if($_GET[self::$getAction] == "login")
			return true;
		}
		return false;
	}
	
	/**
	 * @return array with formdetails (username, password)
	 */
	public function getFormData() {
		if (isset($_POST[self::$username])) {
			$remember = false;	
			if (isset($_POST[self::$username])) {
			$remember = true;
			}
			return array($_POST[self::$username], $_POST[self::$password], $remember );
		}
		
		return NULL;
	}
  /**
      * Homepage - a view for users that are not logged in.
      *
      * @return string - Homepage
      */
    public function showHomepage() {
	 
      $html = "<h2>Welcome to Music Logbook!</h2>";
	  $html .= "<p>A perfect place to keep track of your favourite songs and progress!</p>";
	  $html .= "<a href='?".NavigationView::$action."=".NavigationView::$actionRegister."' id='signUp'>Sign up</a>"; 
	  $html .= "<a href='?".NavigationView::$action."=".NavigationView::$actionLogin."' id='login'>Login</a>";  

      return $html;
    }

    /**
      * A view for users that wants to login
      *
      * @return string - The page log in page
      */
    public function showLogin() {
	  $username =  $this->sessionHelper->getCreatedUsername();
	 
	  if (empty($username))
	    $username = empty($_POST[self::$username]) ? '' : $_POST[self::$username];
	 
	  $ret  = "<div id='loginView'>";
      $ret .= "<h2>Login</h2>";

      $ret .= "
	  <form action='?" . NavigationView::$action . "=" . NavigationView::$actionLogin ."&".self::$getLogin ."' method='post'>";
	  $ret .=  "<input type='text' name='". self::$username . "' placeholder='Username' value='".$username."' maxlength='30'>
	    <input type='password' name='". self::$password. "' placeholder='Password' value='' maxlength='30'>
	    <input type='checkbox' id='". self::$rememberUser. "' name='". self::$rememberUser. "' class='checkbox'>
	    <p>Remember me</p>
	    <input type='submit' value='Login' name='". self::$loginBtn. "'>
	  </form>"; 
	  $ret .= "<div class='errorMessage'><p>".$this->sessionHelper->getAlert() ."</p></div>";
	  $ret .= "</div>";

      return $ret;
    }

    /**
      * A view for users logged in
      *
      * @return string - The page log out page
      */
    public function showLogout() {   //TODO - REMOVE USE OF SESSION IN VIEW (not my code!! Had no time to fix this )
      // Get the username either from session or cookie
      if (isset($_SESSION[self::$username])) {
        $username = $_SESSION[self::$username];
      } else {
      // $username = $this->cookieStorage->getCookieValue(self::$username);  // TODO denna strular tillde
      }

      $ret = "<h2>" . $username . " är inloggad</h2>";
      $ret .= "<span class='alert'>" . $this->sessionHelper->getAlert() . "</span> "; // If there are any alerts, show them
      $ret .= "<a href='?" . self::$getLogout . "'>Logga ut</a>
      ";

      return $ret;
    }


    public function LogoutAttempt() {
      if (isset($_GET[self::$getLogout]))
        return true;

      return false;
    }
	
	public function rememberUser(){
		if (isset($_POST[self::$rememberUser]))
        return true;

      return false;	
	}	
	
	public function getUsernameInput(){
			return $this->sessionHelper->makeSafe($_POST[self::$username]);
	}
	
	public function getPasswordInput(){
			return $this->sessionHelper->makeSafe($_POST[self::$password]);
	}		
	
	public function setCookies($postRemember) {
	 // Make the inputs safe to use in the code
     	$un = $this->getUsernameInput();   
    	$pw =  $this->getPasswordInput();
		 // If $postRemember got a value then set a cookie
        if ($postRemember) {
        
          $this->cookieStorage->save(self::$uniqueID, $_SESSION[self::$uniqueID], true);
          $this->cookieStorage->save(self::$username, $un);  
          $this->cookieStorage->save(self::$password, $this->sessionHelper->encryptString($pw));

          $this->sessionHelper->setAlert("Inloggning lyckades och vi kommer ihåg dig nästa gång");
        } 
	}
	
    public function checkCookies() {
    if ($this->cookieStorage->isCookieSet(self::$uniqueID)) {
        // Check if cookie is valid
        if ($this->cookieStorage->getCookieValue(self::$uniqueID) === $this->sessionHelper->setUniqueID() &&
          $this->cookieStorage->getCookieValue(self::$username) === $this->getUsernameInput() &&
          $this->cookieStorage->getCookieValue(self::$password) === $this->sessionHelper->encryptString($this->getPasswordInput())) {

          // Check if the uniqid cookie is valid
          if (!$this->cookieStorage->isCookieValid($this->cookieStorage->getCookieValue(self::$uniqueID))) {
            // Destroy all cookies
            $this->cookieStorage->destroy(self::$uniqueID);
            $this->cookieStorage->destroy(self::$username);
            $this->cookieStorage->destroy(self::$password);

            // Set an alert
            $this->sessionHelper->setAlert("Felaktig information i cookie.");
            return false;
          }

          // All valid and good? Then log em in
          $this->sessionHelper->setAlert("Inloggning lyckades via cookies.");
          return true;
        } else {
          // Destroy all cookies
          $this->cookieStorage->destroy(self::$uniqueID);
          $this->cookieStorage->destroy(self::$username);
          $this->cookieStorage->destroy(self::$password);
		  
          // Set an alert
          $this->sessionHelper->setAlert("Felaktig information i cookie.");
          return false;
        }
      } else {
        return false;
      }
     }

  }
