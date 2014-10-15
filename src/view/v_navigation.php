<?php
namespace view;

/**
 * Class containing static methods and functions for navigation.
 */
class NavigationView {
	public static $action = 'action';
	public static $id = 'id';
	
	//Instrument
	public static $actionAddInstrument = 'add';   
	public static $actionShowInstrument = 'show';
	public static $actionDeleteInstrument = 'deleteInstrument';
	public static $actionShowAll = 'showAll';
	public static $actionSetMainInstrument = 'mainInstrument';
	//Song
	public static $actionAddSong = 'addSong';
	public static $actionShowSong = 'showSong';
	public static $actionDeleteSong = 'deleteSong';
	
	private $songMenu = "";
	
	/**
	 * Get the base menu with correct routed actions.;
	 * 
	 * @return String HTML
	 */
	public function getMenu(){
		$html = "<div id='menu'>
					<ul>";
		$html .= self::getBaseMenu();
		$html .= $this->songMenu;
		$html .= "</ul></div>";
		return $html;
	}
	
	public static function getBaseMenu(){
		$html = "<li><a href='?".self::$action."=".self::$actionShowAll."'>Show all instruments</a></li>";  //&nbsp = TAB TODO remove comment 
		$html .= "<li><a href='?".self::$action."=".self::$actionAddInstrument."'>Add Instrument</a></li>";  // TODO - add list
		return $html;
	}
	
	//takes parameter containg html
	public function setSongMenu($songMenu) {
		$this->songMenu = $songMenu;
	}
	
	//return logo that links to homepage
	public static function getLogo(){
		$html = "<div id='logo'>";
		$html .= "<a href='?".self::$action."=".self::$actionShowAll."'><img src='images/logo.png' alt='logo' />
		</a>";  
		return $html;
	}
	
	
	/**
	 * Return the current action asked for.
	 * 
	 * @todo Transform the action to a class of it's own?
	 * 
	 * @return String action
	 */
	public static function getAction() {
		if (isset($_GET[self::$action]))
			return $_GET[self::$action];
		
		return self::$actionShowAll;
	}
	
	/**
	 * Get a generic ID field.
	 * 
	 * @todo Create a "setId()" to connect it to the routing?
	 * 
	 * @return String
	 */
	public static function getId() {
		if (isset($_GET[self::$id])) {
			return $_GET[self::$id];
		}
		
		return NULL;
	}
	
	/**
	 * Redirect to home URL
	 */
	public static function RedirectHome() {
		header('Location: /' . \Settings::$ROOT_PATH. '/');
	}

	/**
	 * Redirect to error URL
	 */
	public static function RedirectToErrorPage() {
		header('Location: /' . \Settings::$ROOT_PATH. '/error.html');
	}
	
	
	/**
	 * get html for Instrument Button
	 */
	public static function getInstrumentButton($instrument) {
		$button ="<li><a href='?action=".NavigationView::$actionShowInstrument."&amp;".InstrumentView::$getLocation."=" . 
					urlencode($instrument->getInstrumentID()) ."'id='instrumentBtn'>" .
					$instrument->getName()."</a></li>";
		return $button;
	}
	
	
	/**
	 * Redirect to a instrument page.
	 * 
	 * @todo Move to instrument view?
	 * 
	 * @param String $uniqueString unique key for the instrument.
	 */
	public static function RedirectToInstrument($uniqueString) {
		header('Location: /' . \Settings::$ROOT_PATH. '/?'.self::$action.'='.self::$actionShowInstrument.' &amp;'. InstrumentView::$getLocation.'='.$uniqueString);
	}
}
