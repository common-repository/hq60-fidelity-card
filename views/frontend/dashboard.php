<div class="hq60-plugin-container">
	
	<div class="hq60-user-menu">
		
		<?php require_once ( 'user-menu.php' ); ?>
		
	</div>
	
	<div class="hq60-main-window-container">
		
		<h1><?php echo $title; ?></h1>
		
		<?php
		
			switch ( $view ) {
				
				case 'password':
					
					require_once ( 'user-manage-password.php' );
					
				break;
				
				case 'data':
					
					require_once ( 'user-data.php' );
					
				break;
				
				case 'campaign':
					
					if ( isset ( $campaign_list ) ) {
						
						require_once ( 'user-campaign-list.php' );
						
					}
					
					if ( isset ( $single_campaign ) ) {
						
						require_once ( 'user-balance.php' );
						
					}
					
					
				break;
				
			}
		
			/*if ( isset ( $view ) ) {
				
				if ( $view == 'password' ) {
					
					require_once ( 'user-manage-password.php' );
					
				} else {
				
					if ( isset ( $campaign_list ) ) {
						
						require_once ( 'user-campaign-list.php' );
						
					}
					
					if ( isset ( $single_campaign ) ) {
						
						require_once ( 'user-balance.php' );
						
					}
					
				}
				
			}*/
		
		?>
		
	</div>
	
</div>