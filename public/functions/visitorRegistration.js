//Get DataTable
function getCheckOutDataTable() {
    document.getElementById("loader").style.display = "block";
    document.getElementById("VisitorTbl").style.display = "none";
    $.ajax({
        type: "GET",
        url: APP_URL + "/api/Visitor/getVisitorLog",
        dataType: 'json',
        data: $('#searchForm').serialize() + '&' + $.param({ 'apiKey': apiKey, 'Exit': 1 }),
        success: function(data) {
            document.getElementById("loader").style.display = "none";
            document.getElementById("VisitorTbl").style.display = "inline-table";
            if (data.error == false) {
                var returnedData = data;
                var data = data['data'];
                if (data.length < 1) {
                    $('#VisitorTbl').dataTable().fnClearTable();
                } else {
                    $('#VisitorTbl').dataTable().fnClearTable();
                    $('#VisitorTbl').dataTable().fnAddData(data);
                }
            } else {
                $('#VisitorTbl').dataTable().fnClearTable();
            }
        },
        error: function(jqXHR, exception) {
            if (jqXHR.status == 500) {
                document.getElementById("loader").style.display = "block";
                location.reload();
            } else {
                $('#VisitorTbl').dataTable().fnClearTable();
                document.getElementById("loader").style.display = "none";
                document.getElementById("VisitorTbl").style.display = "inline-table";
                alert('Error Occured');
            }
        }
    });
}

function checkIn() {
    //add
    $('.overlay').css({ 'display': 'block' });
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Visitor/visitorCheckIn",
        data: $('#CheckInForm').serialize() + '&' + $.param({ 'apiKey': apiKey }),
        success: function(data) {
            if (data.error == false) {
                $('#CheckInModal').modal('toggle');
                $('#success-alert-checkIn span').text(data.message);
                $('#success-alert-checkIn').fadeIn('slow', function() {
                    $(this).delay(2500).fadeOut();
                });
                $('#CheckInForm')[0].reset();
                $('#checkInVisitPlace').trigger("change");
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

function checkOut(id) {
    $('.overlay').css({ 'display': 'block' });
    $.ajax({
        type: "POST",
        url: APP_URL + "/api/Visitor/visitorCheckOut",
        data: { 'apiKey': apiKey, 'VisitorLogID': id },
        success: function(data) {
            if (data.error == false) {
                $('#success-alert-checkOut span').text(data.message);
                $('#success-alert-checkOut').fadeIn('slow', function() {
                    $(this).delay(2500).fadeOut();
                });
                $('#VisitorTbl').dataTable().fnClearTable();
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