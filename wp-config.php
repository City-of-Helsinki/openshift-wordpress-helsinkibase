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

if ( ! function_exists( 'maybe_define_env_const' ) ) {
	function maybe_define_env_const( string $name, callable $handler, string $env_var, mixed $default ): void {
		if ( ! defined( $name ) ) {
			define( $name, call_user_func( $handler, $env_var, $default ) );
		}
	}
}

/**
  * Reverse proxy configuration for WordPress
  * https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
  */
if (
	isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] )
	&& strpos( $_SERVER['HTTP_X_FORWARDED_PROTO'], 'https' ) !== false
) {
	$_SERVER['HTTPS'] = 'on';
}

/** WordPress configuration */
if ( file_exists( __DIR__ . '/config/custom.php' ) ) {
	require_once __DIR__ . '/config/custom.php';
}

if ( file_exists( __DIR__ . '/config/default.php' ) ) {
	require_once __DIR__ . '/config/default.php';
}

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
