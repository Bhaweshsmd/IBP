$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");

    var salonId = $("#salonId").val();
    console.log(salonId);

    $("#salonDetailsForm").on("submit", function (event) {
        event.preventDefault();
        var formdata = new FormData($("#salonDetailsForm")[0]);
        $.ajax({
            url: `${domainUrl}platform-update`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                location.reload();
                $(function () {
                    swal({
                        title: "Success",
                        text: "Company Profile Updated Successfully",
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
    
    $("#addSalonImagesForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addSalonImagesForm")[0]);
        $.ajax({
            url: `${domainUrl}add-platform-image`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addSalonImagesModal").modal("hide");
                $(function () {
                    swal({
                        title: "Success",
                        text: "Company Images Added Successfully",
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                });
                location.reload();
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });

    $("#addSalonImagesFormGallery").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addSalonImagesFormGallery")[0]);
        $.ajax({
            url: `${domainUrl}add-platform-gallery`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addSalonImagesModalGallery").modal("hide");
                $(function () {
                    swal({
                        title: "Success",
                        text: "Gallery Images Added Successfully",
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                });
                location.reload();
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
    
    $("#addSalonImagesFormMap").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addSalonImagesFormMap")[0]);
        $.ajax({
            url: `${domainUrl}add-platform-map`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addSalonImagesModalMap").modal("hide");
                $(function () {
                    swal({
                        title: "Success",
                        text: "Map Image Added Successfully",
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                });
                location.reload();
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
    
    $("#reviewsTable").dataTable({
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
            url: `${domainUrl}reviews-list`,
            data: function (data) {
                data.salonId = salonId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#galleryTable").dataTable({
                dom: "Blfrtip",         lengthMenu:[10, 50,100, 500, 1000],         buttons: ["csv", "excel", "pdf"],         processing: true,
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
            url: `${domainUrl}gallery-list`,
            data: function (data) {
                data.salonId = salonId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#mapTable").dataTable({
        dom: "Blfrtip",         
        lengthMenu:[10, 50,100, 500, 1000],         
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
            url: `${domainUrl}map-list`,
            data: function (data) {
                data.salonId = salonId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#salonPayOutsTable").on("click", ".complete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        $("#completeId").val(id);

        $("#completeModal").modal("show");
    });
    $("#salonPayOutsTable").on("click", ".reject", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        $("#rejectId").val(id);

        $("#rejectModal").modal("show");
    });

    $("#completeForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#completeForm")[0]);
        $.ajax({
            url: `${domainUrl}completeSalonWithdrawal`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#completeModal").modal("hide");
                $("#completeForm").trigger("reset");
                $("#salonPayOutsTable")
                    .DataTable()
                    .ajax.reload(null, false);
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
    
    $("#rejectForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#rejectForm")[0]);
        $.ajax({
            url: `${domainUrl}rejectSalonWithdrawal`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#rejectModal").modal("hide");
                $("#rejectForm").trigger("reset");
                $("#salonPayOutsTable")
                    .DataTable()
                    .ajax.reload(null, false);
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
    
    $("#bookingsTable").dataTable({
        dom: "Blfrtip",         lengthMenu:[10, 50,100, 500, 1000],         buttons: ["csv", "excel", "pdf"],         processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}platform-bookings-list`,
            data: function (data) {
                data.salonId = salonId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#servicesTable").dataTable({
        
                dom: "Blfrtip",         lengthMenu:[10, 50,100, 500, 1000],         buttons: ["csv", "excel", "pdf"],         processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}platform-services-list`,
            data: function (data) {
                data.salonId = salonId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#servicesTable").on("change", ".onoff", function (event) {
        event.preventDefault();
        if ($(this).prop("checked") == true) {
            var value = 1;
        } else {
            value = 0;
        }
        var itemId = $(this).attr("rel");

        var url = `${domainUrl}services-status/${itemId}/${value}`;

        $.getJSON(url).done(function (data) {
            $("#servicesTable").DataTable().ajax.reload(null, false);
            $(function () {
                swal({
                    title: "Success",
                    text: "Service Status Updated Successfully",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        });
    });
    
    $("#reviewsTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}deleteReview` + "/" + id;
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

    $("#galleryTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-platform-gallery` + "/" + id;
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
    
    $("#mapTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-platform-map` + "/" + id;
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
    
    $("#mapTable").on("click", ".view", function (event) {
        event.preventDefault();

        var id = $(this).attr("rel");
        var desc = $(this).data("desc");
        var imageUrl = `${sourceUrl}${$(this).data("image")}`;

        $("#imggalleryPreview").attr("src", imageUrl);
        $("#descGalleryPreview").text(desc);

        $("#previewGalleryModal").modal("show");
    });
    
    $("#galleryTable").on("click", ".view", function (event) {
        event.preventDefault();

        var id = $(this).attr("rel");
        var desc = $(this).data("desc");
        var imageUrl = `${sourceUrl}${$(this).data("image")}`;

        $("#imggalleryPreview").attr("src", imageUrl);
        $("#descGalleryPreview").text(desc);

        $("#previewGalleryModal").modal("show");
    });

    $("#servicesTable").on("click", ".delete", function (event) {
        event.preventDefault();
        swal({
            title: strings.doYouReallyWantToContinue,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                var id = $(this).attr("rel");
                var url = `${domainUrl}delete-services` + "/" + id;

                $.getJSON(url).done(function (data) {
                    console.log(data);
                    $("#servicesTable")
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
    
    $(document).on("click", ".img-delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-platform-image` + "/" + id;
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
