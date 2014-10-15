<?php

namespace controller;

//Dependencies
require_once("./src/view/v_repertoire.php");
require_once("./src/view/v_instrument.php");
require_once("./src/view/v_song.php");
require_once("./src/model/m_instrumentList.php");
require_once('./src/model/m_instrumentRepository.php');
require_once('./src/model/m_songRepository.php');
require_once('./src/helper/Misc.php');
/**
 * Controller for user related application flow.
 */
class InstrumentController {
	private $misc;		
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
		$this->misc = new \helper\Misc();
	}

	/**
	* @return html 
	*/
	public function show() {
		
		$instrumentID = $this->repertoireView->getInstrumentID();  //gets value from url
		
		//save instrumentID in session
		$this->misc->setInstrumentID($instrumentID); 
		
		$instrumentID = $this->misc->getInstrumentID();
		
		if ($this->repertoireView->visitorHasChosenRepertoire() == false) { //<--this is also used after creating a song
			
			$owner = $this->instrumentRepository->get($instrumentID);    

		} else {
				
			//TODO - need to set song id here?
			$owner = $this->instrumentRepository->get($instrumentID);   //TODO -if and else do the same thing! remove! 
	  
		} 
		
		return $this->instrumentView->show($owner);  
	}

	public function showSongMenu () { 
			
			//$this->misc->setInstrumentID(7);	//it unsets somewhere??
			$instrumentID = $this->misc->getInstrumentID();
			var_dump($instrumentID)	;
					
			$owner = $this->instrumentRepository->get($instrumentID);  
			
			return $this->instrumentView->showMenu($owner); 

			 
	}
	
	public function showSong() {
		if ($this->instrumentView->visitorHasChosenSong() == false) {
			
			//TODO - this is used after creating a something for a song hmm=! 
			
			$song = $this->songRepository->get($this->songView->getSongID());    
	
			return $this->songView->show($song);

		} else {
			
			$song = $this->songRepository->get($this->songView->getSongID());  

			
			return $this->songView->show($song);    
		}
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
			$newInstrument = $this->instrumentView->getFormData();    // gets the input from the form
			
			//TODO - validate input!!
			
			/*while ($this->instrumentRepository->toCompactList()->contains($newInstrument)) { TODO - make another solution!
				$newInstrument->setUnique();   //sets unique if to list already contains
			}*/
			
			// TODO - get username from session??!
			$username = 'miaaim';
			
			//adds instrument to database
			$this->instrumentRepository->add($newInstrument, $username);  
			
			\view\NavigationView::RedirectHome(); //TODO -Redirect to newly created instrument?
		} else {
			return $this->instrumentView->getForm();
		}
	}
	
	/**
	 * Controller function to add a project.
	 * Function returns HTML or Redirect.
	 * 
	 * @TODO: Move to an own controller?
	 * 
	 * @return Mixed
	 */
	public function addSong() {
		$view = new \view\SongView();
		if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			$song = $view->getSong();			
			$song->setOwner($this->instrumentRepository->get($view->getOwnerUnique()));
			
			$this->songRepository->add($song);
			\view\NavigationView::RedirectToInstrument($view->getOwnerUnique());
		} else {
			return $view->getForm($this->instrumentRepository->get(\view\NavigationView::getId()));
		}
	}
	
	public function setMainInstrument() {
		
		$instrumentID = $this->repertoireView->getInstrumentIDfromRadioBtn();
		
		$this->instrumentRepository->updateMainInstrument($instrumentID, "miaaim"); //TODO get real user!
			
		\view\NavigationView::RedirectHome();
		
	}
	
	
	public function deleteInstrument() {
		
			$instrumentID = $this->misc->getInstrumentID();	
			$instrument = $this->instrumentRepository->get($instrumentID);  
			
			if (true){   // TODO - fixa confirm !

				//deletes instrument from database
				$this->instrumentRepository->delete($instrument , "miaaim"); //TODO get real user!
				
				\view\NavigationView::RedirectHome();
		  	 }
		   else{
		    // do nothing ?
		    // \view\NavigationView::RedirectHome(); //TODO -Redirect to instrument!?
		   }		
	}
	
	public function deleteSong() {
		
		/*
		 $member = $this->memberView->getOwner();  
		$boat = $this->boatRepository->get($this->memberView->getBoat());  
		
		$this->boatRepository->delete($boat); 
	
		\view\NavigationView::RedirectToMember($member);*/
		
		
			$instrumentID = $this->misc->getInstrumentID();	
			$songID =$this->instrumentView->getSong(); 
			
			if (true){   // TODO - fixa confirm !

				//deletes instrument from database
				$this->songRepository->delete($songID, $instrumentID); 
				
				\view\NavigationView::RedirectToInstrument($instrumentID);
		  	 }
		   else{
		    // do nothing ?
		    // \view\NavigationView::RedirectHome(); //TODO -Redirect to instrument!?
		   }
	}
}
