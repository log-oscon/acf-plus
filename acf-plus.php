<?php

/**
 * ACF Plus
 *
 * Common utility classes for the Advanced Custom Fields (Pro) plugin on WordPress.
 *
 * @link              http://log.pt/
 * @since             1.0.0
 * @package           ACF
 *
 * @wordpress-plugin
 * Plugin Name:       ACF Plus
 * Plugin URI:        https://github.com/log-oscon/acf-plus/
 * Description:       Common utility classes for the Advanced Custom Fields (Pro) plugin on WordPress.
 * Version:           1.0.0
 * Author:            log.OSCON, Lda.
 * Author URI:        http://log.pt/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acf-plus
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/log-oscon/acf-plus
 * GitHub Branch:     master
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

use logoscon\ACF;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
