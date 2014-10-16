<?php
namespace view;
  class Register {
    private $model;
	private $sessionHelper;
	
	private static $username = "Register::UserName";
	private static $password = "Register::Password";
	private static $repeatPw = "Register::repeatPw";
	private static $registerBtn = "Register::registerBtn";
	private static $getRegister = "register";
	private $errorMessage = "";
	private $unValue = "";

    public function __construct(\model\UserRepository $model) {
      $this->model = $model;
	  $this->sessionHelper = new \helper\SessionHelper();
    }
	
	public function showSignUp () {
		
		$ret = "<h2>Sign Up</h2>";
	
		 $ret .= "<span class='alert'>" . $this->sessionHelper->getAlert() . "</span>";  //TODO ta bort ev strängberoende
	      $ret .= "
	  <form action='?" . self::$getRegister . "' method='post'>
	  	<label for='" . self::$username . "'>Username</label>
	    <input type='text' name='" . self::$username . "' placeholder='Username' value='$this->unValue' maxlength='30'>
	    <br />
	    <label for='" . self::$password . "'>Username</label>
	    <input type='password' name='" . self::$password . "' placeholder='Password' value='' maxlength='30'>
	    <br />
	    <label for='" . self::$repeatPw . "'>Repeat password</label>
	    <input type='password' name='" . self::$repeatPw . "' placeholder='Password' value='' maxlength='30'>
	    <br />
	    <input type='submit' value='Sign up' name='" . self::$registerBtn. "'>
	  </form>
	  <br />";
	  //<a href='index.php'>back</a>";

      return $ret;
	}
	
	public function didUserPressRegister () {
		if (isset($_GET[self::$getRegister]))
    {
        return true;   
	}
		return false;
	}
	
	public function RegisterAttempt() {
		if (isset($_POST[self::$registerBtn]))
			return true;
		return false;
	}
	
	public function getUsernameInput(){
		if($this->RegisterAttempt()) {		
					//makes input safe to use in the code
			return $this->sessionHelper->makeSafe($_POST[self::$username]);
		}
	}
	
	public function getPasswordInput(){
		if($this->RegisterAttempt()) {
			return $this->sessionHelper->makeSafe($_POST[self::$repeatPw]);
		}
	}
		
	public function validateInput() {
	  if ($this->RegisterAttempt()) {
	  						
	  	$un = $_POST[self::$username];
		$pw = $_POST[self::$password];		
	  		
	  	// If the provided username/password is empty
		 if (empty($un)) {
		      $this->errorMessage ="Användarnamnet har för få tecken. Minst 3 tecken. <br /> ";
			
		 }
			
		if (empty($pw)) {
		      $this->errorMessage .= "Lösenordet har för få tecken. Minst 6 tecken. <br />";			
		}
		
		if (isset($un)){
				$this->unValue = strip_tags($un);
			} 
		
		//check for Html tags
		if ($un!= strip_tags($un)) {
   			$this->errorMessage = "Användarnamnet innehåller ogiltiga tecken. <br />";
		}
		
		//check if passwords matches
		if (!empty($_POST[self::$repeatPw])) {
		
			 if(!empty($_POST[self::$password])){
	
				if (!$this->isPasswordMatch()) {	
					$this->errorMessage = "Lösenorden matchar inte.. <br />";
				}	
			 }
		}

		return $this->errorMessage;
	  }
	}
	
	public function isPasswordMatch(){
	    	
			if (strcmp($_POST[self::$password], $_POST[self::$repeatPw]) === 0) 
					return true;

			if(isset($_POST[self::$username])) {
					$this->unValue = $_POST[self::$username];
			 }
	return false;
	
	}
}