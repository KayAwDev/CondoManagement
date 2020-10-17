function saveEmployee() {
    //save edit
    $('.overlay').css({ 'display': 'block' });
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Employees/editEmployee",
        data: $('#employeeForm').serialize() + '&' + $.param({ 'apiKey': apiKey }),
        success: function(data) {
            var id = $('#username').val();
            if (data.error == false) {
                $('#employeeModal').modal('toggle');
                alertDialog(data.message);
                getDataTable();
            } else
                alert(data.message);
            $('.overlay').css({ 'display': 'none' });
        },
        error: function(data) {
            alert('Problem occur. Please try again');
            $('.overlay').css({ 'display': 'none' });
        }
    });
}

function addEmployee() {
    $('.overlay').css({ 'display': 'block' });
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Employees/addEmployee",
        data: $('#employeeForm').serialize() + '&' + $.param({ 'apiKey': apiKey }),
        success: function(data) {
            if (data.error == false) {
                var id = $('#username').val();
                $('#employeeModal').modal('toggle');
                alertDialog(data.message);
                getDataTable();
            } else
                alert(data.message);
            $('.overlay').css({ 'display': 'none' });
        },
        error: function(data) {
            alert('Problem occur. Please try again');
            $('.overlay').css({ 'display': 'none' });
        }
    });
}

function getEmployeePassword(password) {
    $.ajax({
        type: "GET",
        url: APP_URL + "/api/decryptPassword",
        data: { encrypted_pw: password },
        success: function(data) {
            $('#password').val(data);
            $('#confirmPassword').val(data);
        },
        error: function() {}
    });
}

function getDataTable() {
    document.getElementById("loader").style.display = "block";
    document.getElementById("employeeTbl").style.display = "none";

    $.ajax({
        type: "GET",
        url: APP_URL + "/api/Employees/getEmployeeList",
        data: { apiKey: apiKey },
        complete: function(data) {
            data = data.responseJSON;
            document.getElementById("loader").style.display = "none";
            document.getElementById("employeeTbl").style.display = "inline-table";

            if (data.error == false) {
                data = data['data'];
                if (data.length < 1) {
                    $('#employeeTbl').dataTable().fnClearTable();
                } else {
                    $('#employeeTbl').dataTable().fnClearTable();
                    $('#employeeTbl').dataTable().fnAddData(data);
                }
            } else {
                $('#employeeTbl').dataTable().fnClearTable();
            }
        },
        error: function(jqXHR, exception) {
            if (jqXHR.status == 500) {
                document.getElementById("loader").style.display = "block";
                location.reload();
            } else {
                alert('Error Occured');
                $('#employeeTbl').dataTable().fnClearTable();
                document.getElementById("loader").style.display = "none";
                document.getElementById("employeeTbl").style.display = "inline-table";
            }
        }
    });
}

function deleteEmployee(username) {
    $('.overlay').css({ 'display': 'block' });
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Employees/deleteEmployee",
        data: { 'apiKey': apiKey, 'username': username },
        success: function(data) {
            if (data.error == false) {
                alertDialog(data.message);
                getDataTable();
            } else {
                alert(data.message);
            }
            $('.overlay').css({ 'display': 'none' });
        },
        error: function() {
            alert('Error Occured');
            $('.overlay').css({ 'display': 'none' });
        }
    });
}