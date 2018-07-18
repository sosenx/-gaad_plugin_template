<?php 
namespace kamadmin;


class rest{



    public static function kamera_lout( $data ){
        $r = array( 'plugin_name' => "kamadmin", 'handler' => "kamera_lout", 'status' => 200 );
        $logged_in = \kamadmin\gaad_kam_admin::getLooggedInUsers( md5( $_GET[ 'p'] ) );
        $_logged_in = array_diff( $logged_in, array( $_GET['u'] ) );
        \kamadmin\gaad_kam_admin::setLooggedInUsers( md5( $_GET[ 'p'] ), $_logged_in );

        return json_encode( $r );
    }


    public static function kamera_login( $data ){

        $r = array( 'plugin_name' => "kamadmin", 'handler' => "app_model", 'status' => 200 );

        $hash = md5( $_GET['p'] );


        $logged_in = \kamadmin\gaad_kam_admin::getLooggedInUsers( $hash );

        $logged_in_count = $logged_in ? count( $logged_in ) : 0;

        $user_id = $_GET['u'];
        $logged_in_count_max = get_post_meta( $_GET['p'], 'max_looged_in')[0];
        $r['logged_in '] = $logged_in ;
        $r['logic' ] = $logged_in_count;
        $r['logim' ] = $logged_in_count_max;
       // header("Content-type: text/html");

        if( $logged_in_count < (int)$logged_in_count_max ){

            $r['logic1' ] = get_current_user_id();
            if( !in_array( $user_id, is_array( $logged_in ) ? $logged_in : array() ) ) \kamadmin\gaad_kam_admin::LooggInKamUser( $hash, $user_id );
            $r['html'] = \kamadmin\gaad_kam_admin::cameraStreamMODAL( $_GET['p'], $user_id );
        } else {
            $r['html'] = \kamadmin\gaad_kam_admin::cameraStreamMODAL__TOOMANY( );
        }
        if( in_array( $user_id, is_array( $logged_in ) ? $logged_in : array() ) )
            $r['html'] = \kamadmin\gaad_kam_admin::cameraStreamMODAL( $_GET['p'], $user_id );


        return json_encode( $r );
    }


	public static function app_model( $data = NULL ){
        $r = array( 'plugin_name' => "kamadmin", 'handler' => "app_model", 'status' => 200 );
        return json_encode( $r );
	}

    public static function rest_test_callback( $data = NULL ){
        $r = array( 'plugin_name' => "kamadmin\\rest::rest_test_callback" );
        return json_encode( $r );
    }

    public static function kamera( $data = NULL ){
        $r = array( 'plugin_name' => "kamadmin\\rest::kamera" );

        header("Content-Type: text/html");
?>

        <video controls autoplay name="media">
            <source src="http://live:pkpplksa@79.162.234.236:4479/video.mp4?line=1&inst=2&rec=0&rnd=13928" type="video/mp4">
        </video>
        <?php
    }
	
}
function my_stream_get_contents (  )
{$timeout_seconds = 0.5;
    $handle = fopen( 'http://live:pkpplksa@79.162.234.236:4479/video.mp4?line=1&inst=2&rec=0&rnd=13928', 'rb');
    $ret = "";
    // feof ALSO BLOCKS:
    // while(!feof($handle)){$ret.=stream_get_contents($handle,1);}
    while (true) {
        $starttime = microtime(true);
        $new = stream_get_contents($handle, 1);
        $endtime = microtime(true);
        if (is_string($new) && strlen($new) >= 1) {
            $ret .= $new;
        }
        $time_used = $endtime - $starttime;
        // var_dump('time_used:',$time_used);
        if (($time_used >= $timeout_seconds) || ! is_string($new) ||
            (is_string($new) && strlen($new) < 1)) {
            break;
        }
    }
    return $ret;
}
?>
