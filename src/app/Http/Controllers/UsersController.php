<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $projectId = 'devopsplusit';
    private $datasetId = 'plusit';
    private $tableId = 'users';
    private $serviceAccountPath = '../resources/apiKey/devopsplusit-c1db2f36cf0f.json';
    private $BigQuery;
    private $BigQueryTable;
    
    public function __construct()
    {
        $this->BigQuery = new BigQueryClient([
            'projectId' => $this->projectId, 'keyFilePath' => $this->serviceAccountPath
        ]);
        $dataset = $this->BigQuery->dataset($this->datasetId);
        $this->BigQueryTable = $dataset->table($this->tableId);
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function auth(Request $request)
    {
        $fields = $request->all();
        $email = $fields['email'];
        $pass = md5($fields['password']);
        $query = "SELECT api_token FROM `devopsplusit.plusit.users` WHERE email = '$email' AND password = '$pass' LIMIT 1";

        $jobConfig = $this->BigQuery->query($query);
        $queryResults = $this->BigQuery->runQuery($jobConfig);

        if($queryResults->isComplete()){
            $i=0;
            $rows = $queryResults->rows();
            foreach($rows as $row){
                $i++;
            }
            if($i>0){
                if($row['api_token']==""){
                    return Str::random(60);
                }else{
                    return $row['api_token'];
                }
            }else{ 
                return response('',401);
            }
        }

        return "No Found rows";
    }

    public function add()
    {
        
        $data = ["name"=>"Felipe","email"=>"fpproducoes@gmail.com","password"=>md5("5567999711166"),"phone"=>"5567999711166","api_token"=>Str::random(60)];

        $insertResponse = $this->BigQueryTable->insertRows([["data"=>$data]]);

        return $insertResponse->isSuccessful();
    }
}
