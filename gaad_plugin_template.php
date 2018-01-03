<?php 
namespace plugins_main_namespace;
/*
 * Plugin Name: Gaad plugin template
 * Text Domain: gaad-plugin-template
 * Version: 1.0
 * Plugin URI: 
 * Description: 
 * Author: Bartek Sosnowski 
 * Requires at least: 3.5
 * Tested up to: 4.8
 *
 * @package WordPress
 * @author Bartek Sosnowski
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
ini_set('max_execution_time', 60*10); //10 minutes

define( 'plugins_main_namespace\GAAD_PLUGIN_TEMPLATE_NAMESPACE', 'plugins_main_namespace\\' );


if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_CORE_SCRIPTS_CDN_USE'))
	define( 
		GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_CORE_SCRIPTS_CDN_USE', true );

if ( !defined( 'WPLANG'))
	define( 'WPLANG', 'pl_PL' );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_ENV'))
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_ENV', 'DEV' );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_SHORTCODE'))
	/*
	* Application lauching shorcode name
	* @default namespace name
	*/
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_SHORTCODE', 'plugins_main_namespace' );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_NAME'))            
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_NAME', trim(dirname(plugin_basename(__FILE__)), '/') );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_COMPONENTS_CSS_DIR'))            
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_COMPONENTS_CSS_DIR', 'css/components' );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_DIR' ) )
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_DIR', plugin_dir_path( __FILE__) );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_VENDOR_DIR' ) )
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_VENDOR_DIR', GAAD_PLUGIN_TEMPLATE_NAMESPACE . GAAD_PLUGIN_TEMPLATE_DIR .'/vendor' );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_AUTOLOAD' ) )
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_AUTOLOAD', GAAD_PLUGIN_TEMPLATE_NAMESPACE . GAAD_PLUGIN_TEMPLATE_VENDOR_DIR . '/autoload.php');

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_THEME_FILES_DIR' ) ) 
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_THEME_FILES_DIR', GAAD_PLUGIN_TEMPLATE_DIR . 'theme_files' );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_APP_TEMPLATES_DIR' ) )
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_APP_TEMPLATES_DIR', GAAD_PLUGIN_TEMPLATE_DIR . 'templates' );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_APP_COMPONENTS_DIR' ) )
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_APP_COMPONENTS_DIR', GAAD_PLUGIN_TEMPLATE_DIR . 'js/components' );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_DIR') ) 
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_DIR', GAAD_PLUGIN_TEMPLATE_DIR . '/' . GAAD_PLUGIN_TEMPLATE_NAME );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_URL') )
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_URL', WP_PLUGIN_URL . '/' . GAAD_PLUGIN_TEMPLATE_NAME );

if ( !defined( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_FORCE_FILES_UPDATED') )
	define( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'GAAD_PLUGIN_TEMPLATE_FORCE_FILES_UPDATED', true );

	is_file( GAAD_PLUGIN_TEMPLATE_AUTOLOAD ) ?  require_once( GAAD_PLUGIN_TEMPLATE_AUTOLOAD ) : false;
	
	
	require_once( 'inc/class-json-data.php' );
	require_once( 'inc/class-rest.php' );	
	require_once( 'inc/register-routers.php' );
	require_once( 'class/class-hooks-mng.php' );
	require_once( 'class/class-shortcodes.php' );
	require_once( 'inc/class-filters.php' );
	require_once( 'inc/class-actions.php' );
	require_once( 'inc/class-admin-actions.php' );
	require_once( 'inc/plugin-hooks.php' );

	
?>