<?php

/**
 * Class Database Menu.
 * Contains all method to interface with datbase
 * 
 * @since 1.0
 * 
 */
namespace Hq60fidelitycard\Database;

class Database extends \Hq60fidelitycard\Base {
	
	/**
	 * Delete multiple temp registration
	 * 
	 * @param int the id to EXCLUDE from delete
	 * @param string the card to search for multiple
	 * 
	 * @since 1.0 
	 */
	public function delete_multiple_temp_registration ( $id_post_to_exclude , $card ) {
		
		$args = array ( 
			'post__not_in' 		=> array( $id_post_to_exclude ),
			'posts_per_page'	=> -999,
			'post_type'			=> $this->get_custom_post_name()
			
		);
			
		$first_key = array (
		
			'key'		=>		$this->get_custom_post_name().'-card-number',
			'value'		=>		$card
			
		);
			
		$meta_query = array (
			
			$first_key
			
		);
			
			
		$args['meta_query'] = array($meta_query);
			
		$temp = new \WP_Query( $args );
		
		while ( $temp->have_posts() ) {
					
			$temp->the_post();
					
			$id_post = get_the_ID();
				
			wp_delete_post ( $id_post );
				
		}
		
	}
	
	/**
	 * 
	 * Set the temp registration as confirmed
	 * 
	 * @param int the id to set as confirmed
	 * 
	 * @since 1.0
	 * 
	 */
	public function set_confirmed_registration ( $id_post ) {
			
		update_post_meta( $id_post , $this->get_custom_post_name().'-confirmed-on' , current_time( 'mysql' ) );
		update_post_meta( $id_post , $this->get_custom_post_name().'-confirmed-on-utc' , current_time( 'mysql' , 1 ) );
		
	}
	
	
	/**
	 * Save the temp registration and return the id
	 * 
	 * @since 1.0
	 */
	public function save_temp_registration() {
		
		$post_id = null;
		
		$card_number = $_POST['card_number'];
		$email = $_POST['email'];
		$privacy = $_POST['privacy'];
		
		$privacy_art_4 = 0;
		$privacy_art_5 = 0;
		
		if ( isset ( $_POST['privacy_art_4'] ) ) {
			
			$privacy_art_4 = 1;
			
		}
		
		if ( isset ( $_POST['privacy_art_5'] ) ) {
			
			$privacy_art_5 = 1;
			
		}
		
		$ip_address = $this->get_ip();
		
		$data = array(
		
			'post_date'			=>	current_time( 'mysql' ),
			'post_date_gmt'		=>	current_time( 'mysql' , 1),
			'comment_status' 	=> 	'close',
			'ping_status'		=>	'close',
			'post_type'			=>	$this->get_custom_post_name(),
			'post_status'   	=>  'publish',
			'post_title'		=>	$card_number
			
		);
		
		$post_id = wp_insert_post ( $data , true );
		
		if ( is_int ( $post_id ) ) {
			
			update_post_meta( $post_id , $this->get_custom_post_name().'-card-number' , $card_number );
			update_post_meta( $post_id , $this->get_custom_post_name().'-email' , $email );
			update_post_meta( $post_id , $this->get_custom_post_name().'-ip-address' , $ip_address );
			update_post_meta( $post_id , $this->get_custom_post_name().'-privacy' , $privacy );
			update_post_meta( $post_id , $this->get_custom_post_name().'-privacy-art-4' , $privacy_art_4 );
			update_post_meta( $post_id , $this->get_custom_post_name().'-privacy-art-5' , $privacy_art_5 );
			
			
		}
		
		return $post_id;
		
	}
			
}