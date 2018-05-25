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


		
//add_action( 'add_meta_boxes_food', 'camera_add_meta_boxes' );
	}


	public static function add_post_meta_box(){

		\add_meta_box( 'camera_meta_box', __( 'Camera settings', 'kamadmin' ), '\kamadmin\gaad_kam_admin::camera_build_meta_box', 'camera', 'advanced', 'high' );

	}

	/**
	 * Save meta value during post save. Check if value exists in POST array
	 * 
	 * @param  [type] $slug [description]
	 */
	public static function save_meta_value( $post_id, $slug ){
		
		$meta_box_text_value = isset( $_POST[ $slug ] ) ? $_POST[ $slug ] : ''; 
	    update_post_meta( $post_id, $slug, $meta_box_text_value );

	}



	/**
	 * Displays cameras list on user profile page
	 * 
	 * @param  [WP_User] $user_profile [description]
	 * @return [type]               [description]
	 */
	public static function user_profile_cameras_list( $user_profile ){
		$is_cam_user = $user_profile->has_cap( 'cam_user');
		$args = array(
			'post_type'  => 'camera',
			'orderby'    => 'meta_value_num',
			'meta_key'   => 'camera-user',
			'meta_value' => $user_profile->ID
		);
		$query = new \WP_Query( $args );
		$cameras = $query->get_posts();
		$_rows = array();

		$max  = count( $cameras );
		for ($i=0; $i < $max; $i++) {

			$camera_ID = $cameras[ $i ]->ID; 
			$cam_name = $cameras[ $i ]->post_title; 
			$cam_url = get_post_meta( $camera_ID, "camera-url", true );

			array_push( $_rows, array(
				'ID'   => $camera_ID, 
				'name' => $cam_name, 
				'url'  => $cam_url 
			) );
			
		}

		/*
		Cameras panel is displayed on cam_user type only
		 */
		if (!$is_cam_user) {
			return;
		}

		?>
			<h2><?php echo \__( "Assigned cameras ", 'kamadmin' ); ?></h2>
			<table class="cameras_list">
				<tr>
					<th>ID</th>
					<th><?php echo \__( 'Camera name', 'kamadmin') ?></th>
					<th><?php echo \__( 'URL', 'kamadmin') ?></th>
				</tr>
				<?php 

					$max  = count( $_rows );
					for ($i=0; $i < $max; $i++) {
						$row = $_rows[ $i ];
						echo "<tr class=\"cameras_list__line\"><td>" . implode( "</td><td>", $row ) . "</td></tr>";
					}
				?>

			</table>
		<?php 
	}

	/**
	 * [save_post_meta description]
	 * @param  [type] $post_id [description]
	 * @param  [type] $post    [description]
	 * @param  [type] $update  [description]
	 * @return [type]          [description]
	 */
	public static function save_post_meta( $post_id, $post, $update ){

		if (!isset( $_POST["camera-meta-box-nonce"] ) || !wp_verify_nonce( $_POST["camera-meta-box-nonce"], basename(__FILE__) ) )
	        return $post_id;

	    if( !current_user_can("edit_post", $post_id ) )
	        return $post_id;

		if( defined("DOING_AUTOSAVE" ) && DOING_AUTOSAVE )
        return $post_id;


    	\kamadmin\gaad_kam_admin::save_meta_value( $post_id, 'camera-user' );
    	\kamadmin\gaad_kam_admin::save_meta_value( $post_id, 'camera-url' );
	}

	/**
	 * Get camera users
	 * @return [type] [description]
	 */
	public static function get_camera_users(){

		$args = array(
			'role'         => 'cam_user',
			'orderby'      => 'login',
			'order'        => 'ASC',
			'fields'       => 'all'
		 ); 
		/**
		Parsed users array
		 */
		$users = array();
		$u_ = \get_users( $args );
		$max  = count( $u_);
		for ($i=0; $i < $max; $i++) { 
			array_push( $users, array(
				'ID' => $u_[ $i ]->data->ID, 
				'login' => $u_[ $i ]->data->user_login 
			) );
			
		}

		return $users;
	}

	/**
	 * Camera meta box HTML
	 * 
	 * @param  [type] $object [description]
	 * @return [type]         [description]
	 */
	public static function camera_build_meta_box( $object ){

		$url = get_post_meta( $object->ID, "camera-url", true );
		$users = \kamadmin\gaad_kam_admin::get_camera_users();
		$current_user = get_post_meta( $object->ID, "camera-user", true );

		wp_nonce_field(basename(__FILE__), "camera-meta-box-nonce");

		?>
			<div>
            	<label for="camera-url"><?php echo \__( 'Camera URL', 'kamadmin' ); ?></label>
            	<input name="camera-url" type="text" value="<?php echo $url; ?>">
			</div>

			<div>
				<label for="camera-user"><?php echo \__( 'Assigned user', 'kamadmin' ) ?></label>
            	<select name="camera-user">
            		<?php 
            			$max  = count( $users );
						for ( $i=0; $i < $max; $i++ ) {
							$selected = $current_user === $users[ $i ][ 'ID' ] ? " selected " : "";

							?>
							<option <?php echo $selected; ?> value="<?php echo $users[ $i ][ 'ID' ]; ?>"><?php echo $users[ $i ][ 'login' ]; ?></option>
							<?php 
						}
            		 ?>
            	</select>
			</div>
		<?php 
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