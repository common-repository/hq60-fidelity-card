<?php

	/**
	 * 
	 * points - raccolta punti - 41
	 * gift		- gift card			90
	 * abbonamenti	abbonamenti		91
	 * points	diamanti		514
	 * 
	 */

	if ( isset ( $campaign_list ) ) {
		
		if ( is_object( $campaign_list ) ) {
			
			if ( isset ( $campaign_list->campaign ) ) {
				
				?>
				
				<ul class="list-menu-campaign">
				
				<?php
				
				foreach ( $campaign_list->campaign as $campaign ) {
					
					//if ( $campaign->type!='subscription' ) {
					
						$name = $campaign->name;
						$id_campaign = $campaign->id;
						
						?>
					
							<li><a href="?view=campaign&id_campaign=<?php echo $id_campaign; ?>"><?php echo $name ?></a></li>
					
						<?php
						
					//}
					
					
					
				} // foreach
				
				?>
				
				</ul>
				
				<?php
				
			}
			
		}
		
	}

?>