<?php namespace Geechs\KintoneRestApi;

use Config;

class Request {


    const VERSION = 'v1';
    const API_BASE_URL = 'https://{subdomain}.cybozu.com/k/{version}/{command}.json';

	private $hashed_user_pass = '';
	private $api_token = '';
	private $url = '';
	private $status_code = '';
	private $data = [];

	public function __construct($auth_default, $subdomain)
	{
		$url = str_replace('{subdomain}', $subdomain, self::API_BASE_URL);
		$url = str_replace('{version}', self::VERSION, $url);

		$this->setAuth($auth_default);
		$this->url = $url;
	}

	public function getStatusCode()
	{
		return $this->status_code;
	}

	public function getData()
	{
		return $this->data;
	}

	private function setAuth($auth_default)
	{
		if ($auth_default == 'user_pass_auth') {
			$user_pass = Config::get('kintone-rest-api.authentications.user_pass.user'). ':'.
							Config::get('kintone-rest-api.authentications.user_pass.pass');
			$this->hashed_user_pass = base64_encode($user_pass);
		} elseif ($auth_default == 'api_token_auth') {
			$this->api_token = Config::get('kintone-rest-api.authentications.api_token.api_token');
		}
	}

	private function buildHeaders($method)
	{
		$headers = [
			0 => 'X-Cybozu-Authorization:'. $this->hashed_user_pass,
			1 => 'X-Cybozu-API-Token:'. $this->api_token,
			2 => 'Content-Type:',
		];

		switch($method){
			case 'GET': case 'DELETE';
				return $headers;
				break;

			case 'POST': case 'PUT':
				$headers[2] = 'Content-Type: application/json';
				return $headers;
				break;

			case 'POST_FILE': 
				$headers[2] = 'multipart/form-data';
				return $headers;
				break;

			default: 
				return [];
				break;
		}
	}

	public function get($params = [], $command)
	{
		$url   = str_replace('{command}', $command, $this->url);
		$query = '?'. http_build_query($params);

		$curl_options = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER	   => $this->buildHeaders('GET'),
		];

		$this->setRequest($url. $query, $curl_options);

		return $this;
	}

	public function post($params = [], $command)
	{
		$url   = str_replace('{command}', $command, $this->url);
		$query = '';

		$curl_options = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER	   => $this->buildHeaders('POST'),
			CURLOPT_POST 	       => true,
			CURLOPT_POSTFIELDS     => json_encode($params['postArgs'])
		];

		$this->setRequest($url. $query, $curl_options);

		return $this;
	}

	public function put($params = [], $command)
	{
		$url   = str_replace('{command}', $command, $this->url);
		$query = '';

		$curl_options = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER	   => $this->buildHeaders('PUT'),
			CURLOPT_CUSTOMREQUEST  => 'PUT',
			CURLOPT_POSTFIELDS     => json_encode($params['putArgs'])
		];

		$this->setRequest($url. $query, $curl_options);

		return $this;
	}	

	public function delete($params = [], $command)
	{
		$url   = str_replace('{command}', $command, $this->url);
		$query = '?'. http_build_query($params);

		$curl_options = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER	   => $this->buildHeaders('DELETE'),
			CURLOPT_CUSTOMREQUEST  => 'DELETE',
		];

		$this->setRequest($url. $query, $curl_options);

		return $this;	
	}

	public function getFile($params = [], $command)
	{
	}

	public function postFile($params = [], $command)
	{
	}

	private function setRequest($url, $curl_options)
	{
		$ch = curl_init($url);
		curl_setopt_array($ch, $curl_options);
		$result = curl_exec($ch);
		$info   = curl_getinfo($ch);
		$error  = curl_error($ch);
		curl_close($ch);

		if ($error) throw new \Exception($error);

		$this->status_code = $info['http_code'];
		$this->data = json_decode($result, true);
	}

}