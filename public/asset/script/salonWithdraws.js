$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".salonWithdrawsSideA").addClass("activeLi");

    // Fetch Sound Categories
    var url = `${domainUrl}faq-categories`;
    var faqCategories;
    $.getJSON(url).done(function (data) {
        faqCategories = data.data;
    });

    $("#pendingTable").dataTable({
        dom: "Bfrtip",
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchSalonPendingWithdrawalsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#completedTable").dataTable({
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
            url: `${domainUrl}fetchSalonCompletedWithdrawalsList`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    $("#rejectedTable").dataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
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
            url: `${domainUrl}fetchSalonRejectedWithdrawalsList`,
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
            url: `${domainUrl}completeSalonWithdrawal`,
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
                iziToast.success({
                    title: strings.success,
                    message: strings.operationSuccessful,
                    position: "topRight",
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
            url: `${domainUrl}rejectSalonWithdrawal`,
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
                iziToast.success({
                    title: strings.success,
                    message: strings.operationSuccessful,
                    position: "topRight",
                });
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
});
