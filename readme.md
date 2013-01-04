# curl

Very simple CURL wrapper for PHP ([http://php.net/curl](http://php.net/curl)). The class supports only GET and POST methods. Also it supports cookie and https.

## Usage

	require_once 'class.curl.php';
	$curl = new curl;

Optionally you may set user-agent and cookie file path:

	$curl->user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
	$curl->cookie = dirname( __FILE__ ) . '/curl.cookie.txt';

You must set a POST data for POST request:

	$post_data = array(
		'key_one' => 'value_one',
		'key_two' => 'value_two'
	);

GET request:

	$response = $curl->request( 'http://example.com/' );

POST request:

	$response = $curl->request( 'http://example.com/', $post_data );


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