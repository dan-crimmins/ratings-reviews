<?php

class RR_User_Reviews {
	
	public $results = null;
	
	protected $_guid;
	
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
	
	protected function _results() {
		
		$rr = RR_Api_Request::factory(array('api' 	=> 'user',
											  'type'	=> 'userid',
											  'term'	=> $this->_guid))
								->response();
								
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
		} 
		
	}
	
	protected function _get_product_data($partnumber) {
		
		return Products_Api_Request::factory(array('api' => 'detail',
													'term' 	=> $partnumber))
									->response();
	}
	
	
}