$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".notificationsSideA").addClass("activeLi");

    var url = `${domainUrl}faq-categories`;
    var faqCategories;
    $.getJSON(url).done(function (data) {
        faqCategories = data.data;
    });

    $("#usersTable").dataTable({
         dom: "Blfrtip",
        lengthMenu: [
        [10, 50,100, 500, 1000],
        [10, 50,100, 500, 1000]
        ],
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
            url: `${domainUrl}notifications-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#salonTable").dataTable({
         dom: "Blfrtip",
        lengthMenu: [
        [10, 50,100, 500, 1000],
        [10, 50,100, 500, 1000]
        ],
        buttons: ["csv", "excel", "pdf",],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1,2,3,4],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}platform-notification-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#salonTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}platform-notification-delete` + "/" + id;
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
    
    $("#usersTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}notifications-delete` + "/" + id;
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
    
    $("#salonTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var title = $(this).data("title");
        var description = $(this).data("description");
        var id = $(this).attr("rel");

        $("#editSalonNotiId").val(id);
        $("#editSalonNotiTitle").val(title);
        $("#editSalonNotiDesc").val(description);

        $("#editSalonNotiModal").modal("show");
    });
    
    $("#usersTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var title = $(this).data("title");
        var description = $(this).data("description");
        var id = $(this).attr("rel");

        $("#editUserNotiId").val(id);
        $("#editUserNotiTitle").val(title);
        $("#editUserNotiDesc").val(description);

        $("#editUserNotiModal").modal("show");
    });
    $("#addSalonNotiForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addSalonNotiForm")[0]);
        $.ajax({
            url: `${domainUrl}addSalonNotification`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addSalonNotiModal").modal("hide");
                $("#addSalonNotiForm").trigger("reset");
                $("#salonTable").DataTable().ajax.reload(null, false);
                iziToast.success({
                    title: strings.success,
                    message: strings.operationSuccessful,
                    position: "topRight",
                });
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
    
    $("#addUserNotiForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addUserNotiForm")[0]);
        $.ajax({
            url: `${domainUrl}notifications-store`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addUserNotiModal").modal("hide");
                $("#addUserNotiForm").trigger("reset");
                $("#usersTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Notification Sent Successfully",
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                });
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
    
    $("#editUserNotiForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editUserNotiForm")[0]);
        $.ajax({
            url: `${domainUrl}notifications-update`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#editUserNotiModal").modal("hide");
                $("#editUserNotiForm").trigger("reset");
                $("#usersTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Notification Updated Successfully",
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                });
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
    
    $("#editSalonNotiForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editSalonNotiForm")[0]);
        $.ajax({
            url: `${domainUrl}editSalonNotification`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#editSalonNotiModal").modal("hide");
                $("#editSalonNotiForm").trigger("reset");
                $("#salonTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Notification Updated Successfully",
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                });
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
});
