<?php 
namespace kamadmin;



class gaad_kam_admin{


	function __construct( ){


	}

	public static function add_caps(){
      global $wp_roles;

      if ( ! class_exists( '\WP_Roles' ) ) {
        return;
      }

      if ( ! isset( $wp_roles ) ) {
        $wp_roles = new \WP_Roles();
      }

      $capabilities = gaad_kam_admin::set_admin_caps();


      foreach ( $capabilities as $cap_group ) {
        foreach ( $cap_group as $cap ) {
          //$wp_roles->add_cap( 'cam_user', $cap );
          $wp_roles->add_cap( 'administrator', $cap );
        }
      }
    }

    public static function set_admin_caps() {
      $capabilities = array();

      $capability_types = array( 'camera' );

      foreach ( $capability_types as $capability_type ) {

        $capabilities[ $capability_type ] = array(
          // Post type
          "edit_{$capability_type}",
          "read_{$capability_type}",
          "delete_{$capability_type}",
          "edit_{$capability_type}s",
          "edit_others_{$capability_type}s",
          "publish_{$capability_type}s",
          "read_private_{$capability_type}s",
          "delete_{$capability_type}s",
          "delete_private_{$capability_type}s",
          "delete_published_{$capability_type}s",
          "delete_others_{$capability_type}s",
          "edit_private_{$capability_type}s",
          "edit_published_{$capability_type}s",

          // Terms
          // "manage_{$capability_type}_terms",
          // "edit_{$capability_type}_terms",
          // "delete_{$capability_type}_terms",
          // "assign_{$capability_type}_terms",

        );
      }

      return $capabilities;
    }

	/**
	 * Adds custom post types
	 *  - camera
	 */
	public static function add_post_types(){

		

		\register_post_type( 'camera',
		  \apply_filters( 'wpse64458_callb_post_type_camera',
		    array(
		      'labels'              => array(
		        'name'                  => __( 'Cameras', 'kamadmin' ),
		        'singular_name'         => __( 'Camera', 'kamadmin' ),
		        'all_items'             => __( 'All Cameras', 'kamadmin' ),
		        'menu_name'             => _x( 'Cameras', 'Admin menu name', 'kamadmin' ),
		        'add_new'               => __( 'Add New', 'kamadmin' ),
		        'add_new_item'          => __( 'Add new camera', 'kamadmin' ),
		        'edit'                  => __( 'Edit', 'kamadmin' ),
		        'edit_item'             => __( 'Edit camera', 'kamadmin' ),
		        'new_item'              => __( 'New camera', 'kamadmin' ),
		        'view'                  => __( 'View camera', 'kamadmin' ),
		        'view_item'             => __( 'View camera', 'kamadmin' ),
		        'search_items'          => __( 'Search cameras', 'kamadmin' ),
		        'not_found'             => __( 'No cameras found', 'kamadmin' ),
		        'not_found_in_trash'    => __( 'No cameras found in trash', 'kamadmin' ),
		        'parent'                => __( 'Parent camera', 'kamadmin' ),
		        'featured_image'        => __( 'Camera image', 'kamadmin' ),
		        'set_featured_image'    => __( 'Set camera image', 'kamadmin' ),
		        'remove_featured_image' => __( 'Remove camera image', 'kamadmin' ),
		        'use_featured_image'    => __( 'Use as camera image', 'kamadmin' ),
		        'insert_into_item'      => __( 'Insert into camera', 'kamadmin' ),
		        'uploaded_to_this_item' => __( 'Uploaded to this camera', 'kamadmin' ),
		        'filter_items_list'     => __( 'Filter cameras', 'kamadmin' ),
		        'items_list_navigation' => __( 'Cameras navigation', 'kamadmin' ),
		        'items_list'            => __( 'Cameras list', 'kamadmin' ),
		      ),
		      'public'              => true,
		      'show_ui'             => true,
		      'capability_type'     => array('page','camera'),
		      'map_meta_cap'        => true,
		      'menu_icon'          => 'dashicons-groups',
		      'publicly_queryable'  => true,
		      'exclude_from_search' => false,
		      'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
		      'rewrite'            => array( 'slug' => 'camera' ),
		      'query_var'           => true,
		      'supports'            => array( 'title' ),
		      'has_archive'         => 'cameras',
		      'show_in_nav_menus'   => true,
		      'show_in_rest'        => true,
		    )
		  )
		);
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
				'name' => '<a href="'.  \admin_url() .'post.php?post=11&action=edit">'. $cam_name.'</a>', 
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
			<div class="settings__line">
            	<label for="camera-url"><?php echo \__( 'Camera URL', 'kamadmin' ); ?></label>
            	<input name="camera-url" type="text" value="<?php echo $url; ?>">
			</div>

			<div class="settings__line">
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



	public static function filter_cameras( $query ){
		if(!is_admin()) return;

		$user = \wp_get_current_user( );
		$is_cam_user = $user->has_cap( 'cam_user' );
		if( $is_cam_user ){


			$query->set('post_type', 'camera');
			$query->set('meta_key', 'camera-user');
        	$query->set('meta_value', $user->ID);
		}

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
		        'read'         => false,  // true allows this capability
		        'read_cameras'         => false,  // true allows this capability
		        'read_camera'         => false,  // true allows this capability
		        'edit_posts'   => true,
		    )
		);

	}



}


 ?>