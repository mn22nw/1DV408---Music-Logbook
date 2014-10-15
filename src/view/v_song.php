<?php
namespace view;

require_once('./src/model/m_song.php');

class SongView {
	public static $getLocation = 'song';
	
	private static $name = 'name';
	private static $instrumentUnique = 'instrumentUnique';
	
	/**
	 * Populate a song model with information from a view
	 * 
	 * @return \model\song
	 */
	public function getsong() {
		if($this->getName() != NULL) {
			$songName = $this->getName();
			return new \model\song($songName);
		}
	}
	
	
	public function getSongID() {
		if (isset($_GET[self::$getLocation])) {  
			return $_GET[self::$getLocation];
		}
		
		return NULL;
	}
	
	/**
	 * Generate HTML form to create a new song bound to a instrument.
	 * 
	 * @param \model\Instrument $owner The instrument that should get the song registred to it.
	 * 
	 * @return String HTML
	 */
	public function getForm(\model\Instrument $owner) { // TODO - fixa song med dropdown etc
		$instrumentUnique = $owner->getInstrumentID();   
		
		$html = "<h1>Add song to ". $owner->getName()."</h1>";
		$html .= "<form action='?action=".NavigationView::$actionAddSong."' method='post'>";
		$html .= "<input type='hidden' name='".self::$instrumentUnique."' value='$instrumentUnique' />";
		$html .= "<input type='text' name='".self::$name."' />";
		$html .= "<input type='submit' value='Add song' />";
		$html .= "</form>";
		
		return $html;
	}
	
	/**
	 * Fetches song name from a form.
	 * 
	 * @return String
	 */
	public function getName() {
		if (isset($_POST[self::$name])) {
			return $_POST[self::$name];
		}
		return null;
	}
	
	/**
	 * Fetches owner unique ID of a song owner.
	 * 
	 * @return String
	 */
	public function getOwnerUnique() {
		if (isset($_POST[self::$instrumentUnique])) {   // TODO - do I use this?!
			return $_POST[self::$instrumentUnique];
		}
		return NULL;
	}
/**
	 * Creates the HTML needed to display a song with all it's details
	 * 
	 * @return String HTML
	 */
	public function show(\model\Song $song) {
			
	//$view = new \view\NavigationView();  // TODO fick bred crums button bass
		
		// RENDER THE 'MENU' with songs 
		//$menu = $view->getInstrumentButton($instrument);
		  
		$ret = '<h1>' . $song->getName() . '</h1>';
		
		//delete-button
		$ret .= "<a href='?".NavigationView::$action."=".NavigationView::$actionDeleteSong."&amp;".self::$getLocation."=" . 
					urlencode($song->getSongID()) ."' class = 'deleteBtn'> Delete song </a>";  // TODO- FIX REALLY NEEDS confirm
		
		$ret .= '<p><span></span></p>';
		
		// NOTES //
		$ret .= '
			<div id="notes"><h2>Notes</h2>
					<textarea id="textbox" name="textarea">'. htmlspecialchars( $song->getNotes()).'</textarea>
					<input type="submit" value="Save" id="edit">
				</form>					
			</div>';
		
		//
		return $ret;
	}	
}

