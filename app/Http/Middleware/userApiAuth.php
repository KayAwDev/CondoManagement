<?php

namespace App\Http\Middleware;

use App\Models\ApiAuth;
use Closure;
use Response;
use Request;
use DB;

class userApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next){
        $apiKey = Request::get('apiKey');

        if(strlen($apiKey) != 128){
            $content = array('error' => true,
                             'code' => '404',
                             'message' => "Invalid key"
                            );
            return response($content, 404);
        }

       if(!$apiKey){
            $content = array('error' => true,
                            'code' => '404',
                            'message' => "Account not found"
                        );
            return response($content, 404);
        }else{
            //first check apiKey
            $check = ApiAuth::where('apikey',$apiKey)->first();

            if(!$check)
            {
                $content = array('error' => true,
                                 'code' => '440',
                                 'message' => "Account not found"
                                );
                return response($content, 401);
            }else{
                $username = $check->username;
                $latestKey = ApiAuth::where('Username',$username)->latest('createdAt')->value('apiKey');

                if($latestKey != $apiKey){
                    $content = array('error' => true,
                                 'code' => '440',
                                 'message' => "Session Timeout"
                                );
                    return response($content, 401);
                }
            }
        }
        return $next($request);
    }
}
