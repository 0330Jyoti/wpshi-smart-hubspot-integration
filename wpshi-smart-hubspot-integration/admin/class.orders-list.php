<?php 
if( !defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'WP_List_Table' ) ) {
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}	

class Order_List extends WP_List_Table {
	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => esc_html__( 'Order', 'wpszi-smart-zoho' ), //singular name of the listed records
			'plural'   => esc_html__( 'Orders', 'wpszi-smart-zoho' ), //plural name of the listed records
			'ajax'     => true //does this table support ajax?
		] );
	}

	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_order( $per_page = 10, $page_number = 1) {
		global $wpdb;
		$orderTableName = $wpdb->prefix.'posts';
		$orderSql = "SELECT $orderTableName.ID, $orderTableName.post_date FROM $orderTableName WHERE $orderTableName.post_type = 'shop_order' AND post_status != 'draft'";
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$orderSql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$orderSql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}
		$orderSql .= " LIMIT $per_page";
		$orderSql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		
		$orderData = $wpdb->get_results( $orderSql, 'ARRAY_A' );
		$orderResult = json_decode(json_encode($orderData), true);			
		return $orderResult;
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM ".$wpdb->prefix."posts WHERE post_type = %s AND post_status != %s", 'shop_order', 'draft') );
	}
	
	/** Text displayed when no customer data is available */
	public function no_items() {
		echo esc_html__( 'No orders avaliable.', 'wpszi-smart-zoho' );
	}

	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'ID':
			case 'post_date':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
		
	function column_action( $item ) {							
		$action = '<form action="" method="post">                      
						<input name="wp_module" value="orders" type="hidden" />
						<input name="id" value="'.esc_attr($item['ID']).'" type="hidden" />
						<button class="button" name="smart_synch" value="zoho" type="submit">'.esc_html__('Sync', 'wpszi-smart-zoho').'</button>
					</form>';
		return $action;
	}

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'ID'    	=> esc_html__( 'Order Id', 'wpszi-smart-zoho' ),
			'post_date' => esc_html__( 'Create Time', 'wpszi-smart-zoho' ),
			'action'    => esc_html__( 'Action', 'wpszi-smart-zoho' )
		];
		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	
	public function get_sortable_columns() {
		$sortable_columns = array(
			'ID' => array( 'ID', true ),
			'post_date' => array( 'post_date', true )
		);
		return $sortable_columns;
	}
	
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
										
		/** Process bulk action */
		$this->process_bulk_action();
		$per_page     = $this->get_items_per_page( 'customers_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );
		
		$this->items = self::get_order( $per_page, $current_page );
	}
}
?>