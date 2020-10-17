@extends('layouts.master')
@section('content')
<head>
    <script src=" {{ asset('functions/visitorLog.js') }}"></script>
</head>
<div class="overlay">
	<div id="loading-img"></div>
</div>
<div class="box-header">
    <h3 class="page-header">Visitor Log</h3>
</div>
<div class="box-body" id="boxBody">
    <div id="loader" style="display:none;"></div>
    <div class="col-md-12">
        <div class="box collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title">Filter</h3>
                <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
                </div>
            </div>
            <div class="box-body" width="auto">
                <div class="row">
                    <form id="searchForm">
                        <div class="col-md-6">
                            <div  class="form-group field-width">
                                <label class="radio-inline" style="margin-right:20px;"><input type="radio" name="DateType" value='Enter' checked>Enter DateTime</label>
                                <label class="radio-inline" ><input type="radio" name="DateType" value='Exit'>Exit DateTime</label>
                            </div>
                            <div class="input-daterange input-group field-width" id="datepicker">
                                <input class="input-sm form-control datepicker" name="DateFrom" placeholder="Start Date" id="searchStartDate"/>
                                <span class="input-group-addon" style="background-color: #00c0ef; border-color: #00acd6; color: white;">To</span>
                                <input class="input-sm form-control datepicker" name="DateTo" placeholder="End Date" id="searchEndDate"/>
                            </div>
                            <br>
                            <div class="form-group field-width">
                                <input type="text" class="form-control" id="searchVisitorNRIC" name="searchVisitorNRIC" placeholder="Visitor NRIC Last 3 Digit">
                                <input type="text" class="form-control" id="searchVisitorPhone" name="searchVisitorPhone" placeholder="Visitor Phone Number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" style="width: 50%;">
                                <label for="searchVisitPlace">Visit Place</label>
                                <select class="form-control" id="searchVisitPlace" name="searchVisitPlace">
                                    <option value='Unit'>Unit</option>
                                    <option value='Function'>Function Room</option>
                                </select>
                            </div>
                            <div class="form-group field-width">
                                <input type="text" class="form-control" id="searchBlock" name="searchBlock" placeholder="Search Block" >
                                <input type="text" class="form-control" id="searchUnit" name="searchUnit" placeholder="Search Unit Number" >
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.row -->
                <div class="form-group field-width" style="margin: 10px 0px 5px;">
                    <button type="button" class="btn btn-info" id="searchBtn">Search</button>
                    <button type="button" class="btn btn-info" id="resetBtn">Reset</button>
                </div>
            </div>
            <!-- ./box-body -->
        </div>
        <!-- /.box -->
    </div>

    <table id="VisitorLogTbl" class="table table-bordered table-hover" width="100%">
      <thead>
        <tr>
          <th>Visitor Name</th>
          <th>Visit Place</th>
          <th>Enter Date and Time</th>
          <th>Exit Date and Time</th>
          <th></th>
        </tr>
      </thead>
      <tbody style="text-align:center;">
      </tbody>
    </table>
</div>

<div class="modal fade" id="VisitorLogModal" tabindex="-1" role="dialog" aria-labelledby="VisitorLogModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModal">Close</button>
        <button type="button" class="btn btn-primary" id="saveEditedVisitorLogBtn">Save</button>
        <h4 class="modal-title" id="VisitorLogModalTitle" style="text-overflow: ellipsis;overflow: hidden;"></h4>
      </div>
      <div class="modal-body">
        <form id="VisitorLogForm" method="POST">
            <!-- VisitorLog -->
            <div id="unit" class="tab-pane fade in active">
                <div>
                    <div class="leftDiv">
                        <label for="EnterDateTime">Enter Date and Time</label>
                        <input type="text" class="form-control" id="EnterDateTime" name="EnterDateTime"><br/>
                    </div>
                    <div class="rightDiv">
                        <label for="ExitDateTime">Exit Date and Time</label>
                        <input type="text" class="form-control" id="ExitDateTime" name="ExitDateTime"><br/>
                    </div>
                </div>
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
var VisitorLogTbl;
var apiKey = '<?php echo $apiKey; ?>';

getVisitorLogDataTable();

//Date Picker
$(function() {
  $("#datepicker").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    endDate : new Date()
  });
});
//End Date Picker

//Table
$("#VisitorLogTbl").DataTable({
  "searching": true,
  "order": [[2,'desc']],
  "data" : [],
  "columns": [
    {data: 'Visitor_Name'},
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
        string += '<button class="btn btn-success viewVisitorLogButton" title="View" name="viewVisitorLogButton"><i class="fa fa-eye" aria-hidden="true"></i></button>';
        string += '<button class="btn btn-warning editVisitorLogButton" title="Edit" name="editVisitorLogButton"><i class="glyphicon glyphicon-edit"></i></button>';

        if(string == '')
            return '-';
        else
            return string;
    }}]
});

//View VisitorLog
$(function(){
  $(document).on('click', 'button[name="viewVisitorLogButton"]', function(e){
    $('.overlay').css({'display':'block'});
    e.preventDefault();
    $('#saveEditedVisitorLogBtn').css({'display': 'none'});

    var row = $(this).closest('tr');
    var data = $('#VisitorLogTbl').DataTable().row(row).data();
    $('#VisitorLogForm')[0].reset();
    fillData(data);
    $('#VisitorLogModalTitle').text('#View Visitor Log Detail');
    $("#VisitorLogForm :input").prop("readonly", true);
    $('.overlay').css({'display':'none'});
  })
});

//Edit VisitorLog
$(function(){
  $(document).on('click', 'button[name="editVisitorLogButton"]', function(e){
    $('.overlay').css({'display':'block'});
    e.preventDefault();
    $('#saveEditedVisitorLogBtn').css({'display': 'inline-block'});

    var row = $(this).closest('tr');
    var data = $('#VisitorLogTbl').DataTable().row(row).data();
    $('#VisitorLogForm')[0].reset();
    fillData(data);
    $('#VisitorLogModalTitle').text('#Edit Visitor Log Detail');
    $("#VisitorLogForm :input").prop("readonly", true);
    $("#ExitDateTime").prop("readonly", false);
    $('.overlay').css({'display':'none'});
  })
});

$('#saveEditedVisitorLogBtn').click(function(event){
    editVisitorLog();
});

$('#resetBtn').click(function(event) {
    $('#searchForm')[0].reset();
    getVisitorLogDataTable();
});

//Search VisitorLog
$('#searchBtn').click(function(event){
  var searchStartDate = $('#searchStartDate').val();
  var searchEndDate = $('#searchEndDate').val();

  if(searchStartDate || searchEndDate){
    var arrStartDate = searchStartDate.split("-");
    var startDate = new Date(arrStartDate[0], arrStartDate[1]-1, arrStartDate[2]);
    var arrEndDate = searchEndDate.split("-");
    var endDate = new Date(arrEndDate[0], arrEndDate[1]-1, arrEndDate[2]);

    if(startDate > endDate){
        alert('Please make sure the entered time period is valid.');
    }else{
        getVisitorLogDataTable();
    }
  }else{
    getVisitorLogDataTable();
  }
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
    $('#ExitDateTime').val(data.ExitDateTime);
    $('#VisitorLogModal').modal('toggle');
}


$('#searchVisitPlace').change(function(){
  var VisitPlace = $(this).val();
  if(VisitPlace == 'Unit'){
    $('#searchBlock').prop("disabled", false);
    $('#searchUnit').prop("disabled", false);
  }else if(VisitPlace == 'Function'){
    $('#searchBlock').prop("disabled", true);
    $('#searchUnit').prop("disabled", true);
    $('#searchBlock').val('');
    $('#searchUnit').val('');
  }
});
</script>
@stop
