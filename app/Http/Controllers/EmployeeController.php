<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use DB;
use Request, View;
use GuzzleHttp\Client;
use Session;
use Illuminate\Support\Facades\Input;
use Validator;
use Redirect;
use Config;
use App\Http\Controllers\general\globalController as globalController;

class EmployeeController extends BaseController
{
	public function __construct()
    {
    	$this->userCtrl = new UserController();
    	$this->globalCtrl = new globalController();
    }

	public function employees(){

        $apiKey = $this->globalCtrl->getApiKey();

        $param = array('apiKey'=>$apiKey);

        $userProfileApi = asset("api/Employees/getUserProfileList");
        $userProfiles = $this->userCtrl->getData($userProfileApi, $param);

    	return view::make('securityAccessControl/employees/employees',compact('apiKey','userProfiles'));
    }
}
