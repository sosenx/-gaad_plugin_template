<?php 
namespace apii;


if ( \is_admin() ) {
	//default settings page
	admin_actions::admin_styles();
	admin_actions::admin_scripts();

}


$core_hooks = new hooks_mng( 'core' ); 
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array( '\apii\actions::core_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array( '\apii\actions::common_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts',  '\apii\actions::common_styles');
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array( '\apii\actions::app_scripts', 10, 0, true));

$core_hooks->add_hook( 'action', 'wp_enqueue_scripts',  '\apii\actions::core_styles');

$core_hooks->add_hook( 'action', 'init', array( '\apii\actions::app_shortcodes', 10, 0, true));
$core_hooks->add_hook( 'action', 'init', array(  '\apii\actions::localisation', 10, 0 ) );

$core_hooks->add_hook( 'action', 'after_setup_theme', array(  '\apii\actions::update_theme_files', 10, 0 ) );


//usuwanie wersji dołączanej do nazwy pliku
$core_hooks->add_hook( 'filter', array('style_loader_src', 'script_loader_src'), array(  '\apii\filters::remove_verion_suffix', 9999, 1 ) );
//defer
$core_hooks->add_hook( 'filter', array('script_loader_tag' ), array(  '\apii\filters::add_defer_attribute', 10, 2 ) );
$core_hooks->add_hook( 'filter', array('clean_url' ), array(  '\apii\admin_actions::ikreativ_async_scripts', 11, 1) );

//ajax
//$core_hooks->add_hook( 'action', 'wp_ajax_nopriv_', array('actions::', 10, 0, true));
//$core_hooks->add_hook( 'action', 'wp_ajax_', array('actions::', 10, 0, true));






$core_hooks->apply_hooks();

 ?>