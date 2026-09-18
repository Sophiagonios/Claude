<?php
/**
 * Baseline hardening that a theme can reasonably own. None of this makes
 * the site "totally secure" — that also depends on the host, WordPress
 * core/plugin updates, admin passwords and backups, which are outside
 * what a theme's code can control.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Force HTTPS. Checks both the direct connection and the
 * X-Forwarded-Proto header, since shared hosting often terminates SSL at
 * a proxy/load balancer in front of PHP.
 */
function antenniste91_force_https() {
	if ( is_admin() ) {
		return;
	}
	$is_https = ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'] )
		|| ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'] );

	if ( ! $is_https && ! empty( $_SERVER['HTTP_HOST'] ) ) {
		wp_safe_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
		exit;
	}
}
add_action( 'template_redirect', 'antenniste91_force_https' );

/** Stop leaking the exact WordPress version — a low-effort target for automated scans. */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

/**
 * XML-RPC is a common brute-force / DDoS-relay target and this site has no
 * use for it (no Jetpack, no remote publishing). Disabling it removes that
 * surface entirely.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Discourage in-admin file editing of theme/plugin PHP — normally set in
 * wp-config.php, but defined here too so it still applies even if nobody
 * added it there.
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}
