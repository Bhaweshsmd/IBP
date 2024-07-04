$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".eventSideA").addClass("activeLi");

    // Fetch Sound Categories
    var url = `${domainUrl}faq-categories`;
    var faqCategories;
    $.getJSON(url).done(function (data) {
        faqCategories = data.data;
    });

    $("#eventList").dataTable({
        dom: "Blfrtip",
        lengthMenu: [
        [10, 50,100, 500, 1000],
        [10, 50,100, 500, 1000]
        ],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
    });

    $("#eventTable").dataTable({
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
                targets: [0, 1,2,3],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}events-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#eventTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-event` + "/" + id;
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
        swal({
            title: strings.doYouReallyWantToContinue,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                var id = $(this).attr("rel");
                var url = `${domainUrl}deleteUserNotification` + "/" + id;

                $.getJSON(url).done(function (data) {
                    console.log(data);
                    $("#usersTable").DataTable().ajax.reload(null, false);
                    iziToast.success({
                        title: strings.success,
                        message: strings.operationSuccessful,
                        position: "topRight",
                    });
                });
            }
        });
    });
    $("#eventTable").on("click", ".edit", function (event) {
        event.preventDefault();
         var title = $(this).data("title");
        var short_id = $(this).data("shortid");
        var status = $(this).data("status");
        var id = $(this).attr("rel");

        $("#editUserNotiId").val(id);
        $("#title").val(title);
        $("#short_id").val(short_id);
        $("#status").val(status);  
        
        $("#editEventType").modal("show");
    });
    $("#usersTable").on("click", ".edit", function (event){
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
                $("#eventTable").DataTable().ajax.reload(null, false);
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
    
    $("#addEventType").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addEventType")[0]);
        $.ajax({
            url: `${domainUrl}store-event`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addUserNotiModal").modal("hide");
                $("#eventTable").trigger("reset");
                $("#eventTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Event Created Successfully",
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
    
    $("#editEventType").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editUserNotiForm")[0]);
        $.ajax({
            url: `${domainUrl}update-event`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#editEventType").modal("hide");
                $("#eventTable").trigger("reset");
                $("#eventTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Event Updated Successfully",
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
});
