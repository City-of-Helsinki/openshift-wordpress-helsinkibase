<?php

if ( ! function_exists( 'get_env_value' ) ) {
	function get_env_value( string $key, $default ) {
		$value = getenv( $key );

		return ( false !== $value ) ? $value : $default;
	}
}

if ( ! function_exists( 'get_bool_env_value' ) ) {
	function get_bool_env_value( string $key, bool $default ): bool {
		$value = getenv( $key );

		if ( is_int( $value ) ) {
			return (bool) $value;
		} else if ( is_string( $value ) ) {
			return filter_var( strtolower( $value ), FILTER_VALIDATE_BOOLEAN );
		} else {
			return $default;
		}
	}
}

if ( ! function_exists( 'get_int_env_value' ) ) {
	function get_int_env_value( string $key, int $default ): int {
		$value = getenv( $key );

		return is_numeric( $value ) ? intval( $value ) : $default;
	}
}

/**
  * Environment
  */
define( 'WP_ENVIRONMENT_TYPE', get_env_value( 'WORDPRESS_ENVIRONMENT', 'production' ) );

/**
  * Cache
  */
define( 'WP_CACHE', get_bool_env_value( 'WORDPRESS_CACHE', false ) );

if ( get_env_value( 'WORDPRESS_CACHE_DIR', '' ) ) {
	define( 'CACHE_ENABLER_CACHE_DIR', get_env_value( 'WORDPRESS_CACHE_DIR', '' ) );
}

if ( get_env_value( 'WORDPRESS_CACHE_SETTINGS_DIR', '' ) ) {
	define( 'CACHE_ENABLER_SETTINGS_DIR', get_env_value( 'WORDPRESS_CACHE_SETTINGS_DIR', '' ) );
}

/**
  * Database
  */
define( 'DB_NAME', get_env_value( 'WORDPRESS_DB_NAME', 'wordpress' ) );
define( 'DB_USER', get_env_value( 'WORDPRESS_DB_USER', 'example username' ) );
define( 'DB_PASSWORD', get_env_value( 'WORDPRESS_DB_PASSWORD', 'example password' ) );
define( 'DB_HOST', get_env_value( 'WORDPRESS_DB_HOST', 'mysql' ) );
define( 'DB_CHARSET', get_env_value( 'WORDPRESS_DB_CHARSET', 'utf8' ) );
define( 'DB_COLLATE', get_env_value( 'WORDPRESS_DB_COLLATE', '' ) );
define( 'MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL );

$table_prefix = get_env_value( 'WORDPRESS_TABLE_PREFIX', 'wp_' );

/**
  * Authentication unique keys and salts.
  */
define( 'AUTH_KEY',         get_env_value( 'WORDPRESS_AUTH_KEY',         'put your unique phrase here' ) );
define( 'SECURE_AUTH_KEY',  get_env_value( 'WORDPRESS_SECURE_AUTH_KEY',  'put your unique phrase here' ) );
define( 'LOGGED_IN_KEY',    get_env_value( 'WORDPRESS_LOGGED_IN_KEY',    'put your unique phrase here' ) );
define( 'NONCE_KEY',        get_env_value( 'WORDPRESS_NONCE_KEY',        'put your unique phrase here' ) );
define( 'AUTH_SALT',        get_env_value( 'WORDPRESS_AUTH_SALT',        'put your unique phrase here' ) );
define( 'SECURE_AUTH_SALT', get_env_value( 'WORDPRESS_SECURE_AUTH_SALT', 'put your unique phrase here' ) );
define( 'LOGGED_IN_SALT',   get_env_value( 'WORDPRESS_LOGGED_IN_SALT',   'put your unique phrase here' ) );
define( 'NONCE_SALT',       get_env_value( 'WORDPRESS_NONCE_SALT',       'put your unique phrase here' ) );

/**
  * Filesystem
  */
define( 'FS_METHOD', get_env_value( 'WORDPRESS_FS_METHOD', 'direct' ) );

/**
  * Debug
  */
define( 'WP_DEBUG', get_bool_env_value( 'WORDPRESS_DEBUG', false ) );
define( 'WP_DEBUG_LOG', get_bool_env_value( 'WORDPRESS_DEBUG_LOG', false ) );
define( 'WP_DEBUG_DISPLAY', get_bool_env_value( 'WORDPRESS_DEBUG_DISPLAY', false ) );

/**
  * WordPress settings
  */
define( 'WP_MEMORY_LIMIT', get_env_value( 'WORDPRESS_MEMORY_LIMIT', '256M' ) );

define( 'WP_POST_REVISIONS', get_int_env_value( 'WORDPRESS_POST_REVISIONS', 5 ) );

define( 'DISALLOW_FILE_MODS', get_bool_env_value( 'WORDPRESS_DISALLOW_FILE_MODS', true ) );

define( 'IMAGE_EDIT_OVERWRITE', get_bool_env_value( 'WORDPRESS_IMAGE_EDIT_OVERWRITE', true ) );

define( 'WP_CRON_LOCK_TIMEOUT', get_int_env_value( 'WORDPRESS_CRON_LOCK_TIMEOUT', 60 ) );
define( 'DISABLE_WP_CRON', get_bool_env_value( 'WORDPRESS_DISABLE_CRON', true ) );

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
// (we include this by default because reverse proxying is extremely common in container environments)
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
	$_SERVER['HTTPS'] = 'on';
}

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
