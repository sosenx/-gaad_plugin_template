<?php 
namespace plugins_main_namespace;



$core_hooks = new wcm_hooks_mng( 'core' ); 
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array(GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_actions::core_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array(GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_actions::common_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array(GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_actions::app_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'init', array(GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_actions::app_shortcodes', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_actions::core_styles');
$core_hooks->add_hook( 'action', 'init', array( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_actions::localisation', 10, 0 ) );
$core_hooks->add_hook( 'action', 'after_setup_theme', array( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_actions::update_theme_files', 10, 0 ) );
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_actions::common_styles');

//usuwanie wersji dołączanej do nazwy pliku
$core_hooks->add_hook( 'filter', array('style_loader_src', 'script_loader_src'), array( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_filters::remove_verion_suffix', 9999, 1 ) );
//defer
$core_hooks->add_hook( 'filter', array('script_loader_tag' ), array( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_filters::add_defer_attribute', 10, 2 ) );
$core_hooks->add_hook( 'filter', array('clean_url' ), array( GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_admin_actions::ikreativ_async_scripts', 11, 1) );

//ajax
//$core_hooks->add_hook( 'action', 'wp_ajax_nopriv_', array('wcm_actions::', 10, 0, true));
//$core_hooks->add_hook( 'action', 'wp_ajax_', array('wcm_actions::', 10, 0, true));
$core_hooks->apply_hooks();

 ?>