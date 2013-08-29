# Curl

Very simple CURL wrapper for PHP ([http://php.net/curl](http://php.net/curl)). The class supports only GET and POST methods. Also it supports cookie and https.

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

or:

	$user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/18.0';
	$data = array(
		'key_one' => 'value_one',
		'key_two' => 'value_two'
	);
	$response = $curl->setUserAgent( $user_agent )->setTimeout( 10 )->get( 'http://example.com/', $data, $headers = null, $cookie = null );

POST request:

	$data = array(
		'key_one' => 'value_one',
		'key_two' => 'value_two'
	);
	$response = $curl->post( 'http://example.com/', $data );

or:

	$response = $curl->post( 'http://example.com/', 'key_one=value_one&key_two=value_two' );

DELETE request:

	$data = array(
		'key_one' => 'value_one',
		'key_two' => 'value_two'
	);
	$response = $curl->delete( 'http://example.com/', $data );

Headers and cookies:

	$headers = array(
		'HeaderName: HeaderValue',
		'HeaderName2: HeaderValue2'
	);
	$response = $curl->get( 'http://example.com/', null, $headers, dirname(__FILE__) . '/cookie.txt' );
	$response = $curl->post( 'http://example.com/', null, $headers, dirname(__FILE__) . '/cookie.txt' );


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

- Source hosted at [GitHub](https://github.com/zelenin/curl)
- Report issues, questions, feature requests on [GitHub Issues](https://github.com/zelenin/curl/issues)

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)