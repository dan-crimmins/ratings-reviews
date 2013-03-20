<?php

class RR_Admin_Settings {
	
	public $settings_field;
	
	public $options;
	
	public $prefix;
	
	public function __construct() {
		
		$this->prefix = SHC_RR_PREFIX;
		$this->settings_field = SHC_RR_PREFIX . "settings";
		$this->options = RR_Utilities::options();
		
		add_action('admin_menu', array(&$this, 'menu'));
        add_action('admin_init', array(&$this, 'register_settings'));
		
	}
	
	public function menu() {
		
		add_options_page('SK Ratings &amp; Reviews Settings', 'Ratings &amp; Reviews Settings', 'manage_options', 'sk-rr-settings', array(&$this, 'settings_page'));
	}
	
	public function register_settings() {
		
		register_setting($this->settings_field, $this->settings_field);
		
		//API settings
		add_settings_section($this->prefix . 'api_section', __('Ratings &amp; Reviews API Settings'), array(&$this, 'api_section'), 'sk-rr-settings');
        add_settings_field('api_env', __('R&R API Environment'), array(&$this, 'api_env'), 'sk-rr-settings', SHC_RR_PREFIX . 'api_section');
		
	}
	
	public function api_section() {
		
		 echo '<p>' . __('Ratings &amp; Reviews API parameters.') . '</p>';
	}
	
	
	public function api_env() {
		
		RR_Utilities::view('form/input_select', array('name' 		=> $this->settings_field . '[api_env]',
														'id'	 	=> SHC_RR_PREFIX . 'api-env',
														'selected' 	=> $this->options['api_env'],
														'options'	=> array('prod'	=> 'Production',
																			'qa'	=>	'QA',
																			'dev'	=>	'Development')));
		
	}
	
	
	public function settings_page() {
		
		RR_Utilities::view('admin/settings', array('settings_field' => $this->settings_field,
													'settings_section' => 'sk-rr-settings'));
	}

}