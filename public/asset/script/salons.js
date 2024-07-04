$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".salonsSideA").addClass("activeLi");

    $("#activeSalonTable").dataTable({
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
            url: `${domainUrl}platforms-list`,
            data: function (data) {
                console.log(data);
            },
            error: (error) => {
                console.log(error);
            },
        },
    });

    function reloadTables() {
        $("#activeSalonTable").DataTable().ajax.reload(null, false);
    }
});
