<?php
class GekosaleClient
{

	public function __construct ($url, $apikey)
	{
		$this->url = $url;
		$this->key = $apikey;
		$this->id = 1;
	}

	public function __call ($method, $params)
	{
		
		if (is_array($params)){
			$params = array_values($params);
		}
		else{
			throw new Exception('Params must be given as array');
		}
		
		$request = array(
			'method' => $method,
			'params' => $params,
			'id' => $this->id,
			'key' => $this->key
		);
		$request = json_encode($request);
		$curl = curl_init($this->url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-type: application/json'
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, true);
		if ($response['id'] != $this->id){
			throw new Exception('Incorrect response id (request id: ' . $this->id . ', response id: ' . $response['id'] . ')');
		}
		if (! is_null($response['error'])){
			throw new Exception('Request error: ' . $response['error']);
		}
		return $response['result'];
	
	}
}