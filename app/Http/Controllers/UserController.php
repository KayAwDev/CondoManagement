<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\general\globalController as globalController;
use DB;
use Request, View;
use GuzzleHttp\Client;
use Session;
use Validator;
use Redirect;
use DateTime;
use Config;
use Response;

class UserController extends BaseController
{
	public function __construct()
    {
        $this->globalCtrl = new globalController();
    }

	public function getData($api, $param){
		$client = new Client();
        $res = $client->request('GET', $api."?".http_build_query($param));
        $data = $res->getBody();
        $data = json_decode($data);

        if($data->error == false){
            $data = $data->data;
            $data = (array)$data;
            return $data;
        }else{
            return array();
        }
	}

	public function postData($api,$data){

		$ch = curl_init($api);
  		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
  		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  		$result = curl_exec($ch);
  		curl_close($ch);
  		$result = json_decode($result);

  		return $result;
	}

    public function login(){
        if(Request::all()){
            $rules = array(
                'username'    => 'required',
                'password'    => 'required',
            );

            $validator = Validator::make(Request::all(), $rules);
            if($validator->passes()){
                $data = array(
                    'username'      => Request::get('username'),
                    'password'      => Request::get('password')
                );
                $api = asset('api/login');
                $login = $this->postData($api,$data);

                if($login->error == true){
                    return Redirect::back()->with('fail',$login->message)->withInput(Request::except('password'));
                }else{
                    $apiKey = $login->apiKey;
                    Session::put('apiKey',$apiKey);

                    $username = Request::get('username');

                    //check
                    $check = DB::table('WebSecurity')
                    ->join('Employees','Employees.Emp_Level','=','WebSecurity.Emp_Level')
                    ->join('WebProgram','WebProgram.ProgramName','=','WebSecurity.ProgramName')
                    ->Where('Employees.Emp_Username', $username)
                    ->where('WebSecurity.Allow',1)
                    ->where('WebProgram.ParentProgramName',NULL)
                    ->where('WebProgram.Active',1)
                    ->orderBy('WebProgram.MenuSequence','asc')
                    ->select('WebSecurity.ProgramName')
                    ->first();

                    if($check){
                        $checkIfHasChild = DB::table('WebProgram')
                            ->join('WebSecurity','WebProgram.ProgramName','=','WebSecurity.ProgramName')
                            ->join('Employees','Employees.Emp_Level','=','WebSecurity.Emp_Level')
                            ->where('WebProgram.Active',1)
                            ->where('Employees.Emp_Username',$username)
                            ->where('ParentProgramName',$check->ProgramName)
                            ->where('Allow',1)
                            ->orderBy('WebProgram.MenuSequence','asc')
                            ->select('WebSecurity.ProgramName')
                            ->first();

                        if($checkIfHasChild){
                            $checkIfHasSecondChild = DB::table('WebProgram')
                            ->join('WebSecurity','WebProgram.ProgramName','=','WebSecurity.ProgramName')
                            ->join('Employees','Employees.Emp_Level','=','WebSecurity.Emp_Level')
                            ->where('WebProgram.Active',1)
                            ->where('Employees.Emp_Username',$username)
                            ->where('ParentProgramName',$checkIfHasChild->ProgramName)
                            ->where('Allow',1)
                            ->orderBy('WebProgram.MenuSequence','asc')
                            ->select('WebSecurity.ProgramName')
                            ->first();

                            if($checkIfHasSecondChild){
                                $route = $check->ProgramName.'/'.$checkIfHasChild->ProgramName.'/'.$checkIfHasSecondChild->ProgramName;
                                return redirect($route);
                            }else{
                                $route = $check->ProgramName.'/'.$checkIfHasChild->ProgramName;
                                return redirect($route);
                            }
                        }else{
                            $route = $check->ProgramName;
                            return redirect($route);
                        }
                    }else{
                        return Redirect::back()->with('fail','You have no permission access in this portal.')->withInput(Request::except('password'));
                    }

                }
            }else{
                return Redirect::back()->withErrors($validator)->withInput(Request::except('password'));
            }
        }else{
            $tabTitle = getenv('TAB_TITLE');
        	return view::make('login',compact('tabTitle'));
        }
    }

    public function utf8_encode_deep(&$input) {
        if (is_string($input)) {
            $input = utf8_encode($input);
        } else if (is_array($input)) {
            foreach ($input as &$value) {
                self::utf8_encode_deep($value);
            }

            unset($value);
        } else if (is_object($input)) {
            $vars = array_keys(get_object_vars($input));

            foreach ($vars as $var) {
                self::utf8_encode_deep($input->$var);
            }
        }
    }

    public function error(){
        return view::make('errors.401');
    }

    public function logout(){
        $sessionFlag = Request::get('sessionFlag');

        Session::flush();
        if($sessionFlag == 1){
            Session::flash('fail','Session Timeout');
        }else
            return redirect('login')->with('success',"Log Out Successfully");
    }
}
