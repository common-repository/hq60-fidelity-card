<?php

/**
 * Admin class.
 * 
 * @version 1.0
 * @since 1.0
 * 
 */

namespace Hq60fidelitycard\Admin;

class Admin extends \Hq60fidelitycard\Admin\AdminMenu {
	
	/**
	 * Error placeholder for admin
	 * 
	 * @var string
	 * @since 1.0
	 */
	private $_error;
	
	/**
	 * Our contructor
	 * 
	 */
	public function __construct($base_directory_plugin = null , $plugin_basename = null , $plugin_dir_url = null) {
		
		parent::__construct($base_directory_plugin , $plugin_basename , $plugin_dir_url);
		
		// start the fidelity integration
		$this->start_hq60(); 
		
		// save option
		$this->admin_add_action();
		
	}
	
	/**
	 * Start HQ60 (if is possible)
	 * 
	 * @return object HQ60 or null if token is empty
	 * 
	 */
	private function start_hq60() {
		
		// get the token from
		
		delete_option( $this->get_prefix().'option_customer_name' );
		
		$token = get_option($this->get_prefix().'option_token');
		
		if ( is_string($token) && !is_null($token) ) {
			
			$this->set_hq60_token($token);
			
			$hq60 = new \Hq60\Hq60($this->get_hq60_token());
			
			if ( is_string ( $hq60->get_token() ) ) {
				
				// save the customer name into the database
				$customer_name = $hq60->get_customer_name();
				if ( $customer_name != null ) {
					
					update_option ( $this->get_prefix().'option_customer_name' , $customer_name );
					
				}
				
				
				$this->set_hq60($hq60);
				
			} else {
				
				$error = __('Token non inserito oppure non valido' , $this->get_language_domain());
				$this->set_error($error);
				
			}
			
		}
		
	}
	
	/**
	 * 
	 * Get the customer name
	 *
	 * @since 1.0
	 * 
	 * @return string if any or null if empty
	 *  
	 */
	protected function get_customer_name() {
		
		$customer_name = get_option( $this->get_prefix().'option_customer_name' );
		
		/*$hq60 = $this->get_hq60();
		
		if ( is_object($hq60) ) {
			
			$customer_name = $hq60->get_customer_name();
			
		}*/
		
		return $customer_name;
		
	}
	
	/**
     * Add all action for admin
	 * 
	 * @since 1.0 
     */
	
	private function admin_add_action() {
		
		add_action ( 'init' 					, array ( $this , 'save_options_to_database' ) );
		add_action ( 'admin_enqueue_scripts'	, array ( $this , 'load_admin_css' ) );
		
	}
	
	/**
	 * Load the css.
	 * Callback from admin_add_action
	 * 
	 * @since 1.5.2
	 */
	public function load_admin_css($hook) {
		
		wp_enqueue_style( 'custom_hq60_admin_css', $this->get_plugin_dir_url().'assets/css/admin/style.css' );
		
	}
	
	
	/**
	 * Save options in database.
	 * 
	 * @since 1.0
	 * 
	 */
	  
	public function save_options_to_database() {
		
		if ( isset ( $_POST['hq60_submit'] ) ) {
			
			$nonce = $_POST['input_nonce'];

			if ( ! wp_verify_nonce( $nonce, $this->get_prefix().'nonce' ) ) {
			
			     die( 'Security check' ); 
			
			} else {
			
			    $option_array = $this->get_array_keys_admin_settings_form();
			
				foreach ( $_POST as $key => $value ) {
				
					foreach ( $option_array as $subkey => $subvalue ) {
					
						if ( $key === $subvalue ) {
						
							update_option ( $key , $value );
							
							if ( $key === $this->get_prefix().'option_token' ) {
								
								$this->start_hq60();
								
							}
						
						}
					
					}
				
				}
				
			}
			
		} // if ( isset ( $_POST['submit'] ) ) {
		
	}
	
	/**
	 * Set the error placeholder
	 * 
	 * @param string
	 * 
	 * @since 1.0
	 */
	
	private function set_error($error) {
		
		$this->_error = $error;
	}
	
	/** Get the error placeholder
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 */
	protected function get_error() {
		
		return $this->_error;
		
	}
	
}
