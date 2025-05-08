<?php

if ( ! function_exists( 'maybe_define_env_const' ) || ! function_exists( 'get_env_value' ) ) {
	die;
}

/**
  * Environment
  */
maybe_define_env_const( 'WP_ENVIRONMENT_TYPE', 'get_env_value', 'WORDPRESS_ENVIRONMENT', 'production' );

/**
  * Database
  */
maybe_define_env_const( 'DB_NAME', 'get_env_value', 'WORDPRESS_DB_NAME', 'wordpress' );
maybe_define_env_const( 'DB_USER', 'get_env_value', 'WORDPRESS_DB_USER', 'example username' );
maybe_define_env_const( 'DB_PASSWORD', 'get_env_value', 'WORDPRESS_DB_PASSWORD', 'example password' );
maybe_define_env_const( 'DB_HOST', 'get_env_value', 'WORDPRESS_DB_HOST', 'mysql' );
maybe_define_env_const( 'DB_CHARSET', 'get_env_value', 'WORDPRESS_DB_CHARSET', 'utf8' );
maybe_define_env_const( 'DB_COLLATE', 'get_env_value', 'WORDPRESS_DB_COLLATE', '' );

if ( ! defined( 'MYSQL_CLIENT_FLAGS' ) ) {
	define( 'MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL );
}

if ( ! isset( $table_prefix ) ) {
	$table_prefix = get_env_value( 'WORDPRESS_TABLE_PREFIX', 'wp_' );
}

/**
  * Authentication unique keys and salts.
  */
maybe_define_env_const( 'AUTH_KEY', 'get_env_value', 'WORDPRESS_AUTH_KEY', 'put your unique phrase here' );
maybe_define_env_const( 'SECURE_AUTH_KEY', 'get_env_value', 'WORDPRESS_SECURE_AUTH_KEY', 'put your unique phrase here' );
maybe_define_env_const( 'LOGGED_IN_KEY', 'get_env_value', 'WORDPRESS_LOGGED_IN_KEY', 'put your unique phrase here' );
maybe_define_env_const( 'NONCE_KEY', 'get_env_value', 'WORDPRESS_NONCE_KEY', 'put your unique phrase here' );
maybe_define_env_const( 'AUTH_SALT', 'get_env_value', 'WORDPRESS_AUTH_SALT', 'put your unique phrase here' );
maybe_define_env_const( 'SECURE_AUTH_SALT', 'get_env_value', 'WORDPRESS_SECURE_AUTH_SALT', 'put your unique phrase here' );
maybe_define_env_const( 'LOGGED_IN_SALT', 'get_env_value', 'WORDPRESS_LOGGED_IN_SALT', 'put your unique phrase here' );
maybe_define_env_const( 'NONCE_SALT', 'get_env_value', 'WORDPRESS_NONCE_SALT', 'put your unique phrase here' );

/**
  * WordPress settings
  */

// Cache
maybe_define_env_const( 'WP_CACHE', 'get_bool_env_value', 'WORDPRESS_CACHE', false );

if ( get_env_value( 'WORDPRESS_CACHE_DIR', '' ) ) {
	maybe_define_env_const( 'CACHE_ENABLER_CACHE_DIR', 'get_env_value', 'WORDPRESS_CACHE_DIR', '' );
}

if ( get_env_value( 'WORDPRESS_CACHE_SETTINGS_DIR', '' ) ) {
	maybe_define_env_const( 'CACHE_ENABLER_SETTINGS_DIR', 'get_env_value', 'WORDPRESS_CACHE_SETTINGS_DIR', '' );
}

// Cron
maybe_define_env_const( 'WP_CRON_LOCK_TIMEOUT', 'get_int_env_value', 'WORDPRESS_CRON_LOCK_TIMEOUT', 60 );
maybe_define_env_const( 'DISABLE_WP_CRON', 'get_bool_env_value', 'WORDPRESS_DISABLE_CRON', true );

// Debug
maybe_define_env_const( 'WP_DEBUG', 'get_bool_env_value', 'WORDPRESS_DEBUG', false );
maybe_define_env_const( 'WP_DEBUG_LOG', 'get_bool_env_value', 'WORDPRESS_DEBUG_LOG', false );
maybe_define_env_const( 'WP_DEBUG_DISPLAY', 'get_bool_env_value', 'WORDPRESS_DEBUG_DISPLAY', false );

// Filesystem
maybe_define_env_const( 'FS_METHOD', 'get_env_value', 'WORDPRESS_FS_METHOD', 'direct' );
maybe_define_env_const( 'DISALLOW_FILE_MODS', 'get_bool_env_value', 'WORDPRESS_DISALLOW_FILE_MODS', true );

// Image editing
maybe_define_env_const( 'IMAGE_EDIT_OVERWRITE', 'get_bool_env_value', 'WORDPRESS_IMAGE_EDIT_OVERWRITE', true );

// Memory management
maybe_define_env_const( 'WP_MEMORY_LIMIT', 'get_env_value', 'WORDPRESS_MEMORY_LIMIT', '256M' );

// Revisions
maybe_define_env_const( 'WP_POST_REVISIONS', 'get_int_env_value', 'WORDPRESS_POST_REVISIONS', 5 );
