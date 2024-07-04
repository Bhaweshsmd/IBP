$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".salonCategoriesSideA").addClass("activeLi");

    $("#categoriesTable").dataTable({
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
                targets: [0, 1, 2,3,4],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}categories-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
     $("#categoriesTable").on("change", ".onoff", function (event) {
        event.preventDefault();
        if ($(this).prop("checked") == true) {
            var value = 1;
        } else {
            value = 0;
        }
        var itemId = $(this).attr("rel");

        var url = `${domainUrl}categories-status/${itemId}/${value}`;

        $.getJSON(url).done(function (data) {
            $("#servicesTable").DataTable().ajax.reload(null, false);

            $(function () {
                swal({
                    title: "Success",
                    text: "Category Status Updated Successfully",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        });
    });

    $("#editSalonCatForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editSalonCatForm")[0]);
        $.ajax({
            url: `${domainUrl}categories-update`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#editSalonCatModal").modal("hide");
                $("#editSalonCatForm").trigger("reset");
                $("#categoriesTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Category Updated Successfully",
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

    $("#addSalonCatForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addSalonCatForm")[0]);
        $.ajax({
            url: `${domainUrl}categories-store`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addSalonCatModal").modal("hide");
                $("#addSalonCatForm").trigger("reset");
                $("#categoriesTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Category Added Successfully",
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
    
    $("#categoriesTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}categories-delete` + "/" + id;
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
         var web_icon = $(this).data("web");
        
         var titlepapiamentu = $(this).data("titlepapiamentu");
         var titledutch = $(this).data("titledutch");
        var sort=$(this).data("sort");
        
        var id = $(this).attr("rel");
        var parnt_id = $(this).data("parent");
         $("#editParent").val(parnt_id);
        $("#editSalonCatId").val(id);
        $("#editSalonCatTitle").val(title);
        
        $("#title_in_papiamentu").val(titlepapiamentu);
        $("#title_in_dutch").val(titledutch);
        
         $("#sort").val(sort);
        
        
        $("#imgSalonCat").attr("src", icon);
        $("#webImgSalonCat").attr("src", web_icon);
        

        $("#editSalonCatModal").modal("show");
    });
});
