<?php

class RR_Api_User extends RR_Api_Base {
	
	
	public function __construct() {
		
		parent::__construct();
		
		$this->_method('v1/reviews/user/id/');
		
	}
	
	
}