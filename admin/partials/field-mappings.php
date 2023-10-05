<div class="loader"></div>

<form method="post" action="<?php echo admin_url('/admin.php?page=wpshi-smart-hubspot-mappings') ?>" id="wpshi-smart-hubspot-mappings-form">

    <h2><?php echo esc_html__('Fields Mapping', 'wpshi-smart-hubspot'); ?></h2>

    <table class="form-table">
        <!-- WP Modules Row -->
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label><?php echo  esc_html__( 'WP Modules', 'wpshi-smart-hubspot' ); ?></label>
            </th>
            <td class="forminp forminp-text">
                <select name="wp_module">
                    <option><?php echo  esc_html__('Select Module', 'wpshi-smart-hubspot'); ?></option>
                    <?php 
                        if($wp_modules){
                            foreach ($wp_modules as $key => $singleModule) {
                                ?>            
                                <option value = "<?php echo $key; ?>"><?php echo esc_html__($singleModule, 'wpshi-smart-hubspot'); ?></option>
                                <?php            
                            }
                        }
                    ?>
                </select>
            </td>
        </tr>

        <!-- WP Fields Row -->
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label><?php echo  esc_html__( 'WP Fields', 'wpshi-smart-hubspot' ); ?></label>
            </th>
            <td class="forminp forminp-text">
                <select name="wp_field">
                    <option><?php echo  esc_html__('Please select WP Modules', 'wpshi-smart-hubspot'); ?></option>
                </select>
            </td>
        </tr>

        <!-- Hubspot Modules Row -->
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label><?php echo  esc_html__( 'Hubspot Modules', 'wpshi-smart-hubspot' ); ?></label>
            </th>
            <td class="forminp forminp-text">
                <select name="hubspot_module">
                    <option><?php echo  esc_html__('Select Hubspot Module', 'wpshi-smart-hubspot'); ?></option>
                    <?php
                        $hubspot_modules_options = "";

                        if($getListModules['modules']){
                            foreach ($getListModules['modules'] as $key => $singleModule) {
                                if( $singleModule['deletable'] &&  $singleModule['creatable'] ){
                    ?>
                                <option value = '<?php echo $singleModule['api_name']; ?>'> 
                                    <?php echo  esc_html__($singleModule['plural_label'], 'wpshi-smart-hubspot'); ?>
                                </option>
                    <?php                
                                }
                            }
                        }
                    ?>
                </select>
            </td>
        </tr>

        <!-- Hubspot Fields Row -->
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label><?php echo  esc_html__( 'Hubspot Fields', 'wpshi-smart-hubspot' ); ?></label>
            </th>
            <td class="forminp forminp-text">
                <select name="hubspot_field">
                    <option><?php echo  esc_html__('Please select Hubspot Modules', 'wpshi-smart-hubspot'); ?></option>
                </select>
            </td>
        </tr>

        <!-- Hubspot Modules Row -->
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label><?php echo  esc_html__( 'Status', 'wpshi-smart-hubspot' ); ?></label>
            </th>
            <td class="forminp forminp-text">
                <select name="status">
                    <option value="active"><?php echo esc_html__( 'Active', 'wpshi-smart-hubspot' ); ?></option>
                    <option value="inactive"><?php echo esc_html__( 'In Active', 'wpshi-smart-hubspot' ); ?></option>
                </select>
            </td>
        </tr>

        <!-- Hubspot Modules Row -->
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label><?php echo esc_html__( 'Description', 'wpshi-smart-hubspot' ); ?></label>
            </th>
            <td class="forminp forminp-text">
                <textarea name="description" rows="5" cols="46"></textarea>
            </td>
        </tr>

    </table>

    <p class="submit">
        <input type="submit" name="add_mapping" class="button-primary woocommerce-save-button" value="<?php echo  esc_html__( 'Add Mapping', 'wpshi-smart-hubspot' ); ?>">
    </p>
</form>