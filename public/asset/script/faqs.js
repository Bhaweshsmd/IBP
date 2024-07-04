$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".faqsSideA").addClass("activeLi");

    // Fetch Sound Categories
    var url = `${domainUrl}faq-categories`;
    var faqCategories;
    $.getJSON(url).done(function (data) {
        faqCategories = data.data;
    });

    $("#categoriesTable").dataTable({
        dom: "Blfrtip",
        lengthMenu: [
        [10, 50,100, 500, 1000],
        [10, 50,100, 500, 1000]
        ],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}faqs-cat-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#faqTable").dataTable({
        dom: "Blfrtip",
        lengthMenu: [
        [10, 50,100, 500, 1000],
        [10, 50,100, 500, 1000]
        ],
        buttons: ["csv", "excel", "pdf"],
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}faqs-list`,
            data: function (data) {},
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#faqTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}faqs-delete` + "/" + id;
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
    
    $("#categoriesTable").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}faqs-cat-delete` + "/" + id;
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

    $("#editFaqForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editFaqForm")[0]);
        $.ajax({
            url: `${domainUrl}editFaq`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#editFaqModal").modal("hide");
                $("#editFaqForm").trigger("reset");
                $("#faqTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "FAQ Updated Successfully",
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
    
    $("#addFaqForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addFaqForm")[0]);
        $.ajax({
            url: `${domainUrl}addFaq`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addFaqModal").modal("hide");
                $("#addFaqForm").trigger("reset");
                $("#faqTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "FAQ Added Successfully",
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
    
    $("#addCatForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#addCatForm")[0]);
        $.ajax({
            url: `${domainUrl}faqs-cat-store`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#addCatModal").modal("hide");
                $("#addCatForm").trigger("reset");
                $("#categoriesTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "FAQ Category Added Successfully",
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
    
    $("#editCatForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#editCatForm")[0]);
        $.ajax({
            url: `${domainUrl}faqs-cat-update`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#editCatModal").modal("hide");
                $("#editCatForm").trigger("reset");
                $("#categoriesTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "FAQ Category Updated Successfully",
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

    $("#categoriesTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var title = $(this).data("title");
        var id = $(this).attr("rel");

        $("#editCatId").val(id);
        $("#editCatTitle").val(title);

        $("#editCatModal").modal("show");
    });
    
    $("#faqTable").on("click", ".edit", function (event) {
        event.preventDefault();

        var question = $(this).data("question");
        var answer = $(this).data("answer");
        var catId = $(this).data("cat");
        var id = $(this).attr("rel");

        $("#editFaqId").val(id);
        $("#editFaqQuestion").val(question);
        $("#editFaqAnswer").val(answer);

        $("#editFaqCategory").empty();

        $.each(faqCategories, function (indexInArray, category) {
            $("#editFaqCategory").append(`
                    <option ${
                        category.id == catId ? "selected" : ""
                    } value="${category.id}">${category.title}</option>
                `);
        });

        $("#editFaqModal").modal("show");
    });
});
