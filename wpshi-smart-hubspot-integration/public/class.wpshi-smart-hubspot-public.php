<?php
class WPSHI_Smart_Hubspot_Public {
  
    public function __construct() {
        
        $this->loadCustomerAction();
        $this->loadOrderAction();
        $this->loadProductAction();
    }


    private function loadCustomerAction() {
        add_action( 'user_register', array($this, 'addUserToZoho') );
        add_action( 'profile_update', array($this, 'addUserToZoho'), 10, 1 );
        add_action( 'woocommerce_update_customer', array($this, 'addUserToZoho'), 10, 1 );
    }


    private function loadOrderAction() {
        add_action( 'save_post', array( $this, 'addOrderToZoho' ), 10, 1 );
        add_action('woocommerce_thankyou', array( $this, 'addOrderToZoho' ), 10, 1);
    }


    private function loadProductAction() {
        add_action( 'woocommerce_update_product', array( $this, 'addProductToZoho' ), 10, 1 );
    }

    public function addUserToZoho( $user_id ){
        global $wpdb;
        $data       = array();
        $user_info  = get_userdata($user_id);

        $default_wp_module = "customers";

        $wpszi_smart_zoho_settings = get_option( 'wpszi_smart_zoho_settings' );
        $synch_settings         = !empty( $wpszi_smart_zoho_settings['synch'] ) ? $wpszi_smart_zoho_settings['synch'] : array();

        foreach ($synch_settings as $wp_zoho_module => $enable) {
            
            $wp_zoho_module = explode('_', $wp_zoho_module);
            $wp_module      = $wp_zoho_module[0];
            $zoho_module    = $wp_zoho_module[1];

            if($default_wp_module == $wp_module){
                
                $get_zoho_field_mapping = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}smart_zoho_field_mapping WHERE wp_module ='".$wp_module."' AND zoho_module = '".$zoho_module."' AND status='active'");

                foreach ($get_zoho_field_mapping as $key => $value) {
                    $wp_field   = $value->wp_field;
                    $zoho_field = $value->zoho_field;

                    if ( $zoho_field ) {
                        if ( isset( $user_info->{$wp_field} ) ) {
                            if ( is_array( $user_info->{$wp_field} ) ) {
                                $user_info->{$wp_field} = implode(';', $user_info->{$wp_field} );
                            }
                            $data[$zoho_module][$zoho_field] = strip_tags( $user_info->{$wp_field} );
                        }
                    }
                }
            }   
        }

        if( $data != null ){
            $this->prepareAndActionOnData( $user_id, $data, $default_wp_module );
        }
    }


    public function addOrderToZoho( $order_id ){
        global $wpdb, $post_type; 
        $data       = array();

        if ( get_post_type( $order_id ) !== 'shop_order' ){
            return;
        }

        $order = wc_get_order( $order_id );
        
        $default_wp_module = "orders";

        $wpszi_smart_zoho_settings = get_option( 'wpszi_smart_zoho_settings' );
        $synch_settings         = !empty( $wpszi_smart_zoho_settings['synch'] ) ? $wpszi_smart_zoho_settings['synch'] : array();

        foreach ($synch_settings as $wp_zoho_module => $enable) {
            
            $wp_zoho_module = explode('_', $wp_zoho_module);
            $wp_module      = $wp_zoho_module[0];
            $zoho_module    = $wp_zoho_module[1];

            if($default_wp_module == $wp_module){
                
                $get_zoho_field_mapping = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}smart_zoho_field_mapping WHERE wp_module ='".$wp_module."' AND zoho_module = '".$zoho_module."' AND status='active'");

                foreach ($get_zoho_field_mapping as $key => $value) {
                    $wp_field   = $value->wp_field;
                    $zoho_field = $value->zoho_field;

                    if ( $zoho_field ) {

                        if ( null !== $order->{$wp_field}() ) {
                            $data[$zoho_module][$zoho_field] = strip_tags( $order->{$wp_field}() );
                        }
                    }
                }
            }   
        }
        
        if( $data != null ){
            $this->prepareAndActionOnData( $order_id, $data, $default_wp_module );
        }
    }


    public function addProductToZoho( $post_id ){
        global $wpdb, $post_type, $data; 
        $data = array();

        if ( get_post_type( $post_id ) !== 'product' ){
            return;
        }
        
        $product = wc_get_product( $post_id );

        $default_wp_module = "products";

        $wpszi_smart_zoho_settings = get_option( 'wpszi_smart_zoho_settings' );
        $synch_settings         = !empty( $wpszi_smart_zoho_settings['synch'] ) ? $wpszi_smart_zoho_settings['synch'] : array();

        foreach ($synch_settings as $wp_zoho_module => $enable) {
            
            $wp_zoho_module = explode('_', $wp_zoho_module);
            $wp_module      = $wp_zoho_module[0];
            $zoho_module    = $wp_zoho_module[1];

            if($default_wp_module == $wp_module){
                
                $get_zoho_field_mapping = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}smart_zoho_field_mapping WHERE wp_module ='".$wp_module."' AND zoho_module = '".$zoho_module."' AND status='active'");

                foreach ($get_zoho_field_mapping as $key => $value) {
                    $wp_field   = $value->wp_field;
                    $zoho_field = $value->zoho_field;

                    if ( $zoho_field ) {

                        if ( null !== $product->{$wp_field}() ) {
                            if(is_array($product->{$wp_field}())){
                                $data[$zoho_module][$zoho_field] = implode(',', $product->{$wp_field}());
                            }else{
                                $data[$zoho_module][$zoho_field] = strip_tags( $product->{$wp_field}() );    
                            }
                        }
                    }
                }
            }   
        }

        if($data != null ){
            $this->prepareAndActionOnData( $post_id, $data, $default_wp_module );
        }
    }


    public function prepareAndActionOnData($id, $data = array(), $default_wp_module = NULL){
        
        if( $default_wp_module == 'orders' ||  $default_wp_module == 'products' ){
            $smart_zoho_relation = get_post_meta( $id, 'smart_zoho_relation', true );
        }else{
            $smart_zoho_relation = get_user_meta( $id, 'smart_zoho_relation', true );    
        }
        

        if ( ! is_array( $smart_zoho_relation ) ) {
            $smart_zoho_relation = array();
        }

        $zoho_api_obj   = new WPSZI_Smart_Zoho_API();
        
        foreach ($data as $zoho_module => $zoho_data) {
            
            $record_id = ( isset( $smart_zoho_relation[$zoho_module] ) ? $smart_zoho_relation[$zoho_module] : 0 );

            if ( $record_id ) {
                $response = $zoho_api_obj->updateRecord($zoho_module, $zoho_data, $record_id);
            }else{
                $response = $zoho_api_obj->addRecord($zoho_module, $zoho_data);
            }
                        
            if ( isset( $response->data[0]->details->id ) ) {
                $record_id = $response->data[0]->details->id;
                $smart_zoho_relation[$zoho_module] = $record_id;
            }
        }

        if( $default_wp_module == 'orders' ||  $default_wp_module == 'products' ){
            update_post_meta( $id, 'smart_zoho_relation', $smart_zoho_relation );
        }else{
            update_user_meta( $id, 'smart_zoho_relation', $smart_zoho_relation );    
        }
        
    }
}

new WPSHI_Smart_Hubspot_Public();
?>