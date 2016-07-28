<?php namespace Geechs\KintoneRestApi;

use Geechs\KintoneRestApi\Request;
use Geechs\KintoneRestApi\Response;
use Config;

header("Content-Type: text/html; charset=UTF-8");

class KintoneRestApi{
	private $request;
	private $command = [
				'record'  => [
					'oneRecord'  => 'record',
					'allRecords' => 'records',
				],
				'file'   => 'file',
				'acl'	 => [
					'field' => 'field/acl',
					'record' => 'record/acl',
					'app'    => 'app/acl',
				],
				'appInfo'=> [
					'oneApp'  => 'app',
					'allApps' => 'apps',
				],
				'formInfo' => 'form',
				'apiList'  => 'apis',
			];

	public function __construct()
	{
		$request =  new Request(Config::get('kintone-rest-api.auth_default'),
						Config::get('kintone-rest-api.subdomain'));
		$this->request = $request;
	}

/*
|--------------------------------------------------------------------------
| CRUD
|--------------------------------------------------------------------------
*/

	public function getById($appID, $id)
	{
		$command = $this->command['record']['oneRecord'];

		$request = $this->request->get(['app' => $appID, 'id' => $id], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function getByQuery($appID, $query_options = NULL, $fields = NULL)
	{
		$command = $this->command['record']['allRecords'];

		$query = '';
		foreach ($query_options as $key) {
			$query .= $key.' ';
		}

		$request = $this->request->get(['app' => $appID, 'query' => $query, 'fields' => $fields], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function getAll($appID)
	{
		$command = $this->command['record']['allRecords'];

		$request = $this->request->get(['app' => $appID], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function createOne($appID, $post_record)
	{
		$record  = ['app' => $appID, 'record' => $post_record];
		$command = $this->command['record']['oneRecord'];

		$request = $this->request->post(['postArgs' => $record], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function createAll($appID, $post_records)
	{
		$records = ['app' => $appID, 'records' => $post_records];
		$command = $this->command['record']['allRecords'];

		$request = $this->request->post(['postArgs' => $records], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function updateOne($appID, $put_record)
	{
		$record  = ['app' => $appID, 'id' => $put_record['id'], 'record' => ''];

		unset($put_record['id']);
		$record['record']  = $put_record;

		$command = $this->command['record']['oneRecord'];

		$request = $this->request->put(['putArgs' => $record], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function updateAll($appID, $put_records)
	{
		$records = ['app' => $appID, 'id' => '', 'records' => ''];

		foreach ($put_records as $idx => $put_record) {
			$records['records'][$idx]['id'] = $put_record['id'];
			unset($put_record['id']);
			$records['records'][$idx]['record'] = $put_record;
		}

		$command = $this->command['record']['allRecords'];

		$request = $this->request->put(['putArgs' => $records], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function delete($appID, $ids)
	{
		$command = $this->command['record']['allRecords'];

		$request = $this->request->delete(['app' => $appID, 'ids' => $ids], 'records');

		$response = new Response($request);
		return $response->getResponse();
	}

	public function download($file_key, $filename)
	{
		$command = $this->command['file'];

		$res = $this->request->download(['fileKey' => $file_key], $filename, $command);
		return $res;
	}

	/*
	* fileKey を返す 
	*/
	public function upload($file_path)
	{
		$command = $this->command['file'];

		$request = $this->request->upload($file_path, $command);

		$response = new Response($request);
		return $response->getResponse();
	}
/*
|--------------------------------------------------------------------------
| Acl
|--------------------------------------------------------------------------
*/
	public function changeFieldAcl($appID, $put_records)
	{
		$records  = ['id' => $appID, 'rights' => $put_records];
		$command = $this->command['acl']['field'];

		$request = $this->request->put(['putArgs' => $records], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function changeRecordAcl($appID, $put_records)
	{
		$records  = ['id' => $appID, 'rights' => [$put_records]];
		$command = $this->command['acl']['record'];

		$request = $this->request->put(['putArgs' => $records], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function changeAppAcl($appID, $put_records)
	{
		$records  = ['app' => $appID, 'rights' => $put_records];
		$command = $this->command['acl']['app'];

		$request = $this->request->put(['putArgs' => $records], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

/*
|--------------------------------------------------------------------------
| Utility
|--------------------------------------------------------------------------
*/
	public function getAppInfo($appID = null)
	{
		$command = $appID != null ?
			$this->command['appInfo']['oneApp'] : $this->command['appInfo']['allApps'];

		$request = $this->request->get(['id' => $appID], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function getFormInfo($appID)
	{
		$command = $this->command['formInfo'];

		$request = $this->request->get(['app' => $appID], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

	public function getApiList()
	{
		$command = $this->command['apiList'];

		$request = $this->request->get([], $command);

		$response = new Response($request);
		return $response->getResponse();
	}

}
