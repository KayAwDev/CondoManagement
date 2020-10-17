<?php

namespace App\Http\Middleware;

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

            $username = DB::table('apiAuth')->where('apiKey',$apiKey)->value('username');

            $check = DB::table('WebSecurity')
                    ->join('Employees','Employees.Emp_Level','=','WebSecurity.Emp_Level')
                    ->join('WebProgram','WebProgram.ProgramName','=','WebSecurity.ProgramName')
                    ->Where('Employees.Emp_Username', $username)
                    ->where('WebSecurity.Allow',1)
                    ->where('WebProgram.ParentProgramName',NULL)
                    ->where('WebProgram.Active',1)
                    ->orderBy('WebProgram.MenuSequence','asc')
                    ->select('WebSecurity.ProgramName')
                    ->first();

            if($check){
                $checkIfHasChild = DB::table('WebProgram')
                    ->join('WebSecurity','WebProgram.ProgramName','=','WebSecurity.ProgramName')
                    ->join('Employees','Employees.Emp_Level','=','WebSecurity.Emp_Level')
                    ->where('WebProgram.Active',1)
                    ->where('Employees.Emp_Username',$username)
                    ->where('ParentProgramName',$check->ProgramName)
                    ->where('Allow',1)
                    ->orderBy('WebProgram.MenuSequence','asc')
                    ->select('WebSecurity.ProgramName')
                    ->first();

                if($checkIfHasChild){
                    $checkIfHasSecondChild = DB::table('WebProgram')
                    ->join('WebSecurity','WebProgram.ProgramName','=','WebSecurity.ProgramName')
                    ->join('Employees','Employees.Emp_Level','=','WebSecurity.Emp_Level')
                    ->where('WebProgram.Active',1)
                    ->where('Employees.Emp_Username',$username)
                    ->where('ParentProgramName',$checkIfHasChild->ProgramName)
                    ->where('Allow',1)
                    ->orderBy('WebProgram.MenuSequence','asc')
                    ->select('WebSecurity.ProgramName')
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
