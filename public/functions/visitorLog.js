//Get DataTable
function getVisitorLogDataTable() {
    document.getElementById("loader").style.display = "block";
    document.getElementById("VisitorLogTbl").style.display = "none";
    $.ajax({
        type: "GET",
        url: APP_URL + "/api/Visitor/getVisitorLog",
        dataType: 'json',
        data: $('#searchForm').serialize() + '&' + $.param({ 'apiKey': apiKey }),
        success: function(data) {
            document.getElementById("loader").style.display = "none";
            document.getElementById("VisitorLogTbl").style.display = "inline-table";
            if (data.error == false) {
                var returnedData = data;
                var data = data['data'];
                if (data.length < 1) {
                    $('#VisitorLogTbl').dataTable().fnClearTable();
                } else {
                    $('#VisitorLogTbl').dataTable().fnClearTable();
                    $('#VisitorLogTbl').dataTable().fnAddData(data);
                }
            } else {
                $('#VisitorLogTbl').dataTable().fnClearTable();
            }
        },
        error: function(jqXHR, exception) {
            if (jqXHR.status == 500) {
                document.getElementById("loader").style.display = "block";
                location.reload();
            } else {
                $('#VisitorLogTbl').dataTable().fnClearTable();
                document.getElementById("loader").style.display = "none";
                document.getElementById("VisitorLogTbl").style.display = "inline-table";
                alert('Error Occured');
            }
        }
    });
}


function editVisitorLog() {
    //edit
    $('.overlay').css({ 'display': 'block' });
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Visitor/editVisitorLog",
        data: $('#VisitorLogForm').serialize() + '&' + $.param({ 'apiKey': apiKey }),
        success: function(data) {
            if (data.error == false) {
                getVisitorLogDataTable();
                $('#VisitorLogModal').modal('toggle');
                alertDialog(data.message);
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