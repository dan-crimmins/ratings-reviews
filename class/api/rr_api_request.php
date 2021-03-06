<?php

class RR_Api_Request {
	
	protected $_allowed_args = array('api'		=> array('user',
														 'recent'),
									 'type'		=> array('userid'));
	
	protected $_args;
	
	protected $_obj;
	
	public $cached = false;

	public function __construct($args) {
		
		$this->_validate_args($args);
		
		$this->_args = $args;
		
		try {
			 
			$this->$args['api']();
			
		} catch (Exception $e) {
			
			die($e->getMessage());
		}
		
	}
	
	public static function factory($args) {
		
		return new RR_Api_Request($args);
	}
	
	public function user() {
		
		$request = RR_Api_Base::factory('user')
								->{$this->_args['type']}($this->_args['term'])
								->load();
		
									
		$this->_obj = new RR_Api_Results($request);
			
	}
	
	public function recent() {
		
		$request = RR_Api_Base::factory('recent')
								->{$this->_args['type']}($this->_args['term'])
								->load();
		
									
		$this->_obj = new RR_Api_Results($request);
	}
	
	
	public function response() {
		
		return $this->_obj;
	}
	
	protected function _validate_args(array $args) {
		
		if(! isset($args['api']) || ! in_array($args['api'], $this->_allowed_args['api'])) 
			throw new Exception('You must include an api attribute in args. eg. user');
			
		if(! isset($args['term']))
			throw new Exception('You must include a term attribute in the args array.');
			
		if($args['api'] == 'user') {
			
			if(! is_numeric($args['term']))
				throw new Exception('The term arg must be numeric. Term should be the SSO guid for the user');
		} 
		
	}
	
}