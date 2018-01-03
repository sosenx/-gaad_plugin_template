<?php 
namespace plugins_main_namespace;

/*
* 
*/
   
class json_data {
  
  private $json = '{}';
  private $tojson = array();
  
  public function __construct(){
    $this->tojson = $this->get();
    $this->getJson();
    return $this;
  }
  
  function update_json(){
    return $this->json = json_encode( $this->tojson);

  }

  function getJson(){
    return $this->update_json();

  }

  function draw( $return = false ){
    $string = 'var '. basename( constant( 'plugins_main_namespace\GAAD_PLUGIN_TEMPLATE_NAMESPACE' ) ) .'__app_model ='. $this->getJson() .';';
    if ( !$return ) {
      echo $string;
    }
    return $string;
  }
  
/*
ta fn pobiera wszystkie niezbedne aplikacji dane
*/
  function get(){       
    return json_decode( rest::app_model(), true );
  }
  
  
}



?>