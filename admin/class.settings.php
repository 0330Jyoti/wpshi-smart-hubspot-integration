<?php
class WPSHI_Smart_Hubspot_Admin_Settings {

    public function processSettingsForm($POST = array()){
       
        $client_id = $client_secret = "";
        
       	if ( isset( $_POST['submit'] ) ) {

            if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == "general"){
                $client_id                  = sanitize_text_field($_REQUEST['wpshi_smart_hubspot_settings']['client_id']);
                $client_secret              = sanitize_text_field($_REQUEST['wpshi_smart_hubspot_settings']['client_secret']);
                $wpshi_smart_hubspot_data_center  = sanitize_text_field($_REQUEST['wpshi_smart_hubspot_settings']['data_center']);    
            }
                        
            $wpshi_smart_hubspot_settings  = !empty(get_option( 'wpshi_smart_hubspot_settings' )) ? get_option( 'wpshi_smart_hubspot_settings' ) : array();

            $wpshi_smart_hubspot_settings = array_merge($wpshi_smart_hubspot_settings, $_REQUEST['wpshi_smart_hubspot_settings']);
            
            update_option( 'wpshi_smart_hubspot_settings', $wpshi_smart_hubspot_settings );
            
            if ( $client_id && $client_secret ) {
                $redirect_uri = esc_url(WPSHI_REDIRECT_URI);
                $redirect_url = "$wpshi_smart_hubspot_data_center/oauth/v2/auth?client_id=$client_id&redirect_uri=$redirect_uri&response_type=code&scope=HubspotCRM.modules.all,HubspotCRM.settings.all&access_type=offline";



                
                if ( wp_redirect( $redirect_url ) ) {
				    exit;
				}
            }
            
        }
    }

    public function displaySettingsForm(){
        require_once WPSHI_PLUGIN_PATH . 'admin/partials/settings.php';
    }
}
?>