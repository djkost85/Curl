<?php

require_once 'vendor/autoload.php';

$curl = new \Zelenin\Curl;

$user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/18.0';
$data = array(
	'key_one' => 'value_one',
	'key_two' => 'value_two'
);
$cookies = array(
	'cookie_name_1: cookie_value_1',
	'cookie_name_2: cookie_value_2'
);
$headers = array(
	'header_name_1: header_value_1',
	'header_name_2: header_value_2'
);

$response = $curl
	->setUserAgent( $user_agent )
	->setTimeout( 10 )
	->setCookieFile( dirname( __FILE__ ) . '/cookie.txt' )
	->post( 'http://home.zelenin.me/Curl/test.php', $data, $headers, $cookies );

print_r( $response );