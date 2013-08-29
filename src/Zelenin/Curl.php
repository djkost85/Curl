<?php

/**
 * A simple curl wrapper
 *
 * @package Curl
 * @author  Aleksandr Zelenin <aleksandr@zelenin.me>
 * @link    https://github.com/zelenin/Curl
 * @license MIT
 * @version 0.4.2
 */

namespace Zelenin;

class Curl
{
	const VERSION = '0.4.2';
	private $_request;
	private $_user_agent;
	private $_timeout = 30;

	public function __construct()
	{
		if ( !function_exists( 'curl_init' ) ) return false;
		return $this->setUserAgent( 'Curl ' . self::VERSION . ' (https://github.com/zelenin/curl)' );
	}

	public function setUserAgent( $user_agent )
	{
		$this->_user_agent = $user_agent;
		return $this;
	}

	public function setTimeout( $timeout )
	{
		$this->_timeout = $timeout;
		return $this;
	}

	public function get( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->_request( $url, $data, $method = 'get', $headers, $cookie );
	}

	public function post( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->_request( $url, $data, $method = 'post', $headers, $cookie );
	}

	public function delete( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->_request( $url, $data, $method = 'delete', $headers, $cookie );
	}

	public function put( $url, $data = null, $headers = null, $cookie = null )
	{
		return $this->_request( $url, $data, $method = 'put', $headers, $cookie );
	}

	private function _request( $url, $data = null, $method = 'get', $headers = null, $cookie = null )
	{
		if ( !$url ) return false;

		if ( $method == 'get' && $data ) {
			$url = is_array( $data ) ? trim( $url, '/' ) . '?' . http_build_query( $data ) : trim( $url, '/' ) . '?' . $data;
		}
		if ( $method == 'delete' && $data ) {
			$url = is_array( $data ) ? trim( $url, '/' ) . '?' . http_build_query( $data ) : trim( $url, '/' ) . '?' . $data;
		}
		if ( $method == 'put' && $data ) {
			$url = is_array( $data ) ? trim( $url, '/' ) . '?' . http_build_query( $data ) : trim( $url, '/' ) . '?' . $data;
		}
		$this->_request = curl_init( $url );

		$options = array(
			CURLOPT_HEADER => true,
			CURLOPT_NOBODY => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => $this->_timeout,
			CURLOPT_CONNECTTIMEOUT => $this->_timeout,
			CURLOPT_USERAGENT => $this->_user_agent,
			CURLOPT_SSL_VERIFYPEER => false
		);

		if ( ( $method == 'post' ) && $data ) {
			$options[CURLOPT_POSTFIELDS] = is_array( $data ) ? http_build_query( $data ) : $data;
		}

		if ( $method == 'delete' ) {
			$options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
		}

		if ( $method == 'put' ) {
			$options[CURLOPT_PUT] = true;
		}

		if ( $headers ) {
			$options[CURLOPT_HTTPHEADER] = $headers;
		}

		if ( $cookie ) {
			$options[CURLOPT_COOKIEFILE] = $cookie;
			$options[CURLOPT_COOKIEJAR] = $cookie;
		}
		curl_setopt_array( $this->_request, $options );
		$result = curl_exec( $this->_request );

		if ( $result ) {
			$info = curl_getinfo( $this->_request );
			$response = $this->parseResponse( $result );
			$response['info'] = $info;
		} else {
			$response = array(
				'number' => curl_errno( $this->_request ),
				'error' => curl_error( $this->_request )
			);
		}
		curl_close( $this->_request );
		return $response;
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