<?php

	if ( $this->get_frontend_success() != '' ) {
		
	?>
	
		<div class="alert alert-success">
			
			<p><?php _e ( $this->get_frontend_success() , $this->get_language_domain() ); ?></p>
			
		</div>
	
	<?php
		
	}

	if ( $this->get_frontend_error() != '' ) {
		
	?>
	
		<div class="alert alert-danger">
			
			<p><?php _e ( $this->get_frontend_error() , $this->get_language_domain() ); ?></p>
			
		</div>
	
	<?php
		
	}

?>

<div class="hq60-explain-registration-container">
	<p><?php _e ( 'Inserisci un indirizzo email a te riconducibile. Invieremo alla tua email un link da confermare. Una volta confermato, riceverai una password temporanea per poter accedere.' , $this->get_language_domain() ) ;?></p>
</div>


<form method="post" action="<?php esc_url( $_SERVER['REQUEST_URI'] ) ?>">
	<!-- nonce -->
		<?php wp_nonce_field( $this->get_prefix().'nonce_field' ); ?>
	<!-- /nonce -->
	
	<div class="form-group">
		<label for="card_number"><?php _e('Card numero' , $this->get_language_domain() ) ?></label>
	    <input type="text" class="form-control" name="card_number" />
	</div>
	
	<div class="form-group">
		<label for="email"><?php _e('Email' , $this->get_language_domain() ) ?></label>
	    <input type="text" class="form-control" name="email" />
	</div>
	
	<!-- trap -->
		
	<div class="form-group invisible-field">
		<label for="trap"><?php _e('Non inserire nulla in questo campo' , $this->get_language_domain() ) ?></label>
	    <input type="text" class="form-control" name="trap" />
	</div>
		
	<!-- /trap -->
	
	<div class="checkbox" style="margin-top:10px">
		<label for="privacy">
			<input type="checkbox" name="privacy" value="1" />
			<a href="/privacy"><?php _e('Autorizzo il trattamento dei miei dati, come riportato alla lett. A punti 1, 2 e 3 della policy privacy' , $this->get_language_domain() ) ?></a>
		</label>
	</div>
		
	<div class="checkbox" style="margin-top:10px">
		<label for="privacy_art_4">
			<input type="checkbox" name="privacy_art_4" value="1" />
			<a href="/privacy"><?php _e('Ho letto l\'informativa privacy ed accetto le finalità previste (Lett. A,  punto 4)' , $this->get_language_domain() ) ?></a>
		</label>
	</div>
			
	<div class="checkbox" style="margin-top:10px">
		<label for="privacy_art_5">
			<input type="checkbox" name="privacy_art_5" value="1" />
			<a href="/privacy"><?php _e('Ho letto l\'informativa privacy ed accetto le finalità previste (Lett. A,  punto 5)' , $this->get_language_domain() ) ?></a>
		</label>
	</div>
		
	<button type="submit" class="btn btn-success btn-custom" name="<?php echo $this->get_prefix() ?>submit-register"><?php _e( 'Registrami' , $this->get_language_domain()) ?></button>
		
</form>