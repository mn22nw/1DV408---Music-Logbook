<?php
require_once("src/view/HTMLView.php");
require_once("src/controller/c_navigation.php");
require_once("src/view/v_navigation.php");
require_once("src/controller/c_signIn.php");
  require_once("src/controller/c_signUp.php");
 
session_start();
//Views
$view = new \view\HTMLView();
$nagivationView = new \view\NavigationView();
//Controllers
$navigation = new \controller\Navigation();
$signUpController = new \controller\signUp();

$head  = '<link rel="stylesheet" type="text/css" href="css/main.css">';
$head .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>';

$htmlBody = $navigation->doControll(); 

$view->echoHTML("Music Logbook - Home", $head, $htmlBody->getBody(), $htmlBody->getMenu());
