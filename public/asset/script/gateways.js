$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".gatewaysSideA").addClass("activeLi");

    $("#paymentGatewayForm").on("submit", function (event) {
        event.preventDefault();
        var formdata = new FormData($("#paymentGatewayForm")[0]);
        $.ajax({
            url: domainUrl + "update-gateways",
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                if (response.status) {
                    $(function () {
                        swal({
                            title: "Success",
                            text: "Payment Settings Updated Successfully",
                            type: "success",
                            confirmButtonColor: "#000",
                            confirmButtonText: "Close",
                            closeOnConfirm: false, 
                        })
                    });
                } else {
                    iziToast.error({
                        title: strings.error,
                        message: response.message,
                        position: "topRight",
                    });
                }
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    });
});
