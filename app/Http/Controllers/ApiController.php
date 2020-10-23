<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\general\globalController as globalController;
use App\Models\ApiAuth;
use App\Models\Employees;
use DB;
use Request, View;
use Response;
use Session;
use Validator;
use File;
use DateTime;
use Log;
use Config;
use Redirect;
use Mail;
use Route;

class ApiController extends BaseController
{
	public function __construct()
    {
        $this->globalCtrl = new globalController();
        $this->employeeKeyPhrase = config('backend.EmpKey');
        $this->key = config('backend.extra');
        $this->now = date("Y-m-d H:i:s");
        $this->larkErrorUrl = 'https://open.larksuite.com/open-apis/bot/v2/hook/0b2d4457-61d9-41b3-a285-a10a67f6a460';
    }

    public function _larkErrorReport($data){
        $url = $this->larkErrorUrl;
        $data_unencode = array('title'=> 'VisitorLogSystem ', 'text'=> $data['text'] );
        $json = json_encode($data_unencode);

        $headers = array(
            "POST  HTTP/1.1",
            "Content-Type: application/json;"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $response = curl_exec($ch);

        if(curl_error($ch)){
            $this->_insertEventLogger('API', '_larkErrorReport', curl_error($ch),$url);
        }

        curl_close($ch);

        return $response;
    }

    public function _getDBInfo($type){
        if($type == 'h'){
            $string = config('backend.db_host');
        }else if($type == 'u'){
            $string = config('backend.db_username');
        }else if($type == 'd'){
            $string = config('backend.db_database');
        }else if($type == 'p'){
            $string = config('backend.db_password');
        }
        return $string;
    }

    public function _getUserInfo($apiKey = null){
        try{
            $userCode = ApiAuth::where('apiKey', $apiKey)->value('username');
            $userInfo = Employees::where('Emp_Username',$userCode)->leftjoin('level_codes','level_codes.LevelCode','=','Employees.Emp_Level')
                        ->select('Employees.Emp_Username as userCode','Employees.Emp_Name as name','level_codes.LevelDesc as userProfile')
                        ->first();

            return $userInfo;
        } catch(\Exception $e){
            return '';
        }
    }

	public function md5hash($keyPhrase){
		$key = mb_convert_encoding($keyPhrase, "ASCII");
		$key = md5($key,true);
    	$key .= substr($key, 0, 8);
    	return $key;
	}

	public function encryptPassword($toEncrypt = null){
        if(Request::get('toEncrypt'))
            $toEncrypt = Request::get('toEncrypt');

        $key = $this->md5hash($this->employeeKeyPhrase);
        $iv = "";
        $newEncrypted = openssl_encrypt($toEncrypt, 'des-ede3', $key, 0, $iv);

        return $newEncrypted;
    }

    public function _encrypt($string = null, $secretKey = null){
        if(Request::get('string'))
            $string = Request::get('string');

        if(Request::get('secretKey'))
            $secretKey = Request::get('secretKey');

        if($secretKey != null)
            $key = $this->md5hash($secretKey);
        else
            $key = $this->md5hash($this->key);

        $iv = "";
        $encrypted = openssl_encrypt($string, 'des-ede3', $key, 0, $iv);
        return $encrypted;
    }

	public function decryptPassword($encrypted_pw = null){
        if(Request::get('encrypted_pw'))
            $encrypted_pw = Request::get('encrypted_pw');

        $key = $this->md5hash($this->employeeKeyPhrase);
        $iv = '';

        $decrypted_pw = openssl_decrypt($encrypted_pw, 'des-ede3', $key, 0, $iv);

        return $decrypted_pw;
    }

    public function _decrypt($string = null,$secretKey = null){
        if($secretKey != null)
            $key = $this->md5hash($secretKey);
        else
            $key = $this->md5hash($this->key);

        $string = str_replace(" ","+",$string);
        $iv = "";
        $decrypted = openssl_decrypt($string, 'des-ede3', $key, 0, $iv);

        return $decrypted;
    }

    public function login(){
    	$username = Request::get('username');
    	$toEncrypt = Request::get('password');

        $user = Employees::find($username);

        if(!$user){
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Invalid username or password"
            ));
        }

    	$decrypted_pw = $this->decryptPassword($user->Emp_Password);

		if($decrypted_pw == $toEncrypt){
            $apiKey = $this->globalCtrl->apiauth();

            DB::beginTransaction();
            try{
                ApiAuth::create(['username' => $username, 'apiKey' => $apiKey, 'createdAt'=> $this->now]);
                DB::commit();
                return Response::json(array(
                    'error'     => false,
                    'code'      => 200,
                    'message'   => "Login Successfully",
                    'apiKey'    => $apiKey
                ));
            }catch(\Exception $e) {
                DB::rollback();
                return Response::json(array(
                    'error' => true,
                    'code' => 400,
                    'message' => "Fail to login"
                ));
            }
		}else{
			return Response::json(array(
				'error' => true,
				'code' => 400,
				'message' => "Invalid username or password"
			));
		}
    }
}
