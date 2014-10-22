<?php

namespace controller;

//Dependencies
require_once("./src/view/v_song.php");
require_once('./src/model/m_songRepository.php');
require_once('./src/helper/sessionHelper.php');
require_once('./src/model/m_validation.php');

class SongController {
	private $sessionHelper;
	private $validation;		
	//model
	private $songRepository;
	//view
	private $songView;
	private $navigationView;

	/**
	 * Instantiate required views and required repositories.
	 */
	public function __construct() {
		$this->navigationView = new \view\NavigationView();
		$this->songRepository = new \model\SongRepository();
		$this->songView = new \view\SongView();
		$this->sessionHelper = new \helper\SessionHelper();
		$this->validation = new \model\Validation();
	}

	
	public function showSong() {
			
			$song = $this->songRepository->get($this->songView->getSongID());  
			
			$instrumentID = $this->sessionHelper->getInstrumentID(); 
			$instrument = $this->instrumentRepository->get($instrumentID);

			return $this->songView->show($song, $instrument); //instrument is needed in songView to show breadcrum
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
