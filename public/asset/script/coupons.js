$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".couponsSideA").addClass("activeLi");

    $("#couponsTable").dataTable({
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
                targets: [0, 1, 2, 3, 4, 5, 6],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}coupons-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#addCouponForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addCouponForm")[0]);
        $.ajax({
            url: `${domainUrl}store-coupon`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addCouponModal").modal("hide");
                $("#addCouponForm").trigger("reset");
                $("#couponsTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Coupon Added Successfully",
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
    
    $("#editCouponForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editCouponForm")[0]);
        $.ajax({
            url: `${domainUrl}update-coupon`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#editCouponModal").modal("hide");
                $("#editCouponForm").trigger("reset");
                $("#couponsTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Coupon Updated Successfully",
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

        var maxDiscAmount = $(this).data("maxdiscamount");
        var minOrderAmount = $(this).data("minorderamount");
        var coupon = $(this).data("coupon");
        var description = $(this).data("description");
        var heading = $(this).data("heading");
        var percentage = $(this).data("percentage");
        var expirydate = $(this).data("expirydate");
        var available = $(this).data("available");
        var availableuser = $(this).data("availableuser");
        var id = $(this).attr("rel");

        $("#editCouponId").val(id);
        $("#editMinOrderAmount").val(minOrderAmount);
        $("#editMaxDiscAmount").val(maxDiscAmount);
        $("#editCoupon").val(coupon);
        $("#editHeading").val(heading);
        $("#editDescription").val(description);
        $("#editPercentage").val(percentage);
        $("#editExpirydate").val(expirydate);
        $("#editAvailable").val(available);
        $("#editAvailableusr").val(availableuser);

        $("#editCouponModal").modal("show");
    });
    
    $("#couponsTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}delete-coupon` + "/" + id;
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
