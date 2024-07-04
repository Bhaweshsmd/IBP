$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".reviewsSideA").addClass("activeLi");

    $("#reviewsTable").dataTable({
         dom: "Blfrtip",
        lengthMenu:[10, 50,100, 500, 1000],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5,6,7],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}list-reviews`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#reviewsTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-reviews` + "/" + id;
        $(function () {
            swal({
                title: "Warning",
                text: strings.doYouReallyWantToContinue,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Cancel",
                cancelButtonText: "<a href='" + url + "'>Continue</a>",
                closeOnConfirm: false, 
                customClass: "Custom_Cancel"
            })
        });
    });
    
    $("#reviewsTable").on("click", ".approve", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}approve-reviews` + "/" + id;
        $(function () {
            swal({
                title: "Warning",
                text: strings.doYouReallyWantToContinue,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Cancel",
                cancelButtonText: "<a href='" + url + "'>Continue</a>",
                closeOnConfirm: false, 
                customClass: "Custom_Cancel"
            })
        });
    });
    
    $("#reviewsTable").on("click", ".reject", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}reject-reviews` + "/" + id;
        $(function () {
            swal({
                title: "Warning",
                text: strings.doYouReallyWantToContinue,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Cancel",
                cancelButtonText: "<a href='" + url + "'>Continue</a>",
                closeOnConfirm: false, 
                customClass: "Custom_Cancel"
            })
        });
    });
});
