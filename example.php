<?php

echo '<pre>';

require_once 'lib/Zelenin/Curl.php';

$curl = new \Zelenin\Curl;

$user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/18.0';
$data = array(
	'key_one' => 'value_one',
	'key_two' => 'value_two'
);
$response = $curl->set_user_agent( $user_agent )->get( 'http://thatif.ru/', $data, $headers = null, $cookie = null );

print_r( $response['body'] );