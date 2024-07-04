$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".cardtransSideA").addClass("activeLi");
    
    var card_id = $('#card_id').val();

    $("#activetransactions").dataTable({
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
            url: `${domainUrl}card-transaction-list` + '/' + card_id,
            data: function (data) {
                console.log(data);
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
});
