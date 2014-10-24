<?php
namespace view;

require_once('./src/model/m_song.php');

class SongView {
	public static $getLocation = 'song';
	
	private static $name = 'name';
	private static $instrumentID = 'instrumentID';
	private static $textarea = 'textarea';
	
	private $sessionHelper;
	
	function __construct() {
		$this->sessionHelper = new \helper\SessionHelper();
	}
	
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
	
	/**
	 * get information from textarea 
	 * 
	 * @return string (notes from textarea)
	 */
	public function getNotes() {
		
		if(isset($_POST[self::$textarea])) {
			return $_POST[self::$textarea];
		}
		
		return "";
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
	public function getForm(\model\Instrument $owner) { 
		$instrumentID = $owner->getInstrumentID();   
		$html = "<div id='addSong'>";
		$html .= "<h1>Add song to ". $owner->getName()."</h1>";
		$html .= "<form action='?action=".NavigationView::$actionAddSong."' method='post'>";
		$html .= "<input type='hidden' name='".self::$instrumentID."' value='$instrumentID' />";
		$html .= "<input type='text' name='".self::$name."' />";
		$html .= "<input type='submit' value='Add song' class='submit'/>";
		$html .= "<div class='errorMessage'><p>". $this->sessionHelper->getAlert() ."</p></div>";
		$html .= "</form>";
		$html .= "</div>";
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
	 * it is used when creating a new song
	 * @return String
	 */ 
	public function getOwner() {
		if (isset($_POST[self::$instrumentID])) {   
			return $_POST[self::$instrumentID];
		}
		return NULL;
	}
/**
	 * Creates the HTML needed to display a song with all it's details
	 * 
	 * @return String HTML
	 */
	public function show(\model\Song $song, \model\Instrument $instrument, $timerOn = false) {
			
		$view = new \view\NavigationView();  // TODO fix bread crums button bass
		$html  = "<div id='songOverview'>";
		$html .=  $view->getInstrumentBreadCrum($instrument);
		$html .= '<h1>' . $song->getName() . '</h1>';
		
		//delete-button
		$html .= "<a href='?".NavigationView::$action."=".NavigationView::$actionDeleteSong."&amp;".self::$getLocation."=" . 
					urlencode($song->getSongID()) ."' class = 'deleteBtn'> Delete song </a>";  // TODO- FIX REALLY NEEDS confirm
		
		//total practice time
		$html .= '<div id= "practiceTime"><p><span> Total practice time: </span>'. $song->getTotalPracticetime(). '</p></div>';
		
		if ($timerOn == false) {
			// FORM - with start button for timer
			$html .= "<form action='?".NavigationView::$action."=".NavigationView::$actionStartTimer."&amp;".self::$getLocation."=" . 
						urlencode($song->getSongID()) ."' method='post'>
		    <input type='submit' value='Start timer' id='startTimer'>
			</form>";
		}
		
		if ($timerOn == true) {
			// FORM - with stop button for timer
			$html .= "<form action='?".NavigationView::$action."=".NavigationView::$actionStopTimer."&amp;".self::$getLocation."=" . 
						urlencode($song->getSongID()) ."' method='post'>
		    <input type='submit' value='Stop timer' id='stopTimer'>
			</form>";
		}
		
		$html .="<h3>Notes</h3>";
		// FORM - for notes
		$html .= "<form action='?".NavigationView::$action."=".NavigationView::$actionSaveNotes."&amp;".self::$getLocation."=" . 
					urlencode($song->getSongID()) ."' method='post'>";
		$html .= '<textarea name="'.self::$textarea.'" id="notes" spellcheck="false" maxlength="1000">' . htmlspecialchars($song->getNotes()). '</textarea>
		 <input type="submit" name="submitNotes" value="Save notes" class="submit" /> 
		';
		
		//save-notes-button
		$html .= "<div class='errorMessage'><p>". $this->sessionHelper->getAlert() ."</p></div>";
		$html .= "</form>";				
		$html .= '</div>';
		$html .= '</div>';
		
		return $html;
	}	
}

