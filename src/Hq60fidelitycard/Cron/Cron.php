<?php

/**
 * Class Cron
 * Contains all method to interface with datbase
 * 
 * @since 1.7
 * 
 */
namespace Hq60fidelitycard\Cron;

class Cron {
	
	/**
	 * 
	 * The custom post name
	 * 
	 * @var string
	 * 
	 */
	private $_custom_post_name;
	
	public function __construct( $custom_post_name ) {
		
		$this->set_custom_post_name ( $custom_post_name );
		
	}
	
	/**
	 * 
	 * Set the custom post name
	 * 
	 * @param string
	 * 
	 */
	private function set_custom_post_name ( $custom_post_name ) {
		
		$this->_custom_post_name = $custom_post_name;
		
	}
	
	/**
	 * Get the custom post name
	 * 
	 * @return string
	 * 
	 */
	private function get_custom_post_name() {
		
		return $this->_custom_post_name;
		
	}
	
	/**
	 * Delete unconfirmed registration
	 * 
	 * @since 1.7.1
	 * 
	 */
	public function delete_unconfirmed_registration() {
		
		$post = null;
		
		$args = array ( 
		
		
			'posts_per_page' => 2,
			'post_type'		=>	$this->get_custom_post_name(),
			
			'date_query' => array(
			
        		'before' => date('Y-m-d H:i:s', strtotime('-1 days'))
				 
    		)
			
		
		);
		
		/*$first_key = array (
		
			'key'		=>		$this->get_custom_post_name().'-confirmed-on',
			'compare'	=>		'NOT EXISTS'
		
		);*/
		
		$first_key = array();
		
		$meta_query = array (
		
			$first_key
		
		);
		
		
		$args['meta_query'] = array($meta_query);
		
		$temp = new \WP_Query( $args );
		
		if ( $temp->have_posts() ) {
			
			while ( $temp->have_posts() ) {
				
				$temp->the_post();
				
				$id_post = get_the_ID();
				
				echo $id_post;
				
				//wp_trash_post($id_post);
				
				
			}
			
		}
		
	}
			
}