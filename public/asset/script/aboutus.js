$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    
    $(".aboutUsSideA").addClass("activeLi");
    

    let summernoteOptions = {
        height: 550,
    };
    $("#summernote").summernote(summernoteOptions);

    $("#terms").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#terms")[0]);
        $.ajax({
            url: domainUrl + "updateTerms",
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
    
    $("#aboutus").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#aboutus")[0]);
        $.ajax({
            url: domainUrl + "updateAboutUs",
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
