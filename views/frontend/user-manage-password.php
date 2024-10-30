<p><?php _e('La nuova password deve avere almeno 8 caratteri, di cui un carattere minuscolo, uno maiuscolo ed un numero' , $this->get_language_domain() ); ?></p>

<?php

	if ( $this->get_frontend_error() != '' ) {
		
	?>
	
		<div class="alert alert-danger">
			
			<p><?php _e ( $this->get_frontend_error() , $this->get_language_domain() ); ?></p>
			
		</div>
	
	<?php
		
	}
	
	if ( $this->get_frontend_success() != '' ) {
		
	?>
	
		<div class="alert alert-success">
			
			<p><?php _e ( $this->get_frontend_success() , $this->get_language_domain() ); ?></p>
			
		</div>
	
	<?php
		
	}

?>

<form method="post" action="<?php esc_url( $_SERVER['REQUEST_URI'] ) ?>">
	<!-- nonce -->
		<?php wp_nonce_field( $this->get_prefix().'nonce_field' ); ?>
	<!-- /nonce -->
	
	<div class="form-group">
		<label for="old_password"><?php _e('Vecchia password' , $this->get_language_domain() ) ?></label>
	    <input type="text" class="form-control" name="old_password" />
	</div>
	
	<div class="form-group">
		<label for="new_password"><?php _e('Nuova password' , $this->get_language_domain() ) ?></label>
	    <input type="new_password" class="form-control" name="new_password" />
	</div>
	
	<div class="form-group">
		<label for="confirm_new_password"><?php _e('Conferma la nuova password' , $this->get_language_domain() ) ?></label>
	    <input type="confirm_new_password" class="form-control" name="confirm_new_password" />
	</div>
	
	<!-- trap -->
		
	<div class="form-group invisible-field">
		<label for="trap"><?php _e('Non inserire nulla in questo campo' , $this->get_language_domain() ) ?></label>
	    <input type="text" class="form-control" name="trap" />
	</div>
		
	<!-- /trap -->
		
	<button type="submit" class="btn btn-success btn-custom" name="<?php echo $this->get_prefix() ?>submit-change-password"><?php _e( 'Cambia password' , $this->get_language_domain()) ?></button>
		
</form>