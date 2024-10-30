<h1 class="wp-heading-inline"><?php _e('Pagine e lista degli shortcode da utilizzare' , $this->get_language_domain() ); ?></h1>

<table class="widefat fixed" cellspacing="0">
	<thead>
		
		<tr>
			<th><strong><?php _e ( 'Pagina' , $this->get_language_domain() ) ;?></strong></th>
			<th><strong><?php _e ( 'URL' , $this->get_language_domain() ) ;?></strong></th>
			<th><strong><?php _e ( 'Shorcode da utilizzare' , $this->get_language_domain() ) ;?></strong></th>
			<th><strong><?php _e ( 'Obbligatoria nel menù?' , $this->get_language_domain() ) ;?></strong></th>
		</tr>
		
	</thead>
	
	<tbody>
		
		<tr>
			<td><?php _e( 'Login' , $this->get_language_domain() ); ?></td>
			<td>fidelity-card-login</td>
			<td>[<?php echo $this->get_shortcode_user_login();?>]</td>
			<td><?php _e( 'SI' , $this->get_language_domain() ); ?></td>
		</tr>
		
		<tr class="alternate">
			<td><?php _e( 'Logout' , $this->get_language_domain() ); ?></td>
			<td>fidelity-card-logout</td>
			<td>-</td>
			<td><?php _e( 'NO' , $this->get_language_domain() ); ?></td>
		</tr>
		
		<tr>
			<td><?php _e( 'Registrazione' , $this->get_language_domain() ); ?></td>
			<td>fidelity-card-registration</td>
			<td>[<?php echo $this->get_shortcode_user_registration();?>]</td>
			<td><?php _e( 'SI' , $this->get_language_domain() ); ?></td>
		</tr>
		
		<tr class="alternate">
			<td><?php _e( 'Dashboard' , $this->get_language_domain() ); ?></td>
			<td>fidelity-card-dashboard</td>
			<td>[<?php echo $this->get_shortcode_user_dashboard();?>]</td>
			<td><?php _e( 'SI' , $this->get_language_domain() ); ?></td>
		</tr>
		
		<tr>
			<td><?php _e( 'Recupero Password' , $this->get_language_domain() ); ?></td>
			<td>fidelity-card-recovery</td>
			<td>[<?php echo $this->get_shortcode_user_recovery_password();?>]</td>
			<td><?php _e( 'SI' , $this->get_language_domain() ); ?></td>
		</tr>
		
	</tbody>
	
</table>