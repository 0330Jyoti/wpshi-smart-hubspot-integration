<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('WP_List_Table'))
  {
    require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
  }

function usersFinalData($users_data)
  {
    return $users_data['data'];
  }

class Customers_List extends WP_List_Table{
    /** Class constructor */
    public function __construct(){
        parent::__construct(['singular'     => esc_html__('Customer', 'wpszi-smart-zoho') , //singular name of the listed records
        'plural'                            => esc_html__('Customers', 'wpszi-smart-zoho') , //plural name of the listed records
        'ajax'                              => true
        //does this table support ajax?
        ]);
    }

    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_customer($per_page    = 10, $page_number = 1){
        if (isset($_REQUEST['orderby']) && $_REQUEST['orderby']){
            $orderby     = $_REQUEST['orderby'];
        }else{
            $orderby     = 'ID';
        }

        if (isset($_REQUEST['order']) && $_REQUEST['order']){
            $order       = $_REQUEST['order'];
        }else{
            $order       = 'ASC';
        }

        $users  = get_users(array(
                    'fields'    => 'all',
                    'role'      => 'customer',
                    'offset'    => ($page_number - 1) * $per_page,
                    'number'    => $per_page,
                    'orderby'   => $orderby,
                    'order'     => $order
            ));

        $result = json_decode(json_encode($users) , true);
        return array_map('usersFinalData', $result);
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count(){
        $users  = get_users(array(
                    'fields'   => 'all',
                    'role'     => 'customer'
                ));

        $total_users = count($users);
        return $total_users;
    }

    /** Text displayed when no customer data is available */
    public function no_items(){
        echo esc_html__('No customers avaliable.', 'wpszi-smart-zoho');
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name){
        switch ($column_name){
            case 'ID':
            case 'user_email':
            case 'user_registered':
            case 'display_name':
            case 'woocommerce_name':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_woocommerce_name($item) {
        $title = '<strong>' . $item['display_name'] . '</strong>';
        return $title;
    }

    function column_action($item){
        $action = '<form action="" method="post">                      
                        <input name="wp_module" value="customers" type="hidden" />
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
    function get_columns(){
        $columns = [
                'ID'                  => esc_html__('Id', 'wpszi-smart-zoho') , 
                'woocommerce_name'    => esc_html__('Name', 'wpszi-smart-zoho') , 
                'user_email'          => esc_html__('Email', 'wpszi-smart-zoho') , 
                'user_registered'     => esc_html__('Create Time', 'wpszi-smart-zoho') , 
                'action'              => esc_html__('Action', 'wpszi-smart-zoho') 
            ];
        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */

    public function get_sortable_columns(){
        $sortable_columns = array(
            'ID' => array(
                'ID',
                true
            ) ,
            'woocommerce_name' => array(
                'display_name',
                true
            ) ,
            'user_email' => array(
                'user_email',
                true
            ) ,
            'user_registered' => array(
                'user_registered',
                true
            )
        );
        return $sortable_columns;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items(){
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
    
        $this->_column_headers = array(
            $columns,
            $hidden,
            $sortable
        );

        /** Process bulk action */
        $this->process_bulk_action();
    
        $per_page     = $this->get_items_per_page('customers_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args(['total_items'             => $total_items, //WE have to calculate the total number of items
        'per_page'             => $per_page
        //WE have to determine how many items to show on a page
        ]);

        $this->items = self::get_customer($per_page, $current_page);
    }
}
?>