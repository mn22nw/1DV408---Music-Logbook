<?php
  namespace controller;

  require_once("src/model/m_login.php");
  require_once("src/view/v_login.php");
  require_once("src/view/v_register.php");
  require_once("src/helper/SessionHelper.php");
   require_once("src/view/v_navigation.php");

  class Login {
    private $model;
    private $view;
    private $sessionHelper;
	private static $username = "Login::Username"; //TODO remove!

    public function __construct() {
      $this->model = new \model\Login();
      $this->view = new \view\Login($this->model);
      $this->sessionHelper = new \helper\SessionHelper();
    }

    public function viewPage() {
    	
      // Check if user is logged in with session or with cookies
      if ($this->model->userIsLoggedIn() || $this->view->checkCookies()) {

        // Check if user pressed log out
        if ($this->view->LogoutAttempt()) {
          // Then log out
          if ($this->model->logOut()) {
            // And then present the login page
            return $this->view->showLogin();
          }
        }

      // Logged in and did not press log out, then show the logout page
      return $this->view->showLogout();
      } 
      
      else {
        // Check if the user did press login
       if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') { 
          $userDetails = $this->view->getFormData();    // gets the name input from the form
        
          //CHECK IN MODEL IF LOGIN IS CORRECT AND SET A SESSION
         if ($this->model->logIn($userDetails[0], $userDetails[1], $userDetails[2])) {
				
			
			// error here might be that session always sets userSession?
			
			// Sets cookies if user wants to be remembered    // 
			$this->view->setCookies($this->view->rememberUser());
			
          // Then show the logout page  
          return $this->view->showLogout();
		 }
		 
        // Else show the login page
      //  throw new \Exception("should be here!");
     // $this->sessionHelper->setAlert("woop");
        return $this->view->showLogin();
      }
    }
	if ($this->view->visitorHasChosenLogin()) {
		return $this->view->showLogin(); 
	}
	
	return $this->view->showHomepage();
  }


 }