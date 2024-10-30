<!-- TOKEN SECTION -->
<tr>
	<th scope="row"><?php _e('Token' , $this->get_language_domain()) ?></th>
		<td>
			<?php
		    		
		    	$value = $form_array['token'];
				$saved_option = esc_attr( get_option ( $value ) );
				$default = ''; // get the blog name as default
				$explain = _x( 'Il token associato all\'utente' , $this->get_language_domain() );
						
				if ( $saved_option == '' ) {
							
					$saved_option = $default;
							
				}
						
		    		
		    ?>
		    		
		    <input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		    <p class="description"><?php printf( esc_html( $explain ), $default , $default ); ?></p>
		    		
		</td>
	</tr>
<!-- /TOKEN SECTION -->