<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $projectId = 'fullstack-350901';
    private $datasetId = 'fullstack';
    private $tableId = 'users';
    private $BigQuery;
    private $BigQueryTable;

    public function __construct()
    {
        $this->BigQuery = new BigQueryClient([
            'projectId' => $this->projectId
        ]);
        $dataset = $this->BigQuery->dataset($this->datasetId);
        $this->BigQueryTable = $dataset->table($this->tableId);
    }
    /**
     * Create a new controller instance.
     * 
     * curl -X POST -d password=55679997111666 -d email=fpproducoes@gmail.com https://dockerphp-bnfkznq3aq-uc.a.run.app/auth
     *
     * @return api_token or error code
     */
    public function auth(Request $request)
    {
        $fields = $request->all();
        $email = isset($fields['email']) ? $fields['email'] : "";
        $password = isset($fields['password']) ? $fields['password'] : "";

        if ($email != "" && $password != "") {
            $pass = md5($password);
            $table = $this->projectId . "." . $this->datasetId . "." . $this->tableId;
            $query = "SELECT api_token FROM `$table` WHERE email = '$email' AND password = '$pass' LIMIT 1";

            $jobConfig = $this->BigQuery->query($query);
            $queryResults = $this->BigQuery->runQuery($jobConfig);

            if ($queryResults->isComplete()) {
                $i = 0;
                $rows = $queryResults->rows();
                foreach ($rows as $row) {
                    $i++;
                }
                if ($i > 0) {
                    if ($row['api_token'] == "") {
                        return Str::random(60);
                    } else {
                        return $row['api_token'];
                    }
                } else {
                    return response('', 401);
                }
            }
        } else {
            return response('', 400);
        }
    }


    /**
     * Create a new controller instance.
     * 
     * curl -X POST -d api_token=zsZPctnPhVZdLyMS0nTdBNqUw8eBSS6ia9VlHzTlnnkNevT5BXD6nic35IdF -d email=fpproducoes@gmail.com -d name=Felipe -d phone=5567999711166  http://localhost
     *
     * @return void
     */
    public function add(Request $request)
    {
        $fields = $request->all();

        return json_encode($fields);

        $api_token = isset($fields['api_token']) ? $fields['api_token'] : "";

        if ($api_token != "") {
            $name = isset($fields['name']) ? $fields['name'] : "";
            $email = isset($fields['email']) ? $fields['email'] : "";
            $phone = isset($fields['phone']) ? $fields['phone'] : "";
            if ($name != "" && $email != "" && $phone != "") {
                $table = $this->projectId . "." . $this->datasetId . "." . $this->tableId;
                $query = "SELECT name FROM `$table` WHERE api_token = '$api_token' LIMIT 1";

                $jobConfig = $this->BigQuery->query($query);
                $queryResults = $this->BigQuery->runQuery($jobConfig);

                if ($queryResults->isComplete()) {
                    $rows = $queryResults->rows();
                    $cont = iterator_count($rows);
                    if ($cont == 1) {
                        //Need data Validation
                        $data = ["name" => $name, "email" => $email, "password" => md5($phone), "phone" => $phone, "api_token" => null];

                        $insertResponse = $this->BigQueryTable->insertRows([["data" => $data]]);

                        return $insertResponse->isSuccessful();
                    } else {
                        return response('', 401);
                    }
                }
            } else {
                return response('', 400);
            }
        } else {
            return response('', 400);
        }
    }

    private function addFirst()
    {

        $data = ["name" => "Felipe", "email" => "fpproducoes@gmail.com", "password" => md5("5567999711166"), "phone" => "5567999711166", "api_token" => Str::random(60)];

        $insertResponse = $this->BigQueryTable->insertRows([["data" => $data]]);

        return $insertResponse->isSuccessful();
    }
}
