<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\general\globalController as globalController;
use Request, View;
use Session;
use Validator;
use Redirect;

class UnitsController extends BaseController
{
	public function __construct()
    {
    	$this->userCtrl = new UserController();
    	$this->globalCtrl = new globalController();
    }

	public function units(){
		$apiKey = $this->globalCtrl->getApiKey();
        $param = array('apiKey'=>$apiKey);

    	return view::make('units/units',compact('apiKey'));
    }
}
