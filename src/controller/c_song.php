<?php

namespace controller;

//Dependencies
require_once("./src/view/v_song.php");
require_once('./src/model/m_songRepository.php');
require_once('./src/model/m_instrumentRepository.php');
require_once('./src/model/m_timer.php');
require_once('./src/helper/sessionHelper.php');
require_once('./src/model/m_validation.php');

class SongController {
	private $sessionHelper;
	private $validation;		
	//model
	private $songRepository;
	private $instrumentRepository;
	private $timer;
	//view
	private $songView;
	private $navigationView;

	/**
	 * Instantiate required views and required repositories.
	 */
	public function __construct() {
		$this->songRepository = new \model\SongRepository();
		$this->instrumentRepository = new \model\InstrumentRepository();
		$this->timer = new \model\Timer();
		
		$this->navigationView = new \view\NavigationView();
		$this->songView = new \view\SongView();
		
		$this->sessionHelper = new \helper\SessionHelper();
		$this->validation = new \model\Validation();
	}

	
	public function showSong($timerOn = false) {
		
			$song = $this->songRepository->get($this->songView->getSongID());  
			
			$instrumentID = $this->sessionHelper->getInstrumentID(); 
			$instrument = $this->instrumentRepository->get($instrumentID);

			return $this->songView->show($song, $instrument, $timerOn); //instrument is needed in songView to show breadcrum
	}	
	
	/**
	 * Controller function to add a song.
	 * Function returns HTML or Redirect.
	 * 
	 * @return Mixed
	 */
	public function addSong() {
		
		$instrumentID = $this->sessionHelper->getInstrumentID();
	
		if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			
			//Only add song if validation is true!
			if($this->validation->validateName($this->songView->getName())) {
				$song = $this->songView->getSong();		
				$instrumentID =	$this->songView->getOwner();
				$song->setOwner($this->instrumentRepository->get($instrumentID));
				
				$songID = $this->songRepository->add($song);
			
				if($songID == null) {	
					\view\NavigationView::RedirectToAddSong();
				}else {
					\view\NavigationView::RedirectToSong($songID);
				}
			}
		}
		return $this->songView->getForm($this->instrumentRepository->get($instrumentID));
	
	}  
	
	/**
	 * Function starts timer.
	 * @return  the HTML from the called function $this->showSong()
	 */
	public function startTimer() {
			
			if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
								
				$this->timer->start();				 						
			}							
			
			return $this->showSong(true); 
	}	

	/**
	 * Function stops timer and saves it in database.
	 * redirect to showsong (to prevent posting form again)
	 */
	public function stopTimer() {
			
			if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
				
				// hämta ut tid fran databas. och plussa ihop den med den elapsade tiden	
				//spara i timmar istället för sekunder  3600 seconds / timme  delat med!! 3600 blir timme  
				//avrunda till 6 siffor för timme  sen ggr 3600 sen round! för o komma tillbaka till sekunder
			
			// get songID 
			$songID = $this->songView->getSongID();
			
			// get practiced time from DB
			$practicedTime = $this->songRepository->getPracticedTime($songID);
			
				
				$this->timer->stop();
			 
				$duration =  $this->timer->elapsed();
				$seconds = $duration/1000000;					
				
				//round will not work if numer contains -
				$seconds = explode("-",$seconds);
				
				$secondsRounded =  round($seconds[0],2);  
			
			//TODO gör sekunder till timma direkt?
			
			$hoursToSeconds = round($practicedTime * 3600, 6); 
			
			//total practice time 
			$totalPracticetimeInSeconds = $hoursToSeconds + $secondsRounded;
				
			//save in hours in database (to save space)
			$totalPracticetimeInHours =  round($totalPracticetimeInSeconds / 3600, 5); 
			
			$this->songRepository->savePracticedTime($totalPracticetimeInHours, $songID);						
			}							
			
			\view\NavigationView::RedirectToSong($songID);
	}	
	
	
	/**
	 * Function saves notes in database.
	 * @return  the HTML from the called function $this->showSong()
	 */
	public function saveNotes() {
			
			//get data from form textarea
			$notes = $this->songView->getNotes();
			
			// get songID 
			$songID = $this->songView->getSongID();  	
			
			//add break for new lines
			$notes = nl2br($notes, false); 
			
			//save notes in DB
			$this->songRepository->saveNotes($notes, $songID);
			
			return $this->showSong(); 
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
