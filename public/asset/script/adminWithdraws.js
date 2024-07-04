$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".adminWithdrawsSideA").addClass("activeLi");
    
    var url = `${domainUrl}faq-categories`;
    var faqCategories;
    $.getJSON(url).done(function (data) {
        faqCategories = data.data;
    });

    $("#pendingTable").dataTable({
        dom: "Blfrtip",         
        lengthMenu: [[10, 50,100, 500, 1000], [10, 50,100, 500, 1000]],
        buttons: ["csv", "excel", "pdf",],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5,6,7],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}pending-withdrawls-request`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#completedTable").dataTable({
        dom: "Blfrtip",         
        lengthMenu: [[10, 50,100, 500, 1000], [10, 50,100, 500, 1000]],
        buttons: ["csv", "excel", "pdf",],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4,5,6],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}completed-withdrawls-request`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#rejectedTable").dataTable({
        dom: "Blfrtip",         
        lengthMenu: [[10, 50,100, 500, 1000], [10, 50,100, 500, 1000]],
        buttons: ["csv", "excel", "pdf",],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4,5,6],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}rejected-withdrawls-request`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#pendingTable").on("click", ".complete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        $("#completeId").val(id);
        $("#completeModal").modal("show");
    });
    
    $("#pendingTable").on("click", ".reject", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        $("#rejectId").val(id);
        $("#rejectModal").modal("show");
    });

    $("#completeForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#completeForm")[0]);
        $.ajax({
            url: `${domainUrl}complete-withdrawls-request`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#completeModal").modal("hide");
                $("#completeForm").trigger("reset");
                $("#pendingTable").DataTable().ajax.reload(null, false);
                $("#completedTable").DataTable().ajax.reload(null, false);
                $("#rejectedTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Request Completed Successfully",
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
    
    $("#rejectForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#rejectForm")[0]);
        $.ajax({
            url: `${domainUrl}reject-withdrawls-request`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#rejectModal").modal("hide");
                $("#rejectForm").trigger("reset");
                $("#pendingTable").DataTable().ajax.reload(null, false);
                $("#completedTable").DataTable().ajax.reload(null, false);
                $("#rejectedTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Request Rejected Successfully",
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
    
    var total_available_amount=0;
    var inputValue=0;
    $("#withdraw_amount" ).on( "keyup", function() {
        inputValue = $(this).val();
        var withdraw_fee=$("#withdraw_fee").val();
        total_available_amount=$("#total_available_amount").val();
        var total_fee=  inputValue*withdraw_fee/100;
        var total_get_amount=inputValue-total_fee;
        $("#total_request").text(parseFloat(inputValue).toFixed(2));
        $("#total_fee").text(parseFloat(total_fee).toFixed(2));
        $("#total_get_amount").text(parseFloat(total_get_amount).toFixed(2));
    });
    
    $("#adminWithdrawalRequest").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#adminWithdrawalRequest")[0]);
        $.ajax({
            url: `${domainUrl}store-withdrawls-request`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response){
                $(".loader").hide();
                $("#available_amount_to_withdraw").text(parseFloat(total_available_amount-inputValue).toFixed(2));
                $("#addSalonCatModal").modal("hide");
                $("#adminWithdrawalRequest").trigger("reset");
                $("#pendingTable").DataTable().ajax.reload(null, false);
                $("#completedTable").DataTable().ajax.reload(null, false);
                $("#rejectedTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Request Submitted Successfully",
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
