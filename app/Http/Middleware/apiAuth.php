<?php

namespace App\Http\Middleware;

use Closure;
use Response;
use Request;
use DB;

class apiAuth
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
            $check = DB::table('apiAuth')->where('apikey',$apiKey)->first();

            if(!$check)
            {
                $content = array('error' => true,
                                 'code' => '440',
                                 'message' => "Account not found"
                                );
                return response($content, 401);
            }else{
                $username = $check->username;
                $latestKey = DB::table('apiAuth')->where('username',$username)->latest('createdAt')->value('apiKey');

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
