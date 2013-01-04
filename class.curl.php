<?php

/**
 * A simple curl wrapper
 * @package curl
 * @author Aleksandr Zelenin <aleksandr@zelenin.me>
 * @link https://github.com/zelenin/curl
 * @version 0.1
 * @license http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 */

if ( !class_exists( 'curl' ) ) :

class curl {

	const version = '0.1';
	public $error = false;
	protected $request;

	public $user_agent;
	public $cookie;

	public function __construct() {
		$this->user_agent = 'curl ' . self::version . ' (https://github.com/zelenin/curl)';
		$this->cookie = dirname( __FILE__ ) . '/cookie.txt';
	}

	public function request( $url, $post_data = false ) {

		$this->request = curl_init( $url );

		$options = array(
			CURLOPT_HEADER => true,
			CURLOPT_NOBODY => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT => $this->user_agent,
			CURLOPT_COOKIEFILE => $this->cookie,
			CURLOPT_COOKIEJAR => $this->cookie,
			CURLOPT_SSL_VERIFYPEER => false
		);

		if ( $post_data )
			$options[CURLOPT_POSTFIELDS] = is_array( $post_data ) ? http_build_query( $post_data ) : $post_data;

		curl_setopt_array( $this->request, $options );
		$result = curl_exec( $this->request );

		if ( $result ) {
			$this->error = false;
			$info = curl_getinfo( $this->request );
			$response = $this->parse_response( $result );
			$response['info'] = $info;
		} else {
			$this->error = true;
			$response = array(
				'number' => curl_errno( $this->request ),
				'error' => curl_error( $this->request )
			);
		}

		curl_close( $this->request );
		return $response;

	}

	public function error() {
		return $this->error;
	}

	private function parse_response( $response ) {

		$response_parts = explode( "\r\n\r\n", $response, 2 );
		$response = array();

		$response['header'] = explode( "\r\n", $response_parts[0] );

		if ( preg_match_all( '/Set-Cookie: (.*?)=(.*?)(\n|;)/i', $response_parts[0], $matches ) ) {
			if ( !empty( $matches ) ) {
				foreach ( $matches[1] as $key => $value ) {
					$cookie[] = $value . '=' . $matches[2][$key] . ';';
				}
				$response['cookie'] = $cookie;
			}
		}
		$response['body'] = $response_parts[1];
		return $response;

    }

}

endif;

?>