<?php

class RR_Api_Recent extends RR_Api_Base implements RR_Api_Type {
	
	
	public function __construct() {
		
		parent::__construct();
		
		$this->_method('v1/reviews/today');
	}
	
	public function store($store) {
		
		$this->_param('originSite', $store);
		
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