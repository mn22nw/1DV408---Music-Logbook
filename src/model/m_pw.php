<?php

namespace model;

require_once("src/helper/SessionHelper.php");

class Password {

	const minPwLen = 6;
	private $pw;
	private $sessionHelper;

	public function __construct($password) {
		$this->sessionHelper = new \helper\SessionHelper();
			
		if ($this->validatePw($password)) {
			$this->pw = $password;
		}
		
	}
	
	public function getHashedPassword(){
		return $this->sessionHelper->encryptString($this->pw);
	}
	
	public function validatePw($password) {
		
		if (mb_strlen($password) < self::minPwLen) {
			$this->sessionHelper->setAlert("Lösenordet har för få tecken. Minst 6 tecken.");  //medveten om ev. strängberoende men ville ha validering även här.
			throw new \Exception();  
		}
		
		return true;
	}
}