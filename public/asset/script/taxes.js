$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".taxesSideA").addClass("activeLi");

    $("#taxesTable").dataTable({
        dom: "Bfrtip",
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}taxes-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#addTaxForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addTaxForm")[0]);
        $.ajax({
            url: `${domainUrl}store-tax`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addTaxModal").modal("hide");
                $("#addTaxForm").trigger("reset");
                $("#taxesTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "New Tax Added Successfully",
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
    
    $("#editTaxForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editTaxForm")[0]);
        $.ajax({
            url: `${domainUrl}update-tax`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#editTaxModal").modal("hide");
                $("#editTaxForm").trigger("reset");
                $("#taxesTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Tax Settings Updated Successfully",
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
    
    $("#taxesTable").on("change", ".onoff", function (event) {
        event.preventDefault();
        if ($(this).prop("checked") == true) {
            var value = 1;
        } else {
            value = 0;
        }
        var itemId = $(this).attr("rel");

        var url = `${domainUrl}change-status/${itemId}/${value}`;

        $.getJSON(url).done(function (data) {
            $("#taxesTable").DataTable().ajax.reload(null, false);

            $(function () {
                swal({
                    title: "Success",
                    text: "Tax Settings Updated Successfully",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        });
    });

    $("#taxesTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var id = $(this).attr("rel");
        var title = $(this).data("taxtitle");
        var value = $(this).data("value");
        var type = $(this).data("type");

        $("#editTaxId").val(id);
        console.log(title);
        $("#edit_tax_title").val(title);
        $("#edit_tax_value").val(value);

        $("#edit_tax_type").empty();
        $("#edit_tax_type").append(
            `<option ${type == 0 ? "selected" : ""} value="0">Percent</option>
            <option ${type == 1 ? "selected" : ""} value="1">Fixed</option>
            `
        );

        $("#editTaxModal").modal("show");
    });
    
    $("#taxesTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-tax` + "/" + id;
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
