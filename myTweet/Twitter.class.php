<?php

class Twitter {
	private $_user;
	private $_password;
	private $_curlHandler;
	private $_defaultFormat;
	
	const TWITTER_API_URL = "http://api.twitter.com/1/";
	
	public function __construct($user = null, $password = null, $format = "xml") {
		$this->_user = $user;
		$this->_password = $password;
		$this->_defaultFormat = $format;
		$this->_curlHandler = null;
	}
	
	public function usersShow($id) {
		$f = $this->_assignFormat($format);
		$url = "users/show/" . urlencode($id). "." . $f;
		$response = $this->doCall($url, $data);
		return $response;
	}
	
	public function getListMemberships($id, $data = null, $format = null) {
		$f = $this->_assignFormat($format);
		$url = urlencode($id)."lists/memberships." . $f;
		$response = $this->doCall($url, $data);
		return $response;
	}
	
	public function rateLimitStatus() {
		$f = $this->_assignFormat($format);
		$url = "account/rate_limit_status." . $f;
		$response = $this->doCall($url, $data);
		return $response;
	}

	public function login() {
		if(is_null($this->_curlHandler))
			$this->_init();
		curl_setopt($this->_curlHandler, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($this->_curlHandler, CURLOPT_USERPWD, $this->_getcUrlUserpwd());
		curl_setopt($this->_curlHandler, CURLOPT_RETURNTRANSFER, true);
	}
	
	private function doCall($url, $data) {
		$this->login();
		$data .= (is_null($data)) ? "" : "?";
		$url = self::TWITTER_API_URL . "$url".$data;
		curl_setopt($this->_curlHandler, CURLOPT_URL, $url);
		$response = curl_exec($this->_curlHandler);
		$headers = curl_getinfo($this->_curlHandler);
		$errorNumber = curl_errno($this->_curlHandler);
		$errorMessage = curl_error($this->_curlHandler);
		echo "$url\n";
		$this->_curlClose();
		return $response;
	}
	
	private function _init() {
		$this->_curlHandler = curl_init();
	}

	private function _curlClose() {
		curl_close($this->_curlHandler);
		$this->_curlHandler = null;
	}
	
	private function _getcUrlUserpwd() {
		return $this->_user . ":" . $this->_password;
	}
	
	private function _assignFormat($format) {
		return (is_null($format)) ? $this->_defaultFormat : $format;
	}
}