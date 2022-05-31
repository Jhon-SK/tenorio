<?php
/*
Plugin Name: Builder Core
Plugin URI:  http://ithemeslab.com
Description: This plugin enables the core features to the Builder theme. You must have to install this plugin to work with the Builder WordPress theme.
Version:     1.1
Author:      iThemesLab
Author URI:  http://ithemeslab.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: builder
Domain Path: /languages
*/

// don't load directly
if (!defined('ABSPATH')) die('-1');

load_plugin_textdomain('builder-core', false, basename( dirname( __FILE__ ) ) . '/languages' );

// import other files
$main_file_dir = plugin_dir_path(__FILE__) . '/inc/metabox/';
foreach ( glob( $main_file_dir . '*.php' ) as $main_files ) {
    require_once $main_files;
}

// import custom post types
$cpt_dir = plugin_dir_path(__FILE__) . '/inc/cpt/';
foreach ( glob( $cpt_dir . '*.php' ) as $cpt_files ) {
    require_once $cpt_files;
}

// import vc addons
$vc_addons_dir = plugin_dir_path(__FILE__) . 'inc/vc-addons/';
foreach ( glob( $vc_addons_dir . '*.php' ) as $vc_addons_files ) {
    require_once $vc_addons_files;
}

