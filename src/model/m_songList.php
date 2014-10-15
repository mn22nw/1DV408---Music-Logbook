<?php
namespace model;

class SongList {
	private $songs;
	
	public function __construct() {
		$this->songs = array();
	}
	
	public function toArray() {
		return $this->songs; 
	}
	
	public function add(Song $song) {
		if (!$this->contains($song))
			$this->songs[] = $song;
	}
	
	public function contains(Song $song) {
		foreach($this->songs as $key => $value) {
			if ($song->equals($value)) {
				return true;
			}
		}
		
		return false;
	}
}