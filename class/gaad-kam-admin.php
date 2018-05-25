<?php 
namespace kamadmin;



class gaad_kam_admin{


	function __construct( ){


	}


	/**
	 * Adds custom post types
	 *  - camera
	 */
	public static function add_post_types(){

		$labels = array(
			'name'                  => _x( 'Cameras', 'Post Type General Name', 'kamadmin' ),
			'singular_name'         => _x( 'Camera', 'Post Type Singular Name', 'kamadmin' ),
			'menu_name'             => __( 'Cameras', 'kamadmin' ),
			'name_admin_bar'        => __( 'Camera', 'kamadmin' ),
			'archives'              => __( 'Item Archives', 'kamadmin' ),
			'attributes'            => __( 'Item Attributes', 'kamadmin' ),
			'parent_item_colon'     => __( 'Parent Item:', 'kamadmin' ),
			'all_items'             => __( 'All Items', 'kamadmin' ),
			'add_new_item'          => __( 'Add New Item', 'kamadmin' ),
			'add_new'               => __( 'Add new camera', 'kamadmin' ),
			'new_item'              => __( 'New camera', 'kamadmin' ),
			'edit_item'             => __( 'Edit camera', 'kamadmin' ),
			'update_item'           => __( 'Update camera', 'kamadmin' ),
			'view_item'             => __( 'View camera', 'kamadmin' ),
			'view_items'            => __( 'View cameras', 'kamadmin' ),
			'search_items'          => __( 'Search cameras', 'kamadmin' ),
			'not_found'             => __( 'Not found', 'kamadmin' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'kamadmin' ),
			'featured_image'        => __( 'Featured Image', 'kamadmin' ),
			'set_featured_image'    => __( 'Set featured image', 'kamadmin' ),
			'remove_featured_image' => __( 'Remove featured image', 'kamadmin' ),
			'use_featured_image'    => __( 'Use as featured image', 'kamadmin' ),
			'insert_into_item'      => __( 'Insert into item', 'kamadmin' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'kamadmin' ),
			'items_list'            => __( 'Items list', 'kamadmin' ),
			'items_list_navigation' => __( 'Items list navigation', 'kamadmin' ),
			'filter_items_list'     => __( 'Filter items list', 'kamadmin' ),
		);
		$args = array(
			'label'                 => __( 'Camera', 'kamadmin' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 80,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => false,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rest_base'             => 'camera',
		);
		register_post_type( 'camera', $args );
	}

	/**
	 * Adds custom users for managing cameras
	 * In case of further plugin devepment (may be handy)
	 */
	public static function add_user_types( ){

		add_role(
		    'cam_user',
		    __( 'Operator kamery' ),
		    array(
		        'read'         => true,  // true allows this capability
		        'edit_posts'   => true,
		    )
		);

	}



}


 ?>