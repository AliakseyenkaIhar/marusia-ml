<?php
/*
	Plugin Name: Marusia MultiLang
	Description: Adds support for Polylang and Loco translate
	Author: Aliakseyenka Ihar
	Version: 1.0.0
	Author URI: https://czernika.com/
*/

/**
 * Load .pot file
 * In order to properly work with Loco translate plugin it must be called inside theme.
 */
add_action( 'after_setup_theme', 'marusia_ml_register_textdomain' );
function marusia_ml_register_textdomain() {

	/**
	 * * This filters are not working, heh
	 */
	$pot_file   = apply_filters( 'marusia_pot_file', get_template_directory() . '/public/languages' );
	$textdomain = apply_filters( 'marusia_textdomain', 'marusia' );
	load_theme_textdomain( $textdomain, $pot_file );
}

/**
 * Check if Loco translate is on
 */
add_action( 'admin_notices', 'marusia_ml_check_loco_translate' );
function marusia_ml_check_loco_translate() {

	if ( ! file_exists( get_template_directory() . '/public/languages/marusia.pot' ) ) {
	?>
		<div class="notice notice-warning">
			<p><?php esc_html_e( 'No pot file exists. Please create one', 'marusia_ml' )?></p>
		</div>
	<?php

		/**
		 * @see https://www.php.net/manual/ru/function.clearstatcache.php
		 */
		clearstatcache();
	}
}

/**
 * Multilanguage support with Polylang
 */
function marusia_ml_add_to_context( $context ) {
	if ( function_exists( 'pll_the_languages' ) ) {
		$context['languages']        = pll_the_languages(
			[ 'raw' => true ]
		);
	}
	$context = apply_filters( 'marusia_ml_context', $context );
	return $context;
}
add_filter( 'timber/context', 'marusia_ml_add_to_context' );
