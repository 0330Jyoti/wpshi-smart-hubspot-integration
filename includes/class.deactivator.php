<?php

class WPSHI_Smart_Hubspot_Deactivator
{
    public function deactivate() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$smart_hubspot_report_table_name 			= $wpdb->prefix . 'smart_hubspot_report';
		$smart_hubspot_field_mapping_table_name 	= $wpdb->prefix . 'smart_hubspot_field_mapping';

		delete_option('wpshi_smart_hubspot_settings');
		delete_option('wpshi_smart_hubspot');
		delete_option('wpshi_smart_hubspot_modules_fields');
	}
}
?>