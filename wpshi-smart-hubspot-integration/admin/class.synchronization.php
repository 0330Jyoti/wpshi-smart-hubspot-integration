<?php
class WPSHI_Smart_Hubspot_Admin_Synchronization {

    public function processSynch($POST = array()){
       
       	if ( isset( $_POST['submit'] ) ) {

            if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == "general"){
                $client_id                  = sanitize_text_field($_REQUEST['wpshi_smart_hubspot_settings']['client_id']);
                $client_secret              = sanitize_text_field($_REQUEST['wpshi_smart_hubspot_settings']['client_secret']);
                $wpshi_smart_hubspot_data_center  = sanitize_text_field($_REQUEST['wpshi_smart_hubspot_settings']['data_center']);
            }
                        
            $wpshi_smart_hubspot_settings  = !empty(get_option( 'wpshi_smart_hubspot_settings' )) ? get_option( 'wpshi_smart_hubspot_settings' ) : array();

            $wpshi_smart_hubspot_settings = array_merge($wpshi_smart_hubspot_settings, $_REQUEST['wpshi_smart_hubspot_settings']);
            
            update_option( 'wpshi_smart_hubspot_settings', $wpshi_smart_hubspot_settings );
            
        }


        /*Synch product*/
        if( isset( $_POST['smart_synch'] ) && $_POST['smart_synch'] == 'hubspot' ){

           
            $id = $_POST['id'];

            switch ($_POST['wp_module']) {
                
                case 'products':
                    
                    $WPSHI_Smart_Hubspot_Public = new WPSHI_Smart_Hubspot_Public();
                    $WPSZI_Smart_Zoho_Public->addProductToZoho( $id );

                    break;

                case 'orders':
                    
                    $WPSHI_Smart_Hubspot_Public = new WPSHI_Smart_Hubspot_Public();
                    $WPSHI_Smart_Hubspot_Public->addOrderToZoho( $id );

                    break;

                case 'customers':
                    
                    $WPSHI_Smart_Hubspot_Public = new WPSHI_Smart_Hubspot_Public();
                    $WPSHI_Smart_Hubspot_Public->addUserToZoho( $id );

                    break;    
                
                default:
                    # code...
                    break;
            }
            
        }
        

    }

    public function displaySynchData(){
        require_once WPSHI_PLUGIN_PATH . 'admin/partials/synchronization.php';
    }
}
?>