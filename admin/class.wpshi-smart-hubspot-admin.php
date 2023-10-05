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
        add_action( 'wp_ajax_hubspot_field', array($this, 'wpshi_smart_hubspot_hubspot_field_callback') );
        add_action( 'admin_menu', array($this, 'wpshi_smart_hubspot_main_menu_callback') );
    }

    public function wpshi_smart_hubspot_scripts_callback(  $hook ) {
      
        $hook_array = array(
                            'toplevel_page_wpshi-smart-hubspot-integration',
                            'smart-hubspot_page_wpshi-smart-hubspot-mappings'
                        );
        if (  ! in_array($hook, $hook_array)  ) {
            return;
        }

        // Register the script

        wp_register_script( 
                    'jquery-dataTables-min-js', 
                    WPSHI_PLUGIN_URL . 'admin/js/jquery.dataTables.min.js', 
                    array(), 
                    time() 
                );

        wp_register_script( 
                    'wpshi-smart-hubspot-js', 
                    WPSHI_PLUGIN_URL . 'admin/js/wpshi-smart-hubspot.js', 
                    array(), 
                    time() 
                );

        wp_register_style( 
                    'jquery-dataTables-min-css', 
                    WPSHI_PLUGIN_URL . 'admin/css/jquery.dataTables.min.css', 
                    array(), 
                    time() 
                );

        wp_register_style( 
                    'wpshi-smart-hubspot-style', 
                    WPSHI_PLUGIN_URL . 'admin/css/wpshi-smart-hubspot.css', 
                    array(), 
                    time() 
                );
        

        // Localize the script with new data
        $localize_array = array(
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
        );

        wp_localize_script( 'wpshi-smart-hubspot-js', 'smart_hubspot_js', $localize_array );
         
        // Enqueued script with localized data.

        wp_enqueue_script( 'jquery-dataTables-min-js' );
        wp_enqueue_script( 'wpshi-smart-hubspot-js' );
        
        wp_enqueue_style( 'jquery-dataTables-min-css' );
        wp_enqueue_style( 'wpshi-smart-hubspot-style' );
    }

    public function wpshi_smart_hubspot_wp_field_callback() {
       ob_start(); 
       $wp_fields = array();

       if( isset( $_REQUEST['wp_module_name'] ) ){

            switch ( $_REQUEST['wp_module_name'] ) {
                case 'customers':
                    $wp_fields = WPSHI_Smart_Hubspot::get_customer_fields();
                    break;

                case 'orders':
                    $wp_fields = WPSHI_Smart_Hubspot::get_order_fields();
                    break;

                case 'products':
                    $wp_fields = WPSHI_Smart_Hubspot::get_product_fields();
                    break;

                default:
                    # code...
                    break;
            }
       }
       
       $wp_fields_options = "<option>".esc_html__('Select WP Fields', 'wpshi-smart-hubspot')."</option>";
       
       if($wp_fields){
            foreach ($wp_fields as $option_value => $option_label) {
                $wp_fields_options .=  "<option value='".$option_value."'>".esc_html__($option_label, 'wpshi-smart-hubspot')."</option>";
            }
       }
       
       ob_get_clean();
       echo $wp_fields_options;
       wp_die(); 
    }

    public function wpshi_smart_hubspot_hubspot_field_callback() {
       ob_start(); 
       $hubspot_fields = array();

       if( isset($_REQUEST['hubspot_module_name']) ){
            $hubspot_module    = $_REQUEST['hubspot_module_name'];
                $hubspot_api_obj   = new WPSHI_Smart_Hubspot_API();
                $hubspot_fields    = $hubspot_api_obj->getFieldsMetaData($hubspot_module);
       }
       
       $hubspot_fields_options = "<option>".esc_html__('Select Hubspot Fields', 'wpshi-smart-hubspot')."</option>";
       
       if($hubspot_fields){
            foreach ($hubspot_fields['fields'] as $hubspot_field_key => $hubspot_field_data) {
                if($hubspot_field_data['field_read_only'] == NULL){

                    $system_mandatory   = ($hubspot_field_data['system_mandatory'] == 1) ? " ( Required ) " : "";
                    $data_type          = isset($hubspot_field_data['data_type']) ? " ( ".ucfirst($hubspot_field_data['data_type'])." ) " : "";

                    echo 
                    $hubspot_fields_options .= "<option value='".$hubspot_field_data['api_name']."'>". esc_html__($hubspot_field_data['display_label'], 'wpshi-smart-hubspot') . esc_html($data_type) . esc_html($system_mandatory) . "</option>";
                }
            }
       }
       
       ob_get_clean();
       echo $hubspot_fields_options;
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
                        array($this, 'hubspot_process_callback')
                    );
    }

    public function hubspot_process_callback(){
        
        global $wpdb;

        if ( isset( $_REQUEST['code'] ) ) {
            $code           = sanitize_text_field($_REQUEST['code']);
            $hubspot_api_obj   = new WPSHI_Smart_Hubspot_API();
            $token          = $hubspot_api_obj->getToken( $code, WPSHI_REDIRECT_URI );
            
            if ( isset( $token->error ) ) {
                /*Error logic*/
            } else {
                $hubspot_api_obj->manageToken( $token );    
            }
        }

        $smart_hubspot_obj = new WPSHI_Smart_Hubspot();
        $smart_hubspot_obj->store_required_field_mapping_data();
        
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