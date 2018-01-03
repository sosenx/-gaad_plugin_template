<?php 
namespace plugins_main_namespace;




	add_action( 'rest_api_init', function () {
	  register_rest_route( 'plugins_main_namespace/v1', '/model', array(
	    'methods' => 'GET',
	    'callback' => 'plugins_main_namespace\rest::app_model',
	  ) );
	} );




	add_action( 'rest_api_init', function () {
	  register_rest_route( 'plugins_main_namespace/v1', '/test', array(
	    'methods' => 'GET',
	    'callback' => 'plugins_main_namespace\rest::rest_test_callback',
	  ) );
	} );



 ?>