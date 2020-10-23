<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::any('login',array('middleware' => 'userSession','uses'=>'UserController@login'));
Route::get('logout',array('uses'=>'UserController@logout'));
Route::get('_encrypt',array("uses"=>"ApiController@_encrypt"));
Route::get('_getUserInfo',array("uses"=>"ApiController@_getUserInfo"));


Route::group(array('middleware' => ['userAuth','web']), function(){
    Route::group(array('prefix'=>'SecurityAccessControl'),function(){
        Route::get('Employees',array('as'=>'Employees','uses'=>'EmployeeController@employees'));
    });

    Route::get('Units',array('as'=>'Units','uses'=>'UnitsController@units'));

    Route::group(array('prefix'=>'Visitor'),function(){
        Route::get('VisitorLog',array('as'=>'VisitorLog','uses'=>'VisitorController@visitorLog'));
        Route::get('VisitorRegistration',array('as'=>'VisitorRegistration','uses'=>'VisitorController@visitorRegistration'));
    });
});


Route::group(array('prefix' => 'api'), function(){
	Route::post('login',array("uses"=>"ApiController@login"));
    Route::get('decryptPassword',array("uses"=>"ApiController@decryptPassword"));

	Route::group(array('middleware' => 'userApiAuth'), function(){
        //employees
        Route::group(array('prefix' => 'Employees','namespace'=>'Api'), function(){
            Route::get('getUserProfileList',array('uses'=>'EmployeeApiController@getUserProfileList'));
            Route::get('getEmployeeList',array('uses'=>'EmployeeApiController@getEmployeeList'));
            Route::post('editEmployee',array('uses'=>'EmployeeApiController@editEmployee'));
            Route::post('addEmployee',array('uses'=>'EmployeeApiController@addEmployee'));
            Route::post('deleteEmployee',array('uses'=>'EmployeeApiController@deleteEmployee'));
        });

        //Units
        Route::group(array('prefix' => 'Units','namespace'=>'Api'), function(){
            Route::get('getAllUnit',array('uses'=>'UnitsApiController@getAllUnit'));
            Route::post('addUnit',array('uses'=>'UnitsApiController@addUnit'));
            Route::post('editUnit',array('uses'=>'UnitsApiController@editUnit'));
            Route::post('deleteUnit',array('uses'=>'UnitsApiController@deleteUnit'));
            Route::get('getUnitTenants',array('uses'=>'UnitsApiController@getUnitTenants'));
            Route::post('addUnitTenant',array('uses'=>'UnitsApiController@addUnitTenant'));
            Route::post('editUnitTenant',array('uses'=>'UnitsApiController@editUnitTenant'));
            Route::post('deleteUnitTenant',array('uses'=>'UnitsApiController@deleteUnitTenant'));
        });

        //Units
        Route::group(array('prefix' => 'Visitor','namespace'=>'Api'), function(){
            Route::get('getVisitorLog',array('uses'=>'VisitorApiController@getVisitorLog'));
            Route::post('editVisitorLog',array('uses'=>'VisitorApiController@editVisitorLog'));
            Route::post('visitorCheckIn',array('uses'=>'VisitorApiController@visitorCheckIn'));
            Route::post('visitorCheckOut',array('uses'=>'VisitorApiController@visitorCheckOut'));
        });
	});
	//apiAuth Middleware end

});
