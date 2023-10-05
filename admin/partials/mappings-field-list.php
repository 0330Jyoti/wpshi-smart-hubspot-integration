<?php
    global $wpdb;
    $fieldlists = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}smart_hubspot_field_mapping");
?>
    <h2><?php echo esc_html__('Fields Mapping List'); ?></h2>

    <table id="mapping-list-table" class="wp-list-table widefat fixed striped table-view-list display">
        <thead>
            <th><?php echo esc_html__('Id', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('Hubspot Module', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('Hubspot Field', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('WP Module', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('WP Field', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('Status', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('Description', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('Action', 'wpshi-smart-hubspot'); ?></th>
        </thead>

        <tfoot>
            <th><?php echo esc_html__('Id', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('Hubspot Module', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('Hubspot Field', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('WP Module', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('WP Field', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('Status', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('Description', 'wpshi-smart-hubspot'); ?></th>
            <th><?php echo esc_html__('Action', 'wpshi-smart-hubspot'); ?></th>
        </tfoot>
        <tbody>
            <!-- WP Modules Row -->
            <?php
                if ( $fieldlists ) {
                    foreach ( $fieldlists as $singlelist ) {
                        ?>
                        <tr>
                            <td><?php echo esc_html__($singlelist->id, 'wpshi-smart-hubspot'); ?></td>
                            <td><?php echo esc_html__($singlelist->hubspot_module, 'wpshi-smart-hubspot'); ?></td>
                            <td><?php echo esc_html__($singlelist->hubspot_field, 'wpshi-smart-hubspot'); ?></td>
                            <td><?php echo esc_html__($singlelist->wp_module, 'wpshi-smart-hubspot'); ?></td>
                            <td><?php echo esc_html__($singlelist->wp_field, 'wpshi-smart-hubspot'); ?></td>
                            <td><?php echo ucfirst( esc_html__($singlelist->status, 'wpshi-smart-hubspot') ); ?></td>
                            <td><?php echo esc_html__($singlelist->description, 'wpshi-smart-hubspot'); ?></td>
                            <td>
                                <?php if($singlelist->is_predefined != 'yes' ){ ?>
                                    <a href="<?php echo admin_url('admin.php?page=wpshi-smart-hubspot-mappings&action=trash&id='.$singlelist->id); ?>">
                                        <button type="submit"><?php echo esc_html__('Delete', 'wpshi-smart-hubspot'); ?></button>
                                    </a>
                                <?php }?>
                            </td>
                        </tr>
                        <?php
                    }   
                } else {
                    ?>
                    <tr>
                        <td colspan="7">
                            <?php echo esc_html__('No Record Found', 'wpshi-smart-hubspot'); ?>
                        </td>
                    </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>