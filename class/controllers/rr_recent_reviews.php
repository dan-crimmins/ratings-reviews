<?php

class RR_Recent_Reviews {
	
	protected $_limit = 3;
	
	protected $_store;

	public $is_cached = false;
	
	public $results;
	
	public function __construct() {
		
		$this->_store = ((stripos(get_bloginfo('name'), 'sears')) !== false) ? 'sears.com' : 'kmart.com';
	}
	
	public static function factory() {
		
		return new RR_Recent_Reviews();
	}
	
	public function get() {
		
		$cache_key = md5('recent-' . get_bloginfo('name') . date('Ymd'));
		
		if(! $cached = RR_Cache::factory($cache_key)->get()->data) {
		
			$rr = RR_Api_Request::factory(array('api' 	=> 'recent',
												  'type'	=> 'store',
												  'term'	=> $this->_store))
									->response();
									
			if($rr->success && $rr->num_reviews > 0) {
				
				$this->results = array_slice($this->_set_all_reviews_uri($rr->reviews), 0, $this->_limit);
				RR_Cache::factory($cache_key)->set($this->results);
			}
									
			
		} else {
			
			$this->results = $cached;
			$this->is_cached = true;
			
		}
		
		return $this;
	}
	
	public function limit($num) {
		
		$this->_limit = $num;
		
		return $this;
	}
	
	protected function _set_all_reviews_uri($reviews) {
		
		foreach((array) $reviews as $key=>$review) {
			
			$url =  'http://www.' . $review->reviews[0]->origin_site . '/shc/s/';
			
			//Extract product string from product page url (in element 5)
			$prod_parts = explode('/', $review->target_url);
			$reviews[$key]->all_reviews_url = $url . str_replace('p_', 'allmodreviews_', $prod_parts[5]) . '?targetType=seeAllReviews';
		}
		
		return $reviews;
	}
	
}