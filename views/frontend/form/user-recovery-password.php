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

<p><?php _e ( 'Hai smarrito la password?' , $this->get_language_domain() ); ?></p>
<p><?php _e ( 'Inserisci il tuo numero di tessera. Se la card è stata già registrata, riceverai sull\'email che hai confermato una nuova password.' , $this->get_language_domain() ); ?></p>

<form method="post" action="<?php esc_url( $_SERVER['REQUEST_URI'] ) ?>">
	<!-- nonce -->
		<?php wp_nonce_field( $this->get_prefix().'nonce_field' ); ?>
	<!-- /nonce -->
	
	<div class="form-group">
		<label for="card_number"><?php _e('Card numero' , $this->get_language_domain() ) ?></label>
	    <input type="text" class="form-control" name="card_number" />
	</div>
	
	<!-- trap -->
		
	<div class="form-group invisible-field">
		<label for="trap"><?php _e('Non inserire nulla in questo campo' , $this->get_language_domain() ) ?></label>
	    <input type="text" class="form-control" name="trap" />
	</div>
		
	<!-- /trap -->
		
	<button type="submit" class="btn btn-success btn-custom" name="<?php echo $this->get_prefix() ?>submit-recovery"><?php _e( 'Recupera password' , $this->get_language_domain()) ?></button>
		
</form>