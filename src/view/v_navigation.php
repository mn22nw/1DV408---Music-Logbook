<?php
namespace view;

/**
 * Class containing static methods and functions for navigation.
 */
class NavigationView {
	public static $id = 'id';
	public static $actionDefault = 'default';
	public static $action = "action"; 
	
	//signUp & sign in
	public static $actionSignUp = 'signUp';
	public static $actionSignIn = 'signIn';
	
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
	 * Creates the HTML needed to display the menu for sign in / home default page
	 * When user is NOT logged in
	 * @return String HTML
	 */
	public function getBaseMenuStart() {
		$html = "<div id='menu'>
					<ul>"; 	
		$html .= "<li><a href='?".SignIn::$getAction."=".SignIn::$actionSignIn."'>Sign in</a></li>";  
		$html .= "<li><a href='?".self::$action."=".self::$actionSignUp."'>Sign up</a></li>"; 
		$html .= "</ul></div>";
		return $html;
	}	
	
	
	/**
	 * Get the menu when user is logged in.;
	 * 
	 * @return String HTML
	 */
	public function showMenuLoggedIn(){
		$html = "<div id='menu'>
					<ul>";
		$html .= self::showBaseMenu();
		$html .= $this->songMenu;
		$html .= "</ul></div>";
		return $html;
	}
	
	
	public static function showBaseMenu(){
		$html = "<li><a href='?".self::$action."=".self::$actionShowAll."'>Show all instruments</a></li>";  
		$html .= "<li><a href='?".self::$action."=".self::$actionAddInstrument."'>Add Instrument</a></li>";  
		$html .= "<li><a href='?".self::$action."=".SignIn::$actionSignOut."'>Sign out</a></li>";
		return $html;
	}
	
	
		/**
	 * Creates the HTML needed to display a menu with instrument with a list of songs
	 * 
	 * @return String HTML
	 */
	public function showSongMenu(\model\Instrument $instrument) {  
		$songArray = $instrument->getSongs()->toArray();	
		$view = new \view\NavigationView();
		
		// RENDER THE 'MENU' with songs 
		$menu = $view->getInstrumentButton($instrument);
		
		// UL inside an list element (for proper HTML-syntax)
		$menu .= "<li><ul id='songMenu'>";
		
		foreach($songArray as $song) {
			$menu .= "<li><a href='?".NavigationView::$action."=".NavigationView::$actionShowSong;
			$menu .= "&amp;".InstrumentView::$getLocation."=" . 
					urlencode($instrument->getInstrumentID());
			$menu .= "&amp;".SongView::$getLocation."=" . 
					urlencode($song->getSongID()) ."'>".$song->getName()."</a></li>";
		}
		$menu .= "</ul></li>";	
		
		$this->songMenu = $menu;
	}	
	
	
	
	
	//TODO check link
	//return logo that links to homepage
	public static function getLogo(){
		$html = "<div id='logo'>";
		$html .= "<a href='./". "'><img src='images/logo.png' alt='logo' />  
		</a>";  
		return $html;
	}
	
	
	/**
	 * Return the current action asked for.
	 * 
	 * @return String action
	 */
	public static function getAction() {
		if (isset($_GET[self::$action]))
			return $_GET[self::$action];
		
		return self::$actionDefault;   
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
	 * Redirect to sign in (used after sign up is completed)
	 */
	public static function RedirectToSignUp() {
		header('Location: /' . \Settings::$ROOT_PATH. '/?'.self::$action.'='.self::$actionSignIn);
	}
	
	/*
	 * Redirect to a instrument page.
	 */
	public static function RedirectToInstrument($instrumentID) {
		header('Location: /' . \Settings::$ROOT_PATH . '/?'.self::$action.'='.self::$actionShowInstrument.'&'. InstrumentView::$getLocation. '='.$instrumentID);
	}
	
	/*
	 * Redirect to a song page.
	 */
	public static function RedirectToSong($songID) {
		header('Location: /' . \Settings::$ROOT_PATH . '/?'.self::$action.'='.self::$actionShowSong.'&'. SongView::$getLocation. '='.$songID);
	}  
	
	//Redirect to add song page.
	public static function RedirectToAddSong() {
		header('Location: /' . \Settings::$ROOT_PATH . '/?'.self::$action.'='.self::$actionAddSong);
	} 

	//Redirect to add instrument page.
	public static function RedirectToAddInstrument() {
		header('Location: /' . \Settings::$ROOT_PATH . '/?'.self::$action.'='.self::$actionAddInstrument);
	} 
	
	//Redirect to show All.
	public static function RedirectToShowAllInstruments() {
		header('Location: /' . \Settings::$ROOT_PATH . '/?'.self::$action.'='.self::$actionShowAll);
	} 
	
}
