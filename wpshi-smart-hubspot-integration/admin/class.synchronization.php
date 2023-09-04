<?php
class WPSZI_Smart_Zoho_Admin_Synchronization {

    public function processSynch($POST = array()){
       
       	if ( isset( $_POST['submit'] ) ) {

            if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == "general"){
                $client_id                  = sanitize_text_field($_REQUEST['wpszi_smart_zoho_settings']['client_id']);
                $client_secret              = sanitize_text_field($_REQUEST['wpszi_smart_zoho_settings']['client_secret']);
                $wpszi_smart_zoho_data_center  = sanitize_text_field($_REQUEST['wpszi_smart_zoho_settings']['data_center']);
            }
                        
            $wpszi_smart_zoho_settings  = !empty(get_option( 'wpszi_smart_zoho_settings' )) ? get_option( 'wpszi_smart_zoho_settings' ) : array();

            $wpszi_smart_zoho_settings = array_merge($wpszi_smart_zoho_settings, $_REQUEST['wpszi_smart_zoho_settings']);
            
            update_option( 'wpszi_smart_zoho_settings', $wpszi_smart_zoho_settings );
            
        }


        /*Synch product*/
        if( isset( $_POST['smart_synch'] ) && $_POST['smart_synch'] == 'zoho' ){

           
            $id = $_POST['id'];

            switch ($_POST['wp_module']) {
                
                case 'products':
                    
                    $WPSZI_Smart_Zoho_Public = new WPSZI_Smart_Zoho_Public();
                    $WPSZI_Smart_Zoho_Public->addProductToZoho( $id );

                    break;

                case 'orders':
                    
                    $WPSZI_Smart_Zoho_Public = new WPSZI_Smart_Zoho_Public();
                    $WPSZI_Smart_Zoho_Public->addOrderToZoho( $id );

                    break;

                case 'customers':
                    
                    $WPSZI_Smart_Zoho_Public = new WPSZI_Smart_Zoho_Public();
                    $WPSZI_Smart_Zoho_Public->addUserToZoho( $id );

                    break;    
                
                default:
                    # code...
                    break;
            }
            
        }
        

    }

    public function displaySynchData(){
        require_once WPSZI_PLUGIN_PATH . 'admin/partials/synchronization.php';
    }
}
?>