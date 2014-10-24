<?php
namespace view;

class InstrumentView {
	public	static $getLocation = "instrument"; 
	private static $getSong= "song"; 
	private static $name = 'name';
	private static $mainInstrument = 'mainInstrument';
	
	private $sessionHelper = 'sessionHelper'; 
	
	public function __construct() {
		$this->sessionHelper = new \helper\SessionHelper();
	}
	
	/**
	 * Fetches instrumentID from url.
	 * 
	 * @return mixed
	 */
	public function getInstrumentID() {  
		if (isset($_GET[self::$getLocation])) {
			return $_GET[self::$getLocation];
		}
		
		return NULL;
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
	
	public function getSong() {
		if (isset($_GET[self::$getSong])) {
			return $_GET[self::$getSong];
		}
		
		return null;
	}
	
	/**
	 * @return string (name)
	 */
	public function getFormData() {
		if (isset($_POST[self::$name])) {
			return ($_POST[self::$name]);
		}
		
		return NULL;
	}
	
	/**
	 * Retrieves the form to be used to when adding a new instrument.
	 * 
	 * @return String HTML
	 */
	public function getForm() {
		
		$html = "<div id='addInstrument'>";
		$html .= "<h1>Add instrument</h1>";
		$html .= "<form method='post' action='?action=".NavigationView::$actionAddInstrument."'>";
		$html .= "<label for='" . self::$name . "'>Name: </label>";
		$html .= "<input type='text' name='" . self::$name . "' placeholder='' value='' maxlength='50'><br />";
		$html .= "<input type='submit' value='Add Instrument' class='submit' />";
		$html .= "</form>";
		$html .= "<div class='errorMessage'><p>".$this->sessionHelper->getAlert() ."</p></div>";
		$html .= "</div>";
		
		
		return $html;
	}
	
	/**
	 * Creates the HTML needed to display a instrument with a list of songs
	 * 
	 * @return String HTML
	 */
	public function show(\model\Instrument $instrument) {

		$songArray = $instrument->getSongs()->toArray();
		
		$html = '<h1>' . $instrument->getName() . '</h1>';
		
		//delete-button
		$html .= "<a href='?".NavigationView::$action."=".NavigationView::$actionDeleteInstrument."&amp;".self::$getLocation."=" . 
					urlencode($instrument->getInstrumentID()) ."' class = 'deleteBtn'> Delete instrument</a>";  // TODO- FIX REALLY NEEDS confirm
		
		$html .= "<div id='songList'>";
		
		// add-song button
		$html .= "<a href='?".NavigationView::$action."=".NavigationView::$actionAddSong."&amp;".self::$getLocation."=" . 
					urlencode($instrument->getInstrumentID())."'>Add song</a>";;
		
		//TODO Remove <br />
		$html .="<br /><br /><h2> Monthly overview</h2>";
		$html.="<p>Monthly overview är inte ett användarfall! <br />Kommer att utveckla detta om jag fortsätter med projektet efter kursen. 
		</p><p>Tyckte det kunde vara en bra grej att ha i framtiden, <br /> därför lämnar jag plats för det i strukturen.</p>";
		//set feedback message
		$html.="<p>" . $this->sessionHelper->getAlert(). "</p>";

		$html .= "</div>";

		return $html;
	}	
	
	/**
	 * render all instruments.
	 * @param  instrumentlist,  ID of the main instrument of a user (to know which radiobutton that should be selected)
	 * @return html
	 */
	public function showAllInstruments( \model\InstrumentList $instrumentList, $mainInstrumentID) {
		
		$checked="";	
		$html = "<h1>My Instruments</h1>";
		
		if (count($instrumentList->toArray()) == 0) {
			$html .="<div id='addInstrumentIfzero'><p>You have no instruments yet!</p>";
			$html .= $this->getForm() . "</div>";
		}else {
			if (count($instrumentList->toArray()) == 1) {
				$html .= "<p class='mainInstrumentDefault'>Main instrument:<p>";
			} else {
				$html .= "<p class='chooseMainInstrument'>Choose main instrument:<p>";
			}
			$html .= "<form method='post' action='?action=".NavigationView::$actionSetMainInstrument."'>";
			$html .= "<ul id='instrumentlist'>";
			
			foreach ($instrumentList->toArray() as $instrument) {//Changed this to work with new navigation view.
				
				$instrumentID = $instrument->getInstrumentID();
				$html .= "<li><a href='?action=".NavigationView::$actionShowInstrument."&amp;".self::$getLocation."=" . 
						urlencode($instrument->getInstrumentID()) ."'>" .
						$instrument->getName();
				$html .= "<p>Number of songs: " . count($instrument->getSongs()->toArray())."</p><p> Total instument pracice time: ? </p></a>";
				
				//decides which radiobutton that is selected
				if ($instrumentID === $mainInstrumentID) {
					$checked = "checked='checked'";
				} else if (count($instrumentList->toArray()) == 1) {
					$checked = "checked='checked'";
				}
				else {
					$checked = "";
				}
				
				$html .= "<input type='radio' name='".self::$mainInstrument."' value='".$instrument->getInstrumentID() . "'" . $checked ."/></li> <hr />"; 
				
			}; 
			
			$html .= "</ul></form>";
		}
		return $html;
	}
	
}
