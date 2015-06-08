<?php namespace Geechs\KintoneRestApi;

class Response {

	private $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	private function isSuccess()
	{
		return $this->request->getStatusCode() == 200;
	}

	public function getResponse()
	{
		$response = [];
		if ( $this->isSuccess() ) {
			$response['status'] = ['code' => 0, 'message' => 'Success'];
			$response['data']   = $this->request->getData();
		} else {
			$response['status'] = ['code' => -1, 'message' => $this->request->getData()['message']];
			$response['data']   = null;
		}

		return $response;
	}

}