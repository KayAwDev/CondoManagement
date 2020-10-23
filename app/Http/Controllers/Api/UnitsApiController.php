<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\general\globalController as globalController;
use App\Http\Controllers\ApiController as ApiController;
use App\Models\VisitorLog;
use App\Models\Tenants;
use App\Models\Units;
use DB;
use Request, View;
use Response;
use Session;
use Validator;
use ArrayObject;
use DateTime;

class UnitsApiController extends BaseController
{
	public function __construct()
    {
		$this->globalCtrl = new globalController();
        $this->apiCtrl = new ApiController();
        $this->now = date("Y-m-d h:i:s");
    }

    public function getAllUnit(){
        $Block = Request::get('searchBlock');
        $Unit = Request::get('searchUnit');
        $searchType = Request::get('searchType');
        $TenantInput = Request::get('searchInput');

        $data = Units::select('Units.*');

        if($searchType && $TenantInput){
            $data = $data->leftjoin('Tenants','Tenants.Tenant_UnitID','=','Units.UnitID');

            if($searchType == 'name'){
                $data = $data->where('Tenants.Tenant_Name','like','%'.$TenantInput.'%');
            }else if($searchType == 'phone'){
                $data = $data->where('Tenants.Tenant_ContactNumber','like','%'.$TenantInput.'%');
            }
        }else{
            if($Block){
                $data = $data->where('Block', $Block);
            }

            if($Unit){
                $data = $data->where('UnitNumber', $Unit);
            }
        }

        $data = $data->get();

        if(count($data) > 0){
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Successfully retrieve all unit",
                'data'=> $data
            ));
        }else{
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Record not found./ No Data.",
                'data'=> $data
            ));
        }
    }

    public function addUnit(){
        $rules = array('Block'                  => 'required',
                        'UnitNo'                => 'required',
                        'Owner'                 => 'required',
                        'OwnerContactNo'        => 'required'
        );

        $validator = Validator::make(Request::all(),$rules);
        if(!($validator->passes())){
            $validationErrorString = implode(',',$validator->errors()->all());
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => $validationErrorString
            ));
        }

        $Block = Request::get('Block');
        $UnitNo = Request::get('UnitNo');
        $Owner = Request::get('Owner');
        $OwnerContactNo = Request::get('OwnerContactNo');

        $addArray = array('Block'                   => $Block,
                          'UnitNumber'              => $UnitNo,
                          'UnitOwner'               => $Owner,
                          'Owner_ContactNumber'     => $OwnerContactNo,
                          'CreatedDateTime'         => $this->now
                        );

        DB::beginTransaction();
        try{
            Units::create($addArray);
            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Successfully added new Unit"
            ));
        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'addUnitAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Fail to add new Unit"
            ));
        }
    }

    public function editUnit(){
        $rules = array('UnitID'                 => 'required|exists:units,UnitID',
                        'Block'                 => 'required',
                        'UnitNo'                => 'required',
                        'Owner'                 => 'required',
                        'OwnerContactNo'        => 'required'
        );

        $validator = Validator::make(Request::all(),$rules);
        if(!($validator->passes())){
            $validationErrorString = implode(',',$validator->errors()->all());
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => $validationErrorString
            ));
        }

        $UnitID = Request::get('UnitID');
        $Block = Request::get('Block');
        $UnitNo = Request::get('UnitNo');
        $Owner = Request::get('Owner');
        $OwnerContactNo = Request::get('OwnerContactNo');

        $updateArray = array('Block'                   => $Block,
                             'UnitNumber'              => $UnitNo,
                             'UnitOwner'               => $Owner,
                             'Owner_ContactNumber'     => $OwnerContactNo
                            );

        DB::beginTransaction();
        try{
            Units::find($UnitID)->update($updateArray);
            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Successfully update unit detail"
            ));
        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'editUnitAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Fail to  update unit detail"
            ));
        }
    }

    public function deleteUnit(){
        $rules = array(
            'UnitID' => 'required|exists:units,UnitID'
        );

        $validator = Validator::make(Request::all(),$rules);
        if(!($validator->passes())){
            $validationErrorString = implode(',',$validator->errors()->all());
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => $validationErrorString
            ));
        }

        DB::beginTransaction();
        try{
            $UnitID = Request::get('UnitID');

            VisitorLog::where('Visit_UnitID', $UnitID)->delete();
            Tenants::where('Tenant_UnitID', $UnitID)->delete();
            Units::find($UnitID)->delete();

            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => 'Delete Successfully.'
            ));

        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'deleteUnitAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            return Response::json(array(
                'error' => true,
                'code' => 500,
                'message' => $e->getMessage()
            ));
        }
    }

    public function getUnitTenants(){
        $UnitID = Request::get('UnitID');

        $data = Tenants::where('Tenant_UnitID', $UnitID)->get();

        if(count($data) > 0){
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Successfully retrieve all tenant",
                'data'=> $data
            ));
        }else{
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Record not found./ No Data.",
                'data'=> $data
            ));
        }
    }

    public function addUnitTenant(){
        $rules = array('UnitID'             => 'required',
                        'TenantName'        => 'required',
                        'TenantContactNo'   => 'required'
        );

        $validator = Validator::make(Request::all(),$rules);
        if(!($validator->passes())){
            $validationErrorString = implode(',',$validator->errors()->all());
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => $validationErrorString
            ));
        }

        $UnitID = Request::get('UnitID');
        $TenantName = Request::get('TenantName');
        $TenantContactNo = Request::get('TenantContactNo');

        $addTenantArray = array('Tenant_UnitID'             => $UnitID,
                                'Tenant_Name'               => $TenantName,
                                'Tenant_ContactNumber'      => $TenantContactNo,
                                'CreatedDateTime'           => $this->now
                                );

        DB::beginTransaction();
        try{
            Tenants::create($addTenantArray);

            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Successfully added new Tenant"
            ));
        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'addUnitTenantAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Fail to add new Tenant"
            ));
        }
    }

    public function editUnitTenant(){
        $rules = array('UnitTenantID'       => 'required',
                        'TenantName'        => 'required',
                        'TenantContactNo'   => 'required'
        );

        $validator = Validator::make(Request::all(),$rules);
        if(!($validator->passes())){
            $validationErrorString = implode(',',$validator->errors()->all());
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => $validationErrorString
            ));
        }

        $UnitTenantID = Request::get('UnitTenantID');
        $TenantName = Request::get('TenantName');
        $TenantContactNo = Request::get('TenantContactNo');

        $updateTenantArray = array('Tenant_Name'               => $TenantName,
                                   'Tenant_ContactNumber'      => $TenantContactNo
                                );

        DB::beginTransaction();
        try{
            Tenants::find($UnitTenantID)->update($updateTenantArray);

            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Successfully update tenant detail"
            ));
        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'editUnitTenantAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Fail to update tenant detail"
            ));
        }
    }

    public function deleteUnitTenant(){
        $rules = array(
            'UnitTenantID' => 'required|exists:tenants,TenantID'
        );

        $validator = Validator::make(Request::all(),$rules);
        if(!($validator->passes())){
            $validationErrorString = implode(',',$validator->errors()->all());
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => $validationErrorString
            ));
        }

        DB::beginTransaction();
        try{
            $UnitTenantID = Request::get('UnitTenantID');

            Tenants::find($UnitTenantID)->delete();

            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => 'Delete Successfully.'
            ));

        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'deleteUnitTenantAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            return Response::json(array(
                'error' => true,
                'code' => 500,
                'message' => $e->getMessage()
            ));
        }
    }
}
