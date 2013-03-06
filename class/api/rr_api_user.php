<?php

class RR_Api_User extends RR_Api_Base implements RR_Api_Type {
	
	
	public function __construct() {
		
		parent::__construct();
		
		$this->_method('v1/reviews/user/id/');
		
	}
	
	public function userid($user_id) {
		
		$this->_uri_params($user_id);
		
		return $this;
	}
	
	public function load() {
		
		$this->_load();
		
		return $this;
	}
	
	public function response() {
		
		return $this->_response;
	}
	
}