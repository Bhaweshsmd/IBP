$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".usersSideA").addClass("activeLi");

    // Fetch Sound Categories
    var url = `${domainUrl}faq-categories`;
    var faqCategories;
    $.getJSON(url).done(function (data) {
        faqCategories = data.data;
    });

    $("#usersTable").dataTable({
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
                targets: [0, 1, 2, 3, 4,5,6,7],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}users-list`,
            data: function (data) {
                console.log(data);
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#usersTable").on("click", ".block", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}block-user` + "/" + id;
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
    
    $("#usersTable").on("click", ".unblock", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}unblock-user` + "/" + id;
        $(function () {
            swal({
                title: "Success",
                text: strings.doYouReallyWantToContinue,
                type: "success",
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
