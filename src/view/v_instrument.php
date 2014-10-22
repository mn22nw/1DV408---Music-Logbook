<?php
namespace view;

class InstrumentView {
	public	static $getLocation = "instrument"; 
	private static $getSong= "song"; 
	private static $name = 'name';
	private $sessionHelper = 'sessionHelper'; 
	
	public function __construct() {
		$this->sessionHelper = new \helper\SessionHelper();
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
		<br /> Tyckte det kunde vara en bra grej att ha i framtiden, <br /> därför lämnar jag plats för det i strukturen.</p>";
		//set feedback message
		$html.="<p>" . $this->sessionHelper->getAlert(). "</p>";
		
		if (empty($songArray)) 
		$html .= "<p>You have no songs yet.</p> <br /></div>";

		return $html;
	}	
}
