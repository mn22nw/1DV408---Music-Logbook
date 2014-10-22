<?php
  namespace controller;

  require_once("src/model/m_UserRepository.php");
  require_once("src/model/m_user.php");
  require_once("src/view/v_signUp.php");
  require_once("src/helper/SessionHelper.php");

  class SignUp{
    private $model;
    private $view;
	private $sessionHelper;

    public function __construct() {
      $this->model = new \model\UserRepository();
      $this->view = new \view\SignUp($this->model);
	  $this->sessionHelper = new \helper\SessionHelper(); 
    }

    public function viewPage() {
    		if($this->view->SignUpAttempt()) {  
    			if ($this->addUser()) { //<--true if user was successfully added
    				\view\NavigationView::RedirectToSignUp();
    			}
			}
      		return $this->view->showSignUp();
	}
	
	public function addUser() {   // addUser() is called in viewPage()

		try {
			$username  = $this->view->getUsernameInput(); 
			$password  = $this->view->getPasswordInput();
			$errorMessage = $this->view->validateInput();
			
			//throw exception if inputs in view are not valid 
			if (!empty($errorMessage)) {
				$this->sessionHelper->setAlert($errorMessage);
				throw new \Exception();
			}
			
			//check i user already exists in database
			if ($this->model->usernameAlreadyExists($username)) {
				$this->sessionHelper->setAlert("The username is already taken.");
				throw new \Exception();
			}
			
			$pw = new \model\Password($password);
			$user = new \model\User(uniqid(), $username, $pw);	
			$this->model->add($user);	
			
			return true;

		}
		catch(\Exception $e){
			return false;
		}
		
	} 
	
	public function didUserPressSignUp(){ // This is used in index.php file so that I don't need to have an extra page for signUp
		
		if($this->view->didUserPressSignUp()){
      		return true;
      	}
		return false;
	}
  } 
