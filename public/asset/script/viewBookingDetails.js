$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");

    var bookingId = $("#bookingId").val();
    var bookingOtp = $("#completionOtp").val();
    var bookingIdBig = $("#bookingIdBig").val();
    console.log(bookingOtp);

    $("#print-payment").on("click", function (event) {
        event.preventDefault();
        $("#details-body").printThis({
            importCSS: true,
            importStyle: true,
        });
    });
    
    $("#download-pdf").on("click", function (event) {
        event.preventDefault();
        var element = document.getElementById("details-body");
        var opt = {
            margin: 1,
            filename: `${bookingIdBig}.pdf`,
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
        };

        html2pdf().set(opt).from(element).save();
    });
    
    $("select").on("change", function (event) {
        var statusValue= this.value;
        event.preventDefault();
        if(statusValue){
            if(statusValue==2) {
                swal({
                    title: "Mark booking as complete",
                    text: "Please enter completion OTP provided by the customer",
                    input: "text",
                    closeOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText:"Continue",
                    cancelButtonColor: "#fff",
                    reverseButtons:true,
                    inputAttributes: {
                        maxlength: "4",
                    },
                    inputValidator: (value) => {
                        if (!value) {
                            return "Please enter completion OTP ";
                        }
                        if(bookingOtp != value ){
                            return "Please enter correct completion OTP "; 
                        }
                    }
                }).then((isConfirm) => {
                    if(isConfirm){
                        console.log(isConfirm.value);
                        if(isConfirm.value==bookingOtp){
                            var url = `${domainUrl}booking-status`+"/"+bookingId+"/"+statusValue;
                            $.ajax({
                                type: "GET",
                                url: url,
                                dataType: "json",
                                contentType: false,
                                cache: false,
                                processData: false,
                                success: function (response) {
                                    console.log(response.status);
                                    var results =response.message;
                                    console.log(results)
                                    if(response.status==true){
                                        $(function () {
                                            swal({
                                                title: "Success",
                                                text: "Booking Completed Successfully",
                                                type: "success",
                                                confirmButtonColor: "#000",
                                                confirmButtonText: "Close",
                                                closeOnConfirm: false, 
                                            })
                                        });
                                        setTimeout(function(){
                                            location.reload(); 
                                        },5000)
                                    }else{
                                        $('#changeStatus').prop('selectedIndex',0);
                                        $(function () {
                                            swal({
                                                title: "Warning",
                                                text: "Something went wrong.",
                                                type: "warning",
                                                confirmButtonColor: "#000",
                                                confirmButtonText: "Close",
                                                closeOnConfirm: false, 
                                            })
                                        });
                                    }
                                },
                                error: (error) => {
                                    $(".loader").hide();
                                    console.log(JSON.stringify(error));
                                },
                            });
                        }
                        $('#changeStatus').prop('selectedIndex',0);
                    }
                });
            }else{
                var id = $(this).attr("rel");
                var url = `${domainUrl}booking-status`+"/"+bookingId+"/"+statusValue;
                $('#changeStatus').prop('selectedIndex',0);
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
            }
        }
    });
});
