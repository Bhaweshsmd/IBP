$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".bookingsSideA").addClass("activeLi");

    $("#allBookingsTable").dataTable({
        dom: "Blfrtip", 
        lengthMenu: [[10, 50,100, 500, 1000], [10, 50,100, 500, 1000]],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}bookings-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#pendingBookingsTable").dataTable({
        dom: "Blfrtip",       
        lengthMenu: [[10, 50,100, 500, 1000], [10, 50,100, 500, 1000]],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}pending-bookings-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#acceptedBookingsTable").dataTable({
        dom: "Blfrtip",     
        lengthMenu: [[10, 50,100, 500, 1000], [10, 50,100, 500, 1000]],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}accepted-bookings-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#completedBookingsTable").dataTable({
        dom: "Blfrtip",     
        lengthMenu: [[10, 50,100, 500, 1000], [10, 50,100, 500, 1000]],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}completed-bookings-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#cancelledBookingsTable").dataTable({
        dom: "Blfrtip",    
        lengthMenu: [[10, 50,100, 500, 1000], [10, 50,100, 500, 1000]],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}cancelled-bookings-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#declinedBookingsTable").dataTable({
        dom: "Blfrtip",   
        lengthMenu: [[10, 50,100, 500, 1000], [10, 50,100, 500, 1000]],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}declined-bookings-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
});
