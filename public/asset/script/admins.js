$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".adminsSideA").addClass("activeLi");

    $("#activeAdmins").dataTable({
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
            url: `${domainUrl}admin-list`,
            data: function (data) {
                console.log(data);
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#activeAdmins").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}admin-delete` + "/" + id;
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
