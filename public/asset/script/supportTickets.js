$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".supportSideA").addClass("activeLi");

    // Fetch Sound Categories
    var url = `${domainUrl}faq-categories`;
    var faqCategories;
    $.getJSON(url).done(function (data) {
        faqCategories = data.data;
    });

    $("#ticketsTable").dataTable({
        dom: "Blfrtip",
        lengthMenu:[10, 50,100, 500, 1000],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4,5,6],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}tickets-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#ticketsTable").on("click", ".block", function (event) {
        event.preventDefault();
        swal({
            title: strings.doYouReallyWantToContinue,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                var id = $(this).attr("rel");
                var url = `${domainUrl}block-user` + "/" + id;

                $.getJSON(url).done(function (data) {
                    console.log(data);
                    $("#ticketsTable").DataTable().ajax.reload(null, false);
                    iziToast.success({
                        title: strings.success,
                        message: strings.operationSuccessful,
                        position: "topRight",
                    });
                });
            }
        });
    });
    $("#ticketsTable").on("click", ".unblock", function (event) {
        event.preventDefault();
        swal({
            title: strings.doYouReallyWantToContinue,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                var id = $(this).attr("rel");
                var url = `${domainUrl}unblock-user` + "/" + id;

                $.getJSON(url).done(function (data) {
                    console.log(data);
                    $("#ticketsTable").DataTable().ajax.reload(null, false);
                    iziToast.success({
                        title: strings.success,
                        message: strings.operationSuccessful,
                        position: "topRight",
                    });
                });
            }
        });
    });
});
