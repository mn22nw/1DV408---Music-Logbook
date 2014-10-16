<?php
require_once("src/view/HTMLView.php");
require_once("src/controller/c_navigation.php");
require_once("src/view/v_navigation.php");
require_once("src/controller/c_login.php");
  require_once("src/controller/c_register.php");
 
session_start();
//Views
$view = new \view\HTMLView();
$nagivationView = new \view\NavigationView();
//Controllers
$navigation = new \controller\Navigation();
$registerController = new \controller\Register();

$head  = '<link rel="stylesheet" type="text/css" href="css/main.css">';
$head .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>';

$htmlArray = $navigation->doControll();
 

$htmlBody = $htmlArray[0];

//$htmlMenu = $nagivationView->getBaseMenuStart();
	
	if (!empty($htmlArray[1])) {    //TODO - set songMenu in NavigationController or earlier in index under doControll??
		$nagivationView->setSongMenu($htmlArray[1]);
		$htmlMenu = $nagivationView->getMenuLoggedIn();
	}
	
	If (!empty($htmlArray[2])) {  
		$htmlMenu = $nagivationView->getBaseMenuStart();
	}

$view->echoHTML("Music Logbook - Home", $head, $htmlBody, $htmlMenu);
