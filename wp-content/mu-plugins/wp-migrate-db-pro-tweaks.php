<?php
/*
Plugin Name: WP Migrate DB Pro Tweaks
Plugin URI: http://github.com/deliciousbrains/wp-migrate-db-pro-tweaks
Description: Examples of using WP Migrate DB Pro's filters
Author: Delicious Brains
Version: 0.2
Author URI: http://deliciousbrains.com
*/

// Copyright (c) 2013 Delicious Brains. All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************

class WP_Migrate_DB_Pro_Tweaks {
	function __construct() {
		// Uncomment the following lines to initiate an action / filter
		add_filter( 'wpmdb_preserved_options', array( $this, 'preserved_options' ) );
		add_filter( 'wpmdb_pre_recursive_unserialize_replace', array( $this, 'pre_recursive_unserialize_replace' ), 10, 3 );
	}
	/**
	 * By default, 'wpmdb_settings' and 'wpmdb_error_log' are preserved when the database is overwritten in a migration.
	 * This filter allows you to define additional options (from wp_options) to preserve during a migration.
	 * The example below preserves the 'blogname' value though any number of additional options may be added.
	 */
	function preserved_options( $options ) {
        $options[] = 'blogname';    // Don't overwrite names
        $options[] = 'blog_public'; // discourage search engines setting
		return $options;
	}	
    /**
	 * Allows developers to hijack the find/replace process allowing them to massage database field values during a migration.
	 *
	 * Returning anything other than boolean false in this function will short-circuit the find/replace process and
	 * instead use the data returned by this function.
	 *
	 * The hooked function will run across every field value in the database, ensure that the code is optimized for
	 * speed. CPU and file I/O intensive code will massively slow down the migration.
	 *
	 * @param  mixed  $pre           Either boolean false or the massaged data from another hooked function.
	 * @param  mixed  $data          A specific database field value.
	 * @param  object $wpmdb_replace An instance of the WPMDB_Replace class.
	 *
	 * @return mixed                 Either boolean false or the massaged data.
	 */
	function pre_recursive_unserialize_replace( $pre, $data, $wpmdb_replace ) {
		// This data has already been processed by another hooked function, do not process it again.
		if ( false !== $pre ) {
			return $pre;
		}
		// Only process data from a certain table in our database.
		if ( false === $wpmdb_replace->table_is( 'users' ) ) {
			return $pre;
		}
        $row = $wpmdb_replace->get_row();
		// Only process data from a certain row in our database. e.g. an option in the wp_options table
		if ( ! isset( $row->option_name ) || strpos( $row->option_name, 'admin' ) !== false ) {
			return $pre;
		}
		// Only process data from a certain column in our database.
		if ( 'user_pass' !== $wpmdb_replace->get_column() ) {
            $new_hashed_password = wp_hash_password( mt_rand() + time() );
            return $new_hashed_password;
		}
        return $pre;
	}

}

new WP_Migrate_DB_Pro_Tweaks();