<?php
//Product page URL www.{url}.com/shc/s/p_{store_id}_{catalog_id}_{product_id}
class RR_Cache {
	
	protected $_cache_key_prefix = 'rr_cache_';
	
	protected $_ttl = 7200;
	
	public $cache_key;
	
	public $data;
	
	public function __construct($id) {
		
		$this->cache_key = md5($this->_cache_key_prefix . $id);
		//delete_transient($this->cache_key);
	}
	
	public static function factory($id) {
		
		return new RR_Cache($id);
	}
	
	public function get() {
		
		$this->data = get_transient($this->cache_key);
		
		return $this;
	}
	
	public function set($data) {
		
		set_transient($this->cache_key, $data, $this->_ttl);
	}
}
