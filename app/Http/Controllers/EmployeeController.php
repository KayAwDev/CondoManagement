<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\general\globalController as globalController;
use App\Http\Controllers\Api\EmployeeApiController;
use Request, View;
use Session;
use App;
use Validator;
use Redirect;


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

        $userProfileApi = App::make(EmployeeApiController::class);
        $userProfiles = $userProfileApi->callAction('getUserProfileList', $param);
        $userProfiles = $userProfiles->original['data'];

    	return view::make('securityAccessControl/employees/employees',compact('apiKey','userProfiles'));
    }
}
