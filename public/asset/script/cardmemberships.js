$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".cardmembfeeSideA").addClass("activeLi");

    $("#membershipsCards").dataTable({
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
            url: `${domainUrl}card-memberships-list`,
            data: function (data) {
                console.log(data);
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
});
