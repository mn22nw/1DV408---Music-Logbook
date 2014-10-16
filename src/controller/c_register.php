<?php
  namespace controller;

  require_once("src/model/m_UserRepository.php");
  require_once("src/model/m_user.php");
  require_once("src/view/v_register.php");
  require_once("src/helper/SessionHelper.php");

  class Register{
    private $model;
    private $view;
	private $sessionHelper;

    public function __construct() {
      $this->model = new \model\UserRepository();
      $this->view = new \view\Register($this->model);
	  $this->sessionHelper = new \helper\SessionHelper(); 
    }

    public function viewPage() {
    		if($this->view->RegisterAttempt()) {  
    			if ($this->addUser()) { //<--true if user was successfully added
    				 header('Location: http://www.mianygren.nu/PHP-1DV408/Labb4/index.php');
					 exit;
    			}
			}
      		return $this->view->showRegister();
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
				$this->sessionHelper->setAlert("Användarnamnet är redan upptaget.");
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
	
	public function didUserPressRegister(){ // This is used in index.php file so that I don't need to have an extra page for register
		
		if($this->view->didUserPressRegister()){
      		return true;
      	}
		return false;
	}
  } 
