<?php
namespace controller;
require_once('./src/view/v_navigation.php');
require_once('./src/view/v_htmlBody.php');
require_once('./src/controller/c_instrument.php');
require_once('./src/controller/c_signIn.php');
require_once('./src/controller/c_signUp.php');
require_once('Settings.php');

/**
 * Navigation view for a simple routing solution.
 */
class Navigation {
		
	private $htmlBody;
	
	/**
	 * Checks what controller to instansiate and return value of to HTMLView.
	 */
	public function doControll() {
		$this->htmlBody = new \view\HTMLBody();	
		$view = new \view\NavigationView(); 
		$controller;

		try {
			
			$controller = new SignIn();
			
			// checking sign in status and creating homepage if user is not signed in  // TODO maybe have this in index instead?	
		
				$this->htmlBody->setBody($controller->viewPage());
				$this->htmlBody->setMenu($view->getBaseMenuStart()); 

			
			// checking if user want's to sign up
			if ($view->getAction() == $view::$actionSignUp) {
				$signUpController = new SignUp();
				$this->htmlBody->setBody($signUpController->viewPage());
				$this->htmlBody->setMenu($view->getBaseMenuStart()); 
			}
					
			if ($controller->userIsSignedIn() == false)
				return $this->htmlBody; 
			

			//only do this switch statement when user is signed in 

			switch ($view::getAction()) {
						
				// SHOW ALL INSTRUMENTS //		
				case $view::$actionShowAll:
					$controller = new InstrumentController();
					$this->htmlBody->setBody($controller->showAllInstruments());
					$this->htmlBody->setMenu($controller->showSongMenu());
					return $this->htmlBody;
					break;	
					
				// ADD INSTRUMENT //
				case $view::$actionAddInstrument:
					$controller = new InstrumentController();
					$this->htmlBody->setBody($controller->addInstrument());
					$this->htmlBody->setMenu($controller->showSongMenu()); 
					return $this->htmlBody;
					break;
				
				// SHOW INSTRUMENT //
				case $view::$actionShowInstrument:
					$controller = new InstrumentController();		
					$this->htmlBody->setBody($controller->show());
					$this->htmlBody->setMenu($controller->showSongMenu()); 
					return $this->htmlBody;
					break;
					
				// DELETE INSTRUMENT //	
				case $view::$actionDeleteInstrument:
					$controller = new InstrumentController();
					$this->htmlBody->setBody($controller->deleteInstrument());
					$this->htmlBody->setMenu($controller->showSongMenu()); 
					return $this->htmlBody;
				
				// ADD SONG //	
				case $view::$actionAddSong:
					$controller = new SongController();
					$this->htmlBody->setBody($controller->addSong());
					$this->htmlBody->setMenu($controller->showSongMenu()); 
					return $this->htmlBody;
				
				// SHOW SONG //		
				case $view::$actionShowSong:
					$controller = new SongController();
					$this->htmlBody->setBody($controller->showSong());
					$this->htmlBody->setMenu($controller->showSongMenu()); 
					return $this->htmlBody;
				
				// DELETE SONG //		
				case $view::$actionDeleteSong:
					$controller = new SongController();
					$this->htmlBody->setBody($controller->deleteSong());
					$this->htmlBody->setMenu($controller->showSongMenu()); 
					return $this->htmlBody;
				
				// SET MAIN INSTRUMENT //		
				case $view::$actionSetMainInstrument:
					$controller = new InstrumentController();
					$this->htmlBody->setBody($controller->setMainInstrument());
					$this->htmlBody->setMenu($controller->showSongMenu()); 
					return $this->htmlBody;
				
				
				// HOMEPAGE - for signed in users //
				default :   
					$controller = new InstrumentController();
					$this->htmlBody->setBody($controller->showAllInstruments());
					$this->htmlBody->setMenu($controller->showSongMenu());
					return $this->htmlBody;
					break;
			}
		} catch (\Exception $e) {

			error_log($e->getMessage() . "\n", 3, \Settings::$ERROR_LOG);
			if (\Settings::$DO_DEBUG) {
				throw $e;
			} else {
				\view\NavigationView::RedirectToErrorPage();
				die();
			}
		}
	}
}
