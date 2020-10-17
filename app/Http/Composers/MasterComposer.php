<?php
namespace App\Http\Composers;
use Illuminate\Contracts\View\View;
use DB;
use Session;
use Route;
use Config;
use App\Http\Controllers\ApiController as ApiController;

class MasterComposer {

    public function compose(View $view)
    {
        $this->apiCtrl = new ApiController;
    	//get program
        $tabTitle = Config::get('backend.tabTitle');
        $masterTitleMini = Config::get('backend.masterTitleMini');
        $masterTitle1 = Config::get('backend.masterTitle1');
        $masterTitle2 = Config::get('backend.masterTitle2');
        $idleTime = Config::get('backend.idleTime');
        $idleTime = $idleTime*60*1000;

        $apiKey = Session::get('apiKey');
        $username = DB::table('apiAuth')->where('apikey',$apiKey)->value('username');

        $userInfo = $this->apiCtrl->_getUserInfo($apiKey);
		$routeName = Route::currentRouteName();

        $groupCode = DB::table('Employees')->where('Emp_Username',$username)->value('Emp_Level');

		$menus = DB::table('WebProgram')
				->join('WebSecurity','WebProgram.ProgramName','=','WebSecurity.ProgramName')
				->where('WebSecurity.Emp_Level',$groupCode)
				->where('Allow',1)
				->where('Active',1)
				->where('ParentProgramName',NULL)
				->orderBy('MenuSequence','asc')
				->get();

        $menu = array();

        foreach($menus as $menu2){
            $subMenu = DB::table('WebProgram')
                ->join('WebSecurity','WebProgram.ProgramName','=','WebSecurity.ProgramName')
                ->where('WebSecurity.Emp_Level',$groupCode)
                ->where('Allow',1)
                ->where('Active',1)
                ->where('ParentProgramName',$menu2->ProgramName)
                ->orderBy('MenuSequence','asc')
                ->get();

            foreach($subMenu as $menu3){
                $subMenu2 = DB::table('WebProgram')
                    ->join('WebSecurity','WebProgram.ProgramName','=','WebSecurity.ProgramName')
                    ->where('WebSecurity.Emp_Level',$groupCode)
                    ->where('Allow',1)
                    ->where('Active',1)
                    ->where('ParentProgramName',$menu3->ProgramName)
                    ->orderBy('MenuSequence','asc')
                    ->get();

                    $menu3->subProgram = $subMenu2;
            }

            $menu2->subProgram = $subMenu;

            array_push($menu,$menu2);
        }

        $view->with(compact('menu','userInfo','tabTitle','masterTitleMini','masterTitle1','masterTitle2','idleTime'));
    }
}
