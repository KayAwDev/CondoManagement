@extends('layouts.master')
@section('content')
<head>
    <script src=" {{ asset('functions/units.js') }}"></script>
</head>
<div class="overlay">
	<div id="loading-img"></div>
</div>
<div class="box-header">
    <h3 class="page-header">Units</h3>
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
                            <div class="form-group field-width">
                                <input type="text" class="form-control" id="searchBlock" name="searchBlock" placeholder="Block">
                                <input type="text" class="form-control" id="searchUnit" name="searchUnit" placeholder="Unit Number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm" {{-- style="width: 80%;" --}}>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <span id="searchTypeText">Search Tenant Type</span>
                                    <span class="fa fa-caret-down"></span></button>
                                    <ul class="dropdown-menu searchTypeList">
                                        <li class="active" id="name"><a href="javascript:changeSearchType('name')">Name</a></li>
                                        <li id="phone"><a href="javascript:changeSearchType('phone')">Phone</a></li>
                                    </ul>
                                </div>
                                <input type="text" class="form-control" placeholder="Search Tenant Info" id="searchInputText" name="searchInput">
                            </div>
                            <input type="hidden" name="searchType" id="searchType" value="name"/>
                        </div>
                    </form>
                </div>
                <!-- /.row -->
                <div class="form-group field-width" style="margin: 5px 0px 5px;">
                    <button type="button" class="btn btn-info" id="searchBtn">Search</button>
                    <button type="button" class="btn btn-info" id="resetBtn">Reset</button>
                </div>
            </div>
            <!-- ./box-body -->
        </div>
        <!-- /.box -->
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-primary" id="openAddUnitBtn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Unit</button>
    </div>
    <table id="UnitTbl" class="table table-bordered table-hover" width="100%">
      <thead>
        <tr>
          <th>No.</th>
          <th>Block</th>
          <th>Unit</th>
          <th>Owner</th>
          <th></th>
        </tr>
      </thead>
      <tbody style="text-align:center;">
      </tbody>
    </table>
</div>

<div class="modal fade" id="UnitModal" tabindex="-1" role="dialog" aria-labelledby="UnitModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModal">Close</button>
        <button type="button" class="btn btn-primary" id="saveEditedUnitBtn">Save</button>
        <button type="button" class="btn btn-primary" id="addUnitBtn">Add</button>
        <h4 class="modal-title" id="UnitModalTitle" style="text-overflow: ellipsis;overflow: hidden;"></h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" id="success-alert" style="display:none">
            <span></span>
        </div>
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#unit">Unit</a></li>
          <li><a data-toggle="tab" href="#unitTenant" id="UnitTenantTab">Unit Detail</a></li>
        </ul>
        <form id="UnitForm" method="POST">
          <div class="tab-content">
            <!-- Unit -->
            <div id="unit" class="tab-pane fade in active">
                <div class="form-group unitCreatedDateTimeDiv" style="margin-top: 10px;">
                    <label for="unitCreatedDateTime">Record Created Date and Time: </label>
                    <label class="unitCreatedDateTime" id="unitCreatedDateTime" style="color:grey; padding-left: 10px;"></label>
                </div>
                <div>
                    <div class="leftDiv">
                        <label for="Block">Block</label>
                        <input type="text" class="form-control" id="Block" name="Block"><br/>
                    </div>
                    <div class="rightDiv">
                        <label for="UnitNo">Unit Number</label>
                        <input type="text" class="form-control" id="UnitNo" name="UnitNo"><br/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Owner">Owner Name</label>
                    <input type="text" class="form-control" id="Owner" name="Owner">
                </div>
                <div class="form-group">
                    <label for="OwnerContactNo">Owner Contact Number</label>
                    <input type="text" class="form-control" id="OwnerContactNo" name="OwnerContactNo">
                </div>
                <input type="hidden" id="UnitID" name="UnitID" />
            </div>
            <!-- Unit end -->

            <!-- UnitTenant -->
            <div id="unitTenant" class="tab-pane fade">
              <div class="addBtn-UnitTenant" id="openAddUnitTenantDiv">
                <button type="button" class="btn btn-primary" id="openAddUnitTenantBtn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tenant</button>
              </div>
              <table id="UnitTenantTbl" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Tenant ID</th>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody style="text-align:center;">
                </tbody>
              </table>
            </div>
            <!-- UnitTenant end -->
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="UnitTenantModal" tabindex="-1" role="dialog" aria-labelledby="UnitTenantModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModal">Close</button>
        <button type="button" class="btn btn-primary" id="saveEditedUnitTenantBtn">Save</button>
        <button type="button" class="btn btn-primary" id="addUnitTenantBtn">Add</button>
        <h4 class="modal-title" id="UnitTenantModalTitle" style="text-overflow: ellipsis;overflow: hidden;"></h4>
      </div>
      <div class="modal-body">
        <form id="UnitTenantForm" method="POST">
            <div class="form-group unitTenantCreatedDateTimeDiv" style="margin-top: 10px;">
                <label for="unitTenantCreatedDateTime">Record Inserted Date and Time: </label>
                <label class="unitTenantCreatedDateTime" id="unitTenantCreatedDateTime" style="color:grey; padding-left: 10px;"></label>
            </div>
            <div class="form-group">
                <label for="TenantName">Tenant Name</label>
                <input type="text" class="form-control" id="TenantName" name="TenantName"><br/>
            </div>
            <div class="form-group">
                <label for="TenantContactNo">Tenant Contact Number</label>
                <input type="text" class="form-control" id="TenantContactNo" name="TenantContactNo"><br/>
            </div>
            <input type="hidden" id="UnitTenantID" name="UnitTenantID" />
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Error Message Confirmation -->
<div id="confirmDeleteUnit" title="Delete Unit" style="display:none;">All visitor record and tenant data in this unit will be deleted. Are you sure you want to delete unit '<span id="confirmDeleteUnitNumber"></span>'? </div>
<div id="confirmDeleteUnitTenant" title="Delete Tenant" style="display:none;">Are you sure you want to delete this tenant '<span id="confirmDeleteUnitTenantName"></span>' from unit? </div>
<!-- End Delete -->

<script type="text/javascript">
var APP_URL = {!! json_encode(url('/')) !!};
var UnitTbl, UnitTenantTbl;
var apiKey = '<?php echo $apiKey; ?>';
getUnitDataTable();

//get list active
$('#searchTypeText').text($('.searchTypeList .active').text());
//end list active

//Model
$('#UnitModal').on('show.bs.modal', function(e) {
    $('a[href="#unit"]').tab('show');
    $('a[href="#unitTenant"]').css({ 'display': 'none' });
    $('.unitCreatedDateTimeDiv').css({ 'display': 'none' });
});

$('#UnitTenantModal').on('show.bs.modal', function(e) {
    $('.unitTenantCreatedDateTimeDiv').css({ 'display': 'none' });
})
//Model End

//Table
$("#UnitTbl").DataTable({
    "searching": true,
    "order": [
        [0, "desc"]
    ],
    "data": [],
    "columnDefs": [
        { "width": "25%", "targets": 0 }
    ],
    "columns": [
        { data: 'UnitID' },
        { data: 'Block' },
        { data: 'UnitNumber' },
        { data: 'UnitOwner' },
        {
            data: 'UnitID',
            render: function(data, type, full) {
                var string = '';
                string += '<button class="btn btn-success viewUnitButton" title="View" name="viewUnitButton"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                string += '<button class="btn btn-warning editUnitButton" title="Edit" name="editUnitButton"><i class="glyphicon glyphicon-edit"></i></button>';
                string += '<button class="btn btn-danger deleteUnitButton" title="Delete" name="deleteUnitButton"><i class="glyphicon glyphicon-trash"></i></button>';

                if (string == '')
                    return '-';
                else
                    return string;
            }
        }
    ]
});

$(function() {
    UnitTenantTbl = $("#UnitTenantTbl").DataTable({
        "searching": false,
        "order": [
            [1, "asc"]
        ],
        "data": [],
        "columnDefs": [
            { "width": "80%", "targets": 0 }
        ],
        "columns": [
            { data: 'TenantID' },
            { data: 'Tenant_Name' },
            { data: 'Tenant_ContactNumber' },
            {
                data: 'TenantID',
                render: function(data, type, full) {
                    var string = '';

                    string += '<button class="btn btn-success viewUnitTenantButton" title="View" name="viewUnitTenantButton"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                    string += '<button class="btn btn-warning editUnitTenantButton" title="Edit" name="editUnitTenantButton"><i class="glyphicon glyphicon-edit"></i></button>';
                    string += '<button class="btn btn-danger deleteUnitTenantButton" title="Delete" name="deleteUnitTenantButton"><i class="glyphicon glyphicon-trash"></i></button>';

                    if (string == '')
                        return '-';
                    else
                        return string;
                }
            }
        ]
    });
});


//Buttons Function
$('#openAddUnitBtn').click(function(event) {
    //open modal
    $("#UnitForm :input").prop("disabled", false);
    $("#UnitForm :input").prop("readonly", false);
    $('#saveEditedUnitBtn').css({ 'display': 'none' });
    $('#editUnitBtn').css({ 'display': 'none' });
    $('#addUnitBtn').css({ 'display': 'inline-block' });
    $('#UnitForm')[0].reset();
    $('#UnitModalTitle').text('Add New Unit');
    $('#UnitModal').modal('toggle');
});

//View Unit
$(function() {
    $(document).on('click', 'button[name="viewUnitButton"]', function(e) {
        $('.overlay').css({ 'display': 'block' });
        e.preventDefault();
        $('#addUnitBtn').css({ 'display': 'none' });
        $('#saveEditedUnitBtn').css({ 'display': 'none' });

        var row = $(this).closest('tr');
        var data = $('#UnitTbl').DataTable().row(row).data();
        $('#UnitForm')[0].reset();
        fillData(data);
        $('#UnitModalTitle').text('View #' + data.Block + '-' + data.UnitNumber);
        $("#UnitForm :input").prop("readonly", true);
        $('a[href="#unitTenant"]').css({ 'display': 'block' });
    })
});

//Edit Unit
$(function() {
    $(document).on('click', 'button[name="editUnitButton"]', function(e) {
        $('.overlay').css({ 'display': 'block' });
        e.preventDefault();
        $('#addUnitBtn').css({ 'display': 'none' });
        $('#saveEditedUnitBtn').css({ 'display': 'inline-block' });

        var row = $(this).closest('tr');
        var data = $('#UnitTbl').DataTable().row(row).data();
        $('#UnitForm')[0].reset();
        fillData(data);
        $('#UnitModalTitle').text('Edit #' + data.Block + '-' + data.UnitNumber);
        $("#UnitForm :input").prop("readonly", false);
        $('a[href="#unitTenant"]').css({ 'display': 'block' });
    })
});

$(function() {
    $(document).on('click', 'button[name="deleteUnitButton"]', function() {
        var row = $(this).closest('tr');
        var data = $('#UnitTbl').DataTable().row(row).data();
        $('#confirmDeleteUnit').data('id', data.UnitID);
        $('#confirmDeleteUnitNumber').text(data.Block + '-' + data.UnitNumber);
        $("#confirmDeleteUnit").dialog("open");
    })
});

$('#openAddUnitTenantBtn').click(function(event) {
    //open modal
    $("#UnitTenantForm :input").prop("readonly", false);
    $('#saveEditedUnitTenantBtn').css({ 'display': 'none' });
    $('#editUnitTenantBtn').css({ 'display': 'none' });
    $('#addUnitTenantBtn').css({ 'display': 'inline-block' });
    $('#UnitTenantForm')[0].reset();
    $('#UnitTenantModalTitle').text('Add New Tenant');
    $('#UnitTenantModal').modal('toggle');
});

//View UnitTenant
$(function() {
    $(document).on('click', 'button[name="viewUnitTenantButton"]', function(e) {
        e.preventDefault();
        $('#addUnitTenantBtn').css({ 'display': 'none' });
        $('#saveEditedUnitTenantBtn').css({ 'display': 'none' });

        var row = $(this).closest('tr');
        var data = $('#UnitTenantTbl').DataTable().row(row).data();
        fillUnitTenantData(data);
        $("#UnitTenantForm :input").prop("readonly", true);
        $('#UnitTenantModalTitle').text('View Tenant Detail');
    })
});

//Edit UnitTenant
$(function() {
    $(document).on('click', 'button[name="editUnitTenantButton"]', function(e) {
        e.preventDefault();
        $('#addUnitTenantBtn').css({ 'display': 'none' });
        $('#saveEditedUnitTenantBtn').css({ 'display': 'inline-block' });
        var row = $(this).closest('tr');
        var data = $('#UnitTenantTbl').DataTable().row(row).data();
        $('#UnitTenantModalTitle').text('Edit Tenant Detail');
        fillUnitTenantData(data);
        $("#UnitTenantForm :input").prop("readonly", false);
    })
});

//Delete UnitTenant
$(function() {
    $(document).on('click', 'button[name="deleteUnitTenantButton"]', function(e) {
        e.preventDefault();
        var row = $(this).closest('tr');
        var data = $('#UnitTenantTbl').DataTable().row(row).data();
        $('#confirmDeleteUnitTenant').data('id', data.TenantID);
        $('#confirmDeleteUnitTenantName').text(data.Tenant_Name);
        $("#confirmDeleteUnitTenant").dialog("open");
    })
});

$('#addUnitBtn').click(function(event) {
    addUnit();
});

$('#saveEditedUnitBtn').click(function(event) {
    editUnit();
});

$('#addUnitTenantBtn').click(function(event) {
    addUnitTenant();
});

$('#saveEditedUnitTenantBtn').click(function(event) {
    editUnitTenant();
});

//Search Unit
$('#searchBtn').click(function(event) {
    getUnitDataTable();
});

$('#resetBtn').click(function(event) {
    $('#searchForm')[0].reset();
    getUnitDataTable();
});
//End buttons

//Fill Data
function fillData(data) {
    $('#UnitID').val(data.UnitID);
    $('#Block').val(data.Block);
    $('#UnitNo').val(data.UnitNumber);
    $('#Owner').val(data.UnitOwner);
    $('#OwnerContactNo').val(data.Owner_ContactNumber);
    if(data.CreatedDateTime){
        var CreatedDateTime = data.CreatedDateTime;
        var CreatedDateTime = CreatedDateTime.split('.')[0];
    }else{
        var CreatedDateTime = '-';
    }
    $('#unitCreatedDateTime').text(CreatedDateTime);
    getUnitTenantDataTable(data.UnitID);
    $('#UnitTenantTab').data('UnitID', data.UnitID);
    $('#UnitModalTitle').text('Unit Detail');
    $('#UnitModal').modal('toggle');
    $('.unitCreatedDateTimeDiv').css({ 'display': 'block' });
}

function fillUnitTenantData(data) {
    $('#UnitTenantID').val(data.TenantID);
    $('#TenantName').val(data.Tenant_Name);
    $('#TenantContactNo').val(data.Tenant_ContactNumber);
    if(data.CreatedDateTime){
        var CreatedDateTime = data.CreatedDateTime;
        var CreatedDateTime = CreatedDateTime.split('.')[0];
    }else{
        var CreatedDateTime = '-';
    }
    $("#unitTenantCreatedDateTime").text(CreatedDateTime);
    $('#UnitTenantModal').modal('toggle');
    $('.unitTenantCreatedDateTimeDiv').css({ 'display': 'block' });
}
//END Fill Data

$("#confirmDeleteUnit").dialog({
    autoOpen: false,
    modal: true,
    buttons: {
        "Yes": function() {
            deleteUnit($("#confirmDeleteUnit").data('id'));
            $(this).dialog("close");
        },
        "Cancel": function() {
            $(this).dialog("close");
        }
    }
});

$("#confirmDeleteUnitTenant").dialog({
    autoOpen: false,
    modal: true,
    appendTo: "#UnitModal",
    buttons: {
        "Yes": function() {
            deleteUnitTenant($("#confirmDeleteUnitTenant").data('id'));
            $(this).dialog("close");
        },
        "Cancel": function() {
            $(this).dialog("close");
        }
    }
});

function changeSearchType(type) {
    $('#searchType').val(type);
    $('.searchTypeList li').removeClass('active');
    $('#' + type).addClass('active');
    $('#searchTypeText').text($('#' + type).text());
}

</script>
@stop
