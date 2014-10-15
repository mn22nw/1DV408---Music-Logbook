<?php

namespace model;

require_once("m_instrument.php");

/**
 * Type secure collection of instruments.
 */
class InstrumentList {
	private $repertoireOwners;
	
	public function __construct() {
		$this->repertoireOwners = array();
	}
	
	/**
	 * Returns an array of the instruments.
	 *
	 * @return Array
	 */
	public function toArray() {
		
		return $this->repertoireOwners; 
	}
	
	/**
	 * Add a new instrument to the list.
	 * 
	 * @param \model\Instrument $instrument
	 * 
	 * @return Void
	 */
	public function add(Instrument $instrument) {
		if (!$this->contains($instrument))
			$this->repertoireOwners[] = $instrument;
	}
	
	/**
	 * Check if a instrument can be found within the list.
	 * 
	 * @param \model\InstrumentList $instrument The needle to look for.
	 * 
	 * @return Boolean
	 */
	public function contains(Instrument $instrument) {
		foreach($this->repertoireOwners as $key => $owner) {
			if ($owner->getInstrumentID() == $instrument->getInstrumentID() && $owner->getName() == $instrument->getName()) {
				return true;
			}
		}
		
		return false;
	}
}