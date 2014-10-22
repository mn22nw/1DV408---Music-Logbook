<?php
  namespace controller;

  require_once("src/model/m_signIn.php");
  require_once("src/view/v_signIn.php");
  require_once("src/helper/SessionHelper.php");
   require_once("src/view/v_navigation.php");

  class SignIn {
    private $model;
    private $view;
    private $sessionHelper;
	private static $username = "SignIn::Username"; //TODO remove!
	private $signedInStatus;

    public function __construct() {
      $this->model = new \model\SignIn();
      $this->view = new \view\SignIn($this->model);
      $this->sessionHelper = new \helper\SessionHelper();
    }

    public function viewPage() {
    	
	  // Check if user is signed in with session or with cookies
      if ($this->model->userIsSignedIn() || $this->view->checkCookies()) {
			
	        // Check if user pressed sign out
	        if ($this->view->SignOutAttempt()) {
	        	         
			  /* if user was signed in with session, sign out and destroy session (in model)
			   * OR if user was signed in with cookies, sign out and destroy cookies (in view)
			   */
	          if ($this->model->signOut() || $this->view->signOut()) {
	            $this->signedInStatus = false;
				// Set alert message
	       		$this->sessionHelper->setAlert("You have signed out successfully."); 
			  return $this->view->showHomepage();
	          }

        }

      // User is signed in and didn't press sign out
      $this->signedInStatus = true;
	  } 
      
      else {	 
	  		$this->signedInStatus = false;    
		  
		    // if user tried to sign in 
	  		if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	       
		         $userDetails = $this->view->getFormData();    // gets the name input from the form
		        
		          // check (in model) if sign in is correct and set session there  //TODO  use user object instead!
		        if ($this->model->signIn($userDetails[0], $userDetails[1], $userDetails[2])) {
						
					// Sets cookies (in view) if user wants to be remembered   
					$this->view->setCookies($this->view->rememberUser());
					$this->sessionHelper->setUsername($userDetails[0]);
					$this->signedInStatus = true;
					
					// forcing it to continue to switch statement in the navigationcontroller 
					return $this->view->showHomepage();
				 }
				// if user details are wrong / didn't pass validation, let user try again
				else {
					return $this->view->showSignIn();
				}
		    }
			else if ($this->view->hasUserChosenSignInpage()) {

					return $this->view->showSignIn();
				}
		}	
   
	return $this->view->showHomepage();
	}

	public function userIsSignedIn() {
		if ($this->signedInStatus)
			return true;		
		
		return false;
	}
 }