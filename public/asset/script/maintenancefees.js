$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".maintenancefeeSideA").addClass("activeLi");

    $("#assignactiveCards").dataTable({
        dom: "Bfrtip",
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
            url: `${domainUrl}maintenance-card-fees-list`,
            data: function (data) {
                console.log(data);
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
});
