<?php

class RR_Recent_Reviews {
	
	protected $_limit = 3;
	
	protected $_store;

	public $is_cached = false;
	
	public $results;
	
	public function __construct() {
		
		$this->_store = (stripos(get_bloginfo('name'), 'sears')) ? 'sears.com' : 'kmart.com';
	}
	
	public static function factory() {
		
		return new RR_Recent_Reviews();
	}
	
	public function get() {
		
		if(! $cached = RR_Cache::factory('recents')->get()->data) {
		
			$rr = RR_Api_Request::factory(array('api' 	=> 'recent',
												  'type'	=> 'store',
												  'term'	=> $this->_store))
									->response();
									
			
		} else {
			
			$this->results = $cached;
			$this->is_cached = true;
			
			return $this;
		}
	}
	
	public function limit($num) {
		
		$this->_limit = $num;
		
		return $this;
	}
	
	
	
	
}