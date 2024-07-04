$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");

    var serviceId = $("#serviceId").val();
    console.log(serviceId);
   
    $("#serviceStatus").on("change", function (event) {
        event.preventDefault();
        if ($(this).prop("checked") == true) {
            var value = 1;
        } else {
            value = 0;
        }
        var itemId = $(this).attr("rel");

        var url = `${domainUrl}services-status/${itemId}/${value}`;

        $.getJSON(url).done(function (data) {
            location.reload();
        });
    });

    $("#serviceForm").on("submit", function (event) {
        event.preventDefault();
        console.log(event);
        document.getElementById("submitId").disabled = true;
        $("#submitId").val("Updating...");
        var formdata = new FormData($("#serviceForm")[0]);
        console.log(formdata);
        $.ajax({
            url: `${domainUrl}updateService_Admin`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(function () {
                    swal({
                        title: "Success",
                        text: "Service Updated Successfully",
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
    
    $("#serviceFormAdd").on("submit", function (event) {
        event.preventDefault();
        var formdata = new FormData($("#serviceFormAdd")[0]);
        console.log(formdata);
        $.ajax({
            url: `${domainUrl}addServiceToSalon`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                location.reload();
            },
            error: (error) => {
                $(".loader").hide();
                console.log(error);
                // console.log(JSON.stringify(error));
            },
        });
    });

    $("#deleteService").on("click", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-services` + "/" + serviceId;
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
    
    $(document).on("click", ".img-delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-service-image` + "/" + id;
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

    $(document).on("click",".map-img-delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-service-map-image` + "/" + id;
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
    
    $(document).on("click",".deleteSlots", function (event) {
        event.preventDefault();
        var slotId= $(this).attr("data-id");
        var url = `${domainUrl}delete-booking-slots` + "/" + slotId;
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
