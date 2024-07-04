$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".maintenanceSideA").addClass("activeLi");

    $("#activeMaintenance").dataTable({
        dom: "Blfrtip",
        lengthMenu: 
        [10, 50,100, 500, 1000],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4,],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}list-maintainance`,
            data: function (data) {
                console.log(data);
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#activeMaintenance").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-maintainance` + "/" + id;
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
    
    $("#activeMaintenance").on("click", ".remind", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}remind-maintainance` + "/" + id;
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
