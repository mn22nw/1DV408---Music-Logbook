<?php
namespace view;

class HTMLBody {
	
	private $body;
	private $menu;
	private $script;
	
	/**
	 * set body for HTML 
	 * 
	 * @param String HTML
	 * 
	 */
	public function setBody($body) {
		
		$this->body = $body;
	}
	
	/**
	 * get HTML for the body
	 * 
	 * @return String HTML
	 */
	public function getBody() {
		return $this->body;
	}
	
	/**
	 * sets the menu to be displayed 
	 * 
	 * @param String HTML
	 */
	public function setMenu($menu) {
		$this->menu = $menu;
	}
	
	
	/**
	 * Get the menu to be displayed 
	 * 
	 * @return String HTML
	 */
	public function getMenu() { 
		return $this->menu;
	}
	
	public function setScript($script) {
		$this->script = $script;
	}
	
	public function getScript() {  
		if(!empty($this->script))
			return $this->script;
		
		return "";
	}
}

