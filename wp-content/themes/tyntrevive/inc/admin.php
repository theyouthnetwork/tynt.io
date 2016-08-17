<?php
/*
 * Just admin things
 *
 */
 

function tnyt_admin_render_password_opts_page(){
?>
<style type="text/css">
.link-masqueraded-button { border: none; background: none transparent; padding: 0; text-decoration: underline; cursor: pointer; }
.link-masqueraded-button { color: maroon; }
.link-masqueraded-button:hover { color: red; }
</style>
<div class="wrap nosubsub">
    <h1>TYNT Options</h1>
</div>

<div class="wrap">
    <form action="<?php echo home_url('/wp-admin/admin-post.php')?>" method="post">
        <?php wp_nonce_field( basename( __FILE__ ), 'tynt_opts_nonce' ); ?>
        
        <div>
            <p>Password protection: Enter a new password. This will be the password that's required for all visitors to view any page. </p>
            <?php if ( $tynt_viewonly_password = get_option( 'tynt_viewonly_password' ) ): ?>
            <p><span class="dashicons dashicons-yes"></span> Password protection is currently active</p>
            <?php else: ?>
            <p><span class="dashicons dashicons-no"></span> Password protection is not active</p>
            <?php endif; ?>
            <input name="tynt_new_password" type="password">
            <button class="link-masqueraded-button" type="submit" name="tynt_opts_delete_password" value="yes">Remove password</button>
            <p style="text-align: right;">
            </p>
        </div>
        
        <hr>
        
        <input type="hidden" name="action" value="tynt_opts_save">
        <button class="button button-primary" type="submit">Save</button>
    </form>
    
    <?php if ( $tynt_opts_save_resp = get_transient('tynt_opts_save_resp') ): ?>
    <div id="message-success-saved" class="notice notice-success">
        <p><?php echo $tynt_opts_save_resp ?></p>
    </div>
    <?php delete_transient('tynt_opts_save_resp') ?>
    <?php endif;?>
    <?php if ( $tynt_opts_save_resp_error = get_transient('tynt_opts_save_resp_error') ): ?>
    <div id="message-error-cannot-save" class="notice notice-error">
        <p><?php echo $tynt_opts_save_resp_error ?></p>
    </div>
    <?php delete_transient('tynt_opts_save_resp_error') ?>
    <?php endif;?>
</div>

<?php
}

// Save listener
add_action( 'admin_post_tynt_opts_save', 'tynt_opts_save' );
function tynt_opts_save() {
        
    if ( ! (isset( $_POST[ 'tynt_opts_nonce' ] ) && wp_verify_nonce( $_POST[ 'tynt_opts_nonce' ], basename( __FILE__ ) ) ) ) {
        set_transient( 'tynt_opts_save_resp_error', 'Nonce check failed' );
        wp_redirect( admin_url('options-general.php?page=tynt_opts') );
        return;
    }
    
    if ( isset($_POST['tynt_opts_delete_password']) ){
        update_option( 'tynt_viewonly_password', '' );
        set_transient( 'tynt_opts_save_resp', 'Password removed' );
        wp_redirect( admin_url('options-general.php?page=tynt_opts') );
        exit;
    }
    
    $new_password = trim(stripslashes($_POST[ 'tynt_new_password' ]));
    $hashed = wp_hash_password( $new_password );
    update_option( 'tynt_viewonly_password', $hashed );
    set_transient( 'tynt_opts_save_resp', 'New password saved' );
    
    wp_redirect( admin_url('options-general.php?page=tynt_opts') );
}


// Initialize the options fields
add_action( 'admin_init', 'tynt_admin_initialize_password_settings' );
function tynt_admin_initialize_password_settings() {
    add_option( 'tynt_viewonly_password', '' );
} 

add_action( 'admin_menu', 'tynt_admin_setup_password_opts_page' );
function tynt_admin_setup_password_opts_page(){
    add_submenu_page ('options-general.php', __( 'TYNT Options', 'tyntrevive'), __( 'TYNT Options', 'tyntrevive'), 'manage_options', 'tynt_opts', 'tnyt_admin_render_password_opts_page');
}

