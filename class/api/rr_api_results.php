<?php

class RR_Api_Results {
	
	/**
	 * _raw_response - contains raw api response
	 * 
	 * @var object
	 */
	protected $_raw_response;
	
	/**
	 * api_data_type - the api repsonse type
	 * 
	 * @var string [detail | search]
	 */
	public $api_data_type;
	
	/**
	 * data - contains overload properties
	 * 
	 * @var array
	 */
	public $data = array();
	
	/**
	 * __construct
	 * 
	 * @param object Products_Api_Type $obj - an object that implements Interface Products_Api_Type
	 * @return void
	 * 
	 * @uses _obj_type()
	 * @uses _set_properties()
	 */
	public function __construct(RR_Api_Type $obj) {
		
		$this->_obj_type($obj);
		
		$this->_raw_response = $obj->response();
		
		$this->success = $this->_is_success($obj);
		
		//$this->num_pages = $obj->num_pages();
		
		//$this->num_products = $obj->num_products();
		
		//$this->page = $obj->page();
		
		if($this->success)
			$this->_set_properties();
		
	}
	
	/**
	 * _obj_type() - sets api_data_type property
	 * 
	 * @param object $obj (from constructor)
	 * @return void
	 */
	protected function _obj_type($obj) {
		
		if(is_a($obj, 'RR_Api_User'))
			$this->api_data_type = 'user';
			
	}
	
	protected function _is_success($obj) {
		
		if(! $obj->success)
			return false;
			
		//Add a check here for ratings returned
	}
	
	/**
	 * __get() - Magic method for retrieving overloaded properties.
	 * 
	 * @param string $name
	 * @return mixed - the value of the property being accessed
	 * or null if not found.
	 */
	public function __get($name) {
		
		if(isset($this->data[$name])) {
			
			return $this->data[$name];
		} 
		
		return null;
	}
	
	/**
	 * __set() - Magic method to set overload properties.
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		
		$this->data[$name] = $value;
	}
	
	/**
	 * __isset() - Magic method to check for the existence of an
	 * overload property.
	 * 
	 * @param string $name
	 * @return bool
	 */
	public function __isset($name) {
		
		return isset($this->data[$name]);
	}
	
	/**
	 * _set_properties() 
	 * 
	 * Calls either _set_search_properties or _set_detail_properties. Depending
	 * on the which api the response is from.
	 * 
	 * @param void
	 * @return void
	 * @uses _set_search_properties()
	 * @uses _set_detail_properties()
	 */
	protected function _set_properties() {
		
		if($this->api_data_type == 'user')
			
			$this->_set_user_properties();
		
	}
	
	protected function _set_user_properties() {
		
	}
	
}