<?php
$login = 'live';
$password = 'pkpplksa';
$url = $_GET['ref'] . '/view.htm?mode=l';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
$result = curl_exec($ch);
curl_close($ch);
//var_dump($result );
header( "Content-type: text/html");


echo( $result);




    ?>