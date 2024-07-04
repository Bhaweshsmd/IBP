$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".feesSideA").addClass("activeLi");

    $("#feeTable").dataTable({
        dom: "Bfrtip",
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2,3,4],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fees-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#editFeeForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editFeeForm")[0]);
        $.ajax({
            url: `${domainUrl}update-fee`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#editFeeModal").modal("hide");
                $("#editFeeForm").trigger("reset");
                $("#feeTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Fee Settings Updated Successfully",
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
    
    $("#feeTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var id = $(this).attr("rel");
        var title = $(this).data("taxtitle");
        var value = $(this).data("value");
        var type = $(this).data("type");
        var charge_percent = $(this).data("chargepercent");
        var maximum = $(this).data("maximum");
        var minimum = $(this).data("minimum");
        var day_wise = $(this).data("day");
        var week_wise = $(this).data("week");
        var month_wise = $(this).data("month");

        $("#editFeeId").val(id);
        $("#edit_tax_title").val(title);
        $("#edit_tax_value").val(value);
        $("#day_wise").val(day_wise);
        $("#week_wise").val(week_wise);
        $("#month_wise").val(month_wise);
        
        $("#edit_fee_type").empty();
        $("#edit_fee_type").append(
            `<option ${type == 'deposit' ? "selected" : ""} value="deposit">Deposit</option>
            <option ${type == 'withdraw' ? "selected" : ""} value="withdraw">Withdraw</option>
            <option ${type == 'admin_withdraw' ? "selected" : ""} value="admin_withdraw">Admin Withdraw</option>            `
        );
        
        $("#charge_percent").val(charge_percent);
        $("#maximum").val(maximum);
        $("#minimum").val(minimum);

        $("#editFeeModal").modal("show");
    });
});
