<?php

	if ( isset ( $balance ) ) {
		
		$campaign_type = $single_campaign->campaign[0]->type;
		
		/********************************/
		/*		RACCOLTA PUNTI			*/
		
		if ( $campaign_type == 'points' ) {
			
			$decimals = 0;
			
			// get custom label point if any
			$descriptor = $single_campaign->campaign[0]->custom_label_point;
			
			if ( $descriptor == '' ) {
				
				$descriptor = 'punti';
				
			}
			
			if ( $balance == false ) {
				
				$final_balance = '0';
				
			} else {
				
				$final_balance = $balance->balance[0]->balance;
				
			}
			
			// get real balance
			
			?>
			
				<div class="single-balance-container">
					
					<p><?php echo number_format_i18n( $final_balance, $decimals ); ?></p>
					<p><?php echo $descriptor; ?></p>
					
				</div>
			
			<?php
			
		} //if ( $campaign_type == 'points' ) {
					
		/********************************/
		/*		TESSERA SCONTO O GIFT CARD		*/
		
		if ( $campaign_type == 'discount' || $campaign_type == 'gift' ) {
			
			$decimals = 2;
			
			// get custom label point if any
			$descriptor = '€';
			
			if ( $balance == false ) {
				
				$final_balance = '0';
				
			} else {
				
				$final_balance = $balance->balance[0]->balance;
				
			}
			
			// get real balance
			
			?>
			
				<div class="single-balance-container">
					
					<p><?php echo number_format_i18n( $final_balance, $decimals ); ?> <?php echo $descriptor; ?></p>
					
				</div>
			
			<?php
			
		} //if ( $campaign_type == 'discount' ) {
				
		/************************ SUBSCRIPTION			**************************************/
		
		
		
		if ( $campaign_type == 'subscription' ) {
			
			$decimals = 0;
			
			if ( isset ( $balance->balance ) ) {
				
				if ( is_array ( $balance->balance ) && ( count ( $balance->balance ) > 0 ) ) {
					
					?>
					
					<table class="table table-transaction">
					
						<thead>
							<tr>
								<th><?php _e( 'Prodotto / Servizio' , $this->get_language_domain() )?></th>
								<th><?php _e( 'Disponibili' , $this->get_language_domain() )?></th>
							</tr>
						</thead>
						<tbody>
					
						<?php
						
						foreach ( $balance->balance as $bal ) {
							
							if ( $bal->balance > 0 ) {
								
								?>
								
								<tr>
									
									<td><?php echo $bal->item ;?></td>
									<td><strong><?php echo number_format_i18n( $bal->balance, $decimals ) ;?></strong></td>
									
								</tr>
								
								<?php
								
							}
							
						}
						
						?>
						
						</tbody>
					</table>
					
					<hr />
						
						<?php
					
				} else {
					
					?>
				
					<p><?php _e('Nessun prodotto o servizio trovato' , $this->get_language_domain() );?></p>
				
					<?php
					
				}
				
				
				
			} else {
				
				?>
				
				<p><?php _e('Nessun prodotto o servizio trovato' , $this->get_language_domain() );?></p>
				
				<?php
				
			}
			
			
			
		}
		
					
				
		/***************** 		ELENCO TRANSAZIONI			***********************************/
		
		/*** GIFT CARD // TESSERA SCONTO */
		
		if ( $campaign_type == 'subscription' ) {
			
			if ( isset ( $transaction->transaction ) ) {
				
				$decimals = 0;
				?>
				
				<table class="table table-transaction">
					
					<thead>
						<tr>
							<th><?php _e( 'Transazione' , $this->get_language_domain() )?></th>
							<th><?php _e( 'Data' , $this->get_language_domain() )?></th>
						</tr>
					</thead>
					<tbody>
				
				<?php
				foreach ( $transaction->transaction as $t ) {
					
					$class = 'positive-number';
					
					if ( $t->amount_final < 0 ) {
						
						$class = 'negative-number';
						
					}
					
					$amount = number_format_i18n( $t->amount_final, $decimals );
					$date =  date_i18n( 'd/m/Y', strtotime( $t->date ) );
					
					echo '<tr>';
						echo '<td class="'.$class.'">';
							echo $amount.' '.$t->reward_name;
						echo '</td>';
						echo '<td>';
							echo $date;
						echo '</td>';
					echo '</tr>';
					
				}
				?>
				
					</tbody>
				
				</table>
				<?php
				
			}
			
		} // subscription
		
		
					
		if ( $campaign_type == 'points' ) {
			
			if ( isset ( $transaction->transaction ) ) {
					
				$decimals = 0;
				?>
				
				<table class="table table-transaction">
					
					<thead>
						<tr>
							<th><?php _e( 'Punti' , $this->get_language_domain() )?></th>
							<th><?php _e( 'Dettaglio' , $this->get_language_domain() )?></th>
							<th><?php _e( 'Data' , $this->get_language_domain() )?></th>
						</tr>
					</thead>
					<tbody>
				
				<?php
				foreach ( $transaction->transaction as $t ) {
					
					$class = 'positive-number';
					
					if ( $t->amount_final < 0 ) {
						
						$class = 'negative-number';
						
					}
					
					$amount = number_format_i18n( $t->amount_final, $decimals );
					$date =  date_i18n( 'd/m/Y', strtotime( $t->date ) );
					
					echo '<tr>';
						echo '<td class="'.$class.'">';
							echo $amount;
						echo '</td>';
						echo '<td>';
							echo $t->description;
						echo '</td>';
						echo '<td>';
							echo $date;
						echo '</td>';
					echo '</tr>';
					
				}
				?>
				
					</tbody>
				
				</table>
				<?php
				
			}
			
		} // IF POINTS
		
		/*** GIFT CARD // TESSERA SCONTO */
		
		if ( $campaign_type == 'gift' || $campaign_type == 'discount' ) {
			
			if ( isset ( $transaction->transaction ) ) {
					
				$decimals = 2;
				?>
				
				<table class="table table-transaction">
					
					<thead>
						<tr>
							<th><?php _e( 'Importo' , $this->get_language_domain() )?></th>
							<th><?php _e( 'Dettaglio' , $this->get_language_domain() )?></th>
							<th><?php _e( 'Data' , $this->get_language_domain() )?></th>
						</tr>
					</thead>
					<tbody>
				
				<?php
				foreach ( $transaction->transaction as $t ) {
					
					$class = 'positive-number';
					
					if ( $t->amount_final < 0 ) {
						
						$class = 'negative-number';
						
					}
					
					$amount = number_format_i18n( $t->amount_final, $decimals );
					$date =  date_i18n( 'd/m/Y', strtotime( $t->date ) );
					
					echo '<tr>';
						echo '<td class="'.$class.'">';
							echo $amount.' €';
						echo '</td>';
						echo '<td>';
							echo $t->description;
						echo '</td>';
						echo '<td>';
							echo $date;
						echo '</td>';
					echo '</tr>';
					
				}
				?>
				
					</tbody>
				
				</table>
				<?php
				
			}
			
		} // gift card 
		
			
		
	}

?>