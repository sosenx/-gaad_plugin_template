<?php 
namespace plugins_main_namespace;
   
class wcm_actions {
  
  
  public static function localisation(){
    $languages_dir = dirname( dirname( plugin_basename(__FILE__))) .'/languages/';
    load_plugin_textdomain('gaad-mailer', false, $languages_dir );    
    return true;
  }
  
  /**
  * Copy a file, or recursively copy a folder and its contents
  * @author      Aidan Lister <aidan@php.net>
  * @version     1.0.1
  * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
  * @param       string   $source    Source path
  * @param       string   $dest      Destination path
  * @param       int      $permissions New folder creation permissions
  * @return      bool     Returns true on success, false on failure
  */
  public static function xcopy($source, $dest, $permissions = 0755) {
      // Check for symlinks
      if (is_link($source)) {
          return symlink(readlink($source), $dest);
      }

      // Simple copy for a file
      if (is_file($source)) {
          return copy($source, $dest);
      }

      // Make destination directory
      if (!is_dir($dest)) {
          mkdir($dest, $permissions);
      }

      if ( is_dir( $source ) ) {
            // Loop through the folder
            $dir = dir($source);
            while (false !== $entry = $dir->read()) {
                // Skip pointers
                if ($entry == '.' || $entry == '..') {
                    continue;
                }

                // Deep copy directories
                wcm_actions::xcopy("$source/$entry", "$dest/$entry", $permissions);
            }

            // Clean up
            $dir->close();
      } else return false;
      
      return true;
  }

  
  /*
  * Tworzy niezbędne pluginowi pliki i katalogi w bieżącym szablonie
  * Trzeba dopisac akcje zmiany parametru wcm_files_updated kiedy zmienia sie szablon z panelu!!!!!!!!!!!!!!!!
  */
  public static function update_theme_files( ){
   
    $wcm_files_updated = filter_var( get_option( 'wcm_files_updated', 'false' ), FILTER_VALIDATE_BOOLEAN);
    if( !$wcm_files_updated || GAAD_PLUGIN_TEMPLATE_FORCE_FILES_UPDATED ){
      if( wcm_actions::xcopy( GAAD_PLUGIN_TEMPLATE_THEME_FILES_DIR, get_template_directory() ) ){
        update_option( 'wcm_files_updated', 'true', '', 'yes' );
        
        return true;
      }
      
    }
    update_option( 'wcm_files_updated', 'false', '', 'yes' );  
    return false;
    
  }
    
  public static function login_user( $username = NULL, $userpwd = NULL ){
    if( $_POST ){
   
      $username = trim($_POST[ 'username' ]);
      $userpwd = trim($_POST[ 'userpwd' ]);
      $check = wp_authenticate_username_password( NULL, $username, $userpwd );

     /* if( $check instanceof WP_Error ){
        //obsługa błędów
        
        ?><script id="login-wp-error" type="application/javascript">
          var login_wp_error = <?php echo json_encode( WP_Error ); ?>;          
          </script><?php

        
      } else*/if( $check instanceof WP_User ){
        $creds = array();
        $creds['user_login'] = $username;
        $creds['user_password'] = $userpwd;
        $creds['remember'] = true;
        $user = wp_signon( $creds, false );
        wp_set_current_user($user->ID);
      }      
    }    
  }
  
  public static function put_templates( $dir ){ return true;
    global $post;
    $tpl_dir = opendir( $dir = str_replace( '\\', '/', $dir ) );
    $post_slug = $post->post_name; 

    while ( $f = readdir($tpl_dir) ){
      $id = array();
      preg_match('/(.*)[\.]{1}.*$/', $f, $id);
      $id = basename( $dir ) . '-' . empty( $id ) ? $f : $id[ 1 ];
     
      $template = $dir . '/'.$f;      
      if( is_file( $template ) ){
        $template_id = $post_slug . '-' . str_replace( '-php', '', sanitize_title( $id ) );
        ?><script type="template/javascript" id="<?php echo $template_id; ?>"><?php require_once( $template ); ?></script><?php
      }
      
    }
  }
  
  /**
  * Generates javascript/template for common components
  */
  public static function app_templates(){
   global $post;
   $post_slug = $post->post_name; 
   
   //common components templates
   wcm_actions::put_templates( GAAD_PLUGIN_TEMPLATE_APP_TEMPLATES_DIR );
   //app templates
   wcm_actions::put_templates( GAAD_PLUGIN_TEMPLATE_APP_TEMPLATES_DIR . '/' . $post_slug );
  }
  
  
  public static function common_styles(){
    global $post;
    $post_slug = is_object( $post) ? $post->post_name : false ;

    //COMMON
    wp_enqueue_style( 'jqueryui-css', GAAD_PLUGIN_TEMPLATE_URL . '/js/jquery-ui-1.12.1.custom/jquery-ui.min.css', false, false);

    if(  GAAD_PLUGIN_TEMPLATE_ENV === 'DEV' ){      
      wp_enqueue_style( 'gkredytslider-css', GAAD_PLUGIN_TEMPLATE_URL . '/css/app.css', false, false);
    }
    
    if(  GAAD_PLUGIN_TEMPLATE_ENV === 'DIST' ){
      wp_enqueue_style( 'gkredytslider-css', GAAD_PLUGIN_TEMPLATE_URL . '/dist/css/app.min.css', false, false);
    }
     
    
  }
  
  public static function app_shortcodes(){
      add_shortcode( 'kredytslider', GAAD_PLUGIN_TEMPLATE_NAMESPACE . 'wcm_shortcodes::kredytslider' );
  }

  public static function app_scripts(){
    global $post;
    $post_slug = is_object( $post) ? $post->post_name : false ;
    
    wp_enqueue_script( 'jqueryui-js', GAAD_PLUGIN_TEMPLATE_URL . '/js/jquery-ui-1.12.1.custom/jquery-ui.min.js', array( 'jquery' ), false, true );    
    wp_enqueue_script( 'font-awesome-js', 'https://use.fontawesome.com/c93a35a2e5.js', array( ), false, true );    
     // wp_enqueue_script( 'jquery', GAAD_PLUGIN_TEMPLATE_URL . '/dist/js/app.min.js', array( 'jquery' ), false, true );  

    if(  GAAD_PLUGIN_TEMPLATE_ENV === 'DEV' ){
   
      }
    
    if(  GAAD_PLUGIN_TEMPLATE_ENV === 'DIST' ){
      
      wp_enqueue_script( __NAMESPACE__ . '-app-dist-js', GAAD_PLUGIN_TEMPLATE_URL . '/dist/js/app.min.js', array( 'jquery' ), false, true );    

    } 
    
    add_action('wp_head', '\\' . __NAMESPACE__ . '\wcm_actions::app_templates', 9 );
  }
  
  public static function common_scripts(){
     
  //   wp_enqueue_script( 'gkredytslider-app-js', GAAD_PLUGIN_TEMPLATE_URL . '/js/gkredytslider-app.js', array( 'vue-js' ), false, true );
  }
  
  public static function test(){
    echo "test ok";
    die();
  }
  
  /*
  * Skrypty główne wczytywane na każdej posdtronie
  */
  public static function core_scripts(){
    
    wp_enqueue_script( 'tether-js', GAAD_PLUGIN_TEMPLATE_URL . '/node_modules/tether/dist/js/tether.min.js', false, false, null );
    wp_enqueue_script( 'vue-js', 'https://unpkg.com/vue@2.4.2/dist/vue.js', false, false, null );
    wp_enqueue_script( 'vue-router-js', 'https://unpkg.com/vue-router/dist/vue-router.js', array( 'vue-js' ), false, null );
    wp_enqueue_script( 'vue-x-js', 'https://unpkg.com/vuex', array( 'vue-js' ), false, null );
    wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js', array( 'tether-js', 'jquery' ), false, null );
   // wp_enqueue_script( 'jquery-ui-js', GAAD_PLUGIN_TEMPLATE_URL . '/vendor/jquery-ui-1.12.1.custom/jquery-ui.js#defer', array( 'jquery' ), false, null );
   // wp_enqueue_script( 'jquery-scroll-to-js', GAAD_PLUGIN_TEMPLATE_URL . '/vendor/jquery.scrollTo/jquery.scrollTo.min.js#defer', array( 'jquery' ), false, null );
    
    //wp_enqueue_script( 'tinymce-js', GAAD_PLUGIN_TEMPLATE_URL . '/vendor/tinymce/tinymce.min.js?apiKey=13pwfa6nx81qfgrzdpr67td5atfu38w6mv5dfh81tja7wl69', false, false, null );
    
    
    
  }
    
  
  /*
  * Style główne wczytywane na każdej posdtronie
  */
  public static function core_styles(){
    
    wp_enqueue_style( 'bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css', false, false);
    wp_enqueue_style( 'tether-css', GAAD_PLUGIN_TEMPLATE_URL . '/node_modules/tether/dist/css/tether.min.css', false, false);
    //wp_enqueue_style( 'fonts-css', GAAD_PLUGIN_TEMPLATE_URL . '/css/fonts.css', false, false);
    
    
    
    
  }
  
  
  
  public function _constructor(){
    
    return $this;
  }
  
  
}



?>