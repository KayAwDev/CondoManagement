<?php

namespace App\Http\Controllers\general;

use Illuminate\Routing\Controller as BaseController;
use Session;

class globalController extends BaseController
{

    public function getApiKey(){
        $apiKey = Session::get('apiKey');
        return $apiKey;
    }

    public function apiauth(){
        $bytes = openssl_random_pseudo_bytes(64, $cstrong);
        $apikey = bin2hex($bytes);

        if(!$cstrong){
            return 0;
        } else {
            return $apikey;
        }
    }

}
