@extends('layouts.master')
@section('content')
<head>
    <script src=" {{ asset('functions/visitorRegistration.js') }}"></script>
</head>
<div class="overlay">
	<div id="loading-img"></div>
</div>
<div class="box-header">
    <h3 class="page-header">Visitor Registration</h3>
</div>
<div class="box-body" id="boxBody">
    <div id="loader" style="display:none;"></div>
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#CheckIn">Check In</a></li>
      <li><a data-toggle="tab" href="#CheckOut">Check Out</a></li>
    </ul>
    <div class="tab-content">
        <!-- CheckIn -->
        <div id="CheckIn" class="tab-pane fade in active">
            <div class="alert alert-success" id="success-alert-checkIn" style="display:none">
                <span></span>
            </div>
            <form id="CheckInForm" method="POST">
                <br>
                <div class="form-group" style="width: 30%;">
                    <label for="checkInVisitPlace">Visit Place</label>
                    <select class="form-control" id="checkInVisitPlace" name="checkInVisitPlace">
                        <option value='Unit'>Unit</option>
                        <option value='Function'>Function Room</option>
                    </select>
                </div>
                <div class="form-group UnitInfo">
                    <div class="leftDiv">
                        <label for="checkInBlock">Block</label>
                        <input type="text" class="form-control" id="checkInBlock" name="checkInBlock">
                    </div>
                    <div class="rightDiv">
                        <label for="checkInUnitNo">Unit Number</label>
                        <input type="text" class="form-control" id="checkInUnitNo" name="checkInUnitNo">
                    </div>
                </div>
                <div class="form-group">
                    <label for="checkInVisitorName">Visitor Name</label>
                    <input type="text" class="form-control" id="checkInVisitorName" name="checkInVisitorName" required>
                </div>
                <div class="form-group">
                    <label for="checkInVisitorContactNo">Visitor Contact Number</label>
                    <input type="text" class="form-control" id="checkInVisitorContactNo" name="checkInVisitorContactNo" required>
                </div>
                <div class="form-group">
                    <label for="checkInVisitorNRIC">Visitor NRIC</label>
                    <input type="text" class="form-control" id="checkInVisitorNRIC" name="checkInVisitorNRIC" placeholder="Last 3 digit" required>
                </div>

            </form>
            <div class="form-group field-width" style="text-align: center;">
                <button type="button" class="btn btn-info btn-lg" id="checkInBtn">Check In</button>
            </div>
        </div>
        <!-- CheckIn end -->
        <!-- CheckOut -->
        <div id="CheckOut" class="tab-pane">
            <div class="alert alert-success" id="success-alert-checkOut" style="display:none">
                <span></span>
            </div>
            <br>
            <div class="row">
                <form id="searchForm">
                    <div class="col-md-6" style="width: 40%;">
                        <label for="checkOutSearchBy">Search by</label>
                        <select class="form-control" id="checkOutSearchBy" name="checkOutSearchBy">
                            <option value='VisitorInfo'>Visitor Info</option>
                            <option value='VisitPlace'>Visit Place</option>
                        </select>
                    </div>
                    <div class="col-md-6 VisitorInfoDiv">
                        <div class="form-group field-width">
                            <label>Visitor Info</label>
                            <input type="text" class="form-control" id="searchVisitorNRIC" name="searchVisitorNRIC" placeholder="Search Visitor NRIC Last 3 Digit">
                            <input type="text" class="form-control" id="searchVisitorPhone" name="searchVisitorPhone" placeholder="Search Visitor Phone Number">
                        </div>
                    </div>
                    <div class="col-md-6 VisitPlaceDiv" style="display:none">
                        <div class="form-group" style="width: 50%;">
                            <label for="searchVisitPlace">Visit Place</label>
                            <select class="form-control" id="searchVisitPlace" name="searchVisitPlace">
                                <option value='Unit'>Unit</option>
                                <option value='Function'>Function Room</option>
                            </select>
                        </div>
                        <div class="form-group field-width">
                            <input type="text" class="form-control" id="searchBlock" name="searchBlock" placeholder="Search Block" >
                            <input type="text" class="form-control" id="searchUnit" name="searchUnit" placeholder="Search Unit" >
                        </div>
                    </div>
                </form>
            </div>
            <div class="form-group field-width" style="margin-bottom: 10px;">
                <button type="button" class="btn btn-info" id="searchBtn">Search</button>
            </div>
            <table id="VisitorTbl" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Visitor Name</th>
                  <th>Visitor Contact Number</th>
                  <th>Visit Place</th>
                  <th>Visitor Enter DateTime</th>
                  <th>Visitor Exit DateTime</th>
                  <th></th>
                </tr>
              </thead>
              <tbody style="text-align:center;">
              </tbody>
            </table>
          </div>
          <!-- CheckOut end -->
    </div>
</div>

<div class="modal fade" id="VisitorLogModal" tabindex="-1" role="dialog" aria-labelledby="VisitorLogModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModal">Close</button>
          <h4 class="modal-title" id="VisitorLogModalTitle" style="text-overflow: ellipsis;overflow: hidden;"></h4>
        </div>
        <div class="modal-body">
          <form id="VisitorLogForm" method="POST">
              <!-- VisitorLog -->
              <div id="unit" class="tab-pane fade in active">
                  <div>
                      <div class="leftDiv">
                          <label for="EnterDateTime">Enter Date and Time</label>
                          <input type="text" class="form-control" id="EnterDateTime" name="EnterDateTime">
                      </div>
                      <div class="rightDiv">
                          <label for="ExitDateTime">Exit Date and Time</label>
                          <input type="text" class="form-control" id="ExitDateTime" name="ExitDateTime">
                      </div>
                  </div>
                  <br>
                  <div class="form-group">
                      <label for="VisitorName">Visitor Name</label>
                      <input type="text" class="form-control" id="VisitorName" name="VisitorName"><br/>
                  </div>
                  <div class="form-group">
                      <label for="VisitorContactNo">Visitor Contact Number</label>
                      <input type="text" class="form-control" id="VisitorContactNo" name="VisitorContactNo"><br/>
                  </div>
                  <div class="form-group">
                      <label for="VisitorNRIC">Visitor NRIC</label>
                      <input type="text" class="form-control" id="VisitorNRIC" name="VisitorNRIC"><br/>
                  </div>
                  <div class="form-group">
                      <label for="VisitPlace">Visit Place</label>
                      <input type="text" class="form-control" id="VisitPlace" name="VisitPlace"><br/>
                  </div>
                  <input type="hidden" id="VisitorLogID" name="VisitorLogID" />
              </div>
              <!-- VisitorLog end -->
          </form>
        </div>
      </div>
    </div>
  </div>


<script type="text/javascript">
var APP_URL = {!! json_encode(url('/')) !!};
var VisitorTbl;
var apiKey = '<?php echo $apiKey; ?>';

//Table
$("#VisitorTbl").DataTable({
  "searching": true,
  "order": [[ 3, "desc" ]],
  "data" : [],
  "columns": [
    {data: 'Visitor_Name'},
    {data: 'Visitor_ContactNumber'},
    {data: 'VisitPlace', render: function(data,type,full){
        if(data == 'Function'){
            return 'Function Room';
        }else{
            return full.Block+'-'+full.UnitNumber;
        }
    }},
    {data: 'EnterDateTime'},
    {data: 'ExitDateTime', render: function(data,type,full){
        if(data){
            return data;
        }else{
            return '-';
        }
    }},
    {data: 'VisitorLogID',render: function(data,type,full){
        var string = '';
        string += '<button class="btn btn-success viewVisitorButton" title="View" name="viewVisitorButton"><i class="fa fa-eye" aria-hidden="true"></i></button>';
        string += '<button class="btn btn-danger checkOutButton" title="Check Out" name="checkOutButton">CHECK OUT</button>';
        return string;
    }}]
});
//Table End

//Buttons Function
//View Visitor
$(function(){
  $(document).on('click', 'button[name="viewVisitorButton"]', function(e){
    $('.overlay').css({'display':'block'});
    e.preventDefault();
    var row = $(this).closest('tr');
    var data = $('#VisitorTbl').DataTable().row(row).data();
    $('#VisitorLogForm')[0].reset();
    fillData(data);
    $('#VisitorLogModalTitle').text('Visitor Detail');
    $("#VisitorLogForm :input").prop("readonly", true);
    $('.overlay').css({'display':'none'});
  })
});

//CheckOut
$(function(){
  $(document).on('click', 'button[name="checkOutButton"]', function(){
    var row = $(this).closest('tr');
    var data = $('#VisitorTbl').DataTable().row(row).data();
    var id = data.VisitorLogID;
    checkOut(id);
  })
});

//CheckIn
$('#checkInBtn').click(function(event){
    checkIn();
});

//Search
$('#searchBtn').click(function(event){
    getCheckOutDataTable();
    $('#searchForm')[0].reset();
    $('#checkOutSearchBy').trigger("change");
});
//End buttons

//Fill Data
function fillData(data){
    $('#VisitorLogID').val(data.VisitorLogID);
    $('#VisitorName').val(data.Visitor_Name);
    $('#VisitorContactNo').val(data.Visitor_ContactNumber);
    $('#VisitorNRIC').val(data.Visitor_NRIC);
    if(data.VisitPlace == 'Function'){
        var visitPlace = 'Function Room';
    }else if(data.VisitPlace == 'Unit'){
        var visitPlace = data.Block+'-'+data.UnitNumber;
    }else{
        var visitPlace = '-';
    }
    $('#VisitPlace').val(visitPlace);
    $('#EnterDateTime').val(data.EnterDateTime);
    if(data.ExitDateTime){
        var ExitDateTime = data.ExitDateTime;
    }else{
        var ExitDateTime = '-';
    }
    $('#ExitDateTime').val(ExitDateTime);
    $('#VisitorLogModal').modal('toggle');
}

$('#checkInVisitPlace').change(function(){
  var VisitPlace = $(this).val();
  if(VisitPlace == 'Unit'){
    $('.UnitInfo').css({'display':'block'});
  }else if(VisitPlace == 'Function'){
    $('.UnitInfo').css({'display':'none'});
  }
});

$('#checkOutSearchBy').change(function(){
  var selection = $(this).val();
  if(selection == 'VisitPlace'){
    $('.VisitPlaceDiv').css({'display':'block'});
    $('.VisitorInfoDiv').css({'display':'none'});
    $('#searchVisitPlace').val('Unit');
    $('#searchVisitPlace').trigger("change");
  }else if(selection == 'VisitorInfo'){
    $('.VisitPlaceDiv').css({'display':'none'});
    $('.VisitorInfoDiv').css({'display':'block'});
  }
});

$('#searchVisitPlace').change(function(){
  var VisitPlace = $(this).val();
  if(VisitPlace == 'Unit'){
    $('#searchBlock').prop("disabled", false);
    $('#searchUnit').prop("disabled", false);
  }else if(VisitPlace == 'Function'){
    $('#searchBlock').prop("disabled", true);
    $('#searchUnit').prop("disabled", true);
  }
});

</script>
@stop
