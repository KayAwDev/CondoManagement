@extends('layouts.master')
@section('content')
<head>
    <script src=" {{ asset('functions/employees.js') }}"></script>
</head>
<div class="overlay">
	<div id="loading-img"></div>
</div>
<div class="box-header">
    <h3 class="page-header">Employees</h3>
</div>
<div class="addBtn-errorMessage" id="openAddEmployeeDiv">
  <button type="button" class="btn btn-primary" id="openAddEmployeeBtn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
</div>
<div class="box-body" id="boxBody">
    <div id="loader" style="display:none;"></div>
    <table id="employeeTbl" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Name</th>
          <th>User Profile</th>
          <th></th>
        </tr>
      </thead>
      <tbody style="text-align:center;">
      </tbody>
    </table>
</div>

<div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModal">Close</button>
        <button type="button" class="btn btn-primary" id="saveEditedEmployeeBtn">Save</button>
        <button type="button" class="btn btn-primary" id="addEmployeeBtn">Add</button>
        <h4 class="modal-title" id="employeeModalTitle"></h4>
      </div>
      <div class="modal-body">
        <form id="employeeForm" method="POST">
          <div class="tab-content">
            <div class="form-group">
                <label for="username">Account Username</label>
                <input type="text" class="form-control" id="username" name="username" maxlength="50">
            </div>
            <div class="form-group">
                <label for="employeeName">Employee Name</label>
                <input type="text" class="form-control" id="employeeName" name="employeeName" maxlength="100" />
            </div>
            <hr>
            <div class="form-group">
                <label for="userProfile">User Profile</label>
                <select class="form-control" id="userProfile" name="userProfile">
                    @foreach($userProfiles as $userProfile)
                    <option value="{{ $userProfile->code }}">{{ $userProfile->name }}</option>
                    @endforeach
                </select>
            </div>
            <hr>
            <div class="checkbox">
                <label id="enablePasswordLabel">
                <input type="checkbox" id="enablePasswordCheckbox" name="enablePassword">Enable Password Entry
                </label>
                <label id="showPasswordLabel">
                <input type="checkbox" id="showPassword">Show Password
                </label>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" />
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Error Message Confirmation -->
<div id="confirmDelete" title="Delete" style="display:none;">Are you sure you want to delete employee with username '<span id="confirmDeleteEmployeeCode"></span>'?</div>
<!-- End Delete -->

<script>
var APP_URL = {!! json_encode(url('/')) !!};
var apiKey = '<?php echo $apiKey; ?>';
getDataTable();

$("#employeeTbl").DataTable({
  "searching": true,
  "order": [[ 0, "asc" ]],
  "data" : [],
  "columns": [
    { data: 'Emp_Name'},
    { data: 'LevelDesc',render: function(data, type, full){
      if(data)
        return data;
      else
        return '-';
    }},
    { data: 'Emp_Username', render: function(data, type, full){
       var string = '';

        string += '<button class="btn btn-success viewEmployeeButton" title="View" name="viewEmployeeButton"><i class="fa fa-eye" aria-hidden="true"></i></button>';
        string += '<button class="btn btn-warning editEmployeeButton" title="Edit" name="editEmployeeButton"><i class="glyphicon glyphicon-edit"></i></button>';
        string += '<button class="btn btn-danger deleteEmployeeButton" title="Delete" data-employee-username="'+data+'" name="deleteEmployeeButton"><i class="glyphicon glyphicon-trash"></i></button>';

      if(string == '')
        return '-';
      else
        return string;
    }}]
});

$(function(){
  $(document).on('click', 'button[name="viewEmployeeButton"]', function(){
    var row = $(this).closest('tr');
    var data = $('#employeeTbl').DataTable().row(row).data();
    fillData(data);
    $('#enablePasswordCheckbox').prop('disabled',true);
    $('#showPassword').prop('disabled',false);
  })
});

$(function(){
  $(document).on('click', 'button[name="deleteEmployeeButton"]', function(){
    var username = $(this).data('employee-username');
    $('#confirmDelete').data('username', username);
    $('#confirmDeleteEmployeeCode').text(username);
    $("#confirmDelete").dialog("open");
  })
});

function fillData(data){
  getEmployeePassword(data.Emp_Password);
  $('#addEmployeeBtn').css({'display': 'none'});
  $('#saveEditedEmployeeBtn').css({'display': 'none'});
  $("#employeeForm :input").prop("disabled", true);
  $('#employeeModalTitle').text('Employee : '+data.Emp_Name);
  $('#username').val(data.Emp_Username);
  $('#employeeName').val(data.Emp_Name);
  $('#userProfile').val(data.Emp_Level);
  $("#enablePasswordCheckbox").prop('checked', false);
  $('#showPasswordLabel').css('display','inline-block');
  $('#employeeModal').modal('toggle');
}

$('#employeeModal').on('shown.bs.modal', function() {
  $("#showPassword").prop("checked", false);
  $('#password').attr('type','password');
  $('#confirmPassword').attr('type','password');
});

$(function(){
  $(document).on('click', 'button[name="editEmployeeButton"]', function(){
    var row = $(this).closest('tr');
    var data = $('#employeeTbl').DataTable().row(row).data();
    fillData(data);
    $("#employeeForm :input").prop("disabled", false);
    $('#saveEditedEmployeeBtn').css({'display': 'inline-block'});
    $('#editEmployeeBtn').css({'display': 'none'});
    $('#username').prop('readonly', true);
    $('#password').prop('readonly', true);
    $('#confirmPassword').prop('readonly', true);
    $('#enablePasswordLabel').css({'display': 'inline-block'});
  })
});

$('#openAddEmployeeBtn').click(function(event){
  //open modal
  $("#employeeForm :input").prop("disabled", false);
  $('#employeeForm :input').prop('readonly', false);
  $('#saveEditedEmployeeBtn').css({'display': 'none'});
  $('#editEmployeeBtn').css({'display': 'none'});
  $('#enablePasswordLabel').css({'display': 'none'});
  $('#addEmployeeBtn').css({'display': 'inline-block'});
  $('#employeeForm')[0].reset();
  $('#employeeModalTitle').text('Add New Employee');
  $('#showPasswordLabel').css('display','inline-block');
  $('#employeeModal').modal('toggle');
});

$('#enablePasswordCheckbox').change(function() {
    if($(this).is(":checked")) {
      $('#password').prop('readonly', false);
      $('#confirmPassword').prop('readonly', false);
    }else{
      $('#password').prop('readonly', true);
      $('#confirmPassword').prop('readonly', true);
    }
});

$('#showPassword').change(function() {
  if($(this).is(":checked")){
    //show password
    $('#password').attr('type','text');
    $('#confirmPassword').attr('type','text');
  }else{
    //hide password
    $('#password').attr('type','password');
    $('#confirmPassword').attr('type','password');
  }
});

$('#saveEditedEmployeeBtn').click(function(event){
  //check password and confirm password
  var username = $('#username').val();
  var employeeName = $('#employeeName').val();
  var password = $('#password').val();
  var confirmPassword = $('#confirmPassword').val();

  if(username && employeeName){
    if(password == confirmPassword){
      saveEmployee();
    }else{
      alert('Password confirmation does not match password');
    }
  }else{
    alert('Please make sure all the field are fill in.');
  }
});

$('#addEmployeeBtn').click(function(event){
  var username = $('#username').val();
  var employeeName = $('#employeeName').val();
  var password = $('#password').val();
  var confirmPassword = $('#confirmPassword').val();

  if(username && employeeName){
    if(password == confirmPassword){
      addEmployee();
    }else{
      alert('Password confirmation does not match password');
    }
  }else{
    alert('Please make sure all the field are fill in.');
  }
});

$("#confirmDelete").dialog({
  autoOpen: false,
  modal: true,
  buttons : {
    "Yes" : function() {
      deleteEmployee($("#confirmDelete").data('username'));
      $(this).dialog("close");
    },
    "Cancel" : function() {
      $(this).dialog("close");
    }
  }
});

$(function(){
  $('#username').bind('input', function(){
    $(this).val(function(_, v){
     return v.replace(/\s+/g, '');
    });
  });
});
</script>
@stop
