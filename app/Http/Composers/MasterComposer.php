<?php
namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\ApiController as ApiController;
use App\Models\WebProgram;
use App\Models\Employees;
use App\Models\ApiAuth;
use Session;
use Route;
use Config;

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

        $userInfo = $this->apiCtrl->_getUserInfo($apiKey);

        $routeName = Route::currentRouteName();

        $username = ApiAuth::where('apikey',$apiKey)->value('username');
        $groupCode = Employees::where('Emp_Username',$username)->value('Emp_Level');

        $menus = WebProgram::join('web_securities','web_programs.ProgramName','=','web_securities.ProgramName')
				->where('web_securities.Emp_Level',$groupCode)
				->where('Allow',1)
				->where('Active',1)
				->where('ParentProgramName',NULL)
				->orderBy('MenuSequence','asc')
				->get();

        $menu = array();

        foreach($menus as $menu2){
            $subMenu = WebProgram::join('web_securities','web_programs.ProgramName','=','web_securities.ProgramName')
                        ->where('web_securities.Emp_Level',$groupCode)
                        ->where('Allow',1)
                        ->where('Active',1)
                        ->where('ParentProgramName',$menu2->ProgramName)
                        ->orderBy('MenuSequence','asc')
                        ->get();

            foreach($subMenu as $menu3){
                $subMenu2 = WebProgram::join('web_securities','web_programs.ProgramName','=','web_securities.ProgramName')
                            ->where('web_securities.Emp_Level',$groupCode)
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
