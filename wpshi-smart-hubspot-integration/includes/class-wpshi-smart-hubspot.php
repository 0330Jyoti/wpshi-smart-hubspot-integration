<?php
class WPSHI_Smart_Hubspot {

	protected $plugin_name;

	protected $version;

	public function __construct() {
		$this->version = '1.0.0';
		$this->plugin_name = 'wpszi-smart-zoho';
	}

	public function run() {
		/*
			Load all class files
		*/
		require_once WPSHI_PLUGIN_PATH . 'includes/class-wpshi-smart-hubspot-api.php';
        require_once WPSHI_PLUGIN_PATH . 'admin/class.wpshi-smart-hubspot-admin.php';
		require_once WPSHI_PLUGIN_PATH . 'public/class.wpshi-smart-hubspot-public.php';
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}
	
	public function get_version() {
		return $this->version;
	}

	public function get_wp_modules(){
		return array(
                'customers' => esc_html__('Customers','wpszi-smart-zoho'),
                'orders'    => esc_html__('Orders','wpszi-smart-zoho'),
                'products'  => esc_html__('Products','wpszi-smart-zoho'),
            );
	}

	public function get_zoho_modules(){

		$zoho_api_obj   = new WPSHI_Smart_Hubspot_API();
       
        /*get list modules*/
        $getListModules = $zoho_api_obj->getListModules();
        
        return $getListModules;
	}

	public static function get_customer_fields(){
    	
    	global $wpdb;
		$wc_fields = array(
		    'first_name'            => esc_html__('First Name', 'wpszi-smart-zoho'),
		    'last_name'             => esc_html__('Last Name', 'wpszi-smart-zoho'),
		    'user_email'            => esc_html__('Email', 'wpszi-smart-zoho'),
		    'billing_first_name'    => esc_html__('Billing First Name', 'wpszi-smart-zoho'),
		    'billing_last_name'     => esc_html__('Billing Last Name', 'wpszi-smart-zoho'),
		    'billing_company'       => esc_html__('Billing Company', 'wpszi-smart-zoho'),
		    'billing_address_1'     => esc_html__('Billing Address 1', 'wpszi-smart-zoho'),
		    'billing_address_2'     => esc_html__('Billing Address 2', 'wpszi-smart-zoho'),
		    'billing_city'          => esc_html__('Billing City', 'wpszi-smart-zoho'),
		    'billing_state'         => esc_html__('Billing State', 'wpszi-smart-zoho'),
		    'billing_postcode'      => esc_html__('Billing Postcode', 'wpszi-smart-zoho'),
		    'billing_country'       => esc_html__('Billing Country', 'wpszi-smart-zoho'),
		    'billing_phone'         => esc_html__('Billing Phone', 'wpszi-smart-zoho'),
		    'billing_email'         => esc_html__('Billing Email', 'wpszi-smart-zoho'),
		    'shipping_first_name'   => esc_html__('Shipping First Name', 'wpszi-smart-zoho'),
		    'shipping_last_name'    => esc_html__('Shipping Last Name', 'wpszi-smart-zoho'),
		    'shipping_company'      => esc_html__('Shipping Company', 'wpszi-smart-zoho'),
		    'shipping_address_1'    => esc_html__('Shipping Address 1', 'wpszi-smart-zoho'),
		    'shipping_address_2'    => esc_html__('Shipping Address 2', 'wpszi-smart-zoho'),
		    'shipping_city'         => esc_html__('Shipping City', 'wpszi-smart-zoho'),
		    'shipping_postcode'     => esc_html__('Shipping Postcode', 'wpszi-smart-zoho'),
		    'shipping_country'      => esc_html__('Shipping Country', 'wpszi-smart-zoho'),
		    'shipping_state'        => esc_html__('Shipping State', 'wpszi-smart-zoho'),
		    'user_url'              => esc_html__('Website', 'wpszi-smart-zoho'),
		    'description'           => esc_html__('Biographical Info', 'wpszi-smart-zoho'),
		    'display_name'          => esc_html__('Display name publicly as', 'wpszi-smart-zoho'),
		    'nickname'              => esc_html__('Nickname', 'wpszi-smart-zoho'),
		    'user_login'            => esc_html__('Username', 'wpszi-smart-zoho'),
		    'user_registered'       => esc_html__('Registration Date', 'wpszi-smart-zoho')
		);

		return $wc_fields;
    }


    public static  function get_order_fields(){
    	
    	global $wpdb;


        $wc_fields =  array(
                'get_id'                       => esc_html__('Order Number', 'wpszi-smart-zoho'),
                'get_order_key'                => esc_html__('Order Key', 'wpszi-smart-zoho'),
                'get_billing_first_name'       => esc_html__('Billing First Name', 'wpszi-smart-zoho'),
                'get_billing_last_name'        => esc_html__('Billing Last Name', 'wpszi-smart-zoho'),
                'get_billing_company'          => esc_html__('Billing Company', 'wpszi-smart-zoho'),
                'get_billing_address_1'        => esc_html__('Billing Address 1', 'wpszi-smart-zoho'),
                'get_billing_address_2'        => esc_html__('Billing Address 2', 'wpszi-smart-zoho'),
                'get_billing_city'             => esc_html__('Billing City', 'wpszi-smart-zoho'),
                'get_billing_state'            => esc_html__('Billing State', 'wpszi-smart-zoho'),
                'get_billing_postcode'         => esc_html__('Billing Postcode', 'wpszi-smart-zoho'),
                'get_billing_country'          => esc_html__('Billing Country', 'wpszi-smart-zoho'), 
                'get_billing_phone'            => esc_html__('Billing Phone', 'wpszi-smart-zoho'),
                'get_billing_email'            => esc_html__('Billing Email', 'wpszi-smart-zoho'),
                'get_shipping_first_name'      => esc_html__('Shipping First Name', 'wpszi-smart-zoho'),
                'get_shipping_last_name'       => esc_html__('Shipping Last Name', 'wpszi-smart-zoho'),
                'get_shipping_company'         => esc_html__('Shipping Company', 'wpszi-smart-zoho'),
                'get_shipping_address_1'       => esc_html__('Shipping Address 1', 'wpszi-smart-zoho'),
                'get_shipping_address_2'       => esc_html__('Shipping Address 2', 'wpszi-smart-zoho'),
                'get_shipping_city'            => esc_html__('Shipping City', 'wpszi-smart-zoho'),
                'get_shipping_state'           => esc_html__('Shipping State', 'wpszi-smart-zoho'),
                'get_shipping_postcode'        => esc_html__('Shipping Postcode', 'wpszi-smart-zoho'),
                'get_shipping_country'         => esc_html__('Shipping Country',  'wpszi-smart-zoho'),
                'get_formatted_order_total'     => esc_html__('Formatted Order Total', 'wpszi-smart-zoho'),
                'get_cart_tax'                  => esc_html__('Cart Tax', 'wpszi-smart-zoho'),
                'get_currency'                  => esc_html__('Currency', 'wpszi-smart-zoho'),
                'get_discount_tax'              => esc_html__('Discount Tax', 'wpszi-smart-zoho'),
                'get_discount_to_display'       => esc_html__('Discount to Display', 'wpszi-smart-zoho'),
                'get_discount_total'            => esc_html__('Discount Total', 'wpszi-smart-zoho'),
                'get_shipping_tax'              => esc_html__('Shipping Tax', 'wpszi-smart-zoho'),
                'get_shipping_total'            => esc_html__('Shipping Total', 'wpszi-smart-zoho'),
                'get_subtotal'                  => esc_html__('SubTotal', 'wpszi-smart-zoho'),
                'get_subtotal_to_display'       => esc_html__('SubTotal to Display', 'wpszi-smart-zoho'),
                'get_total'                     => esc_html__('Get Total', 'wpszi-smart-zoho'),
                'get_total_discount'            => esc_html__('Get Total Discount', 'wpszi-smart-zoho'),
                'get_total_tax'                 => esc_html__('Total Tax', 'wpszi-smart-zoho'),
                'get_total_refunded'            => esc_html__('Total Refunded', 'wpszi-smart-zoho'),
                'get_total_tax_refunded'        => esc_html__('Total Tax Refunded', 'wpszi-smart-zoho'),
                'get_total_shipping_refunded'   => esc_html__('Total Shipping Refunded', 'wpszi-smart-zoho'),
                'get_item_count_refunded'       => esc_html__('Item count refunded', 'wpszi-smart-zoho'),
                'get_total_qty_refunded'        => esc_html__('Total Quantity Refunded', 'wpszi-smart-zoho'),
                'get_remaining_refund_amount'   => esc_html__('Remaining Refund Amount', 'wpszi-smart-zoho'),
                'get_item_count'                => esc_html__('Item count', 'wpszi-smart-zoho'),
                'get_shipping_method'           => esc_html__('Shipping Method', 'wpszi-smart-zoho'),
                'get_shipping_to_display'       => esc_html__('Shipping to Display', 'wpszi-smart-zoho'),
                'get_date_created'              => esc_html__('Date Created', 'wpszi-smart-zoho'),
                'get_date_modified'             => esc_html__('Date Modified', 'wpszi-smart-zoho'),
                'get_date_completed'            => esc_html__('Date Completed', 'wpszi-smart-zoho'),
                'get_date_paid'                 => esc_html__('Date Paid', 'wpszi-smart-zoho'),
                'get_customer_id'               => esc_html__('Customer ID', 'wpszi-smart-zoho'),
                'get_user_id'                   => esc_html__('User ID', 'wpszi-smart-zoho'),
                'get_customer_ip_address'       => esc_html__('Customer IP Address', 'wpszi-smart-zoho'),
                'get_customer_user_agent'       => esc_html__('Customer User Agent', 'wpszi-smart-zoho'),
                'get_created_via'               => esc_html__('Order Created Via', 'wpszi-smart-zoho'),
                'get_customer_note'             => esc_html__('Customer Note', 'wpszi-smart-zoho'),
                'get_shipping_address_map_url'  => esc_html__('Shipping Address Map URL', 'wpszi-smart-zoho'),
                'get_formatted_billing_full_name'   => esc_html__('Formatted Billing Full Name', 'wpszi-smart-zoho'),
                'get_formatted_shipping_full_name'  => esc_html__('Formatted Shipping Full Name', 'wpszi-smart-zoho'),
                'get_formatted_billing_address'     => esc_html__('Formatted Billing Address', 'wpszi-smart-zoho'),
                'get_formatted_shipping_address'    => esc_html__('Formatted Shipping Address', 'wpszi-smart-zoho'),
                'get_payment_method'            => esc_html__('Payment Method', 'wpszi-smart-zoho'),
                'get_payment_method_title'      => esc_html__('Payment Method Title', 'wpszi-smart-zoho'),
                'get_transaction_id'            => esc_html__('Transaction ID', 'wpszi-smart-zoho'),
                'get_checkout_payment_url'      => esc_html__( 'Checkout Payment URL', 'wpszi-smart-zoho'),
                'get_checkout_order_received_url'   => esc_html__('Checkout Order Received URL', 'wpszi-smart-zoho'),
                'get_cancel_order_url'          => esc_html__('Cancel Order URL', 'wpszi-smart-zoho'),
                'get_cancel_order_url_raw'      => esc_html__('Cancel Order URL Raw', 'wpszi-smart-zoho'),
                'get_cancel_endpoint'           => esc_html__('Cancel Endpoint', 'wpszi-smart-zoho'),
                'get_view_order_url'            => esc_html__('View Order URL', 'wpszi-smart-zoho'),
                'get_edit_order_url'            => esc_html__('Edit Order URL', 'wpszi-smart-zoho'),
                'get_status'                    => esc_html__('Status', 'wpszi-smart-zoho'),
            );
        
        return $wc_fields;
    }


    public static function get_product_fields(){
    	global $wpdb;
		$wc_fields = array(
		    'get_id'              		=> esc_html__('Product Id', 'wpszi-smart-zoho'),
            'get_type'       			=> esc_html__('Product Type', 'wpszi-smart-zoho'),
            'get_name'       			=> esc_html__('Name', 'wpszi-smart-zoho'),
            'get_slug'          		=> esc_html__('Slug', 'wpszi-smart-zoho'),
            'get_date_created'      	=> esc_html__('Date Created', 'wpszi-smart-zoho'),
            'get_date_modified'     	=> esc_html__('Date Modified', 'wpszi-smart-zoho'),
            'get_status'            	=> esc_html__('Status', 'wpszi-smart-zoho'),
            'get_featured'          	=> esc_html__('Featured', 'wpszi-smart-zoho'),
            'get_catalog_visibility'	=> esc_html__('Catalog Visibility', 'wpszi-smart-zoho'),
            'get_description'       	=> esc_html__('Description', 'wpszi-smart-zoho'),
            'get_short_description' 	=> esc_html__('Short Description', 'wpszi-smart-zoho'),
            'get_sku'            		=> esc_html__('Sku', 'wpszi-smart-zoho'),
            'get_menu_order'      		=> esc_html__('Menu Order', 'wpszi-smart-zoho'),
            'get_virtual'       		=> esc_html__('Virtual', 'wpszi-smart-zoho'),
            'get_permalink'         	=> esc_html__('Product Permalink', 'wpszi-smart-zoho'),
            'get_price'       			=> esc_html__('Price', 'wpszi-smart-zoho'),
            'get_regular_price'       	=> esc_html__('Regular Price', 'wpszi-smart-zoho'),
            'get_sale_price'            => esc_html__('Sale Price', 'wpszi-smart-zoho'),
            'get_date_on_sale_from'     => esc_html__('Date on Sale From', 'wpszi-smart-zoho'),
            'get_date_on_sale_to'       => esc_html__('Date on Sale To', 'wpszi-smart-zoho'),
            'get_total_sales'         	=> esc_html__('Total Sales', 'wpszi-smart-zoho'),
            'get_tax_status'     		=> esc_html__('Tax Status', 'wpszi-smart-zoho'),
            'get_tax_class'           	=> esc_html__('Tax Class', 'wpszi-smart-zoho'),
            'get_manage_stock'          => esc_html__('Manage Stock', 'wpszi-smart-zoho'),
            'get_stock_quantity'        => esc_html__('Stock Quantity', 'wpszi-smart-zoho'),
            'get_stock_status'          => esc_html__('Stock Status', 'wpszi-smart-zoho'),
            'get_backorders'       		=> esc_html__('Backorders', 'wpszi-smart-zoho'),
            'get_sold_individually'     => esc_html__('Sold Individually', 'wpszi-smart-zoho'),
            'get_purchase_note'         => esc_html__('Purchase Note', 'wpszi-smart-zoho'),
            'get_shipping_class_id'     => esc_html__('Shipping Class ID', 'wpszi-smart-zoho'),
            'get_weight'               	=> esc_html__('Weight', 'wpszi-smart-zoho'),
            'get_length'              	=> esc_html__('Length', 'wpszi-smart-zoho'),
            'get_width'            		=> esc_html__('Width', 'wpszi-smart-zoho'),
            'get_height'            	=> esc_html__('Height', 'wpszi-smart-zoho'),
            'get_categories'            => esc_html__('Categories', 'wpszi-smart-zoho'),
            'get_category_ids'          => esc_html__('Categories IDs', 'wpszi-smart-zoho'),
            'get_tag_ids'            	=> esc_html__('Tag IDs', 'wpszi-smart-zoho'),
		);
        
		return $wc_fields;
    }

    public function store_required_field_mapping_data(){

        global $wpdb;
        $zoho_api_obj   = new WPSZI_Smart_Zoho_API();
        $wp_modules     = $this->get_wp_modules();
        $getListModules = $this->get_zoho_modules();

        if($getListModules['modules']){
            foreach ($getListModules['modules'] as $key => $singleModule) {
                if( $singleModule['deletable'] &&  $singleModule['creatable'] ){
        
                    $zoho_fields = $zoho_api_obj->getFieldsMetaData( $singleModule['api_name'] );
        
                    if($zoho_fields){
                        foreach ($zoho_fields['fields'] as $zoho_field_key => $zoho_field_data) {
                            if($zoho_field_data['field_read_only'] == NULL){
                                if( $zoho_field_data['system_mandatory'] == 1 ){
                                    if($wp_modules){
                                        foreach ($wp_modules as $wpModuleSlug => $wpModuleLabel) {
        
                                            switch ( $wpModuleSlug ) {
                                                case 'customers':
                                                    $wp_field = "first_name";
                                                    break;
                                                
                                                case 'orders':
                                                    $wp_field = "get_id";
                                                    break;

                                                case 'products':
                                                    $wp_field = "get_name";
                                                    break;

                                                default:
                                                    $wp_field = "";
                                                    break;
                                            }

                                            $status         = 'active';
                                            $description    = '';

                                            $record_exists = $wpdb->get_row( 
                                                $wpdb->prepare(
                                                    "
                                                    SELECT * FROM ".$wpdb->prefix ."smart_zoho_field_mapping  WHERE wp_module = %s AND wp_field = %s  AND zoho_module = %s AND zoho_field = %s
                                                    " ,
                                                    $wpModuleSlug, $wp_field, $singleModule['api_name'], $zoho_field_data['api_name']
                                                    )
                                                
                                            );

                                            if ( null !== $record_exists ) {
                                                
                                              $reccord_id       = $record_exists->id;
                                              $is_predefined    = $record_exists->is_predefined;
                                              

                                                $wpdb->update(
                                                    $wpdb->prefix . 'smart_zoho_field_mapping', 
                                                    array( 
                                                        'wp_module'     => sanitize_text_field($wpModuleSlug),
                                                        'wp_field'      => sanitize_text_field($wp_field),
                                                        'zoho_module'   => sanitize_text_field($singleModule['api_name']),
                                                        'zoho_field'    => sanitize_text_field($zoho_field_data['api_name']), 
                                                        'status'        => sanitize_text_field($status),
                                                        'description'   => sanitize_text_field($description), 
                                                        'is_predefined' => sanitize_text_field($is_predefined), 
                                                    ), 
                                                    array( 'id' => $reccord_id ), 
                                                    array( 
                                                        '%s', 
                                                        '%s', 
                                                        '%s', 
                                                        '%s', 
                                                        '%s', 
                                                        '%s', 
                                                        '%s'
                                                    ),
                                                    array( '%d' )
                                                );

                                            }else{
                                                $wpdb->insert( 
                                                    $wpdb->prefix . 'smart_zoho_field_mapping', 
                                                    array( 
                                                        'wp_module'     => sanitize_text_field($wpModuleSlug),
                                                        'wp_field'      => sanitize_text_field($wp_field),
                                                        'zoho_module'   => sanitize_text_field($singleModule['api_name']),
                                                        'zoho_field'    => sanitize_text_field($zoho_field_data['api_name']), 
                                                        'status'        => sanitize_text_field($status),
                                                        'description'   => sanitize_text_field($description), 
                                                        'is_predefined' => 'yes', 
                                                    ),
                                                    array( 
                                                        '%s', 
                                                        '%s', 
                                                        '%s', 
                                                        '%s', 
                                                        '%s', 
                                                        '%s', 
                                                        '%s'
                                                    ) 
                                                );
                                            }
                                            
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
?>