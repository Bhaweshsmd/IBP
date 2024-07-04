$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".cardSideA").addClass("activeLi");

    $("#activeCards").dataTable({
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
                targets: [0, 1],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}card-list`,
            data: function (data) {
                console.log(data);
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#activeCards").on("click", ".delete", function (event) {
        event.preventDefault();
        swal({
            title: strings.doYouReallyWantToContinue,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                var id = $(this).attr("rel");
                var url = `${domainUrl}admin-delete` + "/" + id;

                $.getJSON(url).done(function (data) {
                    console.log(data);
                    $("#activeCards")
                        .DataTable()
                        .ajax.reload(null, false);
                    iziToast.success({
                        title: strings.success,
                        message: strings.operationSuccessful,
                        position: "topRight",
                    });
                });
            }
        });
    });
    
    $("#adminpassword").on("submit", function (event) {
        event.preventDefault();
        var formdata = new FormData($("#adminpassword")[0]);
        $.ajax({
            url: domainUrl + "admin-password-update",
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                if (response.status) {
                    iziToast.success({
                        title: strings.success,
                        message: strings.operationSuccessful,
                        position: "topRight",
                    });
                } else {
                    iziToast.error({
                        title: strings.error,
                        message: response.message,
                        position: "topRight",
                    });
                }
                $("#adminpassword").trigger("reset");
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
});
