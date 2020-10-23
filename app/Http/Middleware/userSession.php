<?php

namespace App\Http\Middleware;

use App\Models\ApiAuth;
use Closure;
use Session;
use DB;

class userSession
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
        if(Session::has('apiKey')){
            $apiKey = Session::get('apiKey');

            $username = ApiAuth::where('apiKey',$apiKey)->value('username');

            $check = DB::table('web_securities')
                    ->join('Employees','Employees.Emp_Level','=','web_securities.Emp_Level')
                    ->join('web_programs','web_programs.ProgramName','=','web_securities.ProgramName')
                    ->Where('Employees.Emp_Username', $username)
                    ->where('web_securities.Allow',1)
                    ->where('web_programs.ParentProgramName',NULL)
                    ->where('web_programs.Active',1)
                    ->orderBy('web_programs.MenuSequence','asc')
                    ->select('web_securities.ProgramName')
                    ->first();

            if($check){
                $checkIfHasChild = DB::table('web_programs')
                    ->join('web_securities','web_programs.ProgramName','=','web_securities.ProgramName')
                    ->join('Employees','Employees.Emp_Level','=','web_securities.Emp_Level')
                    ->where('web_programs.Active',1)
                    ->where('Employees.Emp_Username',$username)
                    ->where('ParentProgramName',$check->ProgramName)
                    ->where('Allow',1)
                    ->orderBy('web_programs.MenuSequence','asc')
                    ->select('web_securities.ProgramName')
                    ->first();

                if($checkIfHasChild){
                    $checkIfHasSecondChild = DB::table('web_programs')
                    ->join('web_securities','web_programs.ProgramName','=','web_securities.ProgramName')
                    ->join('Employees','Employees.Emp_Level','=','web_securities.Emp_Level')
                    ->where('web_programs.Active',1)
                    ->where('Employees.Emp_Username',$username)
                    ->where('ParentProgramName',$checkIfHasChild->ProgramName)
                    ->where('Allow',1)
                    ->orderBy('web_programs.MenuSequence','asc')
                    ->select('web_securities.ProgramName')
                    ->first();

                    if($checkIfHasSecondChild){
                        $route = $check->ProgramName.'/'.$checkIfHasChild->ProgramName.'/'.$checkIfHasSecondChild->ProgramName;
                        return redirect($route);
                    }else{
                        $route = $check->ProgramName.'/'.$checkIfHasChild->ProgramName;
                        return redirect($route);
                    }
                }else{
                    $route = $check->ProgramName;
                    return redirect($route);
                }
            }else{
                return Redirect::back();
            }
        }

        return $next($request);
    }
}
