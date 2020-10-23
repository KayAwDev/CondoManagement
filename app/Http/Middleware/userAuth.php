<?php

namespace App\Http\Middleware;

use App\Models\ApiAuth;
use App\Models\WebProgram;
use Closure;
use Session;
use DB;
use Route;
use Response;

class userAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Session::has('apiKey')){
            return redirect('login');
        }
        else{
            $apiKey = Session::get('apiKey');

            $userCode = ApiAuth::where('apiKey',$apiKey)->value('username');

            if($userCode){
                $latestKey = ApiAuth::where('username',$userCode)->latest('createdAt')->value('apiKey');

                if($latestKey != $apiKey){
                    Session::flush();
                    return redirect('login')->with('fail',"You have been logged out because you've signed in on another device");
                }
            }else{
                Session::flush();
                return redirect('login')->with('fail','Error Occured');
            }

            $routeName = Route::currentRouteName();
            $registeredProgram = WebProgram::where('ProgramName',$routeName)->where('active',1)->first();

            if($registeredProgram)
            {
                //check parent
                $checkParent = WebProgram::where('ProgramName',$routeName)->value('parentProgramName');

                if($checkParent){
                    $checkParentAuth = DB::table('web_securities')
                    ->join('Employees','Employees.Emp_Level','=','web_securities.Emp_Level')
                    ->where('Employees.Emp_Username',$userCode)
                    ->where('ProgramName',$checkParent)
                    ->value('Allow');

                    if($checkParentAuth)
                    {
                        $check = DB::table('web_securities')
                        ->join('Employees','Employees.Emp_Level','=','web_securities.Emp_Level')
                        ->where('Employees.Emp_Username',$userCode)
                        ->where('ProgramName',$routeName)
                        ->value('Allow');

                        if($check)
                            return $next($request);
                        else{
                            return redirect('error_401');
                        }
                    }else{
                        return redirect('error_401');
                    }
                }
                else{
                    $check = DB::table('web_securities')
                    ->join('Employees','Employees.Emp_Level','=','web_securities.Emp_Level')
                    ->where('Employees.Emp_Username',$userCode)
                    ->where('ProgramName',$routeName)
                    ->value('Allow');

                    if($check)
                        return $next($request);
                    else
                        return redirect('error_401');
                }
            }
            else{
                $routeName = Route::getCurrentRoute()->getPath();
                return Response::view('errors.404', [], 404);
            }
        }
    }
}
