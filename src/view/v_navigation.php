<?php
namespace view;

/**
 * Class containing static methods and functions for navigation.
 */
class NavigationView {
	public static $action = 'action';
	public static $id = 'id';
	public static $actionDefault = 'default';
	
	//Login and Register
	public static $actionLogin = 'login';
	public static $actionSignOut = 'logOut';
	public static $actionRegister = 'register';
	
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
	 * Creates the HTML needed to display the menu for login / home default page
	 * When user is NOT logged in
	 * @return String HTML
	 */
	public function getBaseMenuStart() {
		$html = "<div id='menu'>
					<ul>"; 	
		$html .= "<li><a href='?".self::$action."=".self::$actionLogin."'>Login</a></li>";  
		$html .= "<li><a href='?".self::$action."=".self::$actionRegister."'>Register</a></li>"; 
		$html .= "</ul></div>";
		return $html;
	}	
	
	
	/**
	 * Get the menu when user is logged in.;
	 * 
	 * @return String HTML
	 */
	public function getMenuLoggedIn(){
		$html = "<div id='menu'>
					<ul>";
		$html .= self::getBaseMenu();
		$html .= $this->songMenu;
		$html .= "</ul></div>";
		return $html;
	}
	
	
	public static function getBaseMenu(){
		$html = "<li><a href='?".self::$action."=".self::$actionShowAll."'>Show all instruments</a></li>";  
		$html .= "<li><a href='?".self::$action."=".self::$actionAddInstrument."'>Add Instrument</a></li>";  
		$html .= "<li><a href='?".self::$action."=".self::$actionSignOut."'>Sign out</a></li>";
		return $html;
	}
	
	
	//takes parameter containg html
	public function setSongMenu($songMenu) {
		$this->songMenu = $songMenu;
	}
	
	//return logo that links to homepage
	public static function getLogo(){
		$html = "<div id='logo'>";
		$html .= "<a href='?".self::$action."=".self::$actionDefault."'><img src='images/logo.png' alt='logo' />
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
	public static function getId() {   // TODO is this used? maybe remove
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
	
	public static function getInstrumentBreadCrum($instrument) {
		$button ="<a href='?action=".NavigationView::$actionShowInstrument."&amp;".InstrumentView::$getLocation."=" . 
					urlencode($instrument->getInstrumentID()) ."'id='instrumentBreadcrum'>" .
					$instrument->getName()."</a>";
		return $button;	
	}
	
	//Redirect to a instrument page.
	public static function RedirectToInstrument($instrumentID) {
		header('Location: /' . \Settings::$ROOT_PATH . '/?'.self::$action.'='.self::$actionShowInstrument.'&'. InstrumentView::$getLocation. '='.$instrumentID);
	}
	
	//Redirect to a song page.
	public static function RedirectToSong($songID) {
		header('Location: /' . \Settings::$ROOT_PATH . '/?'.self::$action.'='.self::$actionShowSong.'&'. SongView::$getLocation. '='.$songID);
	}  
	
	//Redirect to add song page.
	public static function RedirectToAddSong() {
		header('Location: /' . \Settings::$ROOT_PATH . '/?'.self::$action.'='.self::$actionAddSong);
	} 

	//Redirect to add song page.
	public static function RedirectToAddInstrument() {
		header('Location: /' . \Settings::$ROOT_PATH . '/?'.self::$action.'='.self::$actionAddInstrument);
	} 
	
}
