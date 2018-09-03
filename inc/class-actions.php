<?php 
namespace apii;
   
class actions {
  
  
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
                actions::xcopy("$source/$entry", "$dest/$entry", $permissions);
            }

            // Clean up
            $dir->close();
      } else return false;
      
      return true;
  }

  
  /*
  * Tworzy niezbędne pluginowi pliki i katalogi w bieżącym szablonie
  * Trzeba dopisac akcje zmiany parametru files_updated kiedy zmienia sie szablon z panelu!!!!!!!!!!!!!!!!
  */
  public static function update_theme_files( ){
   
    $files_updated = filter_var( get_option( 'files_updated', 'false' ), FILTER_VALIDATE_BOOLEAN);
    if( !$files_updated || APII_FORCE_FILES_UPDATED ){
      if( actions::xcopy( APII_THEME_FILES_DIR, get_template_directory() ) ){
        update_option( 'files_updated', 'true', '', 'yes' );
        
        return true;
      }
      
    }
    update_option( 'files_updated', 'false', '', 'yes' );  
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

  /*
  * Puts app templates as a html at the top
  */
  public static function put_templates( $dir ){ 
    global $post;
    $tpl_dir = opendir( $dir = str_replace( '\\', '/', $dir ) );
    $post_slug = $post->post_name; 

    while ( $f = readdir($tpl_dir) ){
      $id = array();
      preg_match('/(.*)[\.]{1}.*$/', $f, $id);
      $id = basename( $dir ) . '-' . empty( $id ) ? $f : $id[ 1 ];
     
      $template = $dir . '/'.$f;      
      if( is_file( $template ) && $f !== 'router.html' ){
        $template_id = 'template-' . basename(APII_NAMESPACE) . '-' . str_replace( '-php', '', sanitize_title( $id ) );
        ?><script type="template/javascript" id="<?php echo $template_id; ?>"><?php require_once( $template ); ?></script><?php
      }      
    }
  }

  /*
  * Puts app components as scripts at the top
  */
  public static function put_components( $dir ){ 
    global $post;
    $tpl_dir = opendir( $dir = str_replace( '\\', '/', $dir ) );
    $post_slug = $post->post_name; 

    while ( $f = readdir($tpl_dir) ){
      $id = array();
      preg_match('/(.*)[\.]{1}.*$/', $f, $id);
      $id = basename( $dir ) . '-' . empty( $id ) ? $f : $id[ 1 ];
     
      $component = $dir . '/'.$f;      
      if( is_file( $component ) ){
        $component = filters::dir_to_url( $component );
        $template_id = $post_slug . '-' . str_replace( '-php', '', sanitize_title( $id ) );
        ?><script type="application/javascript" src="<?php echo $component; ?>" id="<?php echo $template_id; ?>"></script><?php
      }      
    }
  }
  //


  /**
  * Generates javascript/template for common components
  */
  public static function app_components(){
   global $post; 
   if ( is_object( $post ) ) {
      $post_slug = $post->post_name; 
   
      //common components templates
      actions::put_components( APII_APP_COMPONENTS_DIR );

      if ( is_dir( APII_APP_COMPONENTS_DIR . '/' . $post_slug ) ) {
        //app templates
        actions::put_components( APII_APP_COMPONENTS_DIR . '/' . $post_slug );
      }
   }      
 }


  /**
  * Generates javascript/template for common components
  */
  public static function app_templates(){
   global $post;    
   
      if ( is_object( $post)) {
       $post_slug = $post->post_name; 
        //common components templates
         actions::put_templates(  APII_APP_TEMPLATES_DIR );
         
         if ( is_dir( APII_APP_TEMPLATES_DIR . '/' . $post_slug ) ) {
           actions::put_templates( APII_APP_TEMPLATES_DIR . '/' . $post_slug );
         }
      }
  }
  

  public static function app_data_src(){
    $json_data = new json_data();
    ?><script id="<?php echo basename(constant( 'apii\APII_NAMESPACE' )); ?>-json-data" type="application/javascript"><?php $json_data->draw(); ?></script><?php
  }
  
  public static function common_styles(){
    global $post;
    $post_slug = is_object( $post) ? $post->post_name : false ;

    if(  APII_ENV === 'DEV' ){
      wp_enqueue_style( basename(APII_NAMESPACE) . '-app-css', APII_URL . '/css/app.css', false, false);
    }
    
    if(  APII_ENV === 'DIST' ){
      wp_enqueue_style( basename(APII_NAMESPACE) . '-app-css', APII_URL . '/dist/css/app.min.css', false, false);
    }        
  }
  
  public static function app_shortcodes(){
    $namespace = basename( constant( 'apii\APII_NAMESPACE' ) );
    $shortcode = basename( constant( 'apii\APII_SHORTCODE' ) );
    if ( method_exists( $namespace . '\shortcodes', $shortcode ) ) {
      add_shortcode( $shortcode, $namespace . '\shortcodes::' . $shortcode );
    } 
    else {      
      add_shortcode( $shortcode, $namespace . '\shortcodes::no_main_shortcode_error' );
    }      
  }

//method_exists( $namespace . '\shortcodes', 'no_main_shortcode_error' )

  public static function app_scripts(){
    global $post;
    $post_slug = is_object( $post) ? $post->post_name : false ;
    

    wp_enqueue_script( 'font-awesome-js', 'https://use.fontawesome.com/c93a35a2e5.js', array( ), false, true );    
     // wp_enqueue_script( 'jquery', APII_URL . '/dist/js/app.min.js', array( 'jquery' ), false, true );

    if(  APII_ENV === 'DEV' ){
      add_action('wp_head', '\\' . APII_NAMESPACE . 'actions::app_components', 9 );
      wp_enqueue_script( __NAMESPACE__ . '-app-dev-js', APII_URL . '/js/app.js',
        array( 'vue-js', 'vue-router-js', 'bootstrap-vue-js' ),
         false, true );
      }
    
    if(  APII_ENV === 'DIST' ){
      
      wp_enqueue_script( __NAMESPACE__ . '-app-dist-js', APII_URL . '/dist/js/app.min.js', array( 'jquery', 'vue-js' ), false, true );

    } 
    
    add_action('wp_head', '\\' . __NAMESPACE__ . '\actions::app_templates', 9 );
    add_action('wp_head', '\\' . __NAMESPACE__ . '\actions::app_data_src', 8 );
  }
  
  public static function common_scripts(){
     
  //   wp_enqueue_script( 'gkredytslider-app-js', APII_URL . '/js/gkredytslider-app.js', array( 'vue-js' ), false, true );
  }
  
  public static function test(){
    echo "test ok";
    die();
  }
  
  /*
  * Skrypty główne wczytywane na każdej posdtronie
  */
  public static function core_scripts(){

    if ( APII_ENV === 'DIST') {
      $core = array(
        'modules-js' => array( APII_URL . '/dist/js/modules.min.js', array( 'vue-js' ), false, null ),
        'vue-js' => array( 'https://unpkg.com/vue@2.4.2/dist/vue.js', false, false, null  ),        
        'vue-router-js' => array( 'https://unpkg.com/vue-router/dist/vue-router.js', array( 'vue-js' ), false, null ),
        'vue-x-js' => array( 'https://unpkg.com/vuex', array( 'vue-js' ), false, null ),       
        'bootstrap-js' => array( 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js', array( 'modules-js', 'jquery' ), false, null )
      );
    }

    if ( APII_ENV === 'DEV') {
      /*
      * Add core scripts to equeue to core table
      * Table index is a slug. Order of args is the same as in wp_enqueue_script function.
      */
      $core = array(
       'tether-js' => array( APII_URL . '/node_modules/tether/dist/js/tether.min.js', false, false, null ),
       'vue-js' => array( 'https://unpkg.com/vue@2.4.2/dist/vue.js', false, false, null  ),        
       'vue-router-js' => array( 'https://unpkg.com/vue-router/dist/vue-router.js', array( 'vue-js' ), false, null ),
       'vue-x-js' => array( 'https://unpkg.com/vuex', array( 'vue-js' ), false, null ),       
       'bootstrap-js' => array( 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js', array( 'tether-js', 'jquery' ), false, null ),
       'bootstrap-vue-js' => array( APII_URL . '/node_modules/bootstrap-vue/dist/bootstrap-vue.min.js', array( 'vue-js' ), false, null )
       );

      /*
      * Force load core scripts from own serwer
      */
      if ( !APII_CORE_SCRIPTS_CDN_USE ) {
        $core[ 'vue-js' ][0] = APII_URL . '/node_modules/vue/dist/vue.min.js';
        $core[ 'vue-router-js' ][0] = APII_URL . '/node_modules/vue-router/dist/vue-router.min.js';
        $core[ 'vue-x-js' ][0] = APII_URL . '/node_modules/vuex/dist/vuex.min.js';
        $core[ 'bootstrap-js' ][0] = APII_URL . '/node_modules/bootstrap/dist/js/bootstrap.min.js';
      }       
    }  


    
    foreach ($core as $lib => $data) {
      //if ( !wp_script_is( $lib ) ) {
        wp_enqueue_script( $lib, $data[0], $data[1], $data[2], $data[3] );
     // }      
    }      
  }
    
  
  /*
  * Style główne wczytywane na każdej posdtronie
  */
  public static function core_styles(){

    if ( APII_ENV === 'DEV' ) {

       $core = array(
           'bootstrap-css' => array( 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css', false, false ),
           basename( APII_NAMESPACE ) . '-modules-min-css' => array( APII_URL . '/css/modules.min.css', false, false ),
         'app-css' => array( APII_URL . '/css/app.css', false, false )
       );

       $components = glob( APII_DIR . '/css/components/*.css' );
       if ( !empty( $components ) ) {
         foreach ( $components as $file ) {
           $core[ str_replace( '.', '-', basename( $file ) ) ] = array(filters::dir_to_url( $file ), false, false );
         }
       }
       
    }

    if ( APII_ENV === 'DIST' ) {
      /*
      * Add styles to equeue to core table
      * Table index is a slug. Order of args is the same as in wp_enqueue_style function.
      */
       $core = array(
         'tether-css' => array( APII_URL . '/node_modules/tether/dist/css/tether.min.css', false, false ),
         'bootstrap-css' => array( 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css', false, false ),      
         'bootstrap-vue-css' => array( '//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.css', false, false )
       );

      /*
      * Force load core scripts from own serwer
      */
      if ( APII_CORE_SCRIPTS_CDN_USE ) {
         $core[ 'bootstrap-css' ][0] = APII_URL . '/node_modules/bootstrap/dist/css/bootstrap.min.css';
         $core[ 'bootstrap-vue-css' ][0] = APII_URL . '/node_modules/bootstrap-vue/dist/bootstrap-vue.min.css';

      }      

    }

  

    foreach ($core as $lib => $data) {
      //if ( !wp_style_is( $lib ) ) {
        var_dump($data);
        wp_enqueue_style( $lib, $data[0], $data[1], $data[2] );
      //}      
    }    
    
  }
  
  
  
  public function __construct(){
    
    return $this;
  }
  
  
}



?>