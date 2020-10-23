<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\general\globalController as globalController;
use App\Http\Controllers\ApiController as ApiController;
use App\Models\VisitorLog;
use App\Models\Units;
use DB;
use Request, View;
use Response;
use Session;
use Validator;
use ArrayObject;
use DateTime;

class VisitorApiController extends BaseController
{
	public function __construct()
    {
		$this->globalCtrl = new globalController();
        $this->apiCtrl = new ApiController();
        $this->now = date("Y-m-d h:i:s");
    }

    public function getVisitorLog(){
        $DateType = Request::get('DateType');
        $DateFrom = Request::get('DateFrom');
        $DateTo = Request::get('DateTo');
        $VisitPlace = Request::get('searchVisitPlace');
        $Block = Request::get('searchBlock');
        $Unit = Request::get('searchUnit');
        $VisitorNRIC = Request::get('searchVisitorNRIC');
        $VisitorPhone = Request::get('searchVisitorPhone');
        $Exit = Request::get('Exit');

        $data = VisitorLog::leftjoin('Units','Units.UnitID','=','visitor_logs.Visit_UnitID')
                ->select('visitor_logs.*','Units.Block','Units.UnitNumber');

        if($DateFrom || $DateTo){
            if(!$DateFrom || !$DateTo){
                Response::json(array(
                    'error' => true,
                    'code' => 400,
                    'message' => "Please fill in both date from and date to."
                ));
            }

            $DateFrom = str_replace('/', '-', $DateFrom);
            $DateFrom = date('Y-m-d', strtotime($DateFrom));

            $DateTo = str_replace('/', '-', $DateTo);
            $DateTo = date('Y-m-d', strtotime($DateTo));

            $dateArray = array($DateFrom.' 00:00:00',$DateTo.' 23:59:59');
            if($DateType == 'Enter'){
                $data = $data->whereBetween('visitor_logs.EnterDateTime', $dateArray);
            }else if($DateType == 'Exit'){
                $data = $data->whereBetween('visitor_logs.ExitDateTime', $dateArray);
            }
        }else{
            if(!$Exit){
                $Date = date('Y-m-d', strtotime($this->now));
                $dateArray = array($Date.' 00:00:00',$Date.' 23:59:59');
                $data = $data->whereBetween('visitor_logs.EnterDateTime', $dateArray);
            }
        }

        if($VisitPlace == 'Function'){
            $data = $data->where('visitor_logs.VisitPlace', $VisitPlace);
        }else{
            if($Block){
                $data = $data->where('Units.Block', $Block);
            }

            if($Unit){
                $data = $data->where('Units.UnitNumber', $Unit);
            }
        }

        if($VisitorNRIC){
            $VisitorNRIC = substr($VisitorNRIC, -3);
            $data = $data->where('visitor_logs.Visitor_NRIC', $VisitorNRIC);
        }

        if($VisitorPhone){
            $data = $data->where('visitor_logs.Visitor_ContactNumber', 'like','%'.$VisitorPhone.'%');
        }

        if($Exit){
            $data = $data->whereNull('ExitDateTime')->orderby('EnterDateTime','DESC');
        }

        $data = $data->get();

        if(count($data) > 0){
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Successfully retrieve all visitor",
                'data'=> $data
            ));
        }else{
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => "Record not found."
            ));
        }
    }

    public function editVisitorLog(){
        $rules = array(
            'VisitorLogID' => 'required|exists:visitor_logs,VisitorLogID'
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
            $VisitorLogID = Request::get('VisitorLogID');
            $ExitDateTime = Request::get('ExitDateTime');

            VisitorLog::find($VisitorLogID)->update(['ExitDateTime' => $ExitDateTime]);

            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => 'Visitor Detail Update Successfully.'
            ));
        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'editVisitorLogAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            return Response::json(array(
                'error' => true,
                'code' => 500,
                'message' => $e->getMessage()
            ));
        }
    }

    public function visitorCheckIn(){
        $rules = array('checkInVisitorName'         => 'required',
                        'checkInVisitorContactNo'   => 'required',
                        'checkInVisitorNRIC'        => 'required',
                        'checkInVisitPlace'         => 'required',
                        'checkInBlock'              => 'required_if:checkInVisitPlace,Unit',
                        'checkInUnitNo'             => 'required_if:checkInVisitPlace,Unit'
        );

        $customMessages = [
            'checkInVisitorName.required'       => 'Visitor Name is required ',
            'checkInVisitorContactNo.required'  => 'Visitor Contact No. is required ',
            'checkInVisitorNRIC.required'       => 'Visitor NRIC is required ',
            'checkInVisitPlace.required'        => 'Please select a visit place',
            'required_if'   => 'Please fill in both Block and Unit Number'
        ];

        $validator = Validator::make(Request::all(),$rules, $customMessages);
        if(!($validator->passes())){
            $validationErrorString = implode(',',$validator->errors()->all());
            return Response::json(array(
                'error' => true,
                'code' => 400,
                'message' => $validationErrorString
            ));
        }
        //edit
        $VisitorName = Request::get('checkInVisitorName');
        $VisitorContactNo = Request::get('checkInVisitorContactNo');
        $VisitorNRIC = Request::get('checkInVisitorNRIC');
        $VisitPlace = Request::get('checkInVisitPlace');
        $Block = Request::get('checkInBlock');
        $UnitNo = Request::get('checkInUnitNo');


        if($VisitPlace == 'Unit'){
            $unitID = Units::where('Block',$Block)->where('UnitNumber',$UnitNo)->value('UnitID');

            if($unitID){
                $visitor = VisitorLog::where('Visit_UnitID',$unitID)->whereNull('ExitDateTime')->get();

                if(count($visitor) >= 5){
                    return Response::json(array(
                        'error' => true,
                        'code' => 405,
                        'message' => "Access Deny!\nThis unit already have 5 visitor."
                    ));
                }
            }else{
                return Response::json(array(
                    'error' => true,
                    'code' => 400,
                    'message' => "Block/Unit Number not exist. Please confirm the block/Unit Number is correct."
                ));
            }
        }else{
            $unitID = NULL;
        }

        $VisitorNRIC = substr($VisitorNRIC, -3);
        $insertArray = array('Visitor_Name'             => $VisitorName,
                             'Visitor_ContactNumber'    => $VisitorContactNo,
                             'Visitor_NRIC'             => $VisitorNRIC,
                             'VisitPlace'               => $VisitPlace,
                             'Visit_UnitID'             => $unitID,
                             'EnterDateTime'            => $this->now
                            );

        DB::beginTransaction();
        try{
            VisitorLog::create($insertArray);

            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => "Visitor Check In Successfully"
            ));
        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'visitorCheckInAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            Log::error($e);
            return Response::json(array(
                'error' => true,
                'code' => 500,
                'message' => "Fail to Check In Visitor."
            ));
        }
    }

    public function visitorCheckOut(){
        $rules = array(
            'VisitorLogID' => 'required|exists:visitor_logs,VisitorLogID'
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
            $VisitorLogID = Request::get('VisitorLogID');

            VisitorLog::find($VisitorLogID)->update(['ExitDateTime' => $this->now]);

            DB::commit();
            return Response::json(array(
                'error' => false,
                'code' => 200,
                'message' => 'Visitor Check Out Successfully.'
            ));
        }catch(\Exception $e){
            DB::rollback();
            $json = array('text'=> 'visitorCheckOutAPI | '.$e->getMessage());
            $this->apiCtrl->_larkErrorReport($json);
            return Response::json(array(
                'error' => true,
                'code' => 500,
                'message' => $e->getMessage()
            ));
        }
    }
}
