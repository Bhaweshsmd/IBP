$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".contentSideA").addClass("activeLi");

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
                targets: [0, 1, 2, 3,4],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}language-contents`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#addLanguageContend").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addLanguageContend")[0]);
        $.ajax({
            url: `${domainUrl}store-language-content`,
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
                        text: "Langauge Content Added Successfully",
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
    
    $("#editLangaugeFormContent").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editLangaugeFormContent")[0]);
        $.ajax({
            url: `${domainUrl}update-language-content`,
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
                        text: "Langauge Content Updated Successfully",
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

        var string = $(this).data("string");
        var en = $(this).data("en");
        var pap = $(this).data("pap");
        var nl = $(this).data("nl");
        var status = $(this).data("status");
         
         let element = document.getElementById("editstatus");
          element.value = status;
        // var description = $(this).data("description");
        // var heading = $(this).data("heading");
        // var percentage = $(this).data("percentage");
        var id = $(this).attr("rel");
        $("#id").val(id);
        $("#string").val(string);
        $("#en").val(en);
        $("#pap").val(pap);
        $("#nl").val(nl);
        // $("#editDescription").val(description);
        // $("#editPercentage").val(percentage);

        $("#editLangaugeForms").modal("show");
    });
    
    $("#couponsTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-langauge-content` + "/" + id;
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
