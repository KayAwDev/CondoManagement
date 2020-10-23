<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\general\globalController as globalController;
use App\Http\Controllers\ApiController as ApiController;
use App\Models\LevelCode;
use App\Models\Employees;
use DB;
use Request, View;
use Response;
use Session;
use Validator;
use ArrayObject;
use DateTime;

class EmployeeApiController extends BaseController
{
	public function __construct()
    {
		$this->globalCtrl = new globalController();
        $this->apiCtrl = new ApiController();
        $this->now = date("Y-m-d h:i:s");
    }

    public function getUserProfileList(){
        $data = LevelCode::select('LevelCode as code','LevelDesc as name')->orderBy('LevelCode','asc')->get();

        if(count($data) > 0){
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Successfully retrieve user profile list",
                'data'=> $data
            ));
        }else{
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Fail to retrieve user profile list",
                'data'=> $data
            ));
        }
    }

    public function getEmployeeList(){
        $data = Employees::with('LevelCode')->get();

        if(count($data) > 0){
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Successfully retrieve Employees list",
                'data'=> $data
            ));
        }else{
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Fail to retrieve Employees list",
                'data'=> $data
            ));
        }
    }

    public function editEmployee(){
        $rules = array('username'                   => 'required|max:50|exists:Employees,Emp_Username',
                        'employeeName'              => 'required|max:100',
                        'password'                  => 'required',
                        'confirmPassword'           => 'required',
                        'userProfile'               => 'required|exists:level_codes,LevelCode'
        );

        $validator = Validator::make(Request::all(),$rules);
        if(!($validator->passes())){
            $validationErrorString = implode(',',$validator->errors()->all());
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => $validationErrorString
            ));
        }
        //edit
        $employeeName = Request::get('employeeName');
        $username = Request::get('username');
        $password = Request::get('password');
        $confirmPassword = Request::get('confirmPassword');
        $userProfile = Request::get('userProfile');

        if($password != $confirmPassword){
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Password and confirm password does not match"
            ));
        }

        if($password){
            $password = $this->apiCtrl->encryptPassword($password);
        }

        $updateArray = array('Emp_Name'     => $employeeName,
                             'Emp_Level'    => $userProfile,
                             'Emp_Password' => $password
                            );

        DB::beginTransaction();
        try{
            Employees::where('Emp_Username',$username)->update($updateArray);

            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Employee's details update successfully"
            ));
        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'editEmployeeAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            Log::error($e);
            return Response::json(array(
                'error' => true,
                'code' => 500,
                'message' => "Fail to update Employees's details"
            ));
        }
    }

    public function addEmployee(){
        $rules = array('username'                   => 'required|max:50|unique:Employees,Emp_Username',
                        'employeeName'              => 'required|max:100',
                        'password'                  => 'required',
                        'confirmPassword'           => 'required',
                        'userProfile'               => 'required|exists:level_codes,LevelCode'
        );

        $customMessages = [
            'unique' => 'This username is used by another user. Please try another username.'
        ];

        $validator = Validator::make(Request::all(),$rules, $customMessages);
        if(!($validator->passes())){
            $validationErrorString = implode(',',$validator->errors()->all());
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => $validationErrorString
            ));
        }

        $employeeName = Request::get('employeeName');
        $username = Request::get('username');
        $password = Request::get('password');
        $confirmPassowrd = Request::get('confirmPassword');
        $userProfile = Request::get('userProfile');

        if($password){
            $password = $this->apiCtrl->encryptPassword($password);
        }

        $addArray = array('Emp_Username'    => $username,
                          'Emp_Name'        => $employeeName,
                          'Emp_Level'       => $userProfile,
                          'Emp_Password'    => $password,
                          'CreatedDateTime' => $this->now
                        );

        DB::beginTransaction();
        try{
            Employees::create($addArray);

            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Successfully added new Employees"
            ));
        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'addEmployeeAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);

            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Fail to add new Employees"
            ));
        }
    }

    public function deleteEmployee(){
        $rules = array(
            'username' => 'required|exists:Employees,Emp_Username'
        );

        $validator = Validator::make(Request::all(),$rules);
        if(!($validator->passes())){
            $validationErrorString = implode(',',$validator->errors()->all());
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => $validationErrorString
            ));
        }

        DB::beginTransaction();
        try{
            $username = Request::get('username');

            Employees::find($username)->delete();

            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => 'Deleted Successfully'
            ));

        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'deleteEmployeeAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            return Response::json(array(
                'error' => true,
                'code' => 500,
                'message' => $e->getMessage()
            ));
        }
    }
}
