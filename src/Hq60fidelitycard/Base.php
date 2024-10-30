<?php

/**
* Base class.
* It is abstract because we want mantain here his data.
* 
 * @version 1.0
 * @since 1.0
 * 
 */
 
namespace Hq60fidelitycard;

abstract class Base {
	
	/**
     * The name of the plugin
	 * 
	 * @since 1.0
	 * 
     * @var string
     */
	private $_plugin_name = 'HQ60';
	
	/**
     * Current version of plugin
	 * 
	 * @since 1.0
	 * 
     * @var string
     */
	private $_version = '1.0';
	
	/**
     * Language domain
	 * 
	 * @since 1.0
	 * 
     * @return string
     */
	private $_language_domain = 'language_domain';
	
	/**
     * Prefix for every single input / key / field / etc.
	 * Prefix has "_" on the end, you don't need to add on other keyes
	 * 
	 * @since 1.0
	 * 
     * @var string
     */
	private $_prefix = 'hq60fidelitycard_';
	
	/**
	 * Plugin base name.
	 * 
	 * plugin_basename(__FILE__);
	 * 
	 * @since 1.0
	 * 
	 * @var string
	 * 
	 */
	private $_plugin_basename = '';
	
	
	/**
     * Get a slugged plugin name
	 * 
	 * @since 1.0
	 * 
     * @var string
	 * 
     */
	private $_slug = 'hq60-fidelity-card';
	
	/**
	 * Parent slug of settings admin page
	 * 
	 * @since 1.0
	 * 
	 * @var string
	 * 
	 */
	private $_parent_slug = 'admin.php';
	
	/**
     * Settings group for form
	 * 
	 * @since 1.0
	 * 
     * @return string
     */
	private $_option_group;
	
	/**
     * Array that contains all keys for admin settings form
	 * 
	 * @since 1.0
	 * 
	 * @var array
     */
	private $_array_admin_key_form = array();
	
	/**
     * Base dir of the plugin
	 * 
	 * @since 1.0
	 * 
     * @var string
	 * 
     */
	private $_base_dir = '';
	
	/**
     * Base URL of the plugin (e.g. http://www.tresrl.it/wp-content/plugins/name-plugin)
	 * 
	 * @since 1.0
	 * 
     * @var string
	 * 
     */
	private $_plugin_dir_url = '';
	
	/**
	 * Shortcode tag for user register
	 * 
	 * @since 1.0
	 * 
	 * @var string
	 */
	private $_shortcode_tag_user_register = 'hq60_user_register';
	
	/**
	 * Shortcode tag for user login
	 * 
	 * @since 1.0
	 * 
	 * @var string
	 */
	private $_shortcode_tag_user_login = 'hq60_user_login';
	
	/**
	 * Shortcode tag for user recovery password
	 * 
	 * @since 1.0
	 * 
	 * @var string
	 */
	private $_shortcode_tag_user_recovery_password = 'hq60_user_recovery_password';
	
	/**
	 * Shortcode tag for user dashboard
	 * 
	 * @since 1.0
	 * 
	 * @var string
	 */
	private $_shortcode_tag_user_dashboard = 'hq60_user_dashboard';
	
	/****************************/
	/*	CUSTOM POST SECTION		*/
	/****************************/
	
	/**
	 * Custom post name
	 * 
	 * @since 1.0
	 * 
	 * @var string
	 */
	private $_custom_post_name = 'hq60_temp_reg_cp';
	
	/**
	 * Label on Menu for custom post
	 * 
	 * @since 1.0
	 * 
	 * @var string
	 * 
	 */
	private $_menu_label_custom_post_name = 'Registrazioni non confermate';
	
	
	
	
	
	/**
	 * The token of HQ60
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	private $_hq60_token;
	
	/**
	 * HQ60 object
	 * 
	 * @var object | null
	 * 
	 * @since 1.0
	 * 
	 */
	private $_hq60;
	 
	
	
	/**
     * Our constructor.
	 * Prepare all the key/valus to use in our plugin.
	 * 
	 * @param string $base_directory_plugin The base plugin directory
	 * 
     */
	
	public function __construct($base_directory_plugin = null , $plugin_basename = null , $plugin_dir_url = null) {
		
		
		if ( $base_directory_plugin != null ) {
			
			$this->set_base_dir($base_directory_plugin);
			
		}
		
		
		if ( $plugin_basename != null ) {
			
			$this->set_plugin_basename($plugin_basename);
			
		}
		
		if ( $plugin_dir_url != null ) {
			
			$this->set_plugin_dir_url($plugin_dir_url);
			
		}
		
		// Set the option group
		$this->set_option_group();
		
		// Set the keys array for admin settings form
		$this->set_array_keys_admin_settings_form();
		
		// Register the custom post
		$this->register_custom_post();
		
		
		//$this->delete_unconfirmed_registration();
		
		//$cron = new \Hq60fidelitycard\Cron\Cron( $this->get_custom_post_name() );
		//$cron->delete_unconfirmed_registration();
		
		
		
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
		
		
			'posts_per_page'	=> -1,
			'post_type'			=> $this->get_custom_post_name(),
			
			'date_query' => array(
			
        		'before' => date('Y-m-d H:i:s', strtotime('-1 days'))
				 
    		)
			
		
		);
		
		/*$first_key = array (
		
			'key'		=>		$this->get_custom_post_name().'-confirmed-on',
			'compare'	=>		'NOT EXISTS'
		
		);*/
		
		$first_key = null;
				
		$meta_query = array (
		
			$first_key
		
		);
		
		$args['meta_query'] = array($meta_query);
		
		$temp = new \WP_Query( $args );
		
		if ( $temp->have_posts() ) {
			
			while ( $temp->have_posts() ) {
				
				$temp->the_post();
				
				$id_post = get_the_ID();
				
				wp_delete_post($id_post);
				
				
			}
			
		}
		
	}
	
	/**
	 * Set the session start
	 * 
	 * @since 1.0
	 * 
	 */
	public function register_session() {
		
		if( !session_id() ) {
		
			session_start();
		}
		
	}
	
	/**
	 * Check if user is logged
	 * 
	 * @since 1.0
	 */
	public function check_login() {
		
		$protected_url = array (
		
			'/fidelity-card-dashboard/'
		
		);
		
		$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
		
		//$current_url = $_SERVER['REQUEST_URI'];
		
		//echo $current_url;
		
		$current_url = $uri_parts[0];
		
		if ( in_array( $current_url , $protected_url) ) {
			
			if ( !isset ( $_SESSION['hq60']['member_card']['logged'] ) ) {
				
				header('Location: /');
				exit;
				
			}
			
		}
		
		
		
	}
	
	/**
	 * Set the array keys admin settings form
	 * 
	 * @since 1.0
	 * 
	 */
	private function set_array_keys_admin_settings_form() {
		
		// admin form && option name
		$this->_array_admin_key_form['token'] = $this->get_prefix().'option_token';
		
	}
	
	/**
	 * Get the array keys admin settings form
	 * 
	 * @since 1.0
	 * 
	 * @return array
	 * 
	 */
	protected function get_array_keys_admin_settings_form() {
		
		return $this->_array_admin_key_form;
		
	}
	
	/**
     * Return language domain
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_language_domain() {
		
		return $this->get_prefix().$this->_language_domain;
		
	}
	
	/**
     * Return name of the plugin
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_plugin_name() {
		
		return $this->_plugin_name;
		
	}
	
	/**
     * Return version of the plugin
	 * 
	 * @return string
	 * 
	 * @since 1.1
	 * 
     */
	protected function get_plugin_version() {
		
		return $this->_version;
		
	}
	
	/**
     * Return prefix
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	public function get_prefix() {
		
		return $this->_prefix;
		
	}
	
	/**
	 * 
	 * Set the plugin dir_url
	 * plugin_basename(__FILE__);
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	private function set_plugin_dir_url($plugin_dir_url) {
		
		
		$this->_plugin_dir_url = $plugin_dir_url;
		
	}
	
	/**
	 * 
	 * Set the plugin basename
	 * plugin_basename(__FILE__);
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	private function set_plugin_basename($plugin_basename) {
		
		$this->_plugin_basename = $plugin_basename;
		
	}
	
	/**
	 * 
	 * Get the plugin basename
	 * plugin_basename(__FILE__);
	 * 
	 * @return string the plugin_basename(__FILE__); path based
	 * 
	 * @since 1.0
	 */
	protected function get_plugin_basename() {
		
		return $this->_plugin_basename;
		
	}
	
	/**
	 * 
	 * Get the plugin dir url
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	protected function get_plugin_dir_url() {
		
		
		return $this->_plugin_dir_url;
		
	}
	
	/**
     * Set base_dir
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
     */
	protected function set_base_dir($base_dir) {
		
		$this->_base_dir = $base_dir;
		
	}
	
	
	/**
     * Return base_dir
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_base_dir() {
		
		return $this->_base_dir;
		
	}
	
	/**
	 * Return slugged plugin name
	 * 
	 * @return string
	 * 
	 * @since 1.1
	 */
	protected function get_slug() {
		return $this->_slug;
	}
	
	/**
	 * Return shortcode user register
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 */
	protected function get_shortcode_user_registration() {
		
		return $this->_shortcode_tag_user_register;
		
	}
	
	/**
	 * Return shortcode user login
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 */
	protected function get_shortcode_user_login() {
		
		return $this->_shortcode_tag_user_login;
		
	}
	
	/**
	 * Return shortcode user recovery password
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 */
	protected function get_shortcode_user_recovery_password() {
		
		return $this->_shortcode_tag_user_recovery_password;
		
	}
	
	/**
	 * Return shortcode user logout
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 */
	protected function get_shortcode_user_dashboard() {
		
		return $this->_shortcode_tag_user_dashboard;
		
	}
	
	/**
	 * 
	 * Return the parent slug for admin
	 * 
	 * @since 1.0
	 * 
	 * @var string
	 */
	protected function get_parent_slug() {
		
		return $this->_parent_slug;
		
	}
	
	/**
	 * Set the option group name
	 * 
	 * @since 1.0
	 */
	private function set_option_group() {
		
		$this->_option_group = $this->get_prefix() .'_settings_group';
		
	}
	
	/**
	 * Get the option group name
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
	 */
	protected function get_option_group() {
		
		return $this->_option_group;
		
	}
	
	/**
	 * Set the token of Hq60
	 * 
	 * @param string
	 * 
	 * @since 1.0
	 */
	protected function set_hq60_token ( $token ) {
		
		$this->_hq60_token = $token;
		
	}
	
	/**
	 * Get the token
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
	 */
	protected function get_hq60_token() {
		
		return $this->_hq60_token;
		
	}
	
	/**
	 * Set HQ60 object
	 * 
	 * @param object
	 * 
	 * @since 1.0
	 */
	protected function set_hq60 ( $hq60 ) {
		
		$this->_hq60 = $hq60;
		
	}
	
	/**
	 * Get HQ60 object
	 * 
	 * @return object
	 * 
	 * @since 1.0
	 * 
	 */
	protected function get_hq60() {
		
		return $this->_hq60;
		
	}
	
	/**
	 * Get the IP of poster
	 * 
	 * @return string the IP or null
	 * 
	 * @since 1.0
	 * 
	 */
	protected function get_ip() {
		
		$ip = null;
		
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
			
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			
		} else {
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
		}

		return $ip;
		
	}
	
	
	/****************************/
	/*	CUSTOM POST SECTION		*/
	/****************************/
	
	/**
	 * Get custom post name
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
	 */
	protected function get_custom_post_name() {
		
		return $this->_custom_post_name;
		
	}
	
	/**
	 * Get the label for menu for custom post
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
	 */
	public function get_menu_label_custom_post_name() {
		
		return $this->_menu_label_custom_post_name;
		
	}
	
	 /**
     * Register custom post.
	 * 
	 * @since 1.0 
     */
     private function register_custom_post() {
	 	
		// Inizializzazione della funzione
        add_action( 'init', array( $this , 'callback_create_custom_post_type' ) );
		
		add_action ( 'init' , array ( $this , 'delete_unconfirmed_registration' ) );
		
	 }
	 
	 /**
     * Callback from register_register_custom_post()
	 * 
	 * @since 1.0 
     */
     
     public function callback_create_custom_post_type() {
	 	
		// Labels of Custom Post Type
		
        $labels = array(
        
            // Nome plurale del post type
            // H1 + meta title
            'name' => _x( $this->get_menu_label_custom_post_name() , 'Post Type General Name', $this->get_language_domain() ),
        
		    // Nome singolare del post type
            'singular_name' => _x( 'Registrazione', 'Post Type Singular Name', $this->get_language_domain() ),
        
		    // Testo per pulsante Aggiungi
            'add_new' => __( 'Aggiungi', $this->get_language_domain() ),
            
            // Testo per pulsante Tutti gli articoli
            'all_items' => __( 'Tutte', $this->get_language_domain() ),
            
            // Testo per pulsante Aggiungi nuovo articolo
            //'add_new_item' => __( 'Aggiungi Nuovo Contatto', WPTRESF__PLUGIN_DOMAIN ),
            
            // Testo per pulsante Modifica
            'edit_item' => __( 'Modifica', $this->get_language_domain() ),
            
            // Testo per pulsante Nuovo
            'new_item' => __( 'Nuovo', $this->get_language_domain() ),
            
            // Testo per pulsante Visualizza
            'view_item' => __( 'Dettaglio', $this->get_language_domain() ),
            
            // Testo per pulsante Cerca articoli
            'search_items' => __( 'Cerca', $this->get_language_domain() ),
            
            // Testo per nessun articolo trovato
            'not_found' => __( 'Nessun risultato', $this->get_language_domain() ),
            
            // Testo per nessun articolo trovato nel cestino
            'not_found_in_trash' => __( 'Nessun risultato nel cestino', $this->get_language_domain() ),
            
            // Testo per articolo genitore
            'parent_item_colon' => __( 'Genitore:', $this->get_language_domain() ),
            
            // Testo per Menù
            'menu_name' => __( $this->get_menu_label_custom_post_name() , $this->get_language_domain() )
        );
		
        $args = array(
            'labels' => $labels,
            
            // Descrizione
            'description' => _x( $this->get_menu_label_custom_post_name() , $this->get_language_domain() ),
            
            // Rende visibile o meno da front-end il post type
            'public' => false, // here
            
            // Esclude o meno il post type dai risultati di ricerca
            'exclude_from_search' => true, // here
            
            // Rende richiamabile o meno da front-end il post type tramite una query
            'publicly_queryable' => false, // here
            
            // Rende disponibile l'interfaccia grafica del post type da back-end
            'show_ui' => true,
            
            // Rende disponibile o meno il post type per il menù di navigazione
            'show_in_nav_menus' => false,
            
            // Definisce dove sarà disponibile l'interfaccia grafica del post type nel menù di amministrazione
            
            // if is false, doesn't show.
            // @see https://shellcreeper.com/how-to-add-wordpress-cpt-admin-menu-as-sub-menu/
            
            'show_in_menu' => false,
            
            // Rende disponibile o meno il post type per l'admin bar di wordpress
            'show_in_admin_bar' => false,
            
            // La posizione nel menù
            'menu_position' => 30,
            
            // L'icona del post type nel menù
            'menu_icon' => 'dashicons-admin-comments',
            
            // I permessi che servono per editare il post type
            'capability_type' => 'post',
            
			// ADD / EDIT SECTION
			
			'capabilities' => array(
    			'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
  			),
  			'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
            
            // Definisce se il post type è gerarchico o meno
            'hierarchical' => true,
            
            // I meta box che il post type supporta nell'interfaccia di editing
            //'supports' => array( 'title', 'editor', 'page-attributes' ),
            'supports'	=>	array('title'),
            
            // Le tassonomie supportate dal post type
            'taxonomies' => array( 'hq60_registration' ),
            
            // Definisce se il post type ha o meno un archivio
            'has_archive' => false,
            
            // Imposta lo slug del post type
            'rewrite' => array( 'slug' => $this->get_prefix().'temp-registration', 'with_front' => false ),
        );
                // Registra il Custom Post Type
        register_post_type( $this->get_custom_post_name() , $args );
		
	 }
	
		
	
}