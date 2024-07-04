$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".privacySideA").addClass("activeLi");

    let summernoteOptions = {
        height: 550,
    };
    $("#summernote").summernote(summernoteOptions);

    $("#privacy").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
            var formdata = new FormData($("#privacy")[0]);
            $.ajax({
                url: domainUrl + "updatePrivacy",
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    // $(".loader").hide();
                    location.reload();
                },
                error: (error) => {
                    $(".loader").hide();
                    console.log(JSON.stringify(error));
                },
            });
    });
});
