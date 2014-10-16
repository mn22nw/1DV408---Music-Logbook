<?php

namespace controller;

//Dependencies
require_once("./src/view/v_repertoire.php");
require_once("./src/view/v_instrument.php");
require_once("./src/view/v_song.php");
require_once("./src/model/m_instrumentList.php");
require_once('./src/model/m_instrumentRepository.php');
require_once('./src/model/m_songRepository.php');
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
	private $songRepository;
	//view
	private $instrumentView;
	private $repertoireView;
	private $songView;

	/**
	 * Instantiate required views and required repositories.
	 */
	public function __construct() {
		$this->repertoireView = new \view\RepertoireView(); //Still required in class scope?
		$this->instrumentView = new \view\InstrumentView(); //Still required in class scope?
		$this->instrumentRepository = new \model\InstrumentRepository();
		$this->songRepository = new \model\SongRepository();
		$this->songView = new \view\SongView();
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
			
			//$this->sessionHelper->setInstrumentID(7);	//it unsets somewhere??
			$instrumentID = $this->sessionHelper->getInstrumentID();

			if (empty($instrumentID)){
					
				$instrumentID = $this->instrumentRepository->getMainInstrument("miaaim"); //TODO get real username
				
				if ($instrumentID == 0)
				return "";	
				
			}	
				    //TODO get from database
			
			$owner = $this->instrumentRepository->get($instrumentID);  
				
			return $this->instrumentView->showMenu($owner); 	 
	}
	
	public function showSong() {
			
			$song = $this->songRepository->get($this->songView->getSongID());  
			
			$instrumentID = $this->sessionHelper->getInstrumentID(); 
			$instrument = $this->instrumentRepository->get($instrumentID);

			return $this->songView->show($song, $instrument); //instrument is needed in songView to show breadcrum
	}	
	
	
	
	/**
	 * Get the HTML required to show all instruments in compact view.
	 * 
	 * @return String HTML
	 */
	public function showAllInstruments() {
		$mainInstrumentID = $this->instrumentRepository->getMainInstrument("miaaim"); //TODO get real user
		
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
				
				$username = 'miaaim';  // TODO - get username from session??!
				
				//adds instrument to database 
				$instrumentID = $this->instrumentRepository->add($this->sessionHelper->getName(), $username);  
				
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
	
	/**
	 * Controller function to add a song.
	 * Function returns HTML or Redirect.
	 * 
	 * @TODO: Move to an own controller?
	 * 
	 * @return Mixed
	 */
	public function addSong() {
		
		$instrumentID = $this->sessionHelper->getInstrumentID();
		$view = new \view\SongView();
	
		if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			
			//Only add song if validation is true!
			if($this->validation->validateName($view->getName())) {
				$song = $view->getSong();		
				$instrumentID =	$view->getOwner();
				$song->setOwner($this->instrumentRepository->get($instrumentID));
				
				$songID = $this->songRepository->add($song);
			
				if($songID == null) {	
					\view\NavigationView::RedirectToAddSong();
				}else {
					\view\NavigationView::RedirectToSong($songID);
				}
			}
		}
		return $view->getForm($this->instrumentRepository->get($instrumentID));
	
	}
	
	public function setMainInstrument() {
		
		$instrumentID = $this->repertoireView->getInstrumentIDfromRadioBtn();
		
		$this->instrumentRepository->updateMainInstrument($instrumentID, "miaaim"); //TODO get real user!
			
		\view\NavigationView::RedirectHome();
		
	}
	
	
	public function deleteInstrument() {
		
			$instrumentID = $this->sessionHelper->getInstrumentID();	
			$instrument = $this->instrumentRepository->get($instrumentID);  
			
			if (true){   // TODO - fixa confirm !

				//deletes instrument from database
				$this->instrumentRepository->delete($instrument , "miaaim"); //TODO get real user!
				
				\view\NavigationView::RedirectHome();
				
		  	 }else {
		   		 \view\NavigationView::RedirectToInstrument($instrumentID);  
		   }		
	}
	
	public function deleteSong() {
		
			$instrumentID = $this->sessionHelper->getInstrumentID();	
			$songID =$this->instrumentView->getSong(); 
			
			if (true){   // TODO - fixa confirm !

				//deletes song from database
				$this->songRepository->delete($songID, $instrumentID); 
				
				$this->sessionHelper->setAlert("Song was successfully deleted"); 

				\view\NavigationView::RedirectToInstrument($instrumentID);  
				
		  	 }else{
		    	\view\NavigationView::RedirectToSong($songID);  
		   }
	}
}
