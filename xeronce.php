<?php


/**
 * @package Xeronce Base
 * @version 1.0.0
 */
/*
Plugin Name: Xeronce Base
Plugin URI: https://xeronce.com
Description: Base plugin for xeronce sub-plugins.
Author: Tazim Hossain
Version: 1.0.0
Author URI: https://xeronce.com
*/


// Xeronce Plugin Environment
define('XERONCE_ENVIRONMENT', 'development');
//define('XERONCE_ENVIRONMENT', 'production');

if ( ! defined( 'XERONCE_PLUGIN_FILE' ) ) {define( 'XERONCE_PLUGIN_FILE', __FILE__ );}
if ( ! defined( 'XERONCE_PLUGIN_DIR' ) ) {define( 'XERONCE_PLUGIN_DIR', plugin_dir_path( XERONCE_PLUGIN_FILE ) );}
define('XE_AAMARPAY_SANDBOX_API_URL', 'https://sandbox.aamarpay.com/index.php');
define('XE_AAMARPAY_PRODUCTION_API_URL', 'https://secure.aamarpay.com/index.php');
$amarpay_api_url = XERONCE_ENVIRONMENT === 'development' ? XE_AAMARPAY_SANDBOX_API_URL : XE_AAMARPAY_PRODUCTION_API_URL;
define('PAYMENT_URL', $amarpay_api_url);


function XePrintLog($debug_data)
{
	$pluginlog = plugin_dir_path(__FILE__).'debug.log';
	error_log(print_r( $debug_data, true ), 3, $pluginlog);
}



