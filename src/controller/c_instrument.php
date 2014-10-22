<?php

namespace controller;

//Dependencies
require_once("./src/view/v_repertoire.php");
require_once("./src/view/v_instrument.php");
require_once("./src/view/v_song.php");
require_once("./src/model/m_instrumentList.php");
require_once('./src/model/m_instrumentRepository.php');
require_once('./src/helper/sessionHelper.php');
require_once('./src/model/m_validation.php');
/**
 * Controller for user related application flow.
 */
class InstrumentController {
	private $sessionHelper;
	private $validation;		
	//model
	private $instrumentRepository; 
	//view
	private $instrumentView;
	private $repertoireView;
	private $navigationView;

	/**
	 * Instantiate required views and required repositories.
	 */
	public function __construct() {
		$this->repertoireView = new \view\RepertoireView(); //Still required in class scope?
		$this->instrumentView = new \view\InstrumentView(); //Still required in class scope?
		$this->navigationView = new \view\NavigationView();
		$this->instrumentRepository = new \model\InstrumentRepository();
		$this->sessionHelper = new \helper\SessionHelper();
		$this->validation = new \model\Validation();
	}

	/**
	* @return html 
	*/
	public function show() {
		
		$instrumentID = $this->repertoireView->getInstrumentID();  //gets value from url
		
		//save instrumentID in session
		$this->sessionHelper->setInstrumentID($instrumentID); 
		
		$instrumentID = $this->sessionHelper->getInstrumentID($instrumentID);
		
		$owner = $this->instrumentRepository->get($instrumentID);   //TODO -if and else do the same thing! remove! 
	  
		return $this->instrumentView->show($owner);  
	}

	public function showSongMenu () { 
			
			//$this->sessionHelper->setInstrumentID(6);	//it unsets somewhere??
			$instrumentID = $this->sessionHelper->getInstrumentID();  //TODO change maininstrument to0 if user deletes it!
			
			$sessionUsername = $this->sessionHelper->getUsername();
			
			if (empty($instrumentID)){		
				$instrumentID = $this->instrumentRepository->getMainInstrument($this->sessionHelper->getUsername()); //TODO get real username	
			}	
			
			if ($instrumentID == 0)
				return "";	
			
			$owner = $this->instrumentRepository->get($instrumentID);  
				
			$this->navigationView->showSongMenu($owner); 	
			
			return $this->navigationView->showMenuLoggedIn(); 
	}
	
	
	/**
	 * Get the HTML required to show all instruments in compact view.
	 * 
	 * @return String HTML
	 */
	public function showAllInstruments() {
		
		$mainInstrumentID = $this->instrumentRepository->getMainInstrument($this->sessionHelper->getUsername()); 
		
		return $this->repertoireView->showAllInstruments($this->instrumentRepository->toList(), $mainInstrumentID);  
	}
	
	
	/**
	 * Controller function to add a instrument.
	 * 
	 * Function will return HTML or Redirect.
	 * 
	 * @return Mixed
	 */
	public function addInstrument() {
		if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			$name = $this->instrumentView->getFormData();    // gets the name input from the form
			
			
			//Only add instrument if validation is true!
			if($this->validation->validateName($name)) {
								
				//adds instrument to database 
				$instrumentID = $this->instrumentRepository->add($this->sessionHelper->getName(), $this->sessionHelper->getUsername());  
				
				if($instrumentID == null) {	
				\view\NavigationView::RedirectToAddInstrument();
					
				}else {
				\view\NavigationView::RedirectToInstrument($instrumentID); 
				}
				
				
			}else{
				return $this->instrumentView->getForm();
			}	
			
		} else {
			return $this->instrumentView->getForm();
		}
	}
	
	public function setMainInstrument() {
		
		$instrumentID = $this->repertoireView->getInstrumentIDfromRadioBtn();
		
		$this->instrumentRepository->updateMainInstrument($instrumentID, $this->sessionHelper->getUsername()); 
			
		\view\NavigationView::RedirectToShowAllInstruments();
		
	}
	
	
	public function deleteInstrument() {
		
			$instrumentID = $this->sessionHelper->getInstrumentID();	
			$instrument = $this->instrumentRepository->get($instrumentID);  
			
			if (true){   // TODO - fixa confirm !

				//deletes instrument from database
				$this->instrumentRepository->delete($instrument , $this->sessionHelper->getUsername()); 
				
				\view\NavigationView::RedirectHome();
				
		  	 }else {
		   		 \view\NavigationView::RedirectToInstrument($instrumentID);  
		   }		
	}

}
