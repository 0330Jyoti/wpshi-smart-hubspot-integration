<?php
class WPSHI_Smart_Hubspot_Admin {

    public function __construct() {
        $this->load();
        $this->menu();
    }

    private function load() {
        require_once WPSHI_PLUGIN_PATH . 'admin/class.settings.php';
        require_once WPSHI_PLUGIN_PATH . 'admin/class.fields-mappings.php';
        require_once WPSHI_PLUGIN_PATH . 'admin/class.synchronization.php';
        require_once WPSHI_PLUGIN_PATH . 'admin/class.customers-list.php';
        require_once WPSHI_PLUGIN_PATH . 'admin/class.orders-list.php';
        require_once WPSHI_PLUGIN_PATH . 'admin/class.products-list.php';
    }

    private function menu() {
        add_action( 'admin_enqueue_scripts', array($this, 'wpshi_smart_hubspot_scripts_callback') );
        add_action( 'wp_ajax_wp_field', array($this, 'wpshi_smart_hubspot_wp_field_callback') );
        add_action( 'wp_ajax_zoho_field', array($this, 'wpshi_smart_hubspot_hubspot_field_callback') );
        add_action( 'admin_menu', array($this, 'wpshi_smart_hubspot_main_menu_callback') );
    }

    public function wpshi_smart_hubspot_scripts_callback(  $hook ) {
      
        $hook_array = array(
                            'toplevel_page_wpszi-smart-zoho-integration',
                            'smart-zoho_page_wpszi-smart-zoho-mappings'
                        );
        if (  ! in_array($hook, $hook_array)  ) {
            return;
        }

        // Register the script

        wp_register_script( 
                    'jquery-dataTables-min-js', 
                    WPSZI_PLUGIN_URL . 'admin/js/jquery.dataTables.min.js', 
                    array(), 
                    time() 
                );

        wp_register_script( 
                    'wpszi-smart-zoho-js', 
                    WPSZI_PLUGIN_URL . 'admin/js/wpszi-smart-zoho.js', 
                    array(), 
                    time() 
                );

        wp_register_style( 
                    'jquery-dataTables-min-css', 
                    WPSZI_PLUGIN_URL . 'admin/css/jquery.dataTables.min.css', 
                    array(), 
                    time() 
                );

        wp_register_style( 
                    'wpszi-smart-zoho-style', 
                    WPSZI_PLUGIN_URL . 'admin/css/wpszi-smart-zoho.css', 
                    array(), 
                    time() 
                );
        

        // Localize the script with new data
        $localize_array = array(
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
        );

        wp_localize_script( 'wpszi-smart-zoho-js', 'smart_zoho_js', $localize_array );
         
        // Enqueued script with localized data.

        wp_enqueue_script( 'jquery-dataTables-min-js' );
        wp_enqueue_script( 'wpszi-smart-zoho-js' );
        
        wp_enqueue_style( 'jquery-dataTables-min-css' );
        wp_enqueue_style( 'wpszi-smart-zoho-style' );
    }

    public function wpshi_smart_hubspot_wp_field_callback() {
       ob_start(); 
       $wp_fields = array();

       if( isset( $_REQUEST['wp_module_name'] ) ){

            switch ( $_REQUEST['wp_module_name'] ) {
                case 'customers':
                    $wp_fields = WPSZI_Smart_Zoho::get_customer_fields();
                    break;

                case 'orders':
                    $wp_fields = WPSZI_Smart_Zoho::get_order_fields();
                    break;

                case 'products':
                    $wp_fields = WPSZI_Smart_Zoho::get_product_fields();
                    break;

                default:
                    # code...
                    break;
            }
       }
       
       $wp_fields_options = "<option>".esc_html__('Select WP Fields', 'wpszi-smart-zoho')."</option>";
       
       if($wp_fields){
            foreach ($wp_fields as $option_value => $option_label) {
                $wp_fields_options .=  "<option value='".$option_value."'>".esc_html__($option_label, 'wpszi-smart-zoho')."</option>";
            }
       }
       
       ob_get_clean();
       echo $wp_fields_options;
       wp_die(); 
    }

    public function wpshi_smart_hubspot_hubspot_field_callback() {
       ob_start(); 
       $zoho_fields = array();

       if( isset($_REQUEST['zoho_module_name']) ){
            $zoho_module    = $_REQUEST['zoho_module_name'];
                $zoho_api_obj   = new WPSZI_Smart_Zoho_API();
                $zoho_fields    = $zoho_api_obj->getFieldsMetaData($zoho_module);
       }
       
       $zoho_fields_options = "<option>".esc_html__('Select Zoho Fields', 'wpszi-smart-zoho')."</option>";
       
       if($zoho_fields){
            foreach ($zoho_fields['fields'] as $zoho_field_key => $zoho_field_data) {
                if($zoho_field_data['field_read_only'] == NULL){

                    $system_mandatory   = ($zoho_field_data['system_mandatory'] == 1) ? " ( Required ) " : "";
                    $data_type          = isset($zoho_field_data['data_type']) ? " ( ".ucfirst($zoho_field_data['data_type'])." ) " : "";

                    echo 
                    $zoho_fields_options .= "<option value='".$zoho_field_data['api_name']."'>". esc_html__($zoho_field_data['display_label'], 'wpszi-smart-zoho') . esc_html($data_type) . esc_html($system_mandatory) . "</option>";
                }
            }
       }
       
       ob_get_clean();
       echo $zoho_fields_options;
       wp_die(); 
    }

    public function wpshi_smart_hubspot_main_menu_callback() {
        add_menu_page( 
                        esc_html__('Smart Hubspot', 'wpshi-smart-hubspot'), 
                        esc_html__('Smart Hubspot', 'wpshi-smart-hubspot'), 
                        'manage_options', 
                        'wpshi-smart-hubspot-integration', 
                        array($this, 'settings_callback'), 
                        'dashicons-button' 
                    );

        add_submenu_page( 
                        'wpshi-smart-hubspot-integration', 
                        esc_html__( 'Smart Hubspot Settings', 'wpshi-smart-hubspot' ), 
                        esc_html__( 'Smart Hubspot', 'wpshi-smart-hubspot' ), 
                        'manage_options', 
                        'wpshi-smart-hubspot-integration', 
                        array($this, 'settings_callback')
                    );

        add_submenu_page( 
                        'wpshi-smart-hubspot-integration', 
                        esc_html__( 'Smart Hubspot Fields Mappings', 'wpshi-smart-hubspot' ), 
                        esc_html__( 'Fields Mappings', 'wpshi-smart-hubspot' ), 
                        'manage_options', 
                        'wpshi-smart-hubspot-mappings', 
                        array($this, 'mappings_callback')
                    );

        add_submenu_page( 
                        'wpshi-smart-hubspot-integration', 
                        esc_html__( 'Smart Hubspot Synchronization', 'wpshi-smart-hubspot' ), 
                        esc_html__( 'Synchronization', 'wpshi-smart-hubspot' ), 
                        'manage_options', 
                        'wpshi-smart-hubspot-synchronization', 
                        array($this, 'Synchronization_callback')
                    );

        add_submenu_page( 
                        'wpshi-smart-hubspot-integration', 
                        NULL, 
                        NULL, 
                        'manage_options', 
                        'wpshi_smart_hubspot_process', 
                        array($this, 'zoho_process_callback')
                    );
    }

    public function zoho_process_callback(){
        
        global $wpdb;

        if ( isset( $_REQUEST['code'] ) ) {
            $code           = sanitize_text_field($_REQUEST['code']);
            $zoho_api_obj   = new WPSZI_Smart_Zoho_API();
            $token          = $zoho_api_obj->getToken( $code, WPSZI_REDIRECT_URI );
            
            if ( isset( $token->error ) ) {
                /*Error logic*/
            } else {
                $zoho_api_obj->manageToken( $token );    
            }
        }

        $smart_zoho_obj = new WPSZI_Smart_Zoho();
        $smart_zoho_obj->store_required_field_mapping_data();
        
        wp_redirect(WPSHI_SETTINGS_URI);
        exit();
    }

    public function settings_callback(){
        $admin_settings_obj = new WPSHI_Smart_Hubspot_Admin_Settings();
        $admin_settings_obj->processSettingsForm();
        $admin_settings_obj->displaySettingsForm();
    }

    public function mappings_callback(){
        $field_mapping_obj = new WPSHI_Smart_Hubspot_Field_Mappings();
        $field_mapping_obj->processMappingsForm();
        $field_mapping_obj->displayMappingsForm(); 
        $field_mapping_obj->displayMappingsFieldList();
    }

    public function Synchronization_callback(){
        $admin_synch_obj = new WPSHI_Smart_Hubspot_Admin_Synchronization();
        $admin_synch_obj->processSynch();
        $admin_synch_obj->displaySynchData();
    }
}

new WPSHI_Smart_Hubspot_Admin();
?>