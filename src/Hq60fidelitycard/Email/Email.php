<?php

/**
 * Class Email Menu.
 * Contains all method to send emails
 * 
 * @since 1.0
 * 
 */
namespace Hq60fidelitycard\Email;

class Email extends \Hq60fidelitycard\Base {
	
	
	public function __construct( $base_dir = null ) {
		
		if ( $base_dir != null ) {
			
			$this->set_base_dir ( $base_dir );
			
		}
		
	}
	
	/**
	 * Send password got from registration
	 * 
	 * @param int the custom post id (i.e. the temp registration)
	 * 
	 * @since 1.0
	 */
	public function send_temp_password_registration_email ( $password , $recipient , $recovery_password = false ) {
		
		$email_body = $this->get_body_email_temp_password_registration ( $password );
		
		if ( $recovery_password == true ) {
			
			$subject = __('Impostazione nuova password' , $this->get_language_domain () );
			
		} else {
			
			$subject = __('Registrazione confermata!' , $this->get_language_domain () );
			
		}
		
		
		
		$sent = $this->send_email ( $recipient , $subject ,  $email_body , $bcc = 'N' , $id_custom_post = null );
		
		return $sent;
		
	}
	
	
	/**
	 * Send temp email registration
	 * 
	 * @param int the custom post id (i.e. the temp registration)
	 * 
	 * @since 1.0
	 */
	public function send_temp_registration_email ( $id_custom_post ) {
		
		$email_body = $this->get_body_email_temp_registration ( $id_custom_post );
		
		$recipient = get_post_meta ( $id_custom_post , $this->get_custom_post_name().'-email' , true );
		
		$subject = __('Conferma la tua registrazione' , $this->get_language_domain () );
		
		$sent = $this->send_email ( $recipient , $subject ,  $email_body , $bcc = 'N' , $id_custom_post = null );
		
		return $sent;
		
	}
	
	/**
	 * Send really the email
	 * 
	 * @param string recipient the recipient of the email
	 * 
	 * @param string subject the email subject
	 * 
	 * @param string the email body to send
	 * 
	 * @param string bcc if send email in bcc to the admin or no
	 * 
	 * @param int the id_custom_post to get the info (if needed)
	 * 
	 * @since 1.0
	 * 
	 */
	
	private function send_email ( $recipient , $subject ,  $email_body , $bcc = 'N' , $id_custom_post = null ) {
		
		// Main email sender from website
		$main_email = get_option( $this->get_prefix().'option_to_email' );
	 	
		// from is by website. To deny spam
		//$from = 'From: '.get_site_url().' <'.$main_email.'>';
		$from = get_bloginfo ( 'name' );
		$from = 'From: '.$from.' <'.get_option ( 'admin_email' ).'>';
		
		// reply to. So the POSTER can answer directly to website
		//$reply_to = 'Reply-To: '. get_option( $this->get_prefix().'option_from_name' ) .' <'. get_option( $this->get_prefix().'option_to_email' ) .'>';
		
		// construct the errors
		$headers = array(
		
							'Content-Type: text/html; charset=UTF-8',
							$from/*,
							$reply_to*/
		
						);
						
		$sent = wp_mail( $recipient , $subject , $email_body , $headers );
				
		if ( $sent==false ) {
			
			if ( isset ( $GLOBALS['phpmailer']->ErrorInfo ) ) {
				
				return $GLOBALS['phpmailer']->ErrorInfo;
				
			}
			
		} else {
		
			return true;
			
		}
		
	 }

	/**
	 * Prepare the body of email for sending temp password
	 * 
	 * @param string the temp password
	 * 
	 * @param int the id custom post
	 * 
	 * @since 1.0
	 * 
	 */
	private function get_body_email_temp_password_registration ( $password ) {
		
		// 1 -include the php file
		
		//$email_body = file_get_contents( WP_PLUGIN_DIR.'/hq60-fidelity-card/views/email/email-temp-password-registration.php' );
		
		
		$email_body = file_get_contents( $this->get_base_dir().'views/email/email-temp-password-registration.php' );
		
		
		$p_1 = _x( 'La registrazione è andata a buon fine!' , $this->get_language_domain() );
		$p_2 = _x( 'La password che devi usare per accedere è' , $this->get_language_domain() );
		
		$email_body = str_replace('{p_1}' , $p_1 , $email_body);
		$email_body = str_replace('{p_2}' , $p_2 , $email_body);
		$email_body = str_replace('{password}' , $password , $email_body);
		
		
		return $email_body;
		
		
		
	}
	
	/**
	 * Prepare the body of email
	 * 
	 * @param int the id custom post
	 * 
	 * @since 1.0
	 * 
	 */
	private function get_body_email_temp_registration ( $id_custom_post ) {
		
		// 1 -include the php file
		
		//$email_body = file_get_contents( WP_PLUGIN_DIR.'/hq60-fidelity-card/views/email/email-temp-registration-confirm.php' );
		$email_body = file_get_contents( $this->get_base_dir().'views/email/email-temp-registration-confirm.php' );
		
		$card_number = get_post_meta ( $id_custom_post , $this->get_custom_post_name().'-card-number' , true );
		
		$p_1 = _x( 'Abbiamo ricevuto la tua richiesta di registrare la tua card numero' , $this->get_language_domain() );
		$p_2 = _x( 'Per assicurarci di aver inserito un indirizzo email corretto, conferma l\'operazione cliccando sul bottone che segue. Riceverai così la tua password per accedere' , $this->get_language_domain() );
		$p_3 = _x( 'Attenzione! Il link è valido solo per 24 ore, passate le quali dovrai fare una nuova registrazione.' , $this->get_language_domain() );
		
		$confirm = _x( 'Clicca qui per confermare' , $this->get_language_domain() );
		
		$url_confirm = get_home_url().'?confirm_hq60_temp_registration='.$id_custom_post;
		
		$email_body = str_replace('{p_1}' , $p_1 , $email_body);
		$email_body = str_replace('{p_2}' , $p_2 , $email_body);
		$email_body = str_replace('{card_number}' , $card_number , $email_body);
		$email_body = str_replace('{confirm}' , $confirm , $email_body);
		$email_body = str_replace('{url_confirm}' , $url_confirm , $email_body);
		$email_body = str_replace('{p_3}' , $p_3 , $email_body);
		
		
		return $email_body;
		
		
		
	}
			
}