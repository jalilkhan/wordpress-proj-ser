<?php

if ( ! is_user_logged_in() ) {?>
    <a href="<?php echo $my_account_permalink; ?>" target="_self" class="btIconWidget btWidgetWithText btMyAccountLogin">
        <div class="btIconWidgetIcon">
            <span data-ico-fontawesome="&#xf2be;" class="bt_bb_icon_holder"></span>
        </div>
        <div class="btIconWidgetContent">
            <span class="btIconWidgetTitle"><?php esc_html_e( 'Login / Register', 'bt_plugin' ); ?></span>
        </div>
    </a>
<?php } else { ?>
        <?php
            $user = wp_get_current_user();
            $role = ( array ) $user->roles;
        ?>
        <?php if(!in_array('customer',$role)) { ?>
    <a href="<?php echo $my_account_permalink; ?>" target="_self" class="btIconWidget btWidgetWithText btMyAccountLogin">
        <div class="btIconWidgetIcon">
            <span data-ico-fontawesome="&#xf2be;" class="bt_bb_icon_holder"></span>
        </div>
        <div class="btIconWidgetContent">
                <span class="btIconWidgetTitle"><?php esc_html_e( 'My Account', 'bt_plugin' ); ?></span>
        </div>
    </a>
    <?php
    if ( function_exists( 'wc_get_endpoint_url' ) ) {
         $account_listing_endpoint = bt_account_listing_endpoint();
        ?>
        <a href="<?php echo wc_get_endpoint_url( $account_listing_endpoint, '', wc_get_page_permalink( 'myaccount' ) ); ?>" target="_self" class="btIconWidget btAccentIconWidget btWidgetWithText">
            <div class="btIconWidgetContent">
                <span class="btIconWidgetTitle"><?php esc_html_e( 'Create a listing', 'bt_plugin' ); ?></span>
            </div>
        </a>
    <?php } ?>
    <?php } ?>
<?php } ?>