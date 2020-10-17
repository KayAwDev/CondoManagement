//Get DataTable
function getUnitDataTable() {
    document.getElementById("loader").style.display = "block";
    document.getElementById("UnitTbl").style.display = "none";

    $.ajax({
        type: "GET",
        url: APP_URL + "/api/Units/getAllUnit",
        dataType: 'json',
        data: $('#searchForm').serialize() + '&' + $.param({ 'apiKey': apiKey }),
        success: function(data) {
            document.getElementById("loader").style.display = "none";
            document.getElementById("UnitTbl").style.display = "inline-table";
            if (data.error == false) {
                var data = data['data'];
                if (data.length < 1) {
                    $('#UnitTbl').dataTable().fnClearTable();
                } else {
                    $('#UnitTbl').dataTable().fnClearTable();
                    $('#UnitTbl').dataTable().fnAddData(data);
                }
            } else {
                $('#UnitTbl').dataTable().fnClearTable();
            }
        },
        error: function(jqXHR, exception) {
            if (jqXHR.status == 500) {
                document.getElementById("loader").style.display = "block";
                location.reload();
            } else {
                $('#UnitTbl').dataTable().fnClearTable();
                document.getElementById("loader").style.display = "none";
                document.getElementById("UnitTbl").style.display = "inline-table";
                alert('Error Occured');
            }
        }
    });
}

function getUnitTenantDataTable(UnitID) {
    $('.overlay').css({ 'display': 'block' });
    $('#openAddUnitTenantBtn').css({ 'display': 'inline-block' });
    $.ajax({
        type: "GET",
        url: APP_URL + "/api/Units/getUnitTenants",
        data: $.param({ 'apiKey': apiKey, 'UnitID': UnitID }),
        success: function(data) {
            if (data.error == false) {
                var returnedData = data;
                var data = data['data'];
                if (data.length < 1) {
                    $('#UnitTenantTbl').dataTable().fnClearTable();
                } else {
                    $('#UnitTenantTbl').dataTable().fnClearTable();
                    $('#UnitTenantTbl').dataTable().fnAddData(data);

                    $('#openAddUnitTenantBtn').prop('disabled', false);
                    $('.editUnitTenantButton').prop('disabled', false);
                }
            } else {
                $('#UnitTenantTbl').dataTable().fnClearTable()
            }
            $('.overlay').css({ 'display': 'none' });
        },
        error: function() {
            getUnitTenantDataTable(UnitID);
        }
    });
}
//Table End


function addUnit() {
    //add
    $('.overlay').css({ 'display': 'block' });
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Units/addUnit",
        data: $('#UnitForm').serialize() + '&' + $.param({ 'apiKey': apiKey }),
        success: function(data) {
            if (data.error == false) {
                $('#UnitModal').modal('toggle');
                alertDialog(data.message);
                getUnitDataTable();
            } else
                alert(data.message);
            $('.overlay').css({ 'display': 'none' });
        },
        error: function() {
            alert('Problem occur. Please try again');
            $('.overlay').css({ 'display': 'none' });
        }
    });
}

function editUnit() {
    //edit
    $('.overlay').css({ 'display': 'block' });
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Units/editUnit",
        data: $('#UnitForm').serialize() + '&' + $.param({ 'apiKey': apiKey }),
        success: function(data) {
            if (data.error == false) {
                $('#success-alert span').text(data.message);
                $('#success-alert').fadeIn('slow', function() {
                    $(this).delay(2500).fadeOut();
                });
                getUnitDataTable();
            } else
                alert(data.message);
            $('.overlay').css({ 'display': 'none' });
        },
        error: function() {
            alert('Problem occur. Please try again');
            $('.overlay').css({ 'display': 'none' });
        }
    });
}

function deleteUnit(id) {
    $('.overlay').css({ 'display': 'block' });
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Units/deleteUnit",
        data: { 'apiKey': apiKey, 'UnitID': id },
        success: function(data) {
            if (data.error == false) {
                alertDialog(data.message);
                getUnitDataTable();
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

function addUnitTenant() {
    //add
    $('.overlay').css({ 'display': 'block' });
    var UnitID = $('#UnitTenantTab').data('UnitID');
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Units/addUnitTenant",
        data: $('#UnitTenantForm').serialize() + '&' + $.param({ 'apiKey': apiKey, 'UnitID': UnitID }),
        success: function(data) {
            if (data.error == false) {
                $('#UnitTenantModal').modal('toggle');
                alertDialog(data.message);
                getUnitDataTable();
                getUnitTenantDataTable(UnitID);
            } else
                alert(data.message);
            $('.overlay').css({ 'display': 'none' });
        },
        error: function() {
            alert('Problem occur. Please try again');
            $('.overlay').css({ 'display': 'none' });
        }
    });
}

function editUnitTenant() {
    //edit
    $('.overlay').css({ 'display': 'block' });
    var UnitID = $('#UnitTenantTab').data('UnitID');
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Units/editUnitTenant",
        data: $('#UnitTenantForm').serialize() + '&' + $.param({ 'apiKey': apiKey, 'UnitID': UnitID }),
        success: function(data) {
            if (data.error == false) {
                $('#UnitTenantModal').modal('toggle');
                alertDialog(data.message);
                getUnitDataTable();
                getUnitTenantDataTable(UnitID);
            } else
                alert(data.message);
            $('.overlay').css({ 'display': 'none' });
        },
        error: function() {
            alert('Problem occur. Please try again');
            $('.overlay').css({ 'display': 'none' });
        }
    });
}

function deleteUnitTenant(tenantId) {
    $('.overlay').css({ 'display': 'block' });
    var UnitID = $('#UnitTenantTab').data('UnitID');
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Units/deleteUnitTenant",
        data: { 'apiKey': apiKey, 'UnitTenantID': tenantId },
        success: function(data) {
            if (data.error == false) {
                alertDialog(data.message);
                getUnitDataTable();
                getUnitTenantDataTable(UnitID);
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