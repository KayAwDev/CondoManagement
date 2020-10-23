<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\general\globalController as globalController;
use App\Models\Employees;
use App\Models\WebSecurity;
use App\Models\WebProgram;
use Request, View;
use Session;
use Validator;
use Redirect;
use DateTime;
use Config;
use Response;
use App;

class UserController extends BaseController
{
	public function __construct()
    {
        $this->globalCtrl = new globalController();
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

                $controller = App::make(ApiController::class);
                $login = $controller->callAction('login', $data);
                $login = $login->original;

                if($login['error'] == true){
                    return Redirect::back()->with('fail',$login['message'])->withInput(Request::except('password'));
                }else{
                    $apiKey = $login['apiKey'];
                    Session::put('apiKey',$apiKey);

                    $username = Request::get('username');

                    //check
                    $check = WebSecurity::join('Employees','Employees.Emp_Level','=','web_securities.Emp_Level')
                            ->join('web_programs','web_programs.ProgramName','=','web_securities.ProgramName')
                            ->Where('Employees.Emp_Username', $username)
                            ->where('web_securities.Allow',1)
                            ->where('web_programs.ParentProgramName',NULL)
                            ->where('web_programs.Active',1)
                            ->orderBy('web_programs.MenuSequence','asc')
                            ->select('web_securities.ProgramName')
                            ->first();

                    if($check){
                        $checkIfHasChild = WebProgram::join('web_securities','web_programs.ProgramName','=','web_securities.ProgramName')
                                            ->join('Employees','Employees.Emp_Level','=','web_securities.Emp_Level')
                                            ->where('web_programs.Active',1)
                                            ->where('Employees.Emp_Username',$username)
                                            ->where('ParentProgramName',$check->ProgramName)
                                            ->where('Allow',1)
                                            ->orderBy('web_programs.MenuSequence','asc')
                                            ->select('web_securities.ProgramName')
                                            ->first();

                        if($checkIfHasChild){
                            $checkIfHasSecondChild = WebProgram::join('web_securities','web_programs.ProgramName','=','web_securities.ProgramName')
                                                    ->join('Employees','Employees.Emp_Level','=','web_securities.Emp_Level')
                                                    ->where('web_programs.Active',1)
                                                    ->where('Employees.Emp_Username',$username)
                                                    ->where('ParentProgramName',$checkIfHasChild->ProgramName)
                                                    ->where('Allow',1)
                                                    ->orderBy('web_programs.MenuSequence','asc')
                                                    ->select('web_securities.ProgramName')
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
