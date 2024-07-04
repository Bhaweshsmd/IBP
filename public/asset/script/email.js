$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".emailsettSideA").addClass("activeLi");

    $("#emailSettingsForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#emailSettingsForm")[0]);
        $.ajax({
            url: `${domainUrl}email-settings-update`,
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
                        text: "Email Settings Updated Successfully",
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
