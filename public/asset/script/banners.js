$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".bannersSideA").addClass("activeLi");

    $("#bannersTable").dataTable({
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
            url: `${domainUrl}banners-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#addBannerForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addBannerForm")[0]);
        $.ajax({
            url: `${domainUrl}store-banner`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addBannerModal").modal("hide");
                $("#addBannerForm").trigger("reset");
                $("#bannersTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Banner Added Successfully",
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
    
    $("#bannersTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-banner` + "/" + id;
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

    $("#categoriesTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var title = $(this).data("title");
        var icon = $(this).data("icon");
        var id = $(this).attr("rel");

        $("#editSalonCatId").val(id);
        $("#editSalonCatTitle").val(title);
        $("#imgSalonCat").attr("src", icon);

        $("#editSalonCatModal").modal("show");
    });
});
