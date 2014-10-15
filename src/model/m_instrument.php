<?php
namespace model;

require_once('./src/model/m_songList.php');

class Instrument {
	private $instrumentID;
	private $name;
	private $surname;
	private $personalCn;
	private $songs;
	
	/**
	 * Constructor containing mocked overloading in PHP.
	 */
	public function __construct($name, SongList $songs = null, $instrumentID = null) {
	
		if ($instrumentID == null) {
			$this->instrumentId = 0;
		}
		else {
			$this->instrumentID =$instrumentID;
		}
		//$this->$instrumentId = ($instrumentId == null) ? 0 : $instrumentId;
		$this->songs = ($songs == null) ? new SongList(): $songs;
		$this->name = $name;
	}

	/**
	 * @return String
	 */
	public function getName() {
		return $this->name; //TODO - make this return instrument details ? Name, surname , personal code number, 	
	}

	/**
	 * @return String
	 */
	public function getInstrumentID() {
		return $this->instrumentID;
	}
	
	/**
	 * @return Void
	 */
	public function setInstrumentID($instrumentID) {  // send this from instrumentrepository?
		$this->instrumentID = $instrumentID;
	}
	
	/**
	 * Add a new song to the instrument.
	 * Do not add empty songs!?
	 * 
	 * @param \model\Song $song Instance of the populated song to add. 
	 * @return Void
	 */
	public function add(\model\Song $song) {
		$this->songs->add($song);
	}
	
	/**
	 * @return \model\SongList
	 */
	public function getSongs() {
		return $this->songs;
	}
	
}