<?php 
namespace kamadmin; 
/**
* Akcje administratora
*
* Klasa zawiera zestaw akcji wykorzystywanych przez UI po stronie administratora.
*
*/

class admin_actions{
  
  



  public function __constructor(){
    
    return $this;
  }




  public static function add_kam_user(){
    
    return $this;
  }


  /**
  * Dodawanie atrybutu defer do skryptu 
  *
  * Sprawdza czy na końcu adresu skryptu znajduje się `#defer` i jeżeli tak dodaje `defer="defer"` po atrybucie `src` taga script.
  *
  * @param string $url Adres URL skryptu
  * @return string 
  */
  public static function ikreativ_async_scripts( $url )
  {
    if ( strpos( $url, '#defer') === false )
      return $url;
    else if ( is_admin() )
      return str_replace( '#defer', '', $url );
    else return str_replace( '#defer', '', $url )."' defer='defer"; 
  }

  
  
  
  
  
  public function admin_styles(){
    
    wp_enqueue_style( 'font-awesomes-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, false);

    return $this;
  }
  
    
  public function admin_scripts(){
    
    
    wp_enqueue_script( 'tether-js', GAAD_KAM_ADMIN_URL . '/node_modules/tether/dist/js/tether.min.js', false, false, null );
    wp_enqueue_script( 'vue-js', 'https://unpkg.com/vue@2.4.2/dist/vue.js', false, false, null );
    wp_enqueue_script( 'vue-router-js', 'https://unpkg.com/vue-router/dist/vue-router.js', array( 'vue-js' ), false, null );
    wp_enqueue_script( 'vue-x-js', 'https://unpkg.com/vuex', array( 'vue-js' ), false, null );
    wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js', array( 'tether-js', 'jquery' ), false, null );
    
    $mofile = GAAD_KAM_ADMIN_LANG_DIR .'/'. str_replace('\\', '', GAAD_KAM_ADMIN_NAMESPACE ) . '-' . \get_locale() .'.mo';
    if( is_file($mofile ) ){
      load_textdomain( str_replace('\\', '', GAAD_KAM_ADMIN_NAMESPACE ), $mofile);
    }
    
    return $this;
  }
  
  /**
  * Generowanie szablonów aplikacji
  *
  * Funkcja dołącza wszystkie pliki w katalogu GAAD_KAM_ADMIN_TEMPLATES_DIR/common oraz katalogu zdefiniowanym w stałej GAAD_KAM_ADMIN_ADMIN_TEMPLATES_DIR
  * Zdefiniowa wewnątrz funkcji tablica $dirs przechowuje ścieżki do katalogów z szablonami do dołączenia.
  *
  * @param array $additional_directories tablica ścieżek z dodadkowymi plikami szablonów
  *
  * @return void
  */
  public static function admin_templates( $additional_directories = null ){ 
    
    $dirs = array_merge( array(
      GAAD_KAM_ADMIN_TEMPLATES_DIR . '/common',
      GAAD_KAM_ADMIN_ADMIN_TEMPLATES_DIR
    ), is_array( $additional_directories ) ? $additional_directories : array() );

    foreach( $dirs as $dir ){
      if( is_dir( $dir ) ){
        $tpl_dir = opendir( $dir );    

        while ($f = readdir($tpl_dir) ){
          $id = array();
          preg_match('/(.*)[\.]{1}.*$/', $f, $id);
          $id = basename( $dir ) . '-' . $id[ 1 ]; 

          if( is_file( $template = $dir . '/'.$f ) ){
            ?><script type="template/javascript" id="<?php echo $id; ?>"><?php require_once( $template ); ?></script><?php
          }      
        }               
      }      
    }    
  }  
  
  
}



?>