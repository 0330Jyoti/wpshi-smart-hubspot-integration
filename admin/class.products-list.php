<?php 
if( !defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'WP_List_Table' ) ) {
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Product_Listss extends WP_List_Table {
	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => esc_html__( 'Order', 'wpshi-smart-hubspot' ), //singular name of the listed records
			'plural'   => esc_html__( 'Orders', 'wpshi-smart-hubspot' ), //plural name of the listed records
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
	public static function get_product( $per_page = 10, $page_number = 1) {
		global $wpdb;
		$productTableName = $wpdb->prefix.'posts';
		$productSkuTableName = $wpdb->prefix.'postmeta';
		$productSql = "SELECT P.ID, P.post_title, P.post_date,PM.meta_value FROM $productTableName AS P LEFT JOIN $productSkuTableName AS PM ON P.ID = PM.post_id AND meta_key = '_sku' WHERE P.post_type = 'product' AND P.post_status = 'publish'";
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$productSql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$productSql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}
		$productSql .= " LIMIT $per_page";
		$productSql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		
		$productData = $wpdb->get_results( $productSql, 'ARRAY_A' );
		$productResult = json_decode(json_encode($productData), true);	
		return $productResult;
		
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		$productsQuery = wc_get_products( array(
			'limit' => -1
		) );
		$totalProducts = count($productsQuery);
		return $totalProducts;
	}
	
	/** Text displayed when no customer data is available */
	public function no_items() {
		echo esc_html__( 'No products avaliable.', 'wpshi-smart-hubspot' );
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
			case 'post_title':
			case 'meta_value':
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
		$product = wc_get_product( $item['ID'] );
		$action = '<form action="" method="post">                      
						<input name="wp_module" value="products" type="hidden" />
						<input name="id" value="'.esc_attr($item['ID']).'" type="hidden" />
						<input name="product_sku" value="'.esc_attr($product->get_sku($item['ID'])).'" type="hidden" />
						<button class="button" name="smart_synch" value="hubspot" type="submit">'.esc_html__('Sync', 'wpshi-smart-hubspot').'</button>
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
			'ID'    => esc_html__( 'Product Id', 'wpshi-smart-hubspot' ),
			'post_title'    => esc_html__( 'Product Name', 'wpshi-smart-hubspot' ),
			'meta_value'    => esc_html__( 'Sku', 'wpshi-smart-hubspot' ),
			'post_date'    => esc_html__( 'Create Time', 'wpshi-smart-hubspot' ),
			'action'    => esc_html__( 'Action', 'wpshi-smart-hubspot' )
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
			'post_title' => array( 'post_title', true ),
			'meta_value' => array( 'meta_value', true ),
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
		
		$this->items = self::get_product( $per_page, $current_page );
	}
}
?>