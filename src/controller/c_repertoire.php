<?php

namespace controller;

require_once("./src/view/v_repertoire.php");
require_once("./src/model/m_instrumentBook.php");

//TODO - remove this completely?

class RepertoireController {
	private $repertoireView;
	private $instruments;

	public function __construct() {
		$this->repertoireView = new \view\RepertoireView(); // backslash är "root"-namespace 
		$this->instruments = new \model\InstrumentBook();
	}

	/**
	* Ska likna Use-Case "A Visitor Views a Repertoire of Projects"
	* @return String HTML
	*/
	public function selectRepertoire() {
		//fejkad data 
		

		//1. System shows available repertoire owners.
		if ($this->repertoireView->visitorHasChosenRepertoire() == false) {

			return $this->repertoireView->showRepertoireOwners($this->instruments);

		} else {

			//2. The visitor selects a repertoire owner.
			$owner = $this->repertoireView->getChosenOwner();
			
			//3. The system shows a repertoire of all projects where the owner is participant.
			return $this->repertoireView->showRepertoire($owner);
		}
	}
}

