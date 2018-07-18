<?php 
namespace kamadmin;


if ( \is_admin() ) {
	//default settings page
	admin_actions::admin_styles();
	admin_actions::admin_scripts();

}


$core_hooks = new hooks_mng( 'core' ); 
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array(GAAD_KAM_ADMIN_NAMESPACE . 'actions::core_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array(GAAD_KAM_ADMIN_NAMESPACE . 'actions::common_scripts', 10, 0, true));
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', GAAD_KAM_ADMIN_NAMESPACE . 'actions::common_styles');
$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', array(GAAD_KAM_ADMIN_NAMESPACE . 'actions::app_scripts', 10, 0, true));

$core_hooks->add_hook( 'action', 'wp_enqueue_scripts', GAAD_KAM_ADMIN_NAMESPACE . 'actions::core_styles');

$core_hooks->add_hook( 'action', 'init', array(GAAD_KAM_ADMIN_NAMESPACE . 'actions::app_shortcodes', 10, 0, true));
$core_hooks->add_hook( 'action', 'init', array( GAAD_KAM_ADMIN_NAMESPACE . 'actions::localisation', 10, 0 ) );

$core_hooks->add_hook( 'action', 'after_setup_theme', array( GAAD_KAM_ADMIN_NAMESPACE . 'actions::update_theme_files', 10, 0 ) );


//usuwanie wersji dołączanej do nazwy pliku
$core_hooks->add_hook( 'filter', array('style_loader_src', 'script_loader_src'), array( GAAD_KAM_ADMIN_NAMESPACE . 'filters::remove_verion_suffix', 9999, 1 ) );
//defer
$core_hooks->add_hook( 'filter', array('script_loader_tag' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'filters::add_defer_attribute', 10, 2 ) );
$core_hooks->add_hook( 'filter', array('clean_url' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'admin_actions::ikreativ_async_scripts', 11, 1) );

//ajax
//$core_hooks->add_hook( 'action', 'wp_ajax_nopriv_', array('actions::', 10, 0, true));
//$core_hooks->add_hook( 'action', 'wp_ajax_', array('actions::', 10, 0, true));


$kamadmin = new gaad_kam_admin();
$core_hooks->add_hook( 'action', array('init' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::add_user_types', 11, 1) );
$core_hooks->add_hook( 'action', array('init' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::add_post_types', 5, 1) );
$core_hooks->add_hook( 'action', array('init' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::add_caps', 5, 1) );
$core_hooks->add_hook( 'action', array('pre_get_posts' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::filter_cameras', 5, 1) );


$core_hooks->add_hook( 'action', array('add_meta_boxes' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::add_post_meta_box', 10, 1) );
$core_hooks->add_hook( 'action', array('save_post' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::save_post_meta', 10, 3) );

$core_hooks->add_hook( 'action', array('show_user_profile' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::user_profile_cameras_list', 10, 1) );
$core_hooks->add_hook( 'action', array('edit_user_profile' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::user_profile_cameras_list', 10, 1) );


$core_hooks->add_hook( 'action', array('admin_menu' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::disable_admin_menu_items', 999, 0) );
$core_hooks->add_hook( 'action', array('admin_bar_menu' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::disable_admin_bar_nodes', 999) );
$core_hooks->add_hook( 'action', array('admin_footer' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::admin_footer_styles', 10, 0) );

$core_hooks->add_hook( 'action', array('manage_camera_posts_columns' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::manage_camera_posts_columns', 10, 1) );
$core_hooks->add_hook( 'action', array('manage_camera_posts_custom_column' ), array( GAAD_KAM_ADMIN_NAMESPACE . 'gaad_kam_admin::manage_camera_posts_custom_column', 10, 2) );



$core_hooks->apply_hooks();

 ?>