<div class="wrap">
	
	<h1><?php echo $this->get_plugin_name() ?></h1>
	
	<?php
	
		if ( is_plugin_active( 'wp-super-cache/wp-cache.php' ) ) {
			
			?>
			
			<div class="alert alert-danger">
				
				<p><?php _e( 'HQ60 è incompatibile - al momento - con WP Super Cache. Se ottieni un "errore 500", disattiva WP Super Cache' , $this->get_language_domain() ) ?></p>
				
			</div>
			
			<?php
			
		}
	
	?>
	
	<h2><?php _e('Impostazioni' , $this->get_language_domain() ) ?></h2>
	
	<?php
	
	$customer_name = $this->get_customer_name();
	
	if ( !null == $customer_name ) {
		
		?>
		
		<h3><?php _e( 'Software licenziato a '.$this->get_customer_name() , $this->get_language_domain() ) ?></h3>
		
		<?php
		
	}
	
	?>
	
	<?php
	
		$tab = 'generic';
			
		if ( isset ( $_GET['tab'] ) ) {
				
			$tab = $_GET['tab'];
				
		}
		
		//$url = 'options-general.php?page='.$this->get_slug().'&tab=' . $tab.'&settings-updated=true';
		$url = $this->get_parent_slug().'?page='.$this->get_slug().'&tab=' . $tab.'&settings-updated=true';
		
		
		if( isset($_GET['settings-updated']) ) {
	
	?>
	<div id="message" class="updated">
		<p><strong><?php _e('Settings saved.') ?></strong></p>
	</div>
	
	<?php
		}
	?>
	
	<?php
	
		if ( $this->get_error() != '' ) {
			
			?>
			
			<div class="notice notice-error">
				
				<p><?php echo $this->get_error() ?></p>
				
			</div>
			
			<?php
			
		}
	
	?>
	
	<form method="post" action="<?php echo $url; ?>">
		
		<?php
		
			settings_fields( $this->get_option_group() );
			do_settings_sections( $this->get_option_group() );
		
			$form_array = $this->get_array_keys_admin_settings_form();
			
			$nonce_name_action = $this->get_prefix().'nonce';
			$nonce_name_field = 'input_nonce';
			wp_nonce_field( $nonce_name_action , $nonce_name_field );
		
		?>
		
		<table class="form-table">
			
			<?php
			
				$this->create_admin_nav_menu();
			
				$base = $this->get_base_dir();
				
				$tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'generic';
				
				switch ($tab) {
				
					case 'generic':
						
						include( $base.'views/admin/settings-generic.php' );
						
					break;
					
				}
			
			?>
			
		</table>
		
		<?php submit_button( '', 'primary', 'hq60_submit' ); ?>
		
	</form>

</div>