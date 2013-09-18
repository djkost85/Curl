# Curl

CURL wrapper for PHP ([http://php.net/curl](http://php.net/curl)).

## Usage

	$curl = new \Zelenin\Curl;

Optionally you may set user-agent:

	$curl->setUserAgent( 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/18.0' );

Optional timeout:

	$curl->setTimeout( 10 );

GET request:

	$response = $curl->get( 'http://example.com/' );

or:

	$response = $curl->get( 'http://example.com/?key_one=value_one&key_two=value_two' );

POST request (with POST data, headers and cookies):

	$user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/18.0';
	$data = array(
    	'key_one' => 'value_one',
    	'key_two' => 'value_two'
    );
    $cookies = array(
    	'key_one: value_one',
    	'key_two: value_two'
    );
    $headers = array(
    	'key_one: value_one',
    	'key_two: value_two'
    );
	$response = $curl
    	->setUserAgent( $user_agent )
    	->setTimeout( 10 )
    	->setCookieFile( dirname( __FILE__ ) . '/cookie.txt' )
    	->post( 'http://home.zelenin.me/Curl/test.php', $data, $headers, $cookies );

or:

	$response = $curl
		->setUserAgent( $user_agent )
        ->setTimeout( 10 )
        ->setCookieFile( dirname( __FILE__ ) . '/cookie.txt' )
        ->post( 'http://home.zelenin.me/Curl/test.php', 'key_one=value_one&key_two=value_two', $headers, $cookies );

PUT request (same as DELETE, HEAD, CONNECT, OPTION, PATCH):

	$data = array(
		'key_one' => 'value_one',
		'key_two' => 'value_two'
	);
	$response = $curl->put( 'http://example.com/', $data );

## Response

On successfull request:

	Array(
		[header] => Array()
		[cookie] => Array()
		[body] =>
		[info] => Array()
	)

On error:

	Array(
		[number] =>
		[error] =>
	)

## Development

- Source hosted at [GitHub](https://github.com/zelenin/Curl)
- Report issues, questions, feature requests on [GitHub Issues](https://github.com/zelenin/Curl/issues)

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)