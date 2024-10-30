<?php

/**
 * Frontend class.
 * 
 * @version 1.0
 * @since 1.0
 * 
 */

namespace Hq60fidelitycard\Frontend;

class Frontend extends \Hq60fidelitycard\Base {

	/**
	 * Mantain frontend error
	 */
	private $_frontend_error;
	
	/**
	 * Mantain frontend success
	 */
	private $_frontend_success;
	
	
	/**
	 * Our constructor
	 * 
	 * @since 1.0
	 */
	
	public function __construct($base_directory_plugin = null , $plugin_basename = null , $plugin_dir_url = null) {
		
		parent::__construct($base_directory_plugin , $plugin_basename , $plugin_dir_url);
		
		/**
		 * Register shortcode
		 */
		$this->register_shortcode();
		
		/**
		 * Activate all init action
		 */
		$this->frontend_add_action();
		
		/**
		 * Activate all filters
		 */
		$this->frontend_add_filter();
		
		
	}
	
	public function show_hide_menu_items( $items, $menu, $args ) {
		
		if ( !is_admin() ) {
		
			if ( isset ( $_SESSION['hq60']['member_card']['logged'] ) ) {
				
				foreach ( $items as $key => $item ) {
					
					if ( $item->post_name == 'fidelity-card-login' || $item->url == get_home_url().'/fidelity-card-login/' ) {
						
						unset( $items[$key] );
						
					}
					
					if ( $item->post_name == 'register' || $item->url == get_home_url().'/fidelity-card-register/' ) {
						
						unset( $items[$key] );
						
					}
				
	    		}
				
				
			} else {
				
				foreach ( $items as $key => $item ) {
					
					if ( $item->post_name == 'fidelity-card-logout' || $item->url == get_home_url().'/fidelity-card-logout/' ) {
	
						unset( $items[$key] );
						
					}
					
					if ( $item->post_name == 'fidelity-card-dashboard' || $item->url == get_home_url().'/fidelity-card-dashboard/' ) {
	
						unset( $items[$key] );
						
					}
					
					
				
	    		}
				
			}
		
		}
		
		return $items;
    	// Iterate over the items to search and destroy
    	
	}
	
	/**
	 * Add filter action
	 * 
	 * @since 1.0
	 */
	private function frontend_add_filter() {
		
		add_filter( 'wp_get_nav_menu_items' , array ( $this , 'show_hide_menu_items' ), null, 3 );
		
	}
	
	/**
     * Add all action for admin
	 * 
	 * @since 1.0 
     */
	
	private function frontend_add_action() {
			
		add_action ( 'init' , array ( $this , 'register_session' ) );
		add_action ( 'init' , array ( $this , 'perform_post' ) );
		add_action ( 'init' , array ( $this , 'perform_logout' ) );
		add_action ( 'init'	, array ( $this , 'check_login' ) );
		add_action ( 'wp_enqueue_scripts' , array ( $this , 'load_frontend_css' ) );
		add_action ( 'after_setup_theme' , array ( $this , 'check_temp_registration_code' ) );
		
		
		
	}

	/**
	 * Add custom CSS for frontend
	 * 
	 * @since 1.0
	 * 
	 */
	public function load_frontend_css () {
		
    	wp_register_style ( 'hq60', $this->get_plugin_dir_url().'assets/css/frontend/style.css' );
    	wp_enqueue_style ( 'hq60' );
		
	}

	/**
 	* 
	* Check if is a particulare page && if user is logged, otherwise redirect 
	* 
 	*/
 	/*public function check_login() {
 		
		
		
 	}*/
	
	/**
	 * Perform Post submit
	 * 
	 * @since 1.0
	 */
	public function perform_post() {
		
		// Login user
		
		if ( isset ( $_POST[$this->get_prefix().'submit-login'] ) ) {
			
			$nonce = $_POST['_wpnonce'];
			
			if ( wp_verify_nonce ( $nonce , $this->get_prefix().'nonce_field' ) ) {
				
				$this->perform_login();
				
			} else {
				
				die ( _e('Operazione non permessa' , $this->get_language_domain() ) );
				
			}
			
			
		}
		
		// Register user
		
		if ( isset ( $_POST[$this->get_prefix().'submit-register'] ) ) {
			
			$nonce = $_POST['_wpnonce'];
			
			if ( wp_verify_nonce ( $nonce , $this->get_prefix().'nonce_field' ) ) {
				
				$this->perform_register();
				
			} else {
				
				die ( _e('Operazione non permessa' , $this->get_language_domain() ) );
				
			}
			
			
		}
		
		// Recovery password
		
		if ( isset ( $_POST[$this->get_prefix().'submit-recovery'] ) ) {
			
			$nonce = $_POST['_wpnonce'];
			
			if ( wp_verify_nonce ( $nonce , $this->get_prefix().'nonce_field' ) ) {
				
				$this->recovery_password();
				
			} else {
				
				die ( _e('Operazione non permessa' , $this->get_language_domain() ) );
				
			}
			
			
		}
		
		// Change password
		
		if ( isset ( $_POST[$this->get_prefix().'submit-change-password'] ) ) {
			
			$nonce = $_POST['_wpnonce'];
			
			if ( wp_verify_nonce ( $nonce , $this->get_prefix().'nonce_field' ) ) {
				
				$this->manage_change_password();
				
			} else {
				
				die ( _e('Operazione non permessa' , $this->get_language_domain() ) );
				
			}
			
			
		}
		
		// Change member_card data
		
		if ( isset ( $_POST[$this->get_prefix().'submit-data'] ) ) {
			
			$nonce = $_POST['_wpnonce'];
			
			if ( wp_verify_nonce ( $nonce , $this->get_prefix().'nonce_field' ) ) {
				
				$this->manage_change_member_card_data();
				
			} else {
				
				die ( _e('Operazione non permessa' , $this->get_language_domain() ) );
				
			}
			
			
		}
		
	}
	
	/**
	 * Register the shortcode
	 * 
	 * @since 1.0
	 */
	private function register_shortcode() {
		
		// render the form for login user
		add_shortcode( $this->get_shortcode_user_login() , array ( $this , 'render_form_user_login' ) );
		add_shortcode( $this->get_shortcode_user_registration() , array ( $this , 'render_form_user_register' ) );
		add_shortcode( $this->get_shortcode_user_recovery_password() , array ( $this , 'render_form_user_recovery_password' ) );
		add_shortcode( $this->get_shortcode_user_dashboard() , array ( $this , 'render_user_dashboard' ) );
		
	}
	
	/**
	 * Render the registration form
	 * 
	 * @since 1.0
	 */
	public function render_form_user_recovery_password() {
		
		ob_start();
		include_once ( $this->get_base_dir().'/views/frontend/form/user-recovery-password.php' );
		return ob_get_clean();
		
	}
	
	/**
	 * Render the registration form
	 * 
	 * @since 1.0
	 */
	public function render_form_user_register() {
		
		ob_start();
		include_once ( $this->get_base_dir().'/views/frontend/form/user-register.php' );
		return ob_get_clean();
		
	}
	
	/**
	 * Render the dashboard
	 * 
	 * @since 1.0
	 */
	public function render_user_dashboard() {
		
		$id_member_card = $_SESSION['hq60']['member_card']['id'];
		
		$title = __( ' Home ' , $this->get_language_domain() );
		
		if ( isset ( $_GET['view'] ) ) {
			
			if ( ! is_string( $_GET['view'] ) ) {
				
				die ( 'Operazione non consentita' );
				
			}
			
			$view = sanitize_text_field ( $_GET['view'] );
			
			switch ( $view ) {
				
				case 'password':
					
					$title = __( ' Cambio password ' , $this->get_language_domain() );
					
				break;
				
				case 'data':
					
					$title = __( ' I tuoi dati ' , $this->get_language_domain() );
					
					$hq60 = new \Hq60\Hq60();
					
					$member_card_data = $hq60->get_membercard_data ( $id_member_card );
					
					
				break;
				
				case 'campaign':
					
					$hq60 = new \Hq60\Hq60();
					
					if ( isset ( $_GET['id_campaign'] ) ) {
						
						if ( !is_string ( $_GET['id_campaign'] ) ) {
							
							die ( 'Operazione non consentita' );
							
						}
						
						$id_campaign = sanitize_text_field ( $_GET['id_campaign'] ) ;
						
						$single_campaign = $hq60->get_campaign( $id_campaign );
						
						$title = $single_campaign->campaign[0]->name;
						$balance = $hq60->get_balance ( $id_member_card , $id_campaign );
						$transaction = $hq60->get_transaction ( $id_member_card , $id_campaign );
						
					} else {
						
						$title = __( ' Saldo card ' , $this->get_language_domain() );
						
						$campaign_list = $hq60->get_campaign_list();
					
					}
					
				break;
				
			}
			
		}
		
		ob_start();
		include_once ( $this->get_base_dir().'/views/frontend/dashboard.php' );
		return ob_get_clean();
		
	}
	
	/**
	 * Render the form user login
	 * 
	 * @since 1.0
	 */
	public function render_form_user_login() {
		
		ob_start();
		include_once ( $this->get_base_dir().'/views/frontend/form/user-login.php' );
		return ob_get_clean();
		
	}

	/**
	 * Manage change user data
	 * 
	 */
	private function manage_change_member_card_data() {
		
		$email = $_POST['email'];
		$mobile_phone = $_POST['mobile_phone'];
		
		$data = array();
		
		if ( is_email ( $email ) ) {
			
			$data['email'] = $email;
			
		}
		
		$mobile_phone = preg_replace('~\D~', '', $mobile_phone);
		
		if ( $mobile_phone != '' ) {
			
			$data['mobile_phone'] = $mobile_phone;
			
		}
		
		if ( count ( $data ) > 0 ) {
			
			$id_member_card = $_SESSION['hq60']['member_card']['id'];
			
			$hq60 = new \Hq60\Hq60();
			
			$save = $hq60->set_member_card_data ( $id_member_card , $data );
			
			if ( $save == true ) {
				
				$this->set_frontend_success('Operazione eseguita con successo!');
				
			} else {
				
				$this->set_frontend_error('Si è verificato un errore.');
				
			}
			
		}
		
	} //

	/**
 	 * 
	 * Manage the change password
	 * 
	 * @since 1.0
	 *  
 	 */
 	private function manage_change_password() {
 		
		// 1 - check if all fields are compiled
		
		$continue = true;
		
		$old_password = $_POST['old_password'];
		$new_password = $_POST['new_password'];
		$confirm_new_password = $_POST['confirm_new_password'];
		
		if ( !is_string($old_password) || !is_string($new_password) || !is_string($confirm_new_password) ) {
			
			$error = 'Le password devono essere alfanumeriche';
			$continue = false;
			
		}
		
		if ( $continue === true ) {
			
			if ( $old_password == '' || $new_password == '' || $confirm_new_password == '' ) {
				
				$error = 'Tutti i dati richiesti sono obbligatori';
				$continue = false;
				
			}
			
		}
		
		// 2 - Check if old password is right
		
		if ( $continue === true ) {
				
			$id_member_card = $_SESSION['hq60']['member_card']['id'];
			
			$hq60 = new \Hq60\Hq60();
			
			$valid = $hq60->login ( null , $old_password , $id_member_card );
			
			if ( is_string ( $valid ) ) {
				
				$error = 'La vecchia password è errata';
				$continue = false;
				
			}
			
		}
		
		// 3 - check if new password is ok
		
		if ( $continue == true ) {
			
			if( !preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,99}$/", $new_password) || mb_strlen( $new_password) < 8) {
				
				$error = 'La nuova password deve avere almeno 8 caratteri, di cui un carattere minuscolo, uno maiuscolo ed un numero';
				
    			$continue = false;
			
			}
			
		}
		
		// 4 - check if 2 new password are equal
		
		if ( $continue == true ) {
			
			if ( $confirm_new_password != $new_password ) {
				
				$continue = false;
				$error = 'Le due password non corrispondono!';
				
			}
			
		}
		
		// finally change the password
		
		if ( $continue == true ) {
			
			$register = $hq60->set_password ( $id_member_card , $new_password );
			
			if ( $register == true ) {
				
				// il cambio è avvenuto, try login to be sure
				$valid = $hq60->login ( null , $new_password , $id_member_card );
				
				if ( $valid == true ) {
					
					$success = 'Cambio password avvenuto correttamente';
					
				} else {
					
					$error = 'Si è verificato un errore durante il cambio password. Riprova, se l\'errore persiste, contattaci indicandoci esattamente questo codice: FCPV469';
					
				}
				
				
			} else {
				
				$error = 'Si è verificato un errore durante il cambio password. Riprova, se l\'errore persiste, contattaci indicandoci esattamente questo codice: FCP464';
				
			}
			
			
		}
		
		
		if ( isset ( $error ) ) {
			
			$this->set_frontend_error($error);
			
		}
		
		if ( isset ( $success ) ) {
			
			$this->set_frontend_success($success);
			
		}
		
		
		
		
		
		// 4 - check if new password == confirm
		
		// 5 - if all ok manage change
		
 	}
	
	/**
	 * Recover the password
	 * 
	 * @since 1.0
	 * 
	 */
	private function recovery_password() {
		
		// 0 - we need to check that all fields are compiled
		$form_is_ok = false;
		
		if ( $_POST['card_number'] == '' ) {
				
			$this->set_frontend_error('Il numero di card è obbligatorio.');
				
		} else {
			
			$form_is_ok = true;
			
		}
		
		if ( $form_is_ok ) {
			
			$card_exists = true;
			$card_number = sanitize_text_field ( $_POST['card_number'] );
			
			$hq60 = new \Hq60\Hq60();
			
			$id_member_card = $hq60->get_id_member_card_from_card_number($card_number);
			
			if ( $id_member_card == null ) {
				
				$this->set_frontend_error('Numero di card non corretto. Riprova.');
				$card_exists = false;
				
			}
						
		}
		
		
		// 1 - we need to check if card is registered
		if ( isset ( $card_exists ) && $card_exists == true ) {
				
			$registered = $hq60->login ( $card_number , 'asdfasdfasdf' );
			
			if ( $registered == 'Password doesn\'t match' ) {
				
				$previous_registered = true;
				//$this->set_frontend_error('La card risulta già registrata!');
				
			}
			
			if ( $registered == 'Member Card not registered' ) {
				
				$previous_registered = false;
				
			}
			
		}
		
		if ( isset ( $previous_registered ) && ( $previous_registered == true ) ) {
			
			// we need the email from hq60
			
			$email = $hq60->get_email_from_id_member_card ( $id_member_card );
			
			if ( $email == false ) {
				
				$this->set_frontend_error('Nella tua anagrafica non abbiamo trovato alcuna email e non possiamo procedere con il reset automatico. Ti invitiamo a contattarci pertanto per risolvere il problema.');
				
			} else {
				
				// 1 - genera nuova password
				$password = wp_generate_password ( 8 , false );
				
				//echo $password;
				
				//echo $id_member_card;				
				// 2 - salvala nella scheda cliente
				$hq60->set_password ( $id_member_card , $password );
				
				
				// 3 - mandala per email al Cliente
				$mail = new \Hq60fidelitycard\Email\Email( $this->get_base_dir() );
				$sent = $mail->send_temp_password_registration_email( $password , $email , true );
				
				// 4 -stampa messaggio ok
				
				$this->set_frontend_success('Tutto ok! Abbiamo inviato alla tua casella email una nuova password!');				
				
			}
			
		} else {
			
			$this->set_frontend_error('La card che hai inserito non è stata ancora registrata!');
			
		}
		
	}
	
	/**
	 * Perform the registration
	 * 
	 * @since 1.0
	 * 
	 */
	private function perform_register() {
		
		// 0 - we need to check that all fields are compiled
		$form_is_ok = $this->check_register_form();
		
		if ( $form_is_ok ) {
			
			$card_exists = true;
			$card_number = sanitize_text_field ( $_POST['card_number'] );
			
			$hq60 = new \Hq60\Hq60();
			
			$id_member_card = $hq60->get_id_member_card_from_card_number($card_number);
			
			if ( $id_member_card == null ) {
				
				$this->set_frontend_error('Numero di card non corretto. Riprova.');
				$card_exists = false;
				
			}
						
		}
		
		
		// 1 - we need to check if card is registered
		if ( isset ( $card_exists ) && $card_exists == true ) {
				
			$registered = $hq60->login ( $card_number , 'asdfasdfasdf' );
			
			if ( $registered == 'Password doesn\'t match' ) {
				
				$previous_registered = true;
				$this->set_frontend_error('La card risulta già registrata!');
				
			}
			
			if ( $registered == 'Member Card not registered' ) {
				
				$previous_registered = false;
				
			}
			
		}
		
		if ( isset ( $previous_registered ) && ( $previous_registered == false ) ) {
			
			
			// salviamo nel db e inviamo la mail
			$db = new \Hq60fidelitycard\Database\Database();
			$id_registration = $db->save_temp_registration();
			
			if ( $id_registration != null ) {
				
				$email = new \Hq60fidelitycard\Email\Email( $this->get_base_dir() );
				
				$sent = $email->send_temp_registration_email ( $id_registration );
				
				if ( $sent == true ) {
					
					$this->set_frontend_success('Ti stiamo inviando una email. Segui le istruzioni che trovi per procedere');
					
				} else {
					
					$error = 'Si è verificato un errore durante l\'invio. Riprova. '.$sent;
					
					$this->set_frontend_error($error);
					
				}
								
			}
			
		}
		
	}
	
	/**
	 * Check if register form is correctly submitted
	 * 
	 * @since 1.0
	 * 
	 */
	private function check_register_form() {
		
		$check = false;
		
		if ( !isset($_POST['privacy']) ) {
			
			$error = 'L\'accettazione privacy è obbligatoria';
			
		} else {
			
			if ( $_POST['card_number'] == '' ) {
				
				$error = 'Il numero della card è obbligatorio';
				
			} else {
				
				if ( ! is_email ( $_POST['email'] ) ) {
				
					$error = 'L\'indirizzo email inserito non è corretto';
				
				} else {
				
					$check = true;
				
				}
				
			}
						
		}
		
		if ( isset ( $error ) ) {
			
			$this->set_frontend_error($error);
			
		}
		
		return $check;
		
	}
	
	
	/**
	 * Perform the logout
	 * 
	 * @since 1.0
	 * 
	 */
	public function perform_logout() {
			
		$url = $_SERVER['REQUEST_URI'];
		
		if ( $url === '/fidelity-card-logout/' ) {
		
			unset ( $_SESSION['hq60'] );
		
			header("Location: /");
			
			exit;
			
		}
		
	}
	
	/**
	 * Execute login user
	 * 
	 * @since 1.0
	 * 
	 */
	private function perform_login() {
		
		$logged = false;
				
		$card = sanitize_text_field ( $_POST['card_number'] );
		$password = sanitize_text_field($_POST['password']);
		
		if ( $card!='' && $password!='' ) {
		
			$hq60 = new \Hq60\Hq60();
			
			$logged = $hq60->login($card , $password);
			
			if ( $logged === true ) {
				
				$id_member_card = $hq60->get_id_member_card_from_card_number($card);
				
				$_SESSION['hq60']['member_card']['logged'] = true;
				$_SESSION['hq60']['member_card']['id'] = $id_member_card;
				$_SESSION['hq60']['member_card']['card_number'] = $card;
				
				header('Location: /fidelity-card-dashboard/');
				exit;
				
			}
			
			if ( $logged === false ) {
				
				$logged = 'Card not found. Please check it';
								
			}
			
			if ( is_string($logged) ) {
				
				$this->set_frontend_error($logged);
				return false;
				
			}
			
		}
		
	}

	/**
	 * 
	 * Check if exists a code via $_GET
	 * 
	 * @since 1.9.1
	 * 
	 * 
	 */
	public function check_temp_registration_code() {
		
		
		$success_pre = __('La tua richiesta è stata confermata con successo. A breve sulla tua email riceverai una password temporanea.' , $this->get_language_domain() );
		$success_post = __('Registrazione completata! Ora puoi fare il login usando la password che ti abbiamo inviato per email.' , $this->get_language_domain() );
		$error = __('Non abbiamo trovato alcuna richiesta. Forse hai già confermato questa richiesta oppure il link è scaduto? Ricorda che devi confermare la tua richiesta entro 24h!' , $this->get_language_domain() );
		
		if ( isset ( $_GET['confirm_hq60_temp_registration'] ) ) {
			
			$id_post = sanitize_text_field( $_GET['confirm_hq60_temp_registration'] );
			// start the check of email sent
			
		}
		
		if ( isset ( $id_post ) ) {
			
			
			$args = array ( 
			
			
				'posts_per_page'	=> 1,
				'post_type'			=> $this->get_custom_post_name(),
				'p'         		=> $id_post
			
			);
			
			$first_key = array (
		
				'key'		=>		$this->get_custom_post_name().'-confirmed-on',
				'compare'	=>		'NOT EXISTS'
			
			);
			
			$meta_query = array (
			
				$first_key
			
			);
			
			
			$args['meta_query'] = array($meta_query);
			
			$temp = new \WP_Query( $args );
			
			if ( $temp->have_posts() ) {
				
				while ( $temp->have_posts() ) {
					
					$temp->the_post();
					
					$id_temp_registration_post = get_the_ID();
					
					$email = get_post_meta ( $id_temp_registration_post , $this->get_custom_post_name().'-email' , true );
					$card = get_post_meta ( $id_temp_registration_post , $this->get_custom_post_name().'-card-number' , true );
					
					//echo $email;
					//echo $card;
					
					$db = new \Hq60fidelitycard\Database\Database();
					$db->delete_multiple_temp_registration( $id_temp_registration_post , $card );
					
				}					
					
			} else {
				
				//$this->set_frontend_error ( $error );
				echo '<div class="alert alert-danger">';
					echo '<p>'.$error.'</p>';
				echo '</div>';
				
			}
			
		}

		// dopo aver fatto la verifica precedente ed aver cancellato le registrazioni multiple
		// abbiamo un id_temp_registration_post con cui possiamo generare una password 

		if ( isset ( $id_temp_registration_post ) && isset ( $email ) ) {
			
			$password = wp_generate_password ( 8 , false );
			
			// send the password via email
			$mail = new \Hq60fidelitycard\Email\Email( $this->get_base_dir() );
			$sent = $mail->send_temp_password_registration_email( $password , $email );
			
			// set the new password on database
			$hq60 = new \Hq60\Hq60();
			$id_member_card = $hq60->get_id_member_card_from_card_number ( $card );
			
			$registration = $hq60->set_password ( $id_member_card , $password );
			
			if ( $registration == true ) {
				
				$db->set_confirmed_registration ( $id_temp_registration_post );
				
				// update the email
				$update = $hq60->set_email( $id_member_card , $email );
				
				echo '<div class="alert alert-success">';
					echo '<p>'.$success_post.'</p>';
				echo '</div>';
				
			} else {
				
				//$this->set_frontend_error ( $error );
				echo '<div class="alert alert-danger">';
					echo '<p>'.$error.'</p>';
				echo '</div>';
				
			}
			
			
			
		}

		/*if ( $this->get_answer_from_check_code() ) {
		
			
			include_once ( $this->get_base_dir().'/views/frontend/alert-submission.php' );
		
		}*/
		
	}
	
	/**
	 * Mantain frontend success
	 * 
	 * @param string the string to display
	 * 
	 * @since 1.0
	 * 
	 */
	private function set_frontend_success($text) {
		
		switch ( $text ) {
			
			default:
				
				$text = $text;
				
			break;
			
		}
		
		$this->_frontend_success = $text;
		
	}
	
	
	/**
	 * Mantain frontend error
	 * 
	 * @param string the string to display
	 * 
	 * @since 1.0
	 * 
	 */
	private function set_frontend_error($text) {
		
		switch ( $text ) {
			
			case 'Card not found. Please check it':
				
				$text = 'Card o password non riconosciute. Riprova.';
				
			break;
			
			case 'Member Card not registered':
				
				$text = 'Card non ancora registrata. Segui le istruzioni alla pagina "Registrati"';
				
			break;
			
			case 'Password doesn\'t match':
				
				$text = 'Card o password non riconosciute. Riprova.';
				
			break;
			
			default:
				
				$text = $text;
				
			break;
			
		}
		
		$this->_frontend_error = $text;
		
	}
	
	/**
	 * Get frontend success
	 * 
	 * @return string the success
	 * 
	 * @since 1.0
	 * 
	 */
	protected function get_frontend_success() {
		
		return $this->_frontend_success;
		
	}
	
	/**
	 * Get frontend error
	 * 
	 * @return string the error
	 * 
	 * @since 1.0
	 * 
	 */
	protected function get_frontend_error() {
		
		return $this->_frontend_error;
		
	}
	
	
	
	
}
