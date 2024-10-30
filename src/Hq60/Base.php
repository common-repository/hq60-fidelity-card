<?php

/**
* Base class for HQ60 API.
* It is abstract because we want mantain here his data.
* 
 * @version 1.0
 * @since 1.0
 * 
 */

namespace Hq60;

abstract class Base {
	
	/**
	 * Version of base.
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	private $_version = '1.0';
	
	/**
	 * Base API URL
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	private $_base_url = 'https://www.hq60.it/apiv2/';
	

	/**
	 * Token.
	 * The token is necessary to perform every call to the API.
	 * 
	 * @var string
	 * 
	 * @since 1.0 
	 */
	private $_token = null;
	
	
	public function __construct( $token = null ) {
		
		if ( ! is_null ( $token ) ) {
			
			// try if token is valid
			
			$call = $this->get_base_api_url().'account/'.$token.'?token='.$token;
			
			$response = wp_remote_get ( $call );
			
			//if ( isset ( $response['body'] ) ) {
			if ( is_array ( $response ) ) {
				
				$call = wp_remote_retrieve_body( $response );
				
				$call = json_decode($call);
				
				if ( isset ( $call->code ) ) {
					
					if ( $call->code == '-1' ) {
						
						// token is wrong
						
					}
					
				} elseif ( isset( $call->account[0]->id ) ) {
					
					// token is valid
					
					$this->set_token($token);
					
				}
				
				
			}
			
		}
		
	}
	
	/**
	 * 
	 * Perform a get request
	 * 
	 * @param string url the url to call
	 * @param array data optional
	 * 
	 * @return object the answer
	 * 
	 * @since 1.0
	 * 
	 */
	protected function perform_get_request ( $url , $data = null ) {
		
		$response = wp_remote_get($url);
		
		if ( is_array($response) ) {
			
			if ( isset ( $response['body'] ) ) {
					
				$call = json_decode($response['body']);
					
				return $call;
					
			}
			
		} else {
			
			if ( is_wp_error( $response ) ) {
				
				//$error_message = $response->get_error_message();
   				//echo "Something went wrong: $error_message";
				return null;
				
			}
			
		}
		
	}
	
	/**
	 * 
	 * Perform a POST request
	 * 
	 * @param string url the url to call
	 * @param array data optional
	 * 
	 * @return object the answer
	 * 
	 * @since 1.0
	 * 
	 */
	protected function perform_post_request ( $url , $data = null ) {
		
		
		if ( $data != null ) {
			
			$body = array();
			
			foreach ( $data as $key => $value ) {
				
				$body[$key] = $value;
				
			}
			
		}
		
		$param['body'] = $body;
		
		$response = wp_remote_post($url , $param);
		
		if ( is_wp_error( $response ) ) {
			
   			/*$error_message = $response->get_error_message();
			
			echo "Something went wrong: $error_message";*/
			return null;
		
		} else {
			
			if ( isset ( $response['body'] ) ) {
			
   				$call = json_decode($response['body']);
					
				return $call;
				
			}
			
		}
		
	}
	
	
	/**
	 * Set the token
	 * 
	 * @param string
	 * 
	 * @since 1.0
	 */
	private function set_token ( $token ) {
		
		$this->_token = $token;
		
	}
	
	/**
	 * Get the token
	 * 
	 * @return null if empty | string if not empty
	 * 
	 * @since 1.0
	 */
	public function get_token() {
		
		return $this->_token;
		
	}
	
	/**
	 * Get the base API url
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 */
	protected function get_base_api_url() {
		
		return $this->_base_url;
		
	}
	
}	