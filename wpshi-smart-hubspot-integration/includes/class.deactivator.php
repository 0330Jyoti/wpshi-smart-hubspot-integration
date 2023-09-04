<?php

class WPSZI_Smart_Zoho_Deactivator
{
    public function deactivate() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$smart_zoho_report_table_name 			= $wpdb->prefix . 'smart_zoho_report';
		$smart_zoho_field_mapping_table_name 	= $wpdb->prefix . 'smart_zoho_field_mapping';

		delete_option('wpszi_smart_zoho_settings');
		delete_option('wpszi_smart_zoho');
		delete_option('wpszi_smart_zoho_modules_fields');
	}
}
?>