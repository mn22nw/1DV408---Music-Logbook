<?php
namespace model;

class Song {
	private $songID;
	private $name;
	private $notes;
	
	public function __construct($name, $songID = null, $owner = null, $notes = null) {
		if (empty($name)) {
			throw new Exception('Name of song cannot be empty.');
		}
		
		$this->name = $name; 
		$this->songID = $songID;  //TODO handle if null!
		$this->owner = $owner;  //TODO handle if null!
		$this->notes = $notes; // TODO handle if null!
		
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
	
	public function getTotalPracticetime() {  //TODO eh, fix this somehow =S
		return $this->length;
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
