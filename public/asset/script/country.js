$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".countrySideA").addClass("activeLi");
    
    $("#activeCountries").dataTable({
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
            url: `${domainUrl}countries-list`,
            data: function (data) {
                console.log(data);
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#activeCountries").on("click", ".edit", function (event) {
        event.preventDefault();

        var shortname = $(this).data("shortname");
        var name = $(this).data("name");
        var iso3 = $(this).data("iso3");
        var numbercode = $(this).data("numbercode");
        var phonecode = $(this).data("phonecode");
        var currencycode = $(this).data("currencycode");
        var status = $(this).data("status");
        var id = $(this).attr("rel");

        $("#editCountryId").val(id);
        $("#editshortname").val(shortname);
        $("#editname").val(name);
        $("#editiso3").val(iso3);
        $("#editnumbercode").val(numbercode);
        $("#editphonecode").val(phonecode);
        $("#editcurrencycode").val(currencycode);
        $("#editstatus").val(status);

        $("#editCountryModal").modal("show");
    });
    
    $("#editCountryForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editCountryForm")[0]);
        $.ajax({
            url: `${domainUrl}update-country`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#editCountryModal").modal("hide");
                $("#editCountryForm").trigger("reset");
                $("#activeCountries").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Country Updated Successfully",
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
    
    $("#activeCountries").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-country` + "/" + id;
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