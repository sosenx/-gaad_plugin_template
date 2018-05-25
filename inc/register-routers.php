<?php 
namespace kamadmin;




	add_action( 'rest_api_init', function () {
	  register_rest_route( 'kamadmin/v1', '/model', array(
	    'methods' => 'GET',
	    'callback' => 'kamadmin\rest::app_model',
	  ) );
	} );




	add_action( 'rest_api_init', function () {
	  register_rest_route( 'kamadmin/v1', '/test', array(
	    'methods' => 'GET',
	    'callback' => 'kamadmin\rest::rest_test_callback',
	  ) );
	} );



 ?>