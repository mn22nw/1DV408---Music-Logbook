<?php
  namespace view;

  require_once("src/controller/c_signIn.php");
  require_once("src/helper/CookieStorage.php");
  require_once("src/helper/SessionHelper.php");
  require_once("src/helper/FileStorage.php");

  class SignIn {
    private $model;
    private $cookieStorage;
    private $sessionHelper;
	private $fileStorage;
	public static $getAction = "action"; 
	
	//Sign in/out
	public static $actionSignIn = 'signIn';
	public static $actionSignOut = "signOut";
	
    // names for the inputs & buttons used in the html-forms
	private static $uniqueID  = "SignIn::UniqueID";
	private static $signInBtn  = "SignIn:signInBtn";
	private static $rememberUser = "SignIn:Remember";
	private static $username = "SignIn::Username";
	private static $password = "SignIn::Password";


    public function __construct(\model\SignIn $model) {
      $this->model = $model;
      $this->cookieStorage = new \helper\CookieStorage();
	  $this->fileStorage = new \helper\FileStorage();
      $this->sessionHelper = new \helper\SessionHelper(); 
    }
	
		/**
	 * Checks if user has navigated to sign in page
	 * 
	 * @return bolean
	 */
	public static function hasUserChosenSignInpage() {
		if (isset($_GET[self::$getAction])){
			if( $_GET[self::$getAction] == self::$actionSignIn) 
			return true;
		}
		return false;   
	}
		
	public function SignOutAttempt() {  
		if (isset($_GET[self::$getAction])) {
			if($_GET[self::$getAction] == self::$actionSignOut)
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
			return array($_POST[self::$username], $_POST[self::$password], $remember ); //TODO return object
		}
		
		return NULL;
	}
  /**
      * Homepage - a view for users that are not logged in.
      *
      * @return string - Homepage
      */
    public function showHomepage() {
	  $html  = "<div id='homepage'>";
	  $html  = "<div id='startMessage'>";
      $html .= "<h2>Welcome to Music Logbook!</h2>";
	  $html .= "<p>A perfect place to keep track of your <br /> favourite songs and progress!</p>";
	  $html .= "</div>";
	  $html .= "<div class='successMessage'><p>".$this->sessionHelper->getAlert() ."</p></div>";
	  $html .= "<a href='?".self::$getAction."=".NavigationView::$actionSignUp."' id='signUp'>Sign up</a>"; 
	  $html .= "<a href='?".self::$getAction."=".self::$actionSignIn."' id='signIn'>Sign in</a>";  
	  $html .= "<div id='musicbar'><div id='treble'></div><ul>
	  			<li>Keep track of your practicing hours!</li>
	  			<li>Remember all your favourite songs!</li>
	  			<li>Rate your own progress!</li>
	  			<li>Efficiency when playing your instrument!</li>
	  			</ul></div>";  
	  $html .= "</div>";

      return $html;
    }

    /**
      * A view for users that wants to signIn
      *
      * @return string - The page log in page
      */
    public function showSignIn() {
	  $username =  $this->sessionHelper->getCreatedUsername();
	 
	  if (empty($username))
	    $username = empty($_POST[self::$username]) ? '' : $_POST[self::$username];
	 
	  $ret  = "<div id='signInView'>";
      $ret .= "<h2>Sign in</h2>";

      $ret .= "
	  <form action='?" . self::$getAction . "=" . self::$actionSignIn ."' method='post'>";
	  $ret .=  "<input type='text' name='". self::$username . "' placeholder='Username' value='".$username."' maxlength='30'>
	    <input type='password' name='". self::$password. "' placeholder='Password' value='' maxlength='30'>
	    <input type='checkbox' id='". self::$rememberUser. "' name='". self::$rememberUser. "' class='checkbox'>
	    <p>Remember me</p>
	    <input type='submit' value='Sign in' name='". self::$signInBtn. "' id='submit'>
	  </form>"; 
	  $ret .= "<div class='errorMessage'><p>".$this->sessionHelper->getAlert() ."</p></div>";
	  $ret .= "</div>";

      return $ret;
    }

    /**
      * A view for users logged in  // TODO NOT NEEDED!
      *
      * @return string - The page log out page
      */
    public function showSignOut() {  // TODO is this used? eh //TODO - REMOVE USE OF SESSION IN VIEW (not my code!! Had no time to fix this )
      // Get the username either from session or cookie  LOL so fail
      if (isset($_SESSION[self::$username])) {
        $username = $_SESSION[self::$username];
      } else {
      // $username = $this->cookieStorage->getCookieValue(self::$username);  // TODO denna strular tillde
      }

      $ret = "<h2> Welcome " . $username . "</h2>";
      $ret .= "<span class='alert'>" . $this->sessionHelper->getAlert() . "</span> "; // If there are any alerts, show them

      return $ret;
    }
	
	public function SignOut(){
		 if ($this->cookieStorage->isCookieSet(self::$uniqueID)) {
         
		  // Destroy all cookies
          $this->cookieStorage->destroy(self::$uniqueID);
          $this->cookieStorage->destroy(self::$username);
          $this->cookieStorage->destroy(self::$password);
		  
		  // Remove the cookie file
          $this->fileStorage->removeFile($this->cookieStorage->getCookieValue(self::$uniqueID)); 
		return true;
        }
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
    	//TODO need to validate cookie with database?
	// $this->cookieStorage->getCookieValue(self::$username) === $this->getUsernameInput() &&
       // $this->cookieStorage->getCookieValue(self::$password) === $this->sessionHelper->encryptString($this->getPasswordInput()))
  
    if ($this->cookieStorage->isCookieSet(self::$uniqueID)) {
        	
        // Check if uniqid is valid from right browser //TODO set uniqueID is based on browser-detail
        if ($this->cookieStorage->getCookieValue(self::$uniqueID) === $this->sessionHelper->setUniqueID() )
         {
		  
	          // Check if the uniqid cookie is valid and not time-manipulated
	          if (!$this->cookieStorage->isCookieValid($this->cookieStorage->getCookieValue(self::$uniqueID))) {
	          	
	            // Destroy all cookies
	            $this->cookieStorage->destroy(self::$uniqueID);
	            $this->cookieStorage->destroy(self::$username);
	            $this->cookieStorage->destroy(self::$password);
	
	            // Set an alert
	            $this->sessionHelper->setAlert("Felaktig information i cookie.");
	            return false;
	          }
	
	          // All valid and good? Then log in user
	          $this->sessionHelper->setAlert("Inloggning lyckades via cookies."); //TODO remove
	          return true;
			 }
      	   else {
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
