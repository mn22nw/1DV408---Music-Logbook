<?php
  namespace view;

  class HTMLView {

    /** 
      * Creates a HTML page. I blame the indentation
      * on webbrowser and PHP.
      *
      * @param string $title - The page title
      * @param string $body - The middle part of the page
      * @return string - The whole page
      */
		public function echoHTML($title, $head, $body, $htmlMenu) {
			if ($body === NULL) {
				throw new \Exception("HTMLView::echoHTML does not allow body to be null");
			}

			$html = "
				<!DOCTYPE html>
				<html>
				<head>
				<meta charset=\"utf-8\">
				<title>".$title."</title>
			    $head
				</head>
				<body>";
			$html .= "<div id='page'> ";
			$html .= "<div id='fluid'><!-- Gör headerdelen + main, responsiv-->
						<div id='column-right'> <!--innehåller main + header;-->";
			$html .= $body;
			$html .= "	</div>
					</div>";
			
			$html .="<div id='fixed-width'> <!-- Gör vänstra delen (med menyn) statisk-->
				<div id='column-left'>";
			$html .= NavigationView::getLogo();
			$html .= $htmlMenu;
			$html .= "</div>"; // END column left
			$html .= "</div>"; // END fixed-width
			
			$html .= "</div>"; // END page
			$html .= "<script type='text/javascript' src='script/my_jquery.js'></script>";
			$html .= "</body>
				</html>";
				
			echo $html;
		}
}
