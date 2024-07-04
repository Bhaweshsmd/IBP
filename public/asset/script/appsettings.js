$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    var current_url= window.location;
    $(".appsettingsSideA").addClass("activeLi");

    $("#globalSettingsForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#globalSettingsForm")[0]);
        $.ajax({
            url: `${domainUrl}app-settings-update`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $(function () {
                    swal({
                        title: "Success",
                        text: "General Settings Updated Successfully",
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
