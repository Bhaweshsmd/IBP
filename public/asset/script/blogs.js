$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".blogSideA").addClass("activeLi");

    $("#activeBlogs").dataTable({
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
                targets: [0, 1],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}blogs-list`,
            data: function (data) {
                console.log(data);
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#activeBlogs").on("click", ".delete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}blog-delete` + "/" + id;
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
