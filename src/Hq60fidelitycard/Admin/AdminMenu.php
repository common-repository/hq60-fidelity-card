<?php

/**
 * Class Admin Menu.
 * Contains all method to render the ADMIN section menu
 * 
 * @version 1.0
 * @since 1.0
 * 
 */
namespace Hq60fidelitycard\Admin;

class AdminMenu extends \Hq60fidelitycard\Base {
	
	/**
	 * Page title on admin menu
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
	 */
	private $_page_title_on_admin_menu;
	
	/**
	 * Label on admin menu.
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	private $_label_on_admin_menu;
	
	/**
	 * Priority on admin menu.
	 * 
	 * @var int
	 * 
	 * @since 1.0
	 */
	private $_priority_on_admin_menu = 90;
	
	/**
	 * Our constructor.
	 * 
	 * @since 1.0
	 */
	public function __construct($base_directory_plugin = null , $plugin_basename = null , $plugin_dir_url = null) {
		
		// call the parent contruct
		parent::__construct($base_directory_plugin , $plugin_basename , $plugin_dir_url);
		
		/*
		 * Activate the action hooks
		 */
		$this->perform_action_hooked_action();
		
		/*
		 * Activate the filter hooks
		 */
		$this->perform_filter_hooked_action();
		
		// add the link to the settings dashboard
		// see below for detail	
		$this->create_admin_settings_menu();
		
		$this->create_admin_nav_menu();
		
	}
	
	/*********************************************************************************/
	/**
	 *
	 * CREATE THE PAGE OF OPTIONS ON DASHBOARD.
	 * 'Cause wordpress, it will pass on several methods.
	 * 
	 * Let's go!
	 * 
	 *  
	 */
	/*********************************************************************************/
	
	
	/**
     * Perform all actions
	 * 
	 * @since 1.7
     */
	private function perform_action_hooked_action() {
		
		// add other columns to the table on edit.php
		add_action('manage_hq60_temp_reg_cp_posts_custom_column' , array ( $this , 'add_content_to_unconfirmed_list'));
	
	}
	
	/**
     * Perform all hooks for filters
	 * 
	 * @since 1.7
     */
	private function perform_filter_hooked_action() {
		
		// add columns
		add_filter('manage_hq60_temp_reg_cp_posts_columns' , array ( $this , 'add_columns_to_unconfirmed_list'));
		
		// add sortable functions
		//add_filter('manage_edit-simplestform_cp_sortable_columns' , array ( $this , 'add_sortable_to_summary_custom_post'));
		
		//add settings to link on plugin page
		//add_filter('plugin_action_links_'.$this->get_base_plugin_name() , array ( $this , 'add_link_settings_to_plugin_page' ));
		
	}
	
	/**
     * Create the page for the admin settings in backend
	 * 
	 * @since 1.0
	 * 
     */
	 
	 private function create_admin_settings_menu() {
	 	
		add_action( 'admin_menu' , array ( $this , 'add_admin_settings_menu' ) );
		
		add_filter( 'pre_get_posts' , array ( $this , 'set_custom_post_order' ) );
		
	 }

	/**
	  * Order the custom post. 
	  * Called from create_admin_settings_menu
	  * 
	  * @since 1.0
	  * 
	  */
	
	
	public function set_custom_post_order ($wp_query) {
			
		if (is_admin()) {
				
			// Get the post type from the query  
		    $post_type = $wp_query->query['post_type'];  
		  
		    if ( $post_type == $this->get_custom_post_name() ) {
		  
		      	// 'orderby' value can be any column name  
		      	$wp_query->set('orderby', $this->get_custom_post_name().'-date' );  
		  
		      	// 'order' value can be ASC or DESC  
		      	$wp_query->set('order', 'DESC');  
		    
			}
			
		}

	}
	 
	 /**
	  * Add the admin menu.
	  * Called from create_admin_settings_menu
	  * 
	  * @since 1.0
	  * 
	  */
	 
	 public function add_admin_settings_menu() {
	 	
		$page_title = __( $this->get_plugin_name() , $this->get_language_domain() );
		$menu_title = __( $this->get_plugin_name() , $this->get_language_domain() ); // The label visible in the menu tree
		$capable_option = 'manage_options'; // permission needed
		$slug = $this->get_slug();
		$function_callback = array ( $this , 'callback_load_admin_settings_page' );
		$icon = 'dashicons-welcome-write-blog';
		$priority = $this->get_priority_on_admin_menu();
		
		$parent_slug = $this->get_parent_slug();
		
		add_menu_page   (
		
							$page_title,
							$menu_title,
							$capable_option,
							$slug,
							$function_callback,
							$icon,
							$priority
		
						);
						
		$this->add_submenu_page_shortcode_list();
		$this->add_submenu_page_custom_post_temp_registration();
		
	 }

	/**
	 * 
	 * Add submenu page for custom post (temp registration)
	 * 
	 * Url is: /wp-admin/edit.php?post_type=hq60_temp_reg_cp
	 * 
	 * @since 1.0
	 * 
	 */
	private function add_submenu_page_custom_post_temp_registration() {
		
		$page_title = __( 'Registrazioni non confermate' , $this->get_language_domain() );
		$menu_title = __( 'HQ60 - Registrazioni non confermate' , $this->get_language_domain() ); // The label visible in the menu tree
		$capable_option = 'manage_options'; // permission needed
		$slug = 'edit.php?post_type='.$this->get_custom_post_name();
		$icon = 'dashicons-welcome-write-blog';
		$priority = $this->get_priority_on_admin_menu();
		
		$parent_slug = $this->get_slug();
		
		add_submenu_page(
							$parent_slug,
							$page_title,
							$menu_title,
							$capable_option,
							$slug
						);
		
	}
	 
	/**
	 * 
	 * Add submenu page to admin settings
	 * 
	 * @since 1.0
	 * 
	 */
	private function add_submenu_page_shortcode_list() {
		
		$page_title = __( 'Shortcode' , $this->get_language_domain() );
		$menu_title = __( 'HQ60 - Shortcode list' , $this->get_language_domain() ); // The label visible in the menu tree
		$capable_option = 'manage_options'; // permission needed
		$slug = $this->get_slug();
		$function_callback = array ( $this , 'callback_load_admin_shortcode_list_page' );
		$icon = 'dashicons-welcome-write-blog';
		$priority = $this->get_priority_on_admin_menu();
		
		$parent_slug = $this->get_parent_slug();
		
		add_submenu_page(
							$slug,
							$page_title,
							$menu_title,
							$capable_option,
							$slug.'-shortcode',
							$function_callback
						);
		
		//add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' );
		
	}
	 
	 /**
	  * Callback from add_submenu_page_shortcode_list
	  * 
	  * @since 1.0
	  * 
	  */
	  
	public function callback_load_admin_shortcode_list_page() {
			
		if ( !current_user_can( 'manage_options' ) )  {
				
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		
		}
		
		include_once ( $this->get_base_dir().'/views/admin/shortcode-list.php' );
		
		
	}
	
	/**
	  * Callback from add_admin_menu.
	  * Called from create_admin_settings_menu
	  * 
	  * @since 1.0
	  * 
	  */
	  
	public function callback_load_admin_settings_page() {
			
		if ( !current_user_can( 'manage_options' ) )  {
				
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		
		}
		
		include_once ( $this->get_base_dir().'/views/admin/settings.php' );
		
		
	}
	
	/**
	 * 
	 * Filter for add columns to summary custom post.
	 * 
	 * Callback from perform_filter_hooked_action
	 * 
	 * @since 1.7
	 */
	public function add_columns_to_unconfirmed_list( $columns ) {
		
		// New columns to add to table
		$new_columns = array(
			$this->get_custom_post_name().'-card' => __( 'Card', $this->get_language_domain() ),
			$this->get_custom_post_name().'-email' => __( 'Email', $this->get_language_domain() ),
			$this->get_custom_post_name().'-privacy' => __( 'Privacy', $this->get_language_domain() ),
			$this->get_custom_post_name().'-privacy-art-4' => __( 'Privacy ART 4', $this->get_language_domain() ),
			$this->get_custom_post_name().'-privacy-art-5' => __( 'Privacy ART 5', $this->get_language_domain() ),
			$this->get_custom_post_name().'-date' => __( 'Data', $this->get_language_domain() ),
			//$this->get_custom_post_name().'-confirmed-on' => __( 'Confermato il', $this->get_language_domain() ),
			
		);
		
		// Remove CB. The del box!
		unset( $columns['cb'] );
		
		// Remove title column
		unset( $columns['title'] );
		
		// Remove unwanted publish date column
		unset( $columns['date'] );
		  
		// Combine existing columns with new columns
		$filtered_columns = array_merge( $columns, $new_columns );
		
		// Return our filtered array of columns
		return $filtered_columns;
		
	}
	
	/**
	 * 
	 * Filter for add content to columns to summary custom post.
	 * 
	 * Callback from perform_action_hooked_action
	 * 
	 * @since 1.7
	 */
	public function add_content_to_unconfirmed_list( $columns ) {
		
		// Get the post object for this row so we can output relevant data
  		global $post;
  
  		// Check to see if $column matches our custom column names
  		switch ( $columns ) {
  			
				case $this->get_custom_post_name().'-card' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-card-number' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						echo $value;
						
      				}
					
      			break;
  				
				case $this->get_custom_post_name().'-email' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-email' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						echo $value;
						
      				}
					
      			break;
				
				case $this->get_custom_post_name().'-privacy' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-privacy' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						if ( $value == 1) {
							
							$value = _e( 'Si' , $this->get_language_domain() );
							
						} else {
							
							$value = _e( 'No' , $this->get_language_domain() );
							
						}
						
      				}
					
      			break;
				
				case $this->get_custom_post_name().'-privacy-art-4' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-privacy-art-4' , true );
					
					// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						if ( $value == 1) {
							
							$value = _e( 'Si' , $this->get_language_domain() );
							
						} else {
							
							$value = _e( 'No' , $this->get_language_domain() );
							
						}
						
      				} else {
      					
						$value = _e( 'No' , $this->get_language_domain() );
						
      				}
					
      			break;
				
				case $this->get_custom_post_name().'-privacy-art-5' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-privacy-art-5' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						if ( $value == 1) {
							
							$value = _e( 'Si' , $this->get_language_domain() );
							
						} else {
							
							$value = _e( 'No' , $this->get_language_domain() );
							
						} 
						
      				} else {
      					
						$value = _e( 'No' , $this->get_language_domain() );
						
      				}
					
      			break;
				
				case $this->get_custom_post_name().'-confirmed-on' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-confirmed-on' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						echo $value;
						
      				}
					
      			break;
				
				
				case $this->get_custom_post_name().'-date' :
      				// Retrieve post meta
      				$value = get_the_date('d-m-Y H:i');
      
      				echo $value;
					
      			break;
      
  			}
		
	}
	
	/*********************************************************************************/
	/**
	 *
	 * END OF...
	 * CREATE THE PAGE OF OPTIONS ON DASHBOARD.
	 * 
	 *  
	 */
	/*********************************************************************************/
	
	
	
	/**
	 * Create and return the tabbed navigation menu
	 * 
	 * @return string the html
	 * 
	 * @since 1.0
	 */
	 
	private function create_admin_nav_menu() {
		
		
		$current = 'generic';
		
		if ( isset ( $_GET['tab'] ) ) {
			
			$current = $_GET['tab'];
			
		}
		
		// add all tabs
		
		$tabs = array(
		
			'generic'		=>	__( 'Token' , $this->get_language_domain() )
		
		);
		
		$html = '<h2 class="nav-tab-wrapper">';
		
    	foreach( $tabs as $tab => $name ){
    		
        	$class = ( $tab == $current ) ? 'nav-tab-active' : '';
        	$html .= '<a class="nav-tab ' . $class . '" href="'.$this->get_parent_slug().'?page='.$this->get_slug().'&tab=' . $tab . '">' . $name . '</a>';
			
    	}
		
    	$html .= '</h2>';
		
    	return $html;
		
	}
	
	/**
	 * Get priority on menu
	 * 
	 * @var int
	 * 
	 * @since 1.0
	 */
	private function get_priority_on_admin_menu() {
		
		return $this->_priority_on_admin_menu;
		
	}
	
}
