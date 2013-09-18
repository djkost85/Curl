<?php

/**
 * A simple curl wrapper
 *
 * @package Curl
 * @author  Aleksandr Zelenin <aleksandr@zelenin.me>
 * @link    https://github.com/zelenin/Curl
 * @license MIT
 * @version 0.5.0
 */

namespace Zelenin;

class Curl
{
	const VERSION = '0.5.0';

	const GET = 'GET';
	const POST = 'POST';
	const PUT = 'PUT';
	const DELETE = 'DELETE';
	const HEAD = 'HEAD';
	const CONNECT = 'CONNECT';
	const OPTION = 'OPTION';
	const PATCH = 'PATCH';

	private $request;
	private $user_agent;
	private $timeout;
	private $cookie_file = null;

	public function __construct()
	{
		$this->setUserAgent( 'Curl ' . self::VERSION . ' (https://github.com/zelenin/Curl)' );
		$this->setTimeout( 30 );
	}

	public function setUserAgent( $user_agent )
	{
		$this->user_agent = $user_agent;
		return $this;
	}

	public function setTimeout( $timeout )
	{
		$this->timeout = $timeout;
		return $this;
	}

	public function setCookieFile( $cookie_file )
	{
		$this->cookie_file = $cookie_file;
		return $this;
	}

	public function get( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->request( $url, $data, $method = self::GET, $headers, $cookie );
	}

	public function post( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->request( $url, $data, $method = self::POST, $headers, $cookie );
	}

	public function put( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->request( $url, $data, $method = self::PUT, $headers, $cookie );
	}

	public function delete( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->request( $url, $data, $method = self::DELETE, $headers, $cookie );
	}

	public function head( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->request( $url, $data, $method = self::HEAD, $headers, $cookie );
	}

	public function connect( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->request( $url, $data, $method = self::CONNECT, $headers, $cookie );
	}

	public function option( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->request( $url, $data, $method = self::OPTION, $headers, $cookie );
	}

	public function patch( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->request( $url, $data, $method = self::PATCH, $headers, $cookie );
	}

	private function request( $url, $data = null, $method, $headers = null, $cookie = null )
	{
		if ( $method == self::GET && $data ) {
			$url = trim( $url, '/' ) . '?';
			$url .= is_array( $data ) ? http_build_query( $data ) : $data;
		}

		$this->request = curl_init( $url );

		$options = array(
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_HEADER => true,
			CURLOPT_NOBODY => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => $this->timeout,
			CURLOPT_CONNECTTIMEOUT => $this->timeout,
			CURLOPT_USERAGENT => $this->user_agent,
			CURLOPT_SSL_VERIFYPEER => false
		);

		if ( $data && $method != self::GET ) {
			$options[CURLOPT_POSTFIELDS] = is_array( $data ) ? http_build_query( $data ) : $data;
		}

		if ( $headers ) {
			$options[CURLOPT_HTTPHEADER] = $headers;
		}

		if ( $this->cookie_file ) {
			$options[CURLOPT_COOKIEFILE] = $this->cookie_file;
			$options[CURLOPT_COOKIEJAR] = $this->cookie_file;
		}

		if ( $cookie ) {
			$option[CURLOPT_COOKIE] = $this->prepareCookie( $cookie );
		}

		curl_setopt_array( $this->request, $options );
		$result = curl_exec( $this->request );

		if ( $result ) {
			$info = curl_getinfo( $this->request );
			$response = $this->parseResponse( $result );
			$response['info'] = $info;
		} else {
			$response = array(
				'number' => curl_errno( $this->request ),
				'error' => curl_error( $this->request )
			);
		}
		curl_close( $this->request );
		return $response;
	}

	private function prepareCookie( $cookies )
	{
		return implode( '; ', $cookies );
	}

	private function parseResponse( $response )
	{
		$response_parts = explode( "\r\n\r\n", $response, 2 );
		$response = array();
		$cookie = array();

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