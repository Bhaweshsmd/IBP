$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    var current_url= window.location;
     var url= `${domainUrl}revenue-setting`;
    if(url==current_url){
        $(".revenueSettingSideA").addClass("activeLi");
        
    }else{
       $(".settingsSideA").addClass("activeLi");
    }

    $("#revenueTable").dataTable({
        dom: "Blfrtip",  
        lengthMenu:[10, 50,100, 500, 1000],
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
            url: `${domainUrl}revenue-setting-list`,
            data: function (data) {
                $(".loader").hide();
                console.log(data);
                
            },
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#passwordForm").on("submit", function (event) {
        event.preventDefault();
        var formdata = new FormData($("#passwordForm")[0]);
        $.ajax({
            url: domainUrl + "changePassword",
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                if (response.status) {
                    iziToast.success({
                        title: strings.success,
                        message: strings.operationSuccessful,
                        position: "topRight",
                    });
                } else {
                    iziToast.error({
                        title: strings.error,
                        message: response.message,
                        position: "topRight",
                    });
                }
                $("#passwordForm").trigger("reset");
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });

    $("#globalSettingsForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#globalSettingsForm")[0]);
        $.ajax({
            url: `${domainUrl}update-settings`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $(function () {
                    swal({
                        title: "Success",
                        text: "General Settings Updated Successfully",
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
    
    $("#globalRevenueSettingsForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#globalRevenueSettingsForm")[0]);
        $.ajax({
            url: `${domainUrl}revenue-setting-update`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#revenueTable").DataTable().ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Revenue Settings Updated Successfully",
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
