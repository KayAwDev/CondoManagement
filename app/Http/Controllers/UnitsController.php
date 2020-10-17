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
use Config;

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
