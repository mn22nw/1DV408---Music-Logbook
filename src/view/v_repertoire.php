<?php

namespace view;
/**
 * TODO Refactor together with SongView and InstrumentView.
 * Some things might be better off in other views.
 */
class RepertoireView {    
	private static $getLocation = "instrument"; 
	
	public static $mainInstrument = 'mainInstrument';
	
	public function getInstrumentID() {  
		if (isset($_GET[self::$getLocation])) {
			return $_GET[self::$getLocation];
		}
		
		return NULL;
	}

	public function visitorHasChosenRepertoire() {  //TODO not used?!
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
	
	/**
	 * render all instruments.
	 * @param  instrumentlist,  ID of the main instrument of a user (to know which radiobutton that should be selected)
	 * @return html
	 */
	public function showAllInstruments( \model\InstrumentList $instrumentList, $mainInstrumentID) {
		
		$checked="";
				
		$ret = "<h1>My Instruments</h1>";
		
		if (empty($instrumentList)) {
			$ret .="You have no instruments yet!";
		}else {
			$ret .= "<p class='chooseInstrument'>Choose main instrument:<p>";
			$ret .= "<form method='post' action='?action=".NavigationView::$actionSetMainInstrument."'>";
			$ret .= "<ul id='instrumentlist'>";
			
			foreach ($instrumentList->toArray() as $instrument) {//Changed this to work with new navigation view.
				
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
				
				$ret .= "<input type='radio' name='".self::$mainInstrument."' value='".$instrument->getInstrumentID() . "'" . $checked ."/></li> <hr />"; 
				
			}; 
			
			$ret .= "</ul></form>";
		}
		return $ret;
	}
	
}