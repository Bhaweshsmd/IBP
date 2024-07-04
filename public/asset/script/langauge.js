$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".languageWithSideA").addClass("activeLi");

    $("#couponsTable").dataTable({
        dom: "Blfrtip",
        lengthMenu:[10, 50,100, 500, 1000],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}language-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#addLangaugeForm").on("submit", function (event) {
        
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addLangaugeForm")[0]);
        $.ajax({
            url: `${domainUrl}store-language`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addCouponModal").modal("hide");
                $("#addLangaugeForm").trigger("reset");
                $("#couponsTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Language Added Successfully",
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
    
    $("#editLangaugeForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
            var formdata = new FormData($("#editLangaugeForm")[0]);
            $.ajax({
                url: `${domainUrl}update-language`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $(".loader").hide();
                    $("#editLangaugeForms").modal("hide");
                    $("#editLangaugeForms").trigger("reset");
                    $("#couponsTable").DataTable().ajax.reload(null, false);
                    $(function () {
                        swal({
                            title: "Success",
                            text: "Language Updated Successfully",
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

    $("#couponsTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var name = $(this).data("name");
        var short_name = $(this).data("short_name");
        var flag = $(this).data("flag");
        var status = $(this).data("status");
         
        let element = document.getElementById("editstatus");
        element.value = status;
        var id = $(this).attr("rel");
        $("#id").val(id);
        $("#name").val(name);
        $("#short_name").val(short_name);
        $("#editLangaugeForms").modal("show");
    });
    
    $("#couponsTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-langauge` + "/" + id;
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
