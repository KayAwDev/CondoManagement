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

class VisitorController extends BaseController
{
	public function __construct()
    {
    	$this->userCtrl = new UserController();
    	$this->globalCtrl = new globalController();
    }

	public function visitorLog(){
		$apiKey = $this->globalCtrl->getApiKey();
        $param = array('apiKey'=>$apiKey);

    	return view::make('visitor/visitorLog',compact('apiKey'));
    }

    public function visitorRegistration(){
		$apiKey = $this->globalCtrl->getApiKey();
        $param = array('apiKey'=>$apiKey);

    	return view::make('visitor/visitorRegistration',compact('apiKey'));
    }
}
