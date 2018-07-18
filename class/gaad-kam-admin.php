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

      $capabilities = gaad_kam_admin::set_kamuser_caps();
      foreach ( $capabilities as $cap_group ) {
        foreach ( $cap_group as $cap ) {
         $wp_roles->add_cap( 'cam_admin', $cap );
         $wp_roles->add_cap( 'cam_user', $cap );
        }
      }




    }


public static function manage_camera_posts_custom_column( $column_name, $post_id ){

	 if ( $column_name == 'login_btn'){
		$url = get_post_meta( $post_id, "camera-url", true );
        $elink = get_edit_post_link( $post_id);



        $hash = md5( $post_id );
        $logged_in = \kamadmin\gaad_kam_admin::getLooggedInUsers( $hash );
        $logged_in_count = $logged_in ? count( $logged_in ) : 0;
         $logged_in_count_max = get_post_meta( $post_id, 'max_looged_in')[0];
       // var_dump( $logged_in ,       $logged_in_count>=$logged_in_count_max );
         $disabled = $logged_in_count>=$logged_in_count_max ? " " : "";

		echo "<a class=\"button button-primary button-large kam-login {$disabled}\" href=\"{$elink}\" 
data-rest=\"" . get_rest_url() . __NAMESPACE__ .'/v1/kl/?p='. $post_id . '&u=' . get_current_user_id() . "\"
target=\"_new\">Zaloguj</a>";

	}
}

public static function cameraStreamMODAL__TOOMANY( ){
    ob_start();
    ?>
        <div id="camera-stream-modal-overlay" class="" >
            <div id="camera-stream-modal" data-params="<?php echo base64_encode( json_encode( array() ) ) ?>">
                <div class="close-modal">x</div>
               <?php
               $v_post = get_posts( array( 'post_type' => 'info',
                   'post_title' => "Too many conn" ) );
              if (is_array($v_post) && !empty( $v_post )){
                  $err_post = $v_post[0];
                  echo \do_shortcode( $err_post->post_content );
              }

               ?>
            </div>
        </div>

    <?php

    $ext_styles = "#camera-stream-modal {display: block}";

    self::MODAL_STYLE( $ext_styles );
    self::MODAL_SCRIPT( );
    $buf = ob_get_contents();
    ob_end_clean();
    return $buf;
}
public static function cameraStreamMODAL( $post_id, $user_id ){
	    ob_start();
            $user                    = get_post_meta( $post_id, 'remote-user', true );
            $pass                    = get_post_meta( $post_id, 'remote-pass', true );
            $url                     = get_post_meta( $post_id, 'camera-url', true );
            $url2                    = get_post_meta( $post_id, 'camera-url2', true );
            $max_looged_in_time      = get_post_meta( $post_id, 'max_looged_in_time', true );
            $max_looged_in_time = !$max_looged_in_time ? 600 : $max_looged_in_time;
            //var_dump( $user . ":" . $pass . '@' . $url . '?' . $url2 );
?>
            <div id="camera-stream-modal-overlay" class="" >
                <div id="camera-stream-modal"
                     data-params="<?php echo base64_encode( json_encode(
                             array(
                                     'url' => get_rest_url() . __NAMESPACE__ .'/v1/klo/?p=' . $post_id . '&' . 'u=' . $user_id ) ) ) ?>">
                    <div class="close-modal">x</div>

                    <?php
                    $url = str_replace( 'http://', '', $url)
                    ?>
                    <video controls autoplay name="media" width="800">
                        <source src="<?php echo "http://" . $url . '/' . $url2; ?>" type="video/mp4">

                    </video>
                </div>
            </div>

    <?php
        self::MODAL_STYLE( );
        self::MODAL_SCRIPT( );

	    $buf = ob_get_contents();
	    ob_end_clean();

	    return $buf;
	}

    public static function MODAL_STYLE( $ext = NULL  ){
	    ?>
            <style>
                #camera-stream-modal-overlay{
                    position: absolute;
                    top:0;
                    left:0;
                    width: 100vw;
                    height: 100vh;
                    z-index: 100000000;
                    background: rgba( 0,0,0, .45);

                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                #camera-stream-modal{
                    position: absolute;
                    z-index: 100000001;
                    background: white;
                    padding: 10px;
                    min-width: 530px;
                    max-width: 70vw;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    top: 20vh;
                }
                .close-modal{
                    width: 20px;
                    height: 20px;
                    border-radius: 100px;
                    position: absolute;
                    top:-20px;
                    right:-20px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-color: white;
                    line-height: 0;
                    font-weight: bold;
                    font-size: 1rem;
                    padding: 0.7rem;
                    cursor: pointer;
                }
                <?php echo $ext; ?>
            </style>
    <?php
}
    public static function MODAL_SCRIPT( $ext = NULL  ){
	    ?>
        <script>
        logOutUser = function(){
            jQuery.ajax( {
                url : "<?php echo get_rest_url() . __NAMESPACE__ .'/v1/klo/?p=' . $post_id . '&' . 'u=' .$user_id; ?>"
            } ).then( function( data ){
                window.location.reload();
            } );
        }
        setTimeout( logOutUser, <?php echo $max_looged_in_time * 1000?>);



        jQuery(window).on('mouseover', (function () {
            window.onbeforeunload = null;
        }));
        jQuery(window).on('mouseout', (function () {
            window.onbeforeunload = ConfirmLeave;
        }));
        function ConfirmLeave( e ) {
            jQuery.ajax( {
                url : "http://localhost/kamadmin/wp-json/kamadmin/v1/klo/?p=<?php echo $post_id; ?>&u=<?php echo $user_id; ?>"
            } )
            jQuery( '#camera-stream-modal-overlay' ).remove();
            jQuery( '.kam-login[href*="post=<?php echo $post_id; ?>"]' ).removeClass( 'disabled' );

            return "";
        }
        var prevKey="";
        jQuery(document).keydown(function (e) {
            if (e.key=="F5") {
                window.onbeforeunload = ConfirmLeave;
            }
            else if (e.key.toUpperCase() == "W" && prevKey == "CONTROL") {
                window.onbeforeunload = ConfirmLeave;
            }
            else if (e.key.toUpperCase() == "R" && prevKey == "CONTROL") {
                window.onbeforeunload = ConfirmLeave;
            }
            else if (e.key.toUpperCase() == "F4" && (prevKey == "ALT" || prevKey == "CONTROL")) {
                window.onbeforeunload = ConfirmLeave;
            }
            prevKey = e.key.toUpperCase();
        });
    </script>

    <?php
}

public static function manage_camera_posts_columns( $columns ){
    if( is_array( $columns ) )
        $columns['login_btn'] = __( 'Akcja' );
        unset($columns['date']);

    return $columns;

}

    public static function disable_admin_bar_nodes() {
 		global $wp_admin_bar;

 		if ( \current_user_can( 'cam_user' ) || \current_user_can( 'cam_admin' ) ) {
 			//var_dump($wp_admin_bar);
			$wp_admin_bar->remove_node('updates');
			$wp_admin_bar->remove_node('new-content');
			$wp_admin_bar->remove_node('site-name');
		}
    }


    public static function admin_footer_styles(){

		$cur_admin = \wp_get_current_user();
    	global $post;
    	$camera = $post->post_type === "camera";
    	if ( \current_user_can( 'cam_user' ) ) {
    			echo '<style type="text/css">
        	#favorite-actions, .add-new-h2, .tablenav { display:none; }
    		#adminmenu a[href*="post-new.php?post_type=camera"]	 { display:none !important; }
    		.subsubsub, .search-box, .row-actions	 { display:none !important; }
    		#posts-filter input[type="checkbox"] { display:none !important; }
	
	    		#your-profile .user-admin-color-wrap { display:none !important; }
	    		#your-profile .user-admin-bar-front-wrap { display:none !important; }
	    		#your-profile .user-language-wrap { display:none !important; }
	    		#your-profile .user-first-name-wrap { display:none !important; }
	    		#your-profile .user-last-name-wrap { display:none !important; }
	    		#your-profile .user-nickname-wrap { display:none !important; }
	    		#your-profile .user-display-name-wrap { display:none !important; }
	    		#your-profile .user-url-wrap { display:none !important; }
	    		#your-profile .user-description-wrap { display:none !important; }
	    		#your-profile .user-profile-picture { display:none !important; }
	    		#your-profile .user-sessions-wrap { display:none !important; }
	    		
				#your-profile h2:nth-of-type(1){ display:none !important; }
				#your-profile h2:nth-of-type(2){ display:none !important; }
				#your-profile h2:nth-of-type(3){ display:none !important; }
				#your-profile h2:nth-of-type(4){ display:none !important; }
				#your-profile h2:nth-of-type(5){ display:none !important; }
    			#postbox-container-1 { display:none !important; }
        </style>';
    	}

    	if ( \current_user_can( 'cam_admin' ) ) {
    			echo '<style type="text/css">

			    		#your-profile .user-admin-color-wrap { display:none !important; }
			    		#your-profile .user-admin-bar-front-wrap { display:none !important; }
			    		#your-profile .user-language-wrap { display:none !important; }
			    		#your-profile .user-first-name-wrap { display:none !important; }
			    		#your-profile .user-last-name-wrap { display:none !important; }
			    		#your-profile .user-nickname-wrap { display:none !important; }
			    		#your-profile .user-display-name-wrap { display:none !important; }
			    		#your-profile .user-url-wrap { display:none !important; }
			    		#your-profile .user-description-wrap { display:none !important; }
			    		#your-profile .user-profile-picture { display:none !important; }
			    		#your-profile .user-sessions-wrap { display:none !important; }
			    		
						#your-profile h2:nth-of-type(1){ display:none !important; }
						#your-profile h2:nth-of-type(2){ display:none !important; }
						#your-profile h2:nth-of-type(3){ display:none !important; }
						#your-profile h2:nth-of-type(4){ display:none !important; }
						#your-profile h2:nth-of-type(5){ display:none !important; }
		    			
    				#createuser #role option { display:none !important; }
    				#createuser #role option[value="cam_user"] { display:unset !important; }

					#createuser .form-table .form-field:not( .form-required ) { display:none !important; }

		        </style>';


		        echo '<script type="application/javascript"> 
		        		jQuery( "#role option[selected]" ).removeAttr("selected");
		        		jQuery( "#role option[value=\"cam_user\"]" ).attr("selected", "selected");


		        		</script>';
    	}

    }


    public static function disable_admin_menu_items() {
	    if ( current_user_can( 'cam_user' ) || current_user_can( 'cam_admin' ) ) {
	     	global $submenu;

	    	remove_menu_page('index.php');
	    	remove_menu_page('upload.php');
	    	remove_menu_page('themes.php');
	    	remove_menu_page('plugins.php');
	    	remove_menu_page('options-general.php');
	    	remove_menu_page('import.php');


	    //	var_dump($submenu);
	   }

    }

    public static function set_kamuser_caps() {
      $capabilities = array();

      $capability_types = array( 'camera' );

      foreach ( $capability_types as $capability_type ) {

        $capabilities[ $capability_type ] = array(
          // Post type
          "read_{$capability_type}",
          "edit_{$capability_type}",
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

        );
      }

      return $capabilities;
    }

	/**
	 * Adds custom post types
	 *  - camera
	 */
	public static function add_post_types(){

        register_taxonomy_for_object_type( 'category', 'info' );

        \register_post_type( 'camera',
		  \apply_filters( 'wpse64458_callb_post_type_camera',
		    array(
		      'labels'              => array(
		        'name'                  => \__( 'Cameras', 'kamadmin' ),
		        'singular_name'         => \__( 'Camera', 'kamadmin' ),
		        'all_items'             => \__( 'All Cameras', 'kamadmin' ),
		        'menu_name'             => \__( 'Cameras', 'kamadmin' ),
		        'add_new'               => \__( 'Add New', 'kamadmin' ),
		        'add_new_item'          => \__( 'Add new camera', 'kamadmin' ),
		        'edit'                  => \__( 'Edit', 'kamadmin' ),
		        'edit_item'             => \__( 'Edit camera', 'kamadmin' ),
		        'new_item'              => \__( 'New camera', 'kamadmin' ),
		        'view'                  => \__( 'View camera', 'kamadmin' ),
		        'view_item'             => \__( 'View camera', 'kamadmin' ),
		        'search_items'          => \__( 'Search cameras', 'kamadmin' ),
		        'not_found'             => \__( 'No cameras found', 'kamadmin' ),
		        'not_found_in_trash'    => \__( 'No cameras found in trash', 'kamadmin' ),
		        'parent'                => \__( 'Parent camera', 'kamadmin' ),
		        'featured_image'        => \__( 'Camera image', 'kamadmin' ),
		        'set_featured_image'    => \__( 'Set camera image', 'kamadmin' ),
		        'remove_featured_image' => \__( 'Remove camera image', 'kamadmin' ),
		        'use_featured_image'    => \__( 'Use as camera image', 'kamadmin' ),
		        'insert_into_item'      => \__( 'Insert into camera', 'kamadmin' ),
		        'uploaded_to_this_item' => \__( 'Uploaded to this camera', 'kamadmin' ),
		        'filter_items_list'     => \__( 'Filter cameras', 'kamadmin' ),
		        'items_list_navigation' => \__( 'Cameras navigation', 'kamadmin' ),
		        'items_list'            => \__( 'Cameras list', 'kamadmin' ),
		      ),
		      'public'              => true,
		      'show_ui'             => true,
		      'capability_type'     => array( 'pages', 'cameras','camera' ),
		      'map_meta_cap'        => true,
		      'menu_icon'          => 'dashicons-groups',
		      'publicly_queryable'  => true,
		      'exclude_from_search' => true,
		      'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
		      'rewrite'            => array( 'slug' => 'camera' ),
		      'query_var'           => true,
		      'supports'            => array( 'title' ),
		      'has_archive'         => false,
		      'show_in_nav_menus'   => true,
		      'show_in_rest'        => true,
		    )
		  )
		);

		\register_post_type( 'info',
		  \apply_filters( 'wpse64458_callb_post_type_info',
		    array(
		      'labels'              => array(
		        'name'                  => \__( 'Infos', 'kamadmin' ),
		        'singular_name'         => \__( 'Info', 'kamadmin' ),
		        'all_items'             => \__( 'All Infos', 'kamadmin' ),
		        'menu_name'             => \__( 'Infos', 'kamadmin' ),
		        'add_new'               => \__( 'Add New', 'kamadmin' ),
		        'add_new_item'          => \__( 'Add new info', 'kamadmin' ),
		        'edit'                  => \__( 'Edit', 'kamadmin' ),
		        'edit_item'             => \__( 'Edit info', 'kamadmin' ),
		        'new_item'              => \__( 'New info', 'kamadmin' ),
		        'view'                  => \__( 'View info', 'kamadmin' ),
		        'view_item'             => \__( 'View info', 'kamadmin' ),
		        'search_items'          => \__( 'Search infos', 'kamadmin' ),
		        'not_found'             => \__( 'No infos found', 'kamadmin' ),
		        'not_found_in_trash'    => \__( 'No infos found in trash', 'kamadmin' ),
		        'parent'                => \__( 'Parent info', 'kamadmin' ),
		        'featured_image'        => \__( 'Info image', 'kamadmin' ),
		        'set_featured_image'    => \__( 'Set info image', 'kamadmin' ),
		        'remove_featured_image' => \__( 'Remove info image', 'kamadmin' ),
		        'use_featured_image'    => \__( 'Use as info image', 'kamadmin' ),
		        'insert_into_item'      => \__( 'Insert into info', 'kamadmin' ),
		        'uploaded_to_this_item' => \__( 'Uploaded to this info', 'kamadmin' ),
		        'filter_items_list'     => \__( 'Filter infos', 'kamadmin' ),
		        'items_list_navigation' => \__( 'Infos navigation', 'kamadmin' ),
		        'items_list'            => \__( 'Infos list', 'kamadmin' ),
		      ),
		      'public'              => true,
		      'show_ui'             => true,
		      'capability_type'     => array( 'pages', 'infos','info' ),
		      'map_meta_cap'        => true,
		      'menu_icon'          => 'dashicons-groups',
		      'publicly_queryable'  => true,
		      'exclude_from_search' => true,
		      'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
		      'rewrite'            => array( 'slug' => 'info' ),
		      'query_var'           => true,
		      'supports'            => array( 'title', 'editor' ),
		      'has_archive'         => false,
		      'show_in_nav_menus'   => true,
		      'show_in_rest'        => true,
		    )
		  )
		);

        if ( ! get_option( 'kamadmin-info-posts-added' ) ){

            add_option( 'kamadmin-info-posts-added', 'set-'.time(), true );
            $v_post = get_posts( array( 'post_type' => 'info',
                'post_title' => "Too many conn" ) );

            if (is_array($v_post) && empty( $v_post )){

                $content = 'zbyt duzo polaczeń';

                wp_insert_post(array(
                    'post_type' => 'info',
                    'post_title' => "Too many conn",
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'comment_status' => 'closed',   // if you prefer
                    'ping_status' => 'closed',      // if you prefer
                ));
            }
            /**/
        }

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
		
		$meta_box_text_value = isset( $_POST[ $slug ] ) ? $_POST[ $slug ] : false;

		if (!$meta_box_text_value) {
			$val = array();

			foreach ($_POST as $key => $value) {
				$match = array();
				preg_match('/'.$slug.'-(.*)/', $key, $match);
				if ( $match) {
					$val[] =  $match[1];
				}

			}
			$meta_box_text_value = implode(',',$val );
			
		}

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


    	\kamadmin\gaad_kam_admin::save_meta_value( $post_id, 'assign-user' );
    	\kamadmin\gaad_kam_admin::save_meta_value( $post_id, 'camera-url' );
    	\kamadmin\gaad_kam_admin::save_meta_value( $post_id, 'camera-url2' );
    	\kamadmin\gaad_kam_admin::save_meta_value( $post_id, 'remote-user' );
    	\kamadmin\gaad_kam_admin::save_meta_value( $post_id, 'remote-pass' );
    	\kamadmin\gaad_kam_admin::save_meta_value( $post_id, 'max_looged_in' );
    	\kamadmin\gaad_kam_admin::save_meta_value( $post_id, 'max_looged_in_time' );
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

    public static function getLooggedInUsers( $hash ){
        return get_transient( $hash );
    }
    public static function setLooggedInUsers( $hash, $logged_in ){
        set_transient( $hash, $logged_in,0 );
    }


    public static function LooggInKamUser( $hash, $id ){
        $logged_in = \kamadmin\gaad_kam_admin::getLooggedInUsers( $hash );
        $logged_in = $logged_in ? $logged_in : array();
        $logged_in[] = $id;
        set_transient( $hash, $logged_in,0 );
    }
	
	
	/**
	 * Camera meta box HTML
	 * 
	 * @param  [type] $object [description]
	 * @return [type]         [description]
	 */
	public static function camera_build_meta_box( $object ){

	    global $post;
        $max_looged_in  = get_post_meta( $object->ID, 'max_looged_in',  true);
        $max_looged_in_time  = get_post_meta( $object->ID, 'max_looged_in_time',  true);
        $url            = get_post_meta( $object->ID, "camera-url",     true );
        $url2            = get_post_meta( $object->ID, "camera-url2",     true );

        $user            = get_post_meta( $object->ID, "remote-user",     true );
        $pass            = get_post_meta( $object->ID, "remote-pass",     true );


        $users = \kamadmin\gaad_kam_admin::get_camera_users();
        $current_user = explode( ',',  get_post_meta( $object->ID, "assign-user", true ));
        $hash = md5($post->ID);
        $cur_admin = wp_get_current_user();
        $kamuser = ! $cur_admin->has_cap('administrator') ? " disabled=\"disabled\" " : "";

        $logged_in = \kamadmin\gaad_kam_admin::getLooggedInUsers( $hash );
        $logged_in_count = $logged_in ? count( $logged_in ) : 0;

        $hash = md5( $object->ID );
        $logged_in = \kamadmin\gaad_kam_admin::getLooggedInUsers( $hash );

           // var_dump( $kamuser, $logged_in ,       $logged_in_count,  $cur_admin->ID );

		wp_nonce_field(basename(__FILE__), "camera-meta-box-nonce");
		?>

        <?php if( $cur_admin->has_cap('cam_admin') ) { ?>
            <div class="settings__line">
                <label for="max_looged_in"><?php echo \__( 'Max logged in users', 'kamadmin' ); ?></label>
                <input name="max_looged_in" type="text" value="<?php echo $max_looged_in; ?>">
            </div>

            <div class="settings__line">
                <label for="max_looged_in_time"><?php echo \__( 'Max logged in time [sec]', 'kamadmin' ); ?></label>
                <input name="max_looged_in_time" type="text" value="<?php echo $max_looged_in_time; ?>">
            </div>

            <div class="settings__line">
                <label for="camera-url"><?php echo \__( 'Camera URL', 'kamadmin' ); ?></label>
                <input name="camera-url" type="text" value="<?php echo $url; ?>">
            </div>

            <div class="settings__line">
                <label for="camera-url2"><?php echo \__( 'Camera URL Mp4 file', 'kamadmin' ); ?></label>
                <input name="camera-url2" type="text" value="<?php echo $url2; ?>">
            </div>


            <div class="settings__line">
                <label for="remote-user"><?php echo \__( 'Remote 1camera user', 'kamadmin' ); ?></label>
                <input name="remote-user" type="text" value="<?php echo $user; ?>">
            </div>

            <div class="settings__line">
                <label for="remote-pass"><?php echo \__( 'Remote camera password', 'kamadmin' ); ?></label>
                <input name="remote-pass" type="text" value="<?php echo $pass; ?>">
            </div>


            <div class="settings__line">
                <br> <label ><?php echo \__( 'Assigned user', 'kamadmin' ) ?></label>
                <br><br>
            <table border="1" cellpadding="6" cellspacing="0">
                <tr>
                    <td>Przypisz</td>
                    <td>Użytkownik</td>
                    <td>Zalogowany</td>
                </tr>
                <?php
                $max  = count( $users );


                for ( $i=0; $i < $max; $i++ ) {
                    $checked = in_array($users[ $i ][ 'ID' ], $current_user) ? " checked " : "";

                    ?>
                    <tr>
                        <td><input <?php echo $checked?> type="checkbox" style="width: 16px;min-width: 16px" name="assign-user-<?php echo $users[ $i ][ 'ID' ]; ?>"></td>
                        <td><label for="assign-user-<?php echo $users[ $i ][ 'ID' ]; ?>"><?php echo $users[ $i ][ 'ID' ] . ' # ' . $users[ $i ][ 'login' ]; ?></label></td>
                        <td><?php  echo in_array( $users[ $i ][ 'ID' ], is_array ( $logged_in ) ? $logged_in : array() ) ? "zalogowany" : ( $checked ? "nie" : "n/d" ) ?></td>
                    </tr> <?php
                }
                ?>

            </table>
                <strong>zalogowanych użytkowników: </strong><?php echo $logged_in_count ?>

            </div>




        <?php } ?>





		<?php 
	}



	public static function filter_cameras( $query ){
		if(!is_admin()) return;

		$user = \wp_get_current_user( );
		$is_cam_user = $user->has_cap( 'cam_user' );
		if( $is_cam_user ){
			$query->set('post_type', 'camera');
			$query->set('meta_key', 'assign-user');
			$query->set('meta_compare', 'REGEXP');

        	$query->set('meta_value', $user->ID);
		}

	}


	/**
	 * Adds custom users for managing cameras
	 * In case of further plugin devepment (may be handy)
	 */
	public static function add_user_types( ){
		//check if role exist before removing it
		if( get_role('cam_user') ){
		    remove_role( 'cam_user' );
		}

		$parent_role = get_role( 'editor' );
 	 	$capabilities = $parent_role->capabilities;

		add_role(
		    'cam_user',
		    __( 'Camera operator' ),
		   	array_merge( 
		   		$capabilities, 
			    array(
		        	'moderate_comments' => false,
					  'manage_categories' => false,
					  'manage_links' => false,
					  'upload_files' => false,
					  'unfiltered_html' => false,
					  'edit_posts' => false,
					  'edit_others_posts' => false,
					  'edit_published_posts' => false,
					  'publish_posts' => false,
					  'edit_pages' => false,
					  'read' => true,
					  'level_7' => true,
					  'level_6' => true,
					  'level_5' => true,
					  'level_4' => true,
					  'level_3' => true,
					  'level_2' => true,
					  'level_1' => true,
					  'level_0' => true,
					  'edit_others_pages' => false,
					  'edit_published_pages' => false,
					  'publish_pages' => false,
					  'delete_pages' => false,
					  'delete_others_pages' => true,
					  'delete_published_pages' => true,
					  'delete_posts' => false,
					  'delete_others_posts' => false,
					  'delete_published_posts' => false,
					  'delete_private_posts' => true,
					  'edit_private_posts' => true,
					  'read_private_posts' => true,
					  'delete_private_pages' => true,
					  'edit_private_pages' => true,
					  'read_private_pages' => true,
					  'read_camera' => false,
					  'read_private_camera' => false,
					  'edit_camera' => false,
					  'edit_others_camera' => true,
					  'edit_published_camera' => true,
					  'publish_camera' => false,
					  'delete_others_camera' => true,
					  'delete_private_camera' => true,
					  'delete_published_camera' => true,
					  'read_private_cameras' => true,
					  'edit_cameras' => true,
					  'edit_others_cameras' => true,
					  'publish_cameras' => false,
					  'edit_published_cameras' => true,
					  'delete_others_cameras' => false,
					  'delete_private_cameras' => false,
					  'delete_published_cameras' => false,
					  'create_camera' => false,
			    )
			)
		);


		if( get_role('cam_admin') ){
		    remove_role( 'cam_admin' );
		}

		$parent_role = get_role( 'administrator' );
 	 	$capabilities = $parent_role->capabilities;

		add_role(
		    'cam_admin',
		    __( 'Cameras users administrator' ),
		   	array_merge( 
		   		$capabilities, 
			    array(
		        	'moderate_comments' => false,
					  'manage_categories' => false,
					  'manage_links' => false,
					  'upload_files' => false,
					  'unfiltered_html' => false,
					  'edit_posts' => false,
					  'edit_others_posts' => false,
					  'edit_published_posts' => false,
					  'publish_posts' => false,
					  'edit_pages' => false,
					  'read' => true,
					  'level_7' => true,
					  'level_6' => true,
					  'level_5' => true,
					  'level_4' => true,
					  'level_3' => true,
					  'level_2' => true,
					  'level_1' => true,
					  'level_0' => true,
					  'edit_others_pages' => true,
					  'edit_published_pages' => false,
					  'publish_pages' => false,
					  'delete_pages' => false,
					  'delete_others_pages' => true,
					  'delete_published_pages' => true,
					  'delete_posts' => false,
					  'delete_others_posts' => false,
					  'delete_published_posts' => false,
					  'delete_private_posts' => true,
					  'edit_private_posts' => true,
					  'read_private_posts' => true,
					  'delete_private_pages' => true,
					  'edit_private_pages' => true,
					  'read_private_pages' => true,

                    'read_camera' => true,
                    'read_private_camera' => true,
                    'edit_camera' => true,
                    'edit_others_camera' => true,
                    'edit_published_camera' => true,
                    'publish_camera' => false,
                    'delete_others_camera' => true,
                    'delete_private_camera' => true,
                    'delete_published_camera' => true,
                    'read_private_cameras' => true,
                    'edit_cameras' => true,
                    'edit_others_cameras' => true,
                    'publish_cameras' => true,
                    'edit_published_cameras' => true,
                    'delete_others_cameras' => true,
                    'delete_private_cameras' => true,
                    'delete_published_cameras' => true,
                    'create_camera' => true,

                    'read_info' => true,
                    'read_private_info' => true,
                    'edit_info' => true,
                    'edit_others_info' => true,
                    'edit_published_info' => true,
                    'publish_info' => false,
                    'delete_others_info' => true,
                    'delete_private_info' => true,
                    'delete_published_info' => true,
                    'read_private_infos' => true,
                    'edit_infos' => true,
                    'edit_others_infos' => true,
                    'publish_infos' => true,
                    'edit_published_infos' => true,
                    'delete_others_infos' => true,
                    'delete_private_infos' => true,
                    'delete_published_infos' => true,
                    'create_info' => true,
			    )
			)
		);

	}

}


 ?>