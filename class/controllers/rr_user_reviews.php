<?php

class RR_User_Reviews {
	
	public $results;
	
	protected $_guid;
	
	public $next_page;
	
	public $prev_page;
	
	public $is_cached = false;
	
	protected $_posts_per_page = 20;
	
	protected $_offset = 0;
	
	protected $_page = 1;
	
	
	
	public function __construct($sso_guid) {
		
		if(! is_plugin_active('products/plugin.php')) {
			
			throw new Exception('RR_User_Reviews requires the Products plugin.');
		}
		
		$this->_guid = $sso_guid;
		$this->_results();
	}
	
	public static function factory($sso_guid) {
		
		return new RR_User_Reviews($sso_guid);
	}
	
	public function page($page) {
		
		$this->_page = $page;
	}
	
	protected function _paginate() {
		
		$num_results = count((array) $this->results);
		$end = $num_results - 1;
		
		if($num_results > $this->_posts_per_page) {
			
			$total_pages = ceil($num_results / $this->_posts_per_page);
			
			if($this->_page <= $total_pages) {
			
				for($i = 1; $i < $this->_page; $i++) {
					
					$this->_offset = $this->_offset + $this->posts_per_page;
				}
				
					if(isset($this->results[$this->_offset])) {
						
						$last = (($this->_offset + $this->_posts_per_page) > $end) ? $end : $this->_posts_per_page;
						$this->results = array_slice($this->results, $this->_offset, $last);
						
						$this->next_page = (($this->_page + 1) <= $total_pages) ? $this->_page + 1 : null;
						$this->prev_page = ($this->_page != 1) ? ($this->_page - 1) : null;
					}
			}
		}  
		
			
	}
	
	protected function _results() {
		
		if(! $cached = RR_Cache::factory($this->_guid)->get()->data) {
		
			$rr = RR_Api_Request::factory(array('api' 	=> 'user',
												  'type'	=> 'userid',
												  'term'	=> $this->_guid))
									->response();
									
		
		} else {
			
			$this->results = $cached;
			$this->is_cached = true;
			
			$this->_paginate();
			
			return $this;
		}
								
		if($rr->success && $rr->num_reviews > 0) {
			
			$reviews = $rr->reviews;
			
			foreach($reviews as $key=>$review) {
				
				$product = $this->_get_product_data($review->target_id);

				$prod_data = ($product->success) ? new stdClass() : null;
				
				//Set product attributes
				if($prod_data) {
					
					$prod_data->description = $product->descriptionname;
					$prod_data->image = $product->mainimageurl;
					$prod_data->saleprice = $product->saleprice;
					$prod_data->regularprice = $product->regularprice;
					$prod_data->brandname = $product->brandname;
					$prod_data->rating = $product->rating;
					$prod_data->numreview = $product->numreview;
				}
				
				
				$reviews[$key]->product_data = $prod_data;
					
			}
			
			$this->results = $rr->reviews = $reviews;
			
			$this->_paginate();
			
			RR_Cache::factory($this->_guid)->set($this->results);
		} 
		
	}
	
	protected function _get_product_data($partnumber) {
		
		return Products_Api_Request::factory(array('api' => 'detail',
													'term' 	=> $partnumber))
									->response();
	}
	
	
}