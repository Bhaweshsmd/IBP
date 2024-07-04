$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".userWalletRechargeSideA").addClass("activeLi");

    $("#walletRechargeTable").dataTable({
        dom: "Blfrtip",  
        lengthMenu: [         [10, 50,100, 500, 1000],         [10, 50,100, 500, 1000]         ],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}deposits-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
});
