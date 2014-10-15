<?php

namespace view;
/**
 * @todo Refactor together with SongView and InstrumentView.
 * Some things might be better off in other views.
 */
class RepertoireView {    // TODO - rename repertoire!
	private static $getLocation = "instrument"; 
	
	public static $mainInstrument = 'mainInstrument';
	
	public function getInstrumentID() {  
		if (isset($_GET[self::$getLocation])) {
			return $_GET[self::$getLocation];
		}
		
		return NULL;
	}

	public function visitorHasChosenRepertoire() {
		if (isset($_GET[self::$getLocation])) 
			return true;

		return false;
	}
	
	/**
	 * Fetches the value (instrumentID) of a radio button.
	 * 
	 * @return String
	 */
	public function getInstrumentIDfromRadioBtn () {
		if (isset($_POST[self::$mainInstrument])) {   
			return $_POST[self::$mainInstrument];
		}
		return NULL;
	}

	public function showAllInstruments( \model\InstrumentList $repertoireOwners, $mainInstrumentID) {
		
		$checked="";
				
		$ret = "<h1>My Instruments</h1>";
		$ret .= "<p class='chooseInstrument'>Choose main instrument:<p>";
		$ret .= "<form method='post' action='?action=".NavigationView::$actionSetMainInstrument."'>";
		$ret .= "<ul id='instrumentlist'>";
		
		foreach ($repertoireOwners->toArray() as $instrument) {//Changed this to work with new navigation view.
			
			$instrumentID = $instrument->getInstrumentID();
			$ret .= "<li><a href='?action=".NavigationView::$actionShowInstrument."&amp;".self::$getLocation."=" . 
					urlencode($instrument->getInstrumentID()) ."'>" .
					$instrument->getName();
			$ret .= "<p>Number of songs: " . count($instrument->getSongs()->toArray())."</p><p> Total instument pracice time: ? </p></a>";
			
			//decides which radiobutton that is selected
			if ($instrumentID === $mainInstrumentID) {
				$checked = "checked='checked'";
			}else {
				$checked = "";
			}
			
			$ret .= "<input type='radio' name='".self::$mainInstrument."' value='".$instrument->getInstrumentID() . "'" . $checked ."/></li> "; 
			
		}; 
		
		$ret .= "</ul></form>";
		
		return $ret;
	}
	
}