<?php
namespace view;

class InstrumentView {
	public static $getLocation = "instrument"; 
	private static $getSong= "song"; 
	private static $name = 'name';
	
	
	public function visitorHasChosenInstrument() {
		if (isset($_GET[self::$getLocation])) 
			return true;

		return false;
	} 
	
	public function visitorHasChosenSong() {
		if (isset($_GET[SongView::$getLocation])) 
			return true;

		return false;
	}
	
	public function getSong() {
		if (isset($_GET[self::$getSong])) {
			return $_GET[self::$getSong];
		}
		
		return null;
	}
	
	/**
	 * Populate a new instrument model from form data.
	 * @todo Maybe put this in a controller? Create new instrument model that is dumber?
	 * 
	 * @return \model\Instrument
	 */
	public function getFormData() {
		if (isset($_POST[self::$name])) {
			return new \model\Instrument($_POST[self::$name]);
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
		$html .= "<input type='submit' value='Add Instrument' />";
		$html .= "</form>";
		$html .= "</div>";
		
		return $html;
	}
	
		/**
	 * Creates the HTML needed to display a menu with instrument with a list of songs
	 * 
	 * @return String HTML
	 */
	public function showMenu(\model\Instrument $instrument) {  //TODO try an refactor this to v_navigation
		$songArray = $instrument->getSongs()->toArray();	
		$view = new \view\NavigationView();
		
		// RENDER THE 'MENU' with songs 
		$menu = $view->getInstrumentButton($instrument);
		
		// UL inside an list element! (for proper HTML-syntax)
		$menu .= "<li><ul id='songMenu'>";
		
		foreach($songArray as $song) {
			$menu .= "<li><a href='?".NavigationView::$action."=".NavigationView::$actionShowSong;
			$menu .= "&amp;".InstrumentView::$getLocation."=" . 
					urlencode($instrument->getInstrumentID());
			$menu .= "&amp;".SongView::$getLocation."=" . 
					urlencode($song->getSongID()) ."'>".$song->getName()."</a></li>";
		}
		$menu .= "</ul></li>";	
		return $menu;
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
		$html .= "<a href='?".NavigationView::$action."=".NavigationView::$actionAddSong."&amp;".NavigationView::$id."= ".$instrument->getInstrumentID();
		$html .= " '>Add song</a>";
		
		//TODO Remove <br />
		$html .="<br /><br /><h2> Monthly overview</h2>";
		
		if (empty($songArray)) 
		$html .= "<p>You have no songs yet.</p> <br /></div>";

		return $html;
	}	
}
