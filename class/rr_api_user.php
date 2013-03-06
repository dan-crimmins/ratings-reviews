<?php

class RR_Api_User extends RR_Api_Base {
	
	
	public function __construct() {
		
		parent::__construct();
		
		$this->_method('v1/reviews/user/id/');
		
	}
	
	public function userid($userid) {
		
		$this->_uri_params($userid);
	}
	
	public function load() {
		
		$this->_load();
		
		return $this;
	}
	
	public function response() {
		
		return $this->_response;
	}
	
}