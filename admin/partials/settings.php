<?php
	
	$wpshi_smart_hubspot 				= get_option( 'wpshi_smart_hubspot' );
	$wpshi_smart_hubspot_settings 		= get_option( 'wpshi_smart_hubspot_settings' );

	$client_id 						=  isset($wpshi_smart_hubspot_settings['client_id']) ? $wpshi_smart_hubspot_settings['client_id'] : "";
	$client_secret 					= isset($wpshi_smart_hubspot_settings['client_secret']) ? $wpshi_smart_hubspot_settings['client_secret'] : "";
	$wpshi_smart_hubspot_data_center 	= isset($wpshi_smart_hubspot_settings['data_center']) ? $wpshi_smart_hubspot_settings['data_center'] : "";

	$wpshi_smart_hubspot_data_center 	= ( $wpshi_smart_hubspot_data_center ? $wpshi_smart_hubspot_data_center : 'https://accounts.hubspot.com' );
?>

<div class="wrap">                
	
	<h1><?php echo esc_html__( 'Hubspot CRM Settings and Authorization' ); ?></h1>
	<hr>

	<form method="post">
		<?php 
			$tab = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'general';
		?>

		<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
			<a href="<?php echo admin_url('admin.php?page=wpshi-smart-hubspot-integration&tab=general'); ?>" class="nav-tab <?php if($tab == 'general'){ echo 'nav-tab-active';} ?>"><?php echo esc_html__( 'General', 'wpshi-smart-hubspot' ); ?></a>
			<a href="<?php echo admin_url('admin.php?page=wpshi-smart-hubspot-integration&tab=synch_settings'); ?>" class="nav-tab <?php if($tab == 'synch_settings'){ echo 'nav-tab-active';} ?>"><?php echo esc_html__( 'Synch Settings', 'wpshi-smart-hubspot' ); ?></a>
		</nav>
		
		<input type="hidden" name="tab" value="<?php echo esc_html($tab); ?>">

		<?php if( isset($tab) && 'general' == $tab ){ ?>
			
			<table class="form-table general_settings">
				<tbody>
					<tr>
						<th scope="row"><label><?php echo esc_html__( 'Data Center', 'wpshi-smart-hubspot' ); ?></label></th>
						<td>
							<fieldset>
								<label>
									<input 
										type="radio" 
										name="wpshi_smart_hubspot_settings[data_center]" 
										value="https://accounts.hubspot.com"
										<?php echo esc_html( $wpshi_smart_hubspot_data_center == 'https://accounts.hubspot.com' ? ' checked="checked"' : '' ); ?> />
										United States (US)
								</label><br>

								<label>
									<input 
										type="radio" 
										name="wpshi_smart_hubspot_settings[data_center]" 
										value="https://accounts.hubspot.eu"
										<?php echo esc_html( $wpshi_smart_hubspot_data_center == 'https://accounts.hubspot.eu' ? ' checked="checked"' : '' ); ?> />
										Europe (EU)
								</label><br>

								<label>
									<input 
										type="radio" 
										name="wpshi_smart_hubspot_settings[data_center]" 
										value="https://accounts.hubspot.com.cn"
										<?php echo esc_html( $wpshi_smart_hubspot_data_center == 'https://accounts.hubspot.com.cn' ? ' checked="checked"' : '' ); ?> />
										China (CN)
								</label>
							</fieldset>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label><?php echo esc_html__( 'Client ID', 'wpshi-smart-hubspot' ); ?></label>
						</th>
						<td>
							<input class="regular-text" type="text" name="wpshi_smart_hubspot_settings[client_id]" value="<?php echo get_option('client_id') ?>" required />
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label><?php echo esc_html__( 'Client Secret', 'wpshi-smart-hubspot' ); ?></label>
						</th>
						<td>
							<input class="regular-text" type="text" name="wpshi_smart_hubspot_settings[client_secret]" value="<?php echo esc_attr($client_secret); ?>" required />
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label><?php echo esc_attr( 'Redirect URI', 'wpshi-smart-hubspot' ); ?></label>
						</th>
						<td>
							<input class="regular-text" type="text" value="<?php echo esc_url(WPSHI_REDIRECT_URI); ?>" readonly />
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label><?php echo esc_html__( 'Access Token', 'wpshi-smart-hubspot' ); ?></label>
						</th>
						<td>
							
							<?php 
								if(isset($wpshi_smart_hubspot->access_token)){
									echo esc_html($wpshi_smart_hubspot->access_token);
								}
							?>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label><?php echo esc_html__( 'Refresh Token', 'wpshi-smart-hubspot' ); ?></label>
						</th>
						<td>
							<?php 
								if(isset($wpshi_smart_hubspot->refresh_token)){
									echo esc_html($wpshi_smart_hubspot->refresh_token);
								}
							?>
						</td>
					</tr>
					
				</tbody>
			</table>

			<div class="inline">
				<p>
					<input type='submit' class='button-primary' name="submit" value="<?php echo esc_html__( 'Save & Authorize', 'wpshi-smart-hubspot' ); ?>" />
				</p>

				<?php 
					if(isset($wpshi_smart_hubspot->refresh_token)){
						echo '<p class="success">'.esc_html__('Authorized', 'wpshi-smart-hubspot').'</p>';
					}
				?>
			</div>

		<?php }else if( isset($tab) && 'synch_settings' == $tab ){ ?>
			<?php 
				$smart_hubspot_obj   = new WPSHI_Smart_Hubspot();
		        $wp_modules 	= $smart_hubspot_obj->get_wp_modules();
		        $getListModules = $smart_hubspot_obj->get_hubspot_modules();
			?>
			<table class="form-table synch_settings">
				<tbody>
					<?php
						if($getListModules['modules']){
					        foreach ($getListModules['modules'] as $key => $singleModule) {
					            if( $singleModule['deletable'] &&  $singleModule['creatable'] ){
					            	foreach ($wp_modules as $wp_module_key => $wp_module_name) {
					            		?>
						            		<tr>
												<th scope="row"><label><?php echo esc_html__( "Enable {$wp_module_key} to Hubspot {$singleModule['api_name']} Sync", 'wpshi-smart-hubspot' ); ?></label></th>
												<td>
													<fieldset>
														<label>
															<input 
																type="checkbox" 
																name="wpshi_smart_hubspot_settings[synch][<?php echo $wp_module_key.'_'.$singleModule['api_name']; ?>]" 
																<?php @checked( $wpshi_smart_hubspot_settings['synch']["{$wp_module_key}_{$singleModule['api_name']}"], 1 ); ?>
																value="1" />
																Enable
														</label>
													</fieldset>
												</td>
											</tr>
						            	<?php	
					            	}
					            }
					        }
					    }
					?>    
    				
				</tbody>
			</table>
			<p><input type='submit' class='button-primary' name="submit" value="<?php echo esc_html__( 'Save', 'wpshi-smart-hubspot' ); ?>" /></p>
		
		<?php }?>	
		
	</form>
</div>