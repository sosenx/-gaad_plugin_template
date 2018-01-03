<?php 
namespace plugins_main_namespace;



$core_hooks = new hooks_mng( 'core' ); 
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array(GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'actions::core_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array(GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'actions::common_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'actions::common_styles');
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array(GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'actions::app_scripts', 10, 0, true));

$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'actions::core_styles');

$core_hooks->add_hook( 'action', 'init', array(GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'actions::app_shortcodes', 10, 0, true));
$core_hooks->add_hook( 'action', 'init', array( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'actions::localisation', 10, 0 ) );

$core_hooks->add_hook( 'action', 'after_setup_theme', array( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'actions::update_theme_files', 10, 0 ) );


//usuwanie wersji dołączanej do nazwy pliku
$core_hooks->add_hook( 'filter', array('style_loader_src', 'script_loader_src'), array( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'filters::remove_verion_suffix', 9999, 1 ) );
//defer
$core_hooks->add_hook( 'filter', array('script_loader_tag' ), array( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'filters::add_defer_attribute', 10, 2 ) );
$core_hooks->add_hook( 'filter', array('clean_url' ), array( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'admin_actions::ikreativ_async_scripts', 11, 1) );

//ajax
//$core_hooks->add_hook( 'action', 'wp_ajax_nopriv_', array('actions::', 10, 0, true));
//$core_hooks->add_hook( 'action', 'wp_ajax_', array('actions::', 10, 0, true));
$core_hooks->apply_hooks();

 ?>