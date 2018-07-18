<?php 
namespace kamadmin;
/*
 * Plugin Name: Gaad Kamera admin
 * Text Domain: kamadmin
 * Version: 1.25
 * Plugin URI: 
 * Description:  
 * Author: Bartek Sosnowski @ Grupa Nova
 * Requires at least: 4.4
 * Tested up to: 4.8
 *
 * @author Bartek Sosnowski
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
ini_set('max_execution_time', 60*10); //10 minutes

define( 'kamadmin\GAAD_KAM_ADMIN_NAMESPACE', 'kamadmin\\' );


if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_CORE_SCRIPTS_CDN_USE'))
	define( 
		GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_CORE_SCRIPTS_CDN_USE', true );

if ( !defined( 'WPLANG'))
	define( 'WPLANG', 'pl_PL' );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_ENV'))
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_ENV', 'DEV' );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_SHORTCODE'))
	/*
	* Application lauching shorcode name
	* @default namespace name
	*/
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_SHORTCODE', 'kamadmin' );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_NAME'))            
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/') );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_COMPONENTS_CSS_DIR'))            
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_COMPONENTS_CSS_DIR', 'css/components' );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_DIR' ) )
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_DIR', plugin_dir_path( __FILE__) );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_VENDOR_DIR' ) )
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_VENDOR_DIR', GAAD_KAM_ADMIN_NAMESPACE . GAAD_KAM_ADMIN_DIR .'/vendor' );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_AUTOLOAD' ) )
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_AUTOLOAD', GAAD_KAM_ADMIN_NAMESPACE . GAAD_KAM_ADMIN_VENDOR_DIR . '/autoload.php');

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_THEME_FILES_DIR' ) ) 
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_THEME_FILES_DIR', GAAD_KAM_ADMIN_DIR . 'theme_files' );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_APP_TEMPLATES_DIR' ) )
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_APP_TEMPLATES_DIR', GAAD_KAM_ADMIN_DIR . 'templates' );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_APP_COMPONENTS_DIR' ) )
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_APP_COMPONENTS_DIR', GAAD_KAM_ADMIN_DIR . 'js/components' );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_LANG_DIR' ) )
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_LANG_DIR', GAAD_KAM_ADMIN_DIR . 'languages' );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_DIR') ) 
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_DIR', GAAD_KAM_ADMIN_DIR . '/' . GAAD_KAM_ADMIN_NAME );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_URL') )
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_URL', WP_PLUGIN_URL . '/' . GAAD_KAM_ADMIN_NAME );

if ( !defined( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_FORCE_FILES_UPDATED') )
	define( GAAD_KAM_ADMIN_NAMESPACE . 'GAAD_KAM_ADMIN_FORCE_FILES_UPDATED', true );

	is_file( GAAD_KAM_ADMIN_AUTOLOAD ) ?  require_once( GAAD_KAM_ADMIN_AUTOLOAD ) : false;
	
	

	require_once( 'class/gaad-kam-admin.php' );

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