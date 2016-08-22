<?php
/*
 * Just admin things
 * @author AY
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
        
        <?php if ( $tynt_viewonly_password = get_option( 'tynt_viewonly_password' ) ): ?>
        <p style="color: limegreen;"><span class="dashicons dashicons-yes"></span> Password protection is currently active</p>
        <?php else: ?>
        <p style="color: firebrick;"><span class="dashicons dashicons-no"></span> Password protection is not active</p>
        <?php endif; ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="viewer-password">New password to view</label></th>
                <td>
                    <input id="viewer-password" name="tynt_new_password" type="password" class="regular-text">
                    <button class="link-masqueraded-button" type="submit" name="tynt_opts_delete_password" value="yes">Remove password</button>
                    <p class="description">Enter a new password. This will be the password that's required for all visitors to view any page.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="url-ending">Password page URL ending</label></th>
                <td>
                    <input id="url-ending" type="text" class="regular-text" name="tynt_passwordpage_url_ending" placeholder="e.g. /password/" value="<?php echo get_option('tynt_passwordpage_url_ending', '') ?>">
                    <p class="description">Slashes will be added automatically, if required</p>
                </td>
            </tr>
        </table>
        
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
        exit;
    }
    
    if ( isset($_POST['tynt_opts_delete_password']) ){
        delete_option( 'tynt_viewonly_password' );
        set_transient( 'tynt_opts_save_resp', 'Password removed' );
        wp_redirect( admin_url('options-general.php?page=tynt_opts') );
        exit;
    }
    
    if ( $passwordpage_ending = $_POST['tynt_passwordpage_url_ending'] ){
        $passwordpage_ending = str_replace('/', '', $passwordpage_ending);
        $passwordpage_ending = "/$passwordpage_ending/";
        update_option( 'tynt_passwordpage_url_ending', $passwordpage_ending );
        set_transient( 'tynt_opts_save_resp', 'Saved' );
    }
    
    if ( $new_password = trim(stripslashes($_POST[ 'tynt_new_password' ])) ){
        $hashed = wp_hash_password( $new_password );
        update_option( 'tynt_viewonly_password', $hashed );
        set_transient( 'tynt_opts_save_resp', 'Saved' );
    }
    
    wp_redirect( admin_url('options-general.php?page=tynt_opts') );
    exit;
}


// Initialize the options fields
add_action( 'admin_init', 'tynt_admin_initialize_password_settings' );
function tynt_admin_initialize_password_settings() {
    add_option( 'tynt_passwordpage_url_ending', '' );
    add_option( 'tynt_viewonly_password', '' );
} 

add_action( 'admin_menu', 'tynt_admin_setup_password_opts_page' );
function tynt_admin_setup_password_opts_page(){
    add_submenu_page ('options-general.php', __( 'TYNT Options', 'tyntrevive'), __( 'TYNT Options', 'tyntrevive'), 'manage_options', 'tynt_opts', 'tnyt_admin_render_password_opts_page');
}

