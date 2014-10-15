<?php
require_once("src/view/HTMLView.php");
require_once("src/controller/c_navigation.php");
require_once("src/view/v_navigation.php");
//maybe session start?

session_start();

$view = new \view\HTMLView();
$nagivationView = new \view\NavigationView();
$navigation = new \controller\Navigation();

$htmlArray = $navigation->doControll();

$htmlBody = $htmlArray[0];

$htmlMenu = $nagivationView->getMenu();
	
	if (!empty($htmlArray[1])) {    //TODO - set songMenu in NavigationController or earlier in index under doControll??
		$nagivationView->setSongMenu($htmlArray[1]);
		$htmlMenu = $nagivationView->getMenu();
	}

$head = '<link rel="stylesheet" type="text/css" href="css/main.css">';
$head .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>';

$view->echoHTML("Music Logbook - Home", $head, $htmlBody, $htmlMenu);
