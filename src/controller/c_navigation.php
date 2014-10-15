<?php
namespace controller;
require_once('./src/view/v_navigation.php');
require_once('./src/controller/c_instrument.php');
require_once('Settings.php');

/**
 * Navigation view for a simple routing solution.
 */
class Navigation {
	private $htmlArray = array();
	
	
	/**
	 * Checks what controller to instansiate and return value of to HTMLView.
	 */
	public function doControll() {
			
		$view = new \view\NavigationView();
		$controller;

		try {
			switch ($view::getAction()) {
					
				// ADD INSTRUMENT //
				case $view::$actionAddInstrument:
					$controller = new InstrumentController();
					array_push($this->htmlArray, $controller->addInstrument(), $controller->showSongMenu()) ;
					return $this->htmlArray;
					break;
				
				// SHOW INSTRUMENT //
				case $view::$actionShowInstrument:
					$controller = new InstrumentController();		
					array_push($this->htmlArray, $controller->show(), $controller->showSongMenu()) ;
					return $this->htmlArray;
					break;
					
				// DELETE INSTRUMENT //	
				case $view::$actionDeleteInstrument:
					$controller = new InstrumentController();
					array_push($this->htmlArray, $controller->deleteInstrument());
					return $this->htmlArray;
				
				// ADD SONG //	
				case $view::$actionAddSong:
					$controller = new InstrumentController();
					array_push($this->htmlArray, $controller->addSong(), $controller->showSongMenu());
					return $this->htmlArray;
				
				// SHOW SONG //		
				case $view::$actionShowSong:
					$controller = new InstrumentController();
					array_push($this->htmlArray, $controller->showSong(), $controller->showSongMenu());
					return $this->htmlArray; 
				
				// DELETE SONG //		
				case $view::$actionDeleteSong:
					$controller = new InstrumentController();
					array_push($this->htmlArray, $controller->deleteSong(), $controller->showSongMenu());
					return $this->htmlArray; 
				
				// SET MAIN INSTRUMENT //		
				case $view::$actionSetMainInstrument:
					$controller = new InstrumentController();
					array_push($this->htmlArray, $controller->setMainInstrument(), $controller->showSongMenu());
					return $this->htmlArray;
					
				default:
					$controller = new InstrumentController();
					array_push($this->htmlArray, $controller->showAllInstruments(), $controller->showSongMenu());
					return $this->htmlArray;
					break;
			}
		} catch (\Exception $e) {

			error_log($e->getMessage() . "\n", 3, \Settings::$ERROR_LOG);
			if (\Settings::$DO_DEBUG) {
				throw $e;
			} else {
				\view\NavigationView::RedirectToErrorPage();
				die();
			}
		}
	}
}
