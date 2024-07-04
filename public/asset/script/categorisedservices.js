$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".servicesSideA").addClass("activeLi");
    
    var url = window.location.pathname;
    var id = url.substring(url.lastIndexOf('/') + 1);

    $("#categorisedservicesTable").dataTable({
        dom: "Bfrtip",
        buttons: ["csv", "excel", "pdf",],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8,9,10],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}categorised-services-list` + "/" + id,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#categorisedservicesTable").on("change", ".onoff", function (event) {
        event.preventDefault();
        if ($(this).prop("checked") == true) {
            var value = 1;
        } else {
            value = 0;
        }
        var itemId = $(this).attr("rel");

        var url = `${domainUrl}services-status/${itemId}/${value}`;

        $.getJSON(url).done(function (data) {
            $("#categorisedservicesTable").DataTable().ajax.reload(null, false);

            iziToast.success({
                title: strings.success,
                message: strings.operationSuccessful,
                position: "topRight",
                timeOut: 3000,
            });
        });
    });
    
    $("#categorisedservicesTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-services` + "/" + id;
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
