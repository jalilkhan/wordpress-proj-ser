<?php
/**** CLAIM METABOXES SETTINGS ****/

add_action('admin_init', 'bt_claim_settings');
if (!function_exists('bt_claim_settings')) {
     function bt_claim_settings() {
         
        $claimers = array();
        $users = get_users();
        foreach($users as $user_id){
            $claimers[$user_id->ID] = $user_id->user_nicename;
        }

        $claimStatus = array(
                'pending'	=> __('Pending', 'bt_plugin'),
                'approved'	=> __('Approved', 'bt_plugin'),
                'rejected'	=> __('Rejected', 'bt_plugin'),
        );
        
        $claim_options = Array(
                Array(
                        'name' => __('Claim Listing', 'bt_plugin'),
                        'id' => 'claimed_listing',
                        'type' => 'listing',
                        'options' => bt_output_post_type_list('listing'),
                        'desc' => ''),
                Array(
                        'name' => __('Claimed by', 'bt_plugin'),
                        'id' => 'claimer',
                        'type' => 'select',
                        'options' => $claimers,
                        'desc' => ''),
                Array(
                        'name' => __('Listing Author', 'bt_plugin'),
                        'id' => 'owner',
                        'type' => 'static',
                        'desc' => ''),
                Array(
                        'name' => __('Claim Status', 'bt_plugin'),
                        'id' => 'claim_status',
                        'type' => 'select',
                        'options' => $claimStatus,
                        'desc' => ''),
                Array(
                        'name' => __('Claim Description', 'bt_plugin'),
                        'id' => 'details',
                        'type' => 'textarea',
                        'child_of' => '',
                        'match' => '',
                        'desc' => '')
        );
         
         add_meta_box('claim_meta_settings', __( 'Claim Details', 'bt_plugin' ), 'bt_claim_metabox_render', 'claim', 'normal', 'high', $claim_options);
     }  
}

/**** /CLAIM METABOXES SETTINGS ****/

/**** CLAIM METABOXES CONTROLS SETTINGS ****/

if(!function_exists('bt_claim_metabox_render')){
    function bt_claim_metabox_render($post, $metabox) {
        global $post;    
        $options = get_post_meta($post->ID);
        
        ?>
        <input type="hidden" name="bt_claim_meta_box_nonce" value="<?php echo wp_create_nonce(basename(__FILE__));?>" />
        <table class="form-table bt-metaboxes">
            <tbody>					
                    <?php
                    foreach ($metabox['args'] as $settings) {
                        $settings['value'] = isset($options[$settings['id']]) ? $options[$settings['id']] : (isset($settings['std']) ? $settings['std'] : '');
                        call_user_func('bt_settings_'.$settings['type'], $settings);	
                    }
                    ?>
            </tbody>
        </table>
        <?php 
    }
}

/*** METABOX STATIC CONTROL ***/
if (!function_exists('bt_settings_static')) {
    function bt_settings_static($settings){ ?>
        <tr id="lp_field_<?php echo wp_kses_post($settings['id']); ?>">
            <th>
                <label for="<?php echo wp_kses_post($settings['id']); ?>">
                    <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                    <span><?php echo wp_kses_post($settings['desc']); ?></span>
                </label>
            </th>
            <td>
                <?php 
                    if( is_array($settings['value']) && count($settings['value']) > 0 ){
                            $value = $settings['value'][0];
                    }
                    else{
                            $value = $settings['value'];
                    }
                ?>
                <input type="text" name="<?php echo wp_kses_post($settings['id']); ?>" id="<?php echo wp_kses_post($settings['id']); ?>" value="<?php echo wp_kses_post($value); ?>" />
            </td>
        </tr><?php
    }
}

/*** METABOX TEXTAREA CONTROL ***/
if (!function_exists('bt_settings_textarea')) {
    function bt_settings_textarea($settings){ ?>
        <tr id="<?php echo wp_kses_post($settings['id']); ?>">
            <th>
                <label for="<?php echo wp_kses_post($settings['id']); ?>">
                    <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                    <span><?php echo wp_kses_post($settings['desc']); ?></span>
                </label>
            </th>
            <td>
                <?php 
                    if( is_array($settings['value']) && count($settings['value']) > 0 ){
                            $value = $settings['value'][0];
                    }
                    else{
                            $value = $settings['value'];
                    }
                ?>
                <textarea rows="5" cols="100" name="<?php echo esc_attr($settings['id']); ?>"><?php echo $value; ?></textarea>
            </td>
        </tr><?php
    }
}

/*** METABOX TEXT CONTROL ***/
if (!function_exists('bt_settings_text')) {
    function bt_settings_text($settings){ ?>
        <tr id="lp_field_<?php echo wp_kses_post($settings['id']); ?>">
            <th>
                <label for="<?php echo wp_kses_post($settings['id']); ?>">
                    <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                    <span><?php echo wp_kses_post($settings['desc']); ?></span>
                </label>
            </th>
            <td>
                <input type="text" name="<?php echo wp_kses_post($settings['id']); ?>" id="<?php echo wp_kses_post($settings['id']); ?>" value="<?php echo wp_kses_post($settings['value']); ?>" />
            </td>
        </tr><?php
    }
}

/*** METABOX SELECT CONTROL ***/
if (!function_exists('bt_settings_select')) {
    function bt_settings_select($settings){ 
        if (!empty($settings['options'])){
            $current_value = ( is_array($settings['value']) && count($settings['value']) > 0 ) ? $settings['value'][0] : '' ;
            ?>        
                <tr id="<?php echo wp_kses_post($settings['id']); ?>">
                    <th>
                        <label for="<?php echo wp_kses_post($settings['id']); ?>">
                            <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                            <span><?php echo wp_kses_post($settings['desc']); ?></span>
                        </label>
                    </th>
                    <td>
                        <div class="type_select add_item_medium">
                            <select class="medium" name="<?php echo wp_kses_post($settings['id']); ?>" data-value="<?php echo $current_value;?>">
                                <?php
                                    foreach($settings['options'] as $key=>$value) {                                
                                        if ($key == $current_value) {
                                                $selected =  "selected";
                                        }else{
                                                $selected = '';
                                        }  
                                        echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
            <?php
        }
    }
}


/*** METABOX LISTING CONTROL ***/
if (!function_exists('bt_settings_listing')) {
    function bt_settings_listing($settings){
        $current_value = ( !empty($settings['value']) && is_array($settings['value']) && count($settings['value']) > 0 ) ? $settings['value'][0] : '' ;
	?>
        <tr id="<?php echo wp_kses_post($settings['id']); ?>">
            <th>
                <label for="<?php echo wp_kses_post($settings['id']); ?>">
                    <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                    <span><?php echo wp_kses_post($settings['desc']); ?></span>
                </label>
            </th>
            <td>
                <div class="type_listing add_item_medium">
                    <select class="medium" name="<?php echo wp_kses_post($settings['id']); ?>" data-value="<?php echo $current_value;?>">
                            <?php
                                if(!empty($settings['options'])){
                                        foreach($settings['options'] as $key=>$value) { 
                                            if ($key == $current_value) {
                                                    $selected =  "selected";
                                            }else{
                                                    $selected = '';
                                            }  
                                            echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                                        }
                                }
                                else{
                                        if(!empty($settings['value'])){
                                            $selected =  "selected";
                                            echo '<option '.$selected.' value="'.$current_value.'">'.get_the_title($current_value).'</option>';
                                        }
                                }
                            ?>
                    </select>  
                </div>
            </td>
        </tr><?php
    }
}
/**** /CLAIM METABOXES CONTROLS SETTINGS ****/

/**
 * Returns array of ID and post titles from specific custom post type, returns null if found no results.
 *
 * @param string $custom_post_type Custom post type
 */
if (!function_exists('bt_output_post_type_list')) {
    function bt_output_post_type_list($custom_post_type) {
        global $wpdb; 
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );
        if ( ! $results )
            return;

        if ( !empty($results) ){
            $retArray = array();
            foreach($results as $result) { 
                $retArray[$result["ID"]] = $result["post_title"];
            }
            return $retArray;
        }
        return;
    }
}

/**
 * Add new metabox value for specific post metabox
 *
 * @param string $name Metabox name
 * @param string $val  Metabox value
 * @param int $post_id Specific post ID
 */
if (!function_exists('bt_listing_set_metabox')) {
    function bt_listing_set_metabox($name, $val, $post_id) {
        if ($post_id) {
            $metabox = get_post_meta($post_id);
            if (isset($metabox[$name])) {               
                $val = wp_filter_nohtml_kses( $val );
                $retVal = update_post_meta( $post_id, $name, $val );
                return $retVal;
            }
        }
        return false;               
    }
}


/**
 * Update post properties
 *
 * @param array $update_data Array of updates values
 * @param array $where  Array of where conditions
 */
if (!function_exists('bt_listing_metabox_update_post')) {
    function bt_listing_metabox_update_post($update_data = array(), $where = array() ) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $update_format = array('%s');         
        $wpdb->update($prefix . 'posts', $update_data, $where, $update_format);
    }
}

/**
 * Send mail to new and old author of the claim
 *
 * @param int $claimed_post_id Post that is claimed
 * @param int $claimer_id  User ID that claimed
 * @param string $claim_status  Claim status
 */
if (!function_exists('bt_listing_metabox_send_mail')) {
    function bt_listing_metabox_send_mail($claimed_post_id, $claimer_id, $claim_status) {
        
        // new user
        $usermeta           = get_user_by('id', $claimer_id);
        $new_author_name    = $usermeta->user_login;
        $new_author_email   = $usermeta->user_email;
        
        // old user
        $listing_author     = get_post_field('post_author', $claimed_post_id);
        $oldusermeta        = get_user_by('id', $listing_author);
        $old_author_email   = $oldusermeta->user_email;
        
        //claimed by
        $c_mail_subject     = __('Your claim has been submitted', 'bt_plugin' );
        $c_mail_body        = __('Your Claim on listing <a href="%listing_url">%listing_title</a> has been submitted.', 'bt_plugin' );
        // author
        $a_mail_subject     = __('A claim has been submitted on your listing', 'bt_plugin' );
        $a_mail_body        = __('A claim has been submitted on your listing <a href="%listing_url">%listing_title</a>. Please contact admin for further details.', 'bt_plugin' );

        if (!empty($claim_status) && $claim_status == "approved") {
            $c_mail_subject = __('Your claim has been approved', 'bt_plugin' );
            $c_mail_body    = __('Your Claim on listing <a href="%listing_url">%listing_title</a> has been approved.', 'bt_plugin' );

            $a_mail_subject = __('Listing Claim notice', 'bt_plugin' );
            $a_mail_body    = __('Claim against your listing <a href="%listing_url">%listing_title</a> has been approved. Please contact admin for further details.', 'bt_plugin' );
        }
        
        if (!empty($claim_status) && $claim_status == "rejected") {
            $c_mail_subject = __('Your claim has been rejected', 'bt_plugin' );
            $c_mail_body    = __('Your Claim on listing <a href="%listing_url">%listing_title</a> has been rejected. Please contact admin for further details.', 'bt_plugin' );

            $a_mail_subject = __('Listing Claim notice', 'bt_plugin' );
            $a_mail_body    = __('Claim against your listing <a href="%listing_url">%listing_title</a> has been rejected.', 'bt_plugin' );
        }
     
        $admin_email   = get_option('admin_email');
        $website_url   = site_url();
        $website_name  = get_option('blogname');
        $listing_title = get_the_title($claimed_post_id);
        $listing_url   = get_the_permalink($claimed_post_id);
        $headers[]     = 'Content-Type: text/html; charset=UTF-8';
    
        $c_mail_subject   = str_replace('%website_url', '%1$s', $c_mail_subject);
        $c_mail_subject   = str_replace('%listing_title', '%2$s', $c_mail_subject);
        $c_mail_subject   = str_replace('%listing_url', '%3$s', $c_mail_subject);
        $c_mail_subject   = str_replace('%website_name', '%4$s', $c_mail_subject);
        $c_mail_subject_a = sprintf($c_mail_subject, $website_url, $listing_title, $listing_url, $website_name);
        
        $c_mail_body   = str_replace('%website_url', '%1$s', $c_mail_body);
        $c_mail_body   = str_replace('%listing_title', '%2$s', $c_mail_body);
        $c_mail_body   = str_replace('%listing_url', '%3$s', $c_mail_body);
        $c_mail_body   = str_replace('%website_name', '%4$s', $c_mail_body);
        $c_mail_body_a = sprintf($c_mail_body, $website_url, $listing_title, $listing_url, $website_name);
        
        $a_mail_subject   = str_replace('%website_url', '%1$s', $a_mail_subject);
        $a_mail_subject   = str_replace('%listing_title', '%2$s', $a_mail_subject);
        $a_mail_subject   = str_replace('%listing_url', '%3$s', $a_mail_subject);
        $a_mail_subject   = str_replace('%website_name', '%4$s', $a_mail_subject);
        $a_mail_subject_a = sprintf($a_mail_subject, $website_url, $listing_title, $listing_url, $website_name);
        
        $a_mail_body   = str_replace('%website_url', '%1$s', $a_mail_body);
        $a_mail_body   = str_replace('%listing_title', '%2$s', $a_mail_body);
        $a_mail_body   = str_replace('%listing_url', '%3$s', $a_mail_body);
        $a_mail_body   = str_replace('%website_name', '%4$s', $a_mail_body);
        $a_mail_body_a = sprintf($a_mail_body, $website_url, $listing_title, $listing_url, $website_name);
        
        if ( $new_author_email != '' ) {
            wp_mail($new_author_email, $c_mail_subject_a, $c_mail_body_a, $headers);
        }
        if ( $old_author_email != '' ) {
            wp_mail($old_author_email, $a_mail_subject_a, $a_mail_body_a, $headers);
        }
        
        return false;               
    }
}


/**
 * Save claim in WP admin
 *
 * @param int $post_id Post ID that is claimed
 */
add_action('save_post_claim', 'bt_save_claim_meta');
if (!function_exists('bt_save_claim_meta')) {
    function bt_save_claim_meta($post_id){
        
        if ( !isset( $_POST['bt_claim_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['bt_claim_meta_box_nonce'], basename( __FILE__ ) ) ){
                return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ){
                return;
        }
        $post_type = get_post_type($post_id); 
        
        if ("claim" != $post_type)
            return;
        
        if (!empty($_POST['post_title']) && isset($_POST['post_title']) && !empty($_POST['claimer']) && isset($_POST['claimer'])) {
            $claim_details      = $_POST['details'];
            $claim_listing_id   = $_POST['claimed_listing'];
            $claimer_id         = $_POST['claimer'];
            $claim_author       = $_POST['owner'];
            $claim_status       = $_POST['claim_status'];            

            $usermeta           = get_user_by('id', $claimer_id);            
            $new_author_name    = $usermeta ? $usermeta->user_login : '';  
            
            if ( ( isset( $claim_details ) )  ){
                    update_post_meta( $post_id, 'details', $claim_details );
            }        
            if ( ( isset( $claim_listing_id ) ) ){
                    update_post_meta( $post_id, 'claimed_listing', $claim_listing_id );
            }        
            if ( ( isset( $claimer_id ) ) ){
                    update_post_meta( $post_id, 'claimer', $claimer_id );
            }
            if ( ( isset( $claim_author ) ) ){
                    update_post_meta( $post_id, 'owner', $claim_author );
            }
            if ( ( isset( $claim_status ) ) ){
                    update_post_meta( $post_id, 'claim_status', $claim_status );
            }

            if (!empty($claim_status) && $claim_status == "pending") {          
                bt_listing_metabox_send_mail($claim_listing_id, $claimer_id, $claim_status);     
                return 1;
            } else if (!empty($claim_status) && $claim_status == "approved") {  
                update_post_meta( $post_id, 'owner', $new_author_name );
                bt_listing_metabox_send_mail($claim_listing_id, $claimer_id, $claim_status);            
                bt_listing_metabox_update_post( array( 'post_author' => $claimer_id ), array( 'ID' => $claim_listing_id )); 
                return 1;
            } else if (!empty($claim_status) && $claim_status == "rejected") {        
                bt_listing_metabox_send_mail($claim_listing_id, $claimer_id, $claim_status);     
                return 1;
            } else {
                return;
            }
        }
    }
}

/**
 * Save claim in Site form
 *
 * @param int $post_id Post ID that is claimed
 * @param array $params  Params for claim
 */
if (!function_exists('bt_save_claim')) {
    function bt_save_claim($post_id, $params = array()){
        $post_type = get_post_type($post_id); 
        if ("listing" != $post_type)
            return;

        if (!empty($params['claim_title']) && isset($params['claim_title']) && !empty($params['claimer']) && isset($params['claimer'])) {
            $claim_title        = $params['claim_title'];
            $claim_details      = $params['claim_details'];
            $claim_listing_id   = $params['claimed_listing'];
            
            $claimer_id         = $params['claimer'];
            $claim_author       = $params['owner'];
            $claim_status       = $params['claim_status'];            

            $usermeta           = get_user_by('id', get_current_user_id());            
            $new_author_name    = $usermeta ? $usermeta->user_login : ''; 
            
            $claim_id = wp_insert_post(array (
                'post_type' => 'claim',
                'post_title' => $claim_title,
                'post_content' => '',
                'post_status' => 'publish'
            ));
            
            if ( $claim_id ){
                if ( ( isset( $claim_details ) ) && ( $claim_details != '') ){
                        add_post_meta($claim_id, 'details', wp_filter_nohtml_kses( $claim_details ));
                }        
                if ( ( isset( $claim_listing_id ) ) && ( $claim_listing_id > 0 ) ){
                        add_post_meta($claim_id, 'claimed_listing', wp_filter_nohtml_kses( $claim_listing_id ));
                }        
                if ( ( isset( $claimer_id ) ) && ( $claimer_id > 0 ) ){
                        add_post_meta($claim_id, 'claimer', wp_filter_nohtml_kses( $claimer_id ));
                }
                if ( ( isset( $claim_author ) ) && ( $claim_author != '') ){
                        add_post_meta($claim_id, 'owner', wp_filter_nohtml_kses( $claim_author ));
                }
                if ( ( isset( $claim_status ) ) && ( $claim_status != '') ){
                        add_post_meta($claim_id, 'claim_status', wp_filter_nohtml_kses( $claim_status ));
                }

                if (!empty($claim_status) && $claim_status == "pending") {          
                    bt_listing_metabox_send_mail($claim_listing_id, $claimer_id, $claim_status);          
                    //bt_listing_metabox_update_post( array( 'post_author' => 1 ), array( 'ID' => $claim_listing_id ));
                    return 1;
                }                
            }
            return;
        }
    }
}


/**
 * Check if logged user claimed for specific post with status pending
 *
 * @param int $post_id Post ID that is claimed
 * @param array $params  Params for claim
 */
function bt_get_user_claim($user_id, $post_id) {    
     $args = array(
        'post_type'         => 'claim',
        'post_status'       => 'any',
        'posts_per_page'    => -1,
        'meta_query'        => array(
            'relation' => 'AND',
            array(
                'key' => 'claimed_listing',
                'value' => sanitize_text_field( $post_id ),
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'claimer',
                'value' => sanitize_text_field( $user_id ),
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'claim_status',
                'value' => 'pending',
                'compare' => 'LIKE'
            )
        )
    );
    $posts = get_posts($args);
    if ($posts){
        return 1;
    }
    return 0;
}


