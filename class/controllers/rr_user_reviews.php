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
	
	protected $_store;
	
	
	
	public function __construct($sso_guid) {
		
		if(! is_plugin_active('products/plugin.php')) {
			
			throw new Exception('RR_User_Reviews requires the Products plugin.');
		}
		
		$this->_store = ((stripos(get_bloginfo('name'), 'sears')) !== false) ? 'sears.com' : 'kmart.com';
		$this->_guid = $sso_guid;
		
	}
	
	public static function factory($sso_guid) {
		
		return new RR_User_Reviews($sso_guid);
	}
	
	public function page($page) {
		
		$this->_page = $page;
		
		return $this;
	}
	
	protected function _paginate() {
		
		$num_results = count((array) $this->results);
		$end = $num_results - 1;
		
		if($num_results > $this->_posts_per_page) {
			
			$total_pages = ceil($num_results / $this->_posts_per_page);
			
			if($this->_page <= $total_pages) {
			
				for($i = 1; $i < $this->_page; $i++) {
					
					$this->_offset = $this->_offset + $this->_posts_per_page;
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
	
	public function get() {
		
		$cache_key = md5('rr_user-' . get_bloginfo('name') . '-' . $this->_guid);
		
		//if(! $cached = RR_Cache::factory($cache_key)->get()->data) {
		
			$rr = RR_Api_Request::factory(array('api' 	=> 'user',
												  'type'	=> 'userid',
												  'term'	=> $this->_guid))
									->response();
									
		
		/*} else {
			
			$this->results = $cached;
			$this->is_cached = true;
			
			$this->_get_store_reviews();
			$this->_paginate();
			
			return $this;
		}*/
								
		if($rr->success && $rr->num_reviews > 0) {
			
			$reviews = $rr->reviews;
			
			foreach($reviews as $key=>$review) {
				
				$product = $this->_get_product_data($review->target_id);

				$prod_data = ($product->success) ? new stdClass() : null;
				
				//Set product attributes
				if($prod_data) {
					
					$prod_data->description = $product->descriptionname;
					$prod_data->image = str_replace('http://', 'https://', $product->mainimageurl);
					$prod_data->saleprice = $product->saleprice;
					$prod_data->regularprice = $product->regularprice;
					$prod_data->brandname = $product->brandname;
					$prod_data->rating = $product->rating;
					$prod_data->numreview = $product->numreview;
					$prod_data->storeid = $product->storeid;
					$prod_data->catalogid = $product->catalogid;
					$prod_data->product_uri = $product->product_uri;
					
					$reviews[$key]->product_data = $prod_data;
				    $reviews[$key]->edit_link = $this->_edit_link($reviews[$key]);
				    $reviews[$key]->all_reviews_link = $this->_all_reviews_link($reviews[$key]);
					
				} else {
					
					//If there's no product data, remove the review
					unset($reviews[$key]);
				}
				
			}
			
			$this->results = $rr->reviews = $reviews;
			
			$this->_get_store_reviews();
			$this->_paginate();
			
			RR_Cache::factory($cache_key)->set($this->results);
		}

		return $this;	
	}
	
	protected function _get_product_data($partnumber) {
		
		return Products_Api_Request::factory(array('api' => 'detail',
													'term' 	=> $partnumber))
									->response();
	}
	
	protected function _edit_link($review) {
		
		return "https://www.{$review->origin_site}/shc/s/ProfileCreateReview?reviewId={$review->review_id}&catalogId={$review->product_data->catalogid}&langId=-1&requestType=edit_review&storeId={$review->product_data->storeid}&i_cntr=1363128024441&loginFlow=Yes";
		
	}
	
	protected function _all_reviews_link($review) {
		
		return "http://www.{$review->origin_site}/" . str_replace('p_', 'allmodreviews_', $review->product_data->product_uri) . "?targetType=seeAllReviews";
	}
	
	protected function _get_store_reviews() {	
		
		foreach((array)$this->results as $key=>$result) {
			
			if($result->origin_site != $this->_store) {
				
				unset($this->results[$key]);
			}
		}
	}
	
}