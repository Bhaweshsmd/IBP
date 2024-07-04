$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".notificationSideA").addClass("activeLi");

    $("#couponsTable").dataTable({
        dom: "Blfrtip",
        lengthMenu:[10, 50,100, 500, 1000],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}notification-templates-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
});
