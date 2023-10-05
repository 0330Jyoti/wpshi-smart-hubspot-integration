<?php
class WPSHI_Smart_Hubspot_Public {
  
    public function __construct() {
        
        $this->loadCustomerAction();
        $this->loadOrderAction();
        $this->loadProductAction();
    }


    private function loadCustomerAction() {
        add_action( 'user_register', array($this, 'addUserToHubspot') );
        add_action( 'profile_update', array($this, 'addUserToHubspot'), 10, 1 );
        add_action( 'woocommerce_update_customer', array($this, 'addUserToHubspot'), 10, 1 );
    }


    private function loadOrderAction() {
        add_action( 'save_post', array( $this, 'addOrderToHubspot' ), 10, 1 );
        add_action('woocommerce_thankyou', array( $this, 'addOrderToHubspot' ), 10, 1);
    }


    private function loadProductAction() {
        add_action( 'woocommerce_update_product', array( $this, 'addProductToHubspot' ), 10, 1 );
    }

    public function addUserToHubspot( $user_id ){
        global $wpdb;
        $data       = array();
        $user_info  = get_userdata($user_id);

        $default_wp_module = "customers";

        $wpshi_smart_hubspot_settings = get_option( 'wpshi_smart_hubspot_settings' );
        $synch_settings         = !empty( $wpshi_smart_hubspot_settings['synch'] ) ? $wpshi_smart_hubspot_settings['synch'] : array();

        foreach ($synch_settings as $wp_hubspot_module => $enable) {
            
            $wp_hubspot_module = explode('_', $wp_hubspot_module);
            $wp_module      = $wp_hubspot_module[0];
            $hubspot_module    = $wp_hubspot_module[1];

            if($default_wp_module == $wp_module){
                
                $get_hubspot_field_mapping = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}smart_hubspot_field_mapping WHERE wp_module ='".$wp_module."' AND hubspot_module = '".$hubspot_module."' AND status='active'");

                foreach ($get_hubspot_field_mapping as $key => $value) {
                    $wp_field   = $value->wp_field;
                    $hubspot_field = $value->hubspot_field;

                    if ( $hubspot_field ) {
                        if ( isset( $user_info->{$wp_field} ) ) {
                            if ( is_array( $user_info->{$wp_field} ) ) {
                                $user_info->{$wp_field} = implode(';', $user_info->{$wp_field} );
                            }
                            $data[$hubspot_module][$hubspot_field] = strip_tags( $user_info->{$wp_field} );
                        }
                    }
                }
            }   
        }

        if( $data != null ){
            $this->prepareAndActionOnData( $user_id, $data, $default_wp_module );
        }
    }


    public function addOrderToHubspot( $order_id ){
        global $wpdb, $post_type; 
        $data       = array();

        if ( get_post_type( $order_id ) !== 'shop_order' ){
            return;
        }

        $order = wc_get_order( $order_id );
        
        $default_wp_module = "orders";

        $wpshi_smart_hubspot_settings = get_option( 'wpshi_smart_hubspot_settings' );
        $synch_settings         = !empty( $wpshi_smart_hubspot_settings['synch'] ) ? $wpshi_smart_hubspot_settings['synch'] : array();

        foreach ($synch_settings as $wp_hubspot_module => $enable) {
            
            $wp_hubspot_module = explode('_', $wp_hubspot_module);
            $wp_module      = $wp_hubspot_module[0];
            $hubspot_module    = $wp_hubspot_module[1];

            if($default_wp_module == $wp_module){
                
                $get_hubspot_field_mapping = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}smart_hubspot_field_mapping WHERE wp_module ='".$wp_module."' AND hubspot_module = '".$hubspot_module."' AND status='active'");

                foreach ($get_hubspot_field_mapping as $key => $value) {
                    $wp_field   = $value->wp_field;
                    $hubspot_field = $value->hubspot_field;

                    if ( $hubspot_field ) {

                        if ( null !== $order->{$wp_field}() ) {
                            $data[$hubspot_module][$hubspot_field] = strip_tags( $order->{$wp_field}() );
                        }
                    }
                }
            }   
        }
        
        if( $data != null ){
            $this->prepareAndActionOnData( $order_id, $data, $default_wp_module );
        }
    }


    public function addProductToHubspot( $post_id ){
        global $wpdb, $post_type, $data; 
        $data = array();

        if ( get_post_type( $post_id ) !== 'product' ){
            return;
        }
        
        $product = wc_get_product( $post_id );

        $default_wp_module = "products";

        $wpshi_smart_hubspot_settings = get_option( 'wpshi_smart_hubspot_settings' );
        $synch_settings         = !empty( $wpshi_smart_hubspot_settings['synch'] ) ? $wpshi_smart_hubspot_settings['synch'] : array();

        foreach ($synch_settings as $wp_hubspot_module => $enable) {
            
            $wp_hubspot_module = explode('_', $wp_hubspot_module);
            $wp_module      = $wp_hubspot_module[0];
            $hubspot_module    = $wp_hubspot_module[1];

            if($default_wp_module == $wp_module){
                
                $get_hubspot_field_mapping = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}smart_hubspot_field_mapping WHERE wp_module ='".$wp_module."' AND hubspot_module = '".$hubspot_module."' AND status='active'");

                foreach ($get_hubspot_field_mapping as $key => $value) {
                    $wp_field   = $value->wp_field;
                    $hubspot_field = $value->hubspot_field;

                    if ( $hubspot_field ) {

                        if ( null !== $product->{$wp_field}() ) {
                            if(is_array($product->{$wp_field}())){
                                $data[$hubspot_module][$hubspot_field] = implode(',', $product->{$wp_field}());
                            }else{
                                $data[$hubspot_module][$hubspot_field] = strip_tags( $product->{$wp_field}() );    
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
            $smart_hubspot_relation = get_post_meta( $id, 'smart_hubspot_relation', true );
        }else{
            $smart_hubspot_relation = get_user_meta( $id, 'smart_hubspot_relation', true );    
        }
        

        if ( ! is_array( $smart_hubspot_relation ) ) {
            $smart_hubspot_relation = array();
        }

        $hubspot_api_obj   = new WPSHI_Smart_Hubspot_API();
        
        foreach ($data as $hubspot_module => $hubspot_data) {
            
            $record_id = ( isset( $smart_hubspot_relation[$hubspot_module] ) ? $smart_hubspot_relation[$hubspot_module] : 0 );

            if ( $record_id ) {
                $response = $hubspot_api_obj->updateRecord($hubspot_module, $hubspot_data, $record_id);
            }else{
                $response = $hubspot_api_obj->addRecord($hubspot_module, $hubspot_data);
            }
                        
            if ( isset( $response->data[0]->details->id ) ) {
                $record_id = $response->data[0]->details->id;
                $smart_hubspot_relation[$hubspot_module] = $record_id;
            }
        }

        if( $default_wp_module == 'orders' ||  $default_wp_module == 'products' ){
            update_post_meta( $id, 'smart_hubspot_relation', $smart_hubspot_relation );
        }else{
            update_user_meta( $id, 'smart_hubspot_relation', $smart_hubspot_relation );    
        }
        
    }
}

new WPSHI_Smart_Hubspot_Public();
?>