<?php
/**
 * Plugin Name: Affiliates GDPR Framework integration
 * Plugin URI: http://www.netpad.gr
 * Description: Integrates Affiliates plugin with GDPR Framework integration.
 * Version: 1.0.1
 * Author: George Tsiokos
 * Author URI: http://www.netpad.gr
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'plugins_loaded', 'aff_gdpr_check_dependencies' );

/**
 * Check plugin dependencies
 */
function aff_gdpr_check_dependencies() {
	if ( defined( 'AFFILIATES_PLUGIN_DOMAIN' ) && defined( 'GDPR_FRAMEWORK_VERSION' ) ) {
		add_filter( 'affiliates_registration_after_fields', 'affiliates_gdpr_registration_after_fields' );
	}
}

/**
 * Add GDPR consent checkbox in affiliates registration form
 *
 * @param string $output
 * @return string $output
 */
function affiliates_gdpr_registration_after_fields( $output ) {
	$output = '';
	if ( !function_exists( 'gdpr' ) ) {
		include_once ( plugin_dir_path( __DIR__ ) . 'gdpr-framework.php/bootstrap.php' );
	}
	$privacy_policy = get_option( 'gdpr_policy_page' );
	$terms_page = get_option( 'gdpr_terms_page' );

	$termsUrl = $terms_page ? get_permalink( $terms_page ) : false;
	$privacyPolicyUrl = $privacy_policy ? get_permalink( $privacy_policy ) : false;

	$output .= gdpr('view')->render(
		'modules/wordpress-user/registration-terms-checkbox',
		compact( 'privacyPolicyUrl', 'termsUrl' )
	);
	return $output;
}