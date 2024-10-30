<?php

$first_name = '';
$last_name = '';
$card = '';
$mobile_phone = '';
$email = '';

if ( isset ( $member_card_data->member_card[0]->first_name ) ) {
	
	$first_name = $member_card_data->member_card[0]->first_name;
	
}

if ( isset ( $member_card_data->member_card[0]->last_name ) ) {
	
	$last_name = $member_card_data->member_card[0]->last_name;
	
}

if ( isset ( $member_card_data->member_card[0]->card ) ) {
	
	$card = $member_card_data->member_card[0]->card;
	
}

if ( isset ( $member_card_data->member_card[0]->email ) ) {
	
	$email = $member_card_data->member_card[0]->email;
	
}

if ( isset ( $member_card_data->member_card[0]->mobile_phone ) ) {
	
	$mobile_phone = $member_card_data->member_card[0]->mobile_phone;
	
}

?>

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

<p><?php _e ( 'Card' , $this->get_language_domain() ); ?> <?php echo $card; ?>
	
	<?php
	
		if ( $first_name != '' || $last_name != '' ) {
			
			?>
			
			 - <?php _e ( ' intestata a ' , $this->get_language_domain() ); ?> <?php echo $first_name; ?> <?php echo $last_name; ?>
			
			<?php
			
		}
	
	?>
	
</p>

<form method="post" action="<?php esc_url( $_SERVER['REQUEST_URI'] ) ?>">
	<!-- nonce -->
		<?php wp_nonce_field( $this->get_prefix().'nonce_field' ); ?>
	<!-- /nonce -->
	
	<div class="form-group">
		<label for="email"><?php _e('Email' , $this->get_language_domain() ) ?></label>
	    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>" />
	</div>
	
	<div class="form-group">
		<label for="mobile_phone"><?php _e('Cellulare' , $this->get_language_domain() ) ?></label>
	    <input type="text" class="form-control" name="mobile_phone" value="<?php echo $mobile_phone; ?>" />
	</div>
	
	<!-- trap -->
		
	<div class="form-group invisible-field">
		<label for="trap"><?php _e('Non inserire nulla in questo campo' , $this->get_language_domain() ) ?></label>
	    <input type="text" class="form-control" name="trap" />
	</div>
		
	<!-- /trap -->
		
	<button type="submit" class="btn btn-success btn-custom" name="<?php echo $this->get_prefix() ?>submit-data"><?php _e( 'Modifica' , $this->get_language_domain()) ?></button>
		
</form>