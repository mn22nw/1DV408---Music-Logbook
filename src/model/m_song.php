<?php
namespace model;

class Song {
	private $songID;
	private $name;
	private $notes;
	private $totalPracticetime;
	
	public function __construct($name, $songID = null, $owner = null, $notes = null , $totalPracticetime = null) {
		if (empty($name)) {
			throw new Exception('Name of song cannot be empty.');
		}
		
		$this->name = $name; 
		$this->songID = $songID;  //TODO handle if null!
		$this->owner = $owner;  //TODO handle if null!
		$this->notes = $notes; // TODO handle if null!
		if (empty($totalPracticetime)) {
			$this->totalPracticetime = 0;
		}
		else { $this->totalPracticetime = $totalPracticetime; }
		
	}
	
	public function equals(Song $other) {  // TODO is this used?
		return (
			$this->getName() == $other->getName() &&
			$this->getsongID() == $this->getsongID()
			);
	}
	
	public function getName() {
		return $this->name;
	}
	
	/* totalPracticetime is stored in hours in the database
	 * @return time in HTML formated string
	 */
	public function getTotalPracticetime() {
			
		//convert from hours to seconds 
		$seconds = $this->totalPracticetime * 3600; 

		
		$seconds = ceil($seconds);
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds / 60) % 60);
		$seconds = $seconds % 60;
		
		//return $hours . " hrs " . $minutes . " min " . $seconds . " sec";
		
		return sprintf("%2d hours and  %2d:%02d min", $hours, $minutes, $seconds);
		
				
		/*$t = microtime(true);
		$micro = sprintf("%06d",($seconds) * 1000000);  // TODO remove unused code
		$d = new \DateTime( 'Y-m-d H:i:s.'.$micro ); 
					
		return $d->format("Y-m-d H:i:s.u"); */
	}
	
	public function setNotes($notes) {
		$this->notes = $notes;
	}
	
	public function getNotes() {
		return $this->notes;
	}
	
	
	public function getsongID() {
		return $this->songID;
	}
	
	public function setOwner(Instrument $owner) {
		$this->owner = $owner;
	}
	
	public function getOwner() {
		return $this->owner;
	}
}
