<?php

require_once 'class.curl.php';
$curl = new curl;

$curl->user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
$curl->cookie = dirname( __FILE__ ) . '/curl.cookie.txt';

$post_data = array(
	'key_one' => 'value_one',
	'key_two' => 'value_two'
);

echo '<pre>';
$response = $curl->request( 'http://example.com/', $post_data );

print_r( $response );

if ( $curl->error() ) {
	echo 'error';
} else {
	echo 'no error';
}

?>