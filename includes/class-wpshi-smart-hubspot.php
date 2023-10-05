<?php
class WPSHI_Smart_Hubspot {

	protected $plugin_name;

	protected $version;

	public function __construct() {
		$this->version = '1.0.0';
		$this->plugin_name = 'wpshi-smart-hubspot';
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
                'customers' => esc_html__('Customers','wpshi-smart-hubspot'),
                'orders'    => esc_html__('Orders','wpshi-smart-hubspot'),
                'products'  => esc_html__('Products','wpshi-smart-hubspot'),
            );
	}

	public function get_hubspot_modules(){

		$hubspot_api_obj   = new WPSHI_Smart_Hubspot_API();
       
        /*get list modules*/
        $getListModules = $hubspot_api_obj->getListModules();
        
        return $getListModules;
	}

	public static function get_customer_fields(){
    	
    	global $wpdb;
		$wc_fields = array(
		    'first_name'            => esc_html__('First Name', 'wpshi-smart-hubspot'),
		    'last_name'             => esc_html__('Last Name', 'wpshi-smart-hubspot'),
		    'user_email'            => esc_html__('Email', 'wpshi-smart-hubspot'),
		    'billing_first_name'    => esc_html__('Billing First Name', 'wpshi-smart-hubspot'),
		    'billing_last_name'     => esc_html__('Billing Last Name', 'wpshi-smart-hubspot'),
		    'billing_company'       => esc_html__('Billing Company', 'wpshi-smart-hubspot'),
		    'billing_address_1'     => esc_html__('Billing Address 1', 'wpshi-smart-hubspot'),
		    'billing_address_2'     => esc_html__('Billing Address 2', 'wpshi-smart-hubspot'),
		    'billing_city'          => esc_html__('Billing City', 'wpshi-smart-hubspot'),
		    'billing_state'         => esc_html__('Billing State', 'wpshi-smart-hubspot'),
		    'billing_postcode'      => esc_html__('Billing Postcode', 'wpshi-smart-hubspot'),
		    'billing_country'       => esc_html__('Billing Country', 'wpshi-smart-hubspot'),
		    'billing_phone'         => esc_html__('Billing Phone', 'wpshi-smart-hubspot'),
		    'billing_email'         => esc_html__('Billing Email', 'wpshi-smart-hubspot'),
		    'shipping_first_name'   => esc_html__('Shipping First Name', 'wpshi-smart-hubspot'),
		    'shipping_last_name'    => esc_html__('Shipping Last Name', 'wpshi-smart-hubspot'),
		    'shipping_company'      => esc_html__('Shipping Company', 'wpshi-smart-hubspot'),
		    'shipping_address_1'    => esc_html__('Shipping Address 1', 'wpshi-smart-hubspot'),
		    'shipping_address_2'    => esc_html__('Shipping Address 2', 'wpshi-smart-hubspot'),
		    'shipping_city'         => esc_html__('Shipping City', 'wpshi-smart-hubspot'),
		    'shipping_postcode'     => esc_html__('Shipping Postcode', 'wpshi-smart-hubspot'),
		    'shipping_country'      => esc_html__('Shipping Country', 'wpshi-smart-hubspot'),
		    'shipping_state'        => esc_html__('Shipping State', 'wpshi-smart-hubspot'),
		    'user_url'              => esc_html__('Website', 'wpshi-smart-hubspot'),
		    'description'           => esc_html__('Biographical Info', 'wpshi-smart-hubspot'),
		    'display_name'          => esc_html__('Display name publicly as', 'wpshi-smart-hubspot'),
		    'nickname'              => esc_html__('Nickname', 'wpshi-smart-hubspot'),
		    'user_login'            => esc_html__('Username', 'wpshi-smart-hubspot'),
		    'user_registered'       => esc_html__('Registration Date', 'wpshi-smart-hubspot')
		);

		return $wc_fields;
    }


    public static  function get_order_fields(){
    	
    	global $wpdb;


        $wc_fields =  array(
                'get_id'                       => esc_html__('Order Number', 'wpshi-smart-hubspot'),
                'get_order_key'                => esc_html__('Order Key', 'wpshi-smart-hubspot'),
                'get_billing_first_name'       => esc_html__('Billing First Name', 'wpshi-smart-hubspot'),
                'get_billing_last_name'        => esc_html__('Billing Last Name', 'wpshi-smart-hubspot'),
                'get_billing_company'          => esc_html__('Billing Company', 'wpshi-smart-hubspot'),
                'get_billing_address_1'        => esc_html__('Billing Address 1', 'wpshi-smart-hubspot'),
                'get_billing_address_2'        => esc_html__('Billing Address 2', 'wpshi-smart-hubspot'),
                'get_billing_city'             => esc_html__('Billing City', 'wpshi-smart-hubspot'),
                'get_billing_state'            => esc_html__('Billing State', 'wpshi-smart-hubspot'),
                'get_billing_postcode'         => esc_html__('Billing Postcode', 'wpshi-smart-hubspot'),
                'get_billing_country'          => esc_html__('Billing Country', 'wpshi-smart-hubspot'), 
                'get_billing_phone'            => esc_html__('Billing Phone', 'wpshi-smart-hubspot'),
                'get_billing_email'            => esc_html__('Billing Email', 'wpshi-smart-hubspot'),
                'get_shipping_first_name'      => esc_html__('Shipping First Name', 'wpshi-smart-hubspot'),
                'get_shipping_last_name'       => esc_html__('Shipping Last Name', 'wpshi-smart-hubspot'),
                'get_shipping_company'         => esc_html__('Shipping Company', 'wpshi-smart-hubspot'),
                'get_shipping_address_1'       => esc_html__('Shipping Address 1', 'wpshi-smart-hubspot'),
                'get_shipping_address_2'       => esc_html__('Shipping Address 2', 'wpshi-smart-hubspot'),
                'get_shipping_city'            => esc_html__('Shipping City', 'wpshi-smart-hubspot'),
                'get_shipping_state'           => esc_html__('Shipping State', 'wpshi-smart-hubspot'),
                'get_shipping_postcode'        => esc_html__('Shipping Postcode', 'wpshi-smart-hubspot'),
                'get_shipping_country'         => esc_html__('Shipping Country',  'wpshi-smart-hubspot'),
                'get_formatted_order_total'     => esc_html__('Formatted Order Total', 'wpshi-smart-hubspot'),
                'get_cart_tax'                  => esc_html__('Cart Tax', 'wpshi-smart-hubspot'),
                'get_currency'                  => esc_html__('Currency', 'wpshi-smart-hubspot'),
                'get_discount_tax'              => esc_html__('Discount Tax', 'wpshi-smart-hubspot'),
                'get_discount_to_display'       => esc_html__('Discount to Display', 'wpshi-smart-hubspot'),
                'get_discount_total'            => esc_html__('Discount Total', 'wpshi-smart-hubspot'),
                'get_shipping_tax'              => esc_html__('Shipping Tax', 'wpshi-smart-hubspot'),
                'get_shipping_total'            => esc_html__('Shipping Total', 'wpshi-smart-hubspot'),
                'get_subtotal'                  => esc_html__('SubTotal', 'wpshi-smart-hubspot'),
                'get_subtotal_to_display'       => esc_html__('SubTotal to Display', 'wpshi-smart-hubspot'),
                'get_total'                     => esc_html__('Get Total', 'wpshi-smart-hubspot'),
                'get_total_discount'            => esc_html__('Get Total Discount', 'wpshi-smart-hubspot'),
                'get_total_tax'                 => esc_html__('Total Tax', 'wpshi-smart-hubspot'),
                'get_total_refunded'            => esc_html__('Total Refunded', 'wpshi-smart-hubspot'),
                'get_total_tax_refunded'        => esc_html__('Total Tax Refunded', 'wpshi-smart-hubspot'),
                'get_total_shipping_refunded'   => esc_html__('Total Shipping Refunded', 'wpshi-smart-hubspot'),
                'get_item_count_refunded'       => esc_html__('Item count refunded', 'wpshi-smart-hubspot'),
                'get_total_qty_refunded'        => esc_html__('Total Quantity Refunded', 'wpshi-smart-hubspot'),
                'get_remaining_refund_amount'   => esc_html__('Remaining Refund Amount', 'wpshi-smart-hubspot'),
                'get_item_count'                => esc_html__('Item count', 'wpshi-smart-hubspot'),
                'get_shipping_method'           => esc_html__('Shipping Method', 'wpshi-smart-hubspot'),
                'get_shipping_to_display'       => esc_html__('Shipping to Display', 'wpshi-smart-hubspot'),
                'get_date_created'              => esc_html__('Date Created', 'wpshi-smart-hubspot'),
                'get_date_modified'             => esc_html__('Date Modified', 'wpshi-smart-hubspot'),
                'get_date_completed'            => esc_html__('Date Completed', 'wpshi-smart-hubspot'),
                'get_date_paid'                 => esc_html__('Date Paid', 'wpshi-smart-hubspot'),
                'get_customer_id'               => esc_html__('Customer ID', 'wpshi-smart-hubspot'),
                'get_user_id'                   => esc_html__('User ID', 'wpshi-smart-hubspot'),
                'get_customer_ip_address'       => esc_html__('Customer IP Address', 'wpshi-smart-hubspot'),
                'get_customer_user_agent'       => esc_html__('Customer User Agent', 'wpshi-smart-hubspot'),
                'get_created_via'               => esc_html__('Order Created Via', 'wpshi-smart-hubspot'),
                'get_customer_note'             => esc_html__('Customer Note', 'wpshi-smart-hubspot'),
                'get_shipping_address_map_url'  => esc_html__('Shipping Address Map URL', 'wpshi-smart-hubspot'),
                'get_formatted_billing_full_name'   => esc_html__('Formatted Billing Full Name', 'wpshi-smart-hubspot'),
                'get_formatted_shipping_full_name'  => esc_html__('Formatted Shipping Full Name', 'wpshi-smart-hubspot'),
                'get_formatted_billing_address'     => esc_html__('Formatted Billing Address', 'wpshi-smart-hubspot'),
                'get_formatted_shipping_address'    => esc_html__('Formatted Shipping Address', 'wpshi-smart-hubspot'),
                'get_payment_method'            => esc_html__('Payment Method', 'wpshi-smart-hubspot'),
                'get_payment_method_title'      => esc_html__('Payment Method Title', 'wpshi-smart-hubspot'),
                'get_transaction_id'            => esc_html__('Transaction ID', 'wpshi-smart-hubspot'),
                'get_checkout_payment_url'      => esc_html__( 'Checkout Payment URL', 'wpshi-smart-hubspot'),
                'get_checkout_order_received_url'   => esc_html__('Checkout Order Received URL', 'wpshi-smart-hubspot'),
                'get_cancel_order_url'          => esc_html__('Cancel Order URL', 'wpshi-smart-hubspot'),
                'get_cancel_order_url_raw'      => esc_html__('Cancel Order URL Raw', 'wpshi-smart-hubspot'),
                'get_cancel_endpoint'           => esc_html__('Cancel Endpoint', 'wpshi-smart-hubspot'),
                'get_view_order_url'            => esc_html__('View Order URL', 'wpshi-smart-hubspot'),
                'get_edit_order_url'            => esc_html__('Edit Order URL', 'wpshi-smart-hubspot'),
                'get_status'                    => esc_html__('Status', 'wpshi-smart-hubspot'),
            );
        
        return $wc_fields;
    }


    public static function get_product_fields(){
    	global $wpdb;
		$wc_fields = array(
		    'get_id'              		=> esc_html__('Product Id', 'wpshi-smart-hubspot'),
            'get_type'       			=> esc_html__('Product Type', 'wpshi-smart-hubspot'),
            'get_name'       			=> esc_html__('Name', 'wpshi-smart-hubspot'),
            'get_slug'          		=> esc_html__('Slug', 'wpshi-smart-hubspot'),
            'get_date_created'      	=> esc_html__('Date Created', 'wpshi-smart-hubspot'),
            'get_date_modified'     	=> esc_html__('Date Modified', 'wpshi-smart-hubspot'),
            'get_status'            	=> esc_html__('Status', 'wpshi-smart-hubspot'),
            'get_featured'          	=> esc_html__('Featured', 'wpshi-smart-hubspot'),
            'get_catalog_visibility'	=> esc_html__('Catalog Visibility', 'wpshi-smart-hubspot'),
            'get_description'       	=> esc_html__('Description', 'wpshi-smart-hubspot'),
            'get_short_description' 	=> esc_html__('Short Description', 'wpshi-smart-hubspot'),
            'get_sku'            		=> esc_html__('Sku', 'wpshi-smart-hubspot'),
            'get_menu_order'      		=> esc_html__('Menu Order', 'wpshi-smart-hubspot'),
            'get_virtual'       		=> esc_html__('Virtual', 'wpshi-smart-hubspot'),
            'get_permalink'         	=> esc_html__('Product Permalink', 'wpshi-smart-hubspot'),
            'get_price'       			=> esc_html__('Price', 'wpshi-smart-hubspot'),
            'get_regular_price'       	=> esc_html__('Regular Price', 'wpshi-smart-hubspot'),
            'get_sale_price'            => esc_html__('Sale Price', 'wpshi-smart-hubspot'),
            'get_date_on_sale_from'     => esc_html__('Date on Sale From', 'wpshi-smart-hubspot'),
            'get_date_on_sale_to'       => esc_html__('Date on Sale To', 'wpshi-smart-hubspot'),
            'get_total_sales'         	=> esc_html__('Total Sales', 'wpshi-smart-hubspot'),
            'get_tax_status'     		=> esc_html__('Tax Status', 'wpshi-smart-hubspot'),
            'get_tax_class'           	=> esc_html__('Tax Class', 'wpshi-smart-hubspot'),
            'get_manage_stock'          => esc_html__('Manage Stock', 'wpshi-smart-hubspot'),
            'get_stock_quantity'        => esc_html__('Stock Quantity', 'wpshi-smart-hubspot'),
            'get_stock_status'          => esc_html__('Stock Status', 'wpshi-smart-hubspot'),
            'get_backorders'       		=> esc_html__('Backorders', 'wpshi-smart-hubspot'),
            'get_sold_individually'     => esc_html__('Sold Individually', 'wpshi-smart-hubspot'),
            'get_purchase_note'         => esc_html__('Purchase Note', 'wpshi-smart-hubspot'),
            'get_shipping_class_id'     => esc_html__('Shipping Class ID', 'wpshi-smart-hubspot'),
            'get_weight'               	=> esc_html__('Weight', 'wpshi-smart-hubspot'),
            'get_length'              	=> esc_html__('Length', 'wpshi-smart-hubspot'),
            'get_width'            		=> esc_html__('Width', 'wpshi-smart-hubspot'),
            'get_height'            	=> esc_html__('Height', 'wpshi-smart-hubspot'),
            'get_categories'            => esc_html__('Categories', 'wpshi-smart-hubspot'),
            'get_category_ids'          => esc_html__('Categories IDs', 'wpshi-smart-hubspot'),
            'get_tag_ids'            	=> esc_html__('Tag IDs', 'wpshi-smart-hubspot'),
		);
        
		return $wc_fields;
    }

    public function store_required_field_mapping_data(){

        global $wpdb;
        $hubspot_api_obj   = new WPSHI_Smart_Hubspot_API();
        $wp_modules     = $this->get_wp_modules();
        $getListModules = $this->get_hubspot_modules();

        if($getListModules['modules']){
            foreach ($getListModules['modules'] as $key => $singleModule) {
                if( $singleModule['deletable'] &&  $singleModule['creatable'] ){
        
                    $hubspot_fields = $hubspot_api_obj->getFieldsMetaData( $singleModule['api_name'] );
        
                    if($hubspot_fields){
                        foreach ($hubspot_fields['fields'] as $hubspot_field_key => $hubspot_field_data) {
                            if($hubspot_field_data['field_read_only'] == NULL){
                                if( $hubspot_field_data['system_mandatory'] == 1 ){
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
                                                    SELECT * FROM ".$wpdb->prefix ."smart_hubspot_field_mapping  WHERE wp_module = %s AND wp_field = %s  AND hubspot_module = %s AND hubspot_field = %s
                                                    " ,
                                                    $wpModuleSlug, $wp_field, $singleModule['api_name'], $hubspot_field_data['api_name']
                                                    )
                                                
                                            );

                                            if ( null !== $record_exists ) {
                                                
                                              $reccord_id       = $record_exists->id;
                                              $is_predefined    = $record_exists->is_predefined;
                                              

                                                $wpdb->update(
                                                    $wpdb->prefix . 'smart_hubspot_field_mapping', 
                                                    array( 
                                                        'wp_module'     => sanitize_text_field($wpModuleSlug),
                                                        'wp_field'      => sanitize_text_field($wp_field),
                                                        'hubspot_module'   => sanitize_text_field($singleModule['api_name']),
                                                        'hubspot_field'    => sanitize_text_field($hubspot_field_data['api_name']), 
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
                                                    $wpdb->prefix . 'smart_hubspot_field_mapping', 
                                                    array( 
                                                        'wp_module'     => sanitize_text_field($wpModuleSlug),
                                                        'wp_field'      => sanitize_text_field($wp_field),
                                                        'hubspot_module'   => sanitize_text_field($singleModule['api_name']),
                                                        'hubspot_field'    => sanitize_text_field($hubspot_field_data['api_name']), 
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