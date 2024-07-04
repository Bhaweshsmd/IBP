$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");

    var userId = $("#userId").val();
    console.log(userId);
    
    var today = new Date().toISOString().split('T')[0];
    document.getElementById("date").setAttribute('min', today);

    $("#walletRechargeLogsTable").dataTable({
        dom: "Blfrtip", 
        lengthMenu:[10, 50,100, 500, 1000], 
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
            url: `${domainUrl}user-recharge-logs`,
            data: function (data) {
                data.userId = userId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#tabWithdrawRequestsTable").dataTable({
        dom: "Blfrtip",         
        lengthMenu:[10, 50,100, 500, 1000],         
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
            url: `${domainUrl}user-withdraw-requests`,
            data: function (data) {
                data.userId = userId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    $("#walletStatementTable").dataTable({
        dom: "Blfrtip",         
        lengthMenu:[10, 50,100, 500, 1000],         
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
            url: `${domainUrl}user-wallet-statements`,
            data: function (data) {
                data.userId = userId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });
    
    
    $("#cardStatementTable").dataTable({
        dom: "Blfrtip",         
        lengthMenu:[10, 50,100, 500, 1000],         
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
            url: `${domainUrl}user-card-statements`,
            data: function (data) {
                data.userId = userId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#bookingsTable").dataTable({
        dom: "Blfrtip",         
        lengthMenu:[10, 50,100, 500, 1000],         
        buttons: ["csv", "excel", "pdf"],         
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}users-bookings-list`,
            data: function (data) {
                data.userId = userId;
            },
            error: (error) => {
                console.log(error);
            },
        },
    });

    $("#tabWithdrawRequestsTable").on("click", ".complete", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        $("#completeId").val(id);

        $("#completeModal").modal("show");
    });
    
    $("#tabWithdrawRequestsTable").on("click", ".reject", function (event) {
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
            url: `${domainUrl}user-complete-withdrawls-request`,
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
                $("#tabWithdrawRequestsTable")
                .DataTable()
                .ajax.reload(null, false);
                $("#walletStatementTable")
                .DataTable()
                .ajax.reload(null, false);
                $(function () {
                    swal({
                        title: "Success",
                        text: "Request Approved Successfully.",
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
            url: `${domainUrl}rejectUserWithdrawal`,
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
                $("#tabWithdrawRequestsTable")
                    .DataTable()
                    .ajax.reload(null, false);
                $("#walletStatementTable")
                    .DataTable()
                    .ajax.reload(null, false);
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

    $("#rechargeWallet").on("click", function (event) {
        event.preventDefault();
        $("#rechargeWalletModal").modal("show");
    });
    
    $("#BookingService").on("click", function (event) {
        event.preventDefault();
        $("#BookingServiceModal").modal("show");
    });
    
    var  slottime=0;
	var  bookings=0;
    const minus = $('.quantity__minus');
    const plus = $('.quantity__plus');
    const input = $('.quantity__input');
    minus.click(function(e) {
        e.preventDefault();
        var slides = document.getElementsByClassName("form-check-input");
        for (var i = 0; i < slides.length; i++){
            var slottime= $('input[name="slot"]:checked').val();
	        var bookings= $('input[name="slot"]:checked').data('bookings');
        }
	    if (slottime && bookings) {
	        document.getElementById('slotsNotSelected').textContent =" ";
	        var value = input.val();
            if (value > 1) {
                value--;
            }
            input.val(value);
        }else{
            document.getElementById('slotsNotSelected').textContent="Slots Not Selected";
        }
    });
  
    plus.click(function(e) {
        e.preventDefault();
        var slides = document.getElementsByClassName("form-check-input");
        for (var i = 0; i < slides.length; i++){
            var slottime= $('input[name="slot"]:checked').val();
            var bookings= $('input[name="slot"]:checked').data('bookings');
        }
	    if(slottime && bookings) {
	        var value = input.val();
	        if(bookings > value){     
               value++;
               input.val(value);
	        }
            
	        document.getElementById('slotsNotSelected').textContent =" ";
        }else{
            document.getElementById('slotsNotSelected').textContent="Slots Not Selected";
        }
    });
       
    
    $("#date").on("change", function(e){
        var date=this.value;
        var bookingHours=$("#selectSlots").val();
        var service_id=document.getElementById("service_id").value;
        var user_id=$("#user_id").val();
        var booking_numbers=$("#booking_numbers").val();
        // alert(service_id);
        getSlotsBookingHours(date,bookingHours,service_id,user_id,booking_numbers);
    }); 
        
    $("#category_id").on("change", function(e){
        var category_id = $("#category_id").val();
        var user_id = $("#user_id").val();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}fetch-services`,
            type: "POST",
            dataType: "json",
            data: {category_id:category_id,user_id:user_id},
            success: function (response) {
                document.getElementById("service_id").innerHTML = "";
                if(response.status===true){
                    var services = response.data.services;
                    if(services.length>0){ 
                        var slist = `<option value="">Select Services </option>`;
                        services.forEach(serviceFuntion);
                        function serviceFuntion(item, index) {
                            slist += `<option data-tokens="${item.id}" value="${item.id}">${item.title}</option>`;
                            document.getElementById("service_id").innerHTML = slist;
                        }
                    }else{
                        document.getElementById("service_id").innerHTML = "No Services Found" 
                    }
                }else{
                    document.getElementById("service_id").innerHTML = "No Services Found" 
                }
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    }); 
    
    $("#selectSlots").on("change", function(e){
        var booking_price = document.getElementById("booking_price").innerHTML;
        var booking_discount = document.getElementById("booking_discount").innerHTML;
        var payable_amount = document.getElementById("payable_amount").innerHTML;
        var discounted_price = document.getElementById("discounted_price").innerHTML;
        var item_name = document.getElementById("item_name").innerHTML;
        var quantity = document.getElementById("quantity").innerHTML;
        var booking_tax = document.getElementById("booking_tax").innerHTML;
        var total_amount = document.getElementById("total_amount").innerHTML;
        var booking_hours = $("#selectSlots").val();
        var service_id = document.getElementById("service_id").value;
        
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}calculate-fees`,
            type: "POST",
            dataType: "json",
            data: {booking_price:booking_price,booking_discount:booking_discount,payable_amount:payable_amount,discounted_price:discounted_price,item_name:item_name,quantity:quantity,booking_tax:booking_tax,total_amount:total_amount,booking_hours:booking_hours,service_id:service_id},
            success: function (response) {
                var slots = response.data.slots;
                var bookhours = response.data.booking_hours;
                var booking_price = response.data.booking_price;
                var booking_discount = response.data.booking_discount;
                var payable_amount = response.data.payable_amount;
                var discounted_price = response.data.discounted_price;
                var item_name = response.data.item_name;
                var quantity = response.data.quantity;
                var booking_tax = response.data.booking_tax;
                var total_amount = response.data.total_amount;
                document.getElementById("booking_price").innerHTML = booking_price;
                document.getElementById("booking_discount").innerHTML = booking_discount;
                document.getElementById("payable_amount").innerHTML = payable_amount;
                document.getElementById("discounted_price").innerHTML = discounted_price;
                document.getElementById("item_name").innerHTML = item_name;
                document.getElementById("quantity").innerHTML = quantity;
                document.getElementById("booking_tax").innerHTML = booking_tax;
                document.getElementById("total_amount").innerHTML = total_amount;
                
                var date = $("#date").val();
                var bookingHours = $("#selectSlots").val();
                var service_id = $("#service_id").val();
                var user_id=$("#user_id").val();
                var booking_numbers=$("#booking_numbers").val();
                
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: `${domainUrl}available-bookings`,
                    type: "POST",
                    dataType: "json",
                    data: {date:date,booking_hours:bookingHours,service_id:service_id,user_id:user_id,booking_numbers:booking_numbers},
                    success: function (response) {
                        document.getElementById("slotslist").innerHTML = "";
                        if(response.status===true){
                            var slots = response.data.slots;
                            var service_type = response.data.service_type;
                            var maximum_quantity = response.data.maximum_quantity;
                            if(slots.length>0){ 
                                slots.forEach(slotFuntion);
                                function slotFuntion(item, index) {
                                    var disabled="";
                                    var className="";
                                    var displayNone="";
                                    var booking_limit = item.booking_limit;
                                    
                                    if(!booking_limit){
                                        disabled="disabled target";
                                        className ="notavailabe";
                                    }else{
                                        className ="availabe";
                                        disabled="notdisabled";
                                    }
                                    
                                    if(service_type==1){
                                        displayNone ="d-none";
                                        booking_limit = maximum_quantity;
                                    }
                                    
                                    const list  = `<div class="form-check-inline visits me-0 ${disabled}" >
                                                     <label class="visit-btns ${className}">
                                                      <input type="radio" class="form-check-input" name="slot" value="${item.time}" data-bookings="${booking_limit}" ${disabled}>
                                                     <span class="visit-rsn" data-bs-toggle="tooltip" title="${item.time}">${item.time}</span>
                                                     <p class="visit-rsn ${displayNone} text-white" data-bs-toggle="tooltip" title="${booking_limit}">${booking_limit} Available </p>
                                                     </label></div>`;
                                    document.getElementById("slotslist").innerHTML += list;
                                }
                            }else{
                                document.getElementById("slotslist").innerHTML="No Time Avaibale"
                            } 
                        }else{
                            document.getElementById("slotslist").innerHTML="No Time Available" 
                        }
                    },
                    error: (error) => {
                        console.log(JSON.stringify(error));
                    },
                });
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    }); 
    
    $("#plus_quant").on("click", function(e){
        var booking_price = document.getElementById("booking_price").innerHTML;
        var booking_discount = document.getElementById("booking_discount").innerHTML;
        var payable_amount = document.getElementById("payable_amount").innerHTML;
        var discounted_price = document.getElementById("discounted_price").innerHTML;
        var item_name = document.getElementById("item_name").innerHTML;
        var quantity = $("#booking_numbers").val();
        var booking_tax = document.getElementById("booking_tax").innerHTML;
        var total_amount = document.getElementById("total_amount").innerHTML;
        var booking_hours = $("#selectSlots").val();
        var service_id = document.getElementById("service_id").value;
        
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}calculate-fees`,
            type: "POST",
            dataType: "json",
            data: {booking_price:booking_price,booking_discount:booking_discount,payable_amount:payable_amount,discounted_price:discounted_price,item_name:item_name,quantity:quantity,booking_tax:booking_tax,total_amount:total_amount,booking_hours:booking_hours,service_id:service_id},
            success: function (response) {
                var slots = response.data.slots;
                var bookhours = response.data.booking_hours;
                var booking_price = response.data.booking_price;
                var booking_discount = response.data.booking_discount;
                var payable_amount = response.data.payable_amount;
                var discounted_price = response.data.discounted_price;
                var item_name = response.data.item_name;
                var quantity = response.data.quantity;
                var booking_tax = response.data.booking_tax;
                var total_amount = response.data.total_amount;
                document.getElementById("booking_price").innerHTML = booking_price;
                document.getElementById("booking_discount").innerHTML = booking_discount;
                document.getElementById("payable_amount").innerHTML = payable_amount;
                document.getElementById("discounted_price").innerHTML = discounted_price;
                document.getElementById("item_name").innerHTML = item_name;
                document.getElementById("quantity").innerHTML = quantity;
                document.getElementById("booking_tax").innerHTML = booking_tax;
                document.getElementById("total_amount").innerHTML = total_amount;
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    }); 
    
    $("#minus_quant").on("click", function(e){
        var booking_price = document.getElementById("booking_price").innerHTML;
        var booking_discount = document.getElementById("booking_discount").innerHTML;
        var payable_amount = document.getElementById("payable_amount").innerHTML;
        var discounted_price = document.getElementById("discounted_price").innerHTML;
        var item_name = document.getElementById("item_name").innerHTML;
        var quantity = $("#booking_numbers").val();
        var booking_tax = document.getElementById("booking_tax").innerHTML;
        var total_amount = document.getElementById("total_amount").innerHTML;
        var booking_hours = $("#selectSlots").val();
        var service_id = document.getElementById("service_id").value;
        
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}calculate-fees`,
            type: "POST",
            dataType: "json",
            data: {booking_price:booking_price,booking_discount:booking_discount,payable_amount:payable_amount,discounted_price:discounted_price,item_name:item_name,quantity:quantity,booking_tax:booking_tax,total_amount:total_amount,booking_hours:booking_hours,service_id:service_id},
            success: function (response) {
                var slots = response.data.slots;
                var bookhours = response.data.booking_hours;
                var booking_price = response.data.booking_price;
                var booking_discount = response.data.booking_discount;
                var payable_amount = response.data.payable_amount;
                var discounted_price = response.data.discounted_price;
                var item_name = response.data.item_name;
                var quantity = response.data.quantity;
                var booking_tax = response.data.booking_tax;
                var total_amount = response.data.total_amount;
                document.getElementById("booking_price").innerHTML = booking_price;
                document.getElementById("booking_discount").innerHTML = booking_discount;
                document.getElementById("payable_amount").innerHTML = payable_amount;
                document.getElementById("discounted_price").innerHTML = discounted_price;
                document.getElementById("item_name").innerHTML = item_name;
                document.getElementById("quantity").innerHTML = quantity;
                document.getElementById("booking_tax").innerHTML = booking_tax;
                document.getElementById("total_amount").innerHTML = total_amount;
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    }); 
  
    function getSlotsBookingHours(date,bookingHours,service_id,user_id,booking_numbers)
    {
        var formdata="";

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}available-bookings`,
            type: "POST",
            dataType: "json",
            data: {date:date,booking_hours:bookingHours,service_id:service_id,user_id:user_id,booking_numbers:booking_numbers},
            success: function (response) {
                document.getElementById("selectSlots").innerHTML= "";

                if(response.status===true){
                    var slots = response.data.slots;
                    var bookhours = response.data.booking_hours;
                    var booking_price = response.data.booking_price;
                    var booking_discount = response.data.booking_discount;
                    var payable_amount = response.data.payable_amount;
                    var discounted_price = response.data.discounted_price;
                    var item_name = response.data.item_name;
                    var quantity = response.data.quantity;
                    var booking_tax = response.data.booking_tax;
                    var total_amount = response.data.total_amount;
                    document.getElementById("booking_price").innerHTML = booking_price;
                    document.getElementById("booking_discount").innerHTML = booking_discount;
                    document.getElementById("payable_amount").innerHTML = payable_amount;
                    document.getElementById("discounted_price").innerHTML = discounted_price;
                    document.getElementById("item_name").innerHTML = item_name;
                    document.getElementById("quantity").innerHTML = quantity;
                    document.getElementById("booking_tax").innerHTML = booking_tax;
                    document.getElementById("total_amount").innerHTML = total_amount;
                    if(bookhours.length>0){
                        var firstbookinghours = bookhours[0];
                        var list_option  = `<option value="">Select Booking Hours </option>`;
                        bookhours.forEach(bookingHoursFuntion);
                        var item_hours;
                        function bookingHoursFuntion(item, index){
                                 if(item==16){
                                         item_hours="Whole Day";
                                    }else{
                                         item_hours=item+" Hrs";  
                              }
                            
                            list_option  += `<option value="${item}">${item_hours}</option>`;
                            document.getElementById("selectSlots").innerHTML = list_option;
                        }
                    }else{
                        document.getElementById("selectSlots").innerHTML="<option value=''>No Hours Available</option>";
                    }
                }else{
                    document.getElementById("selectSlots").innerHTML="No Hours Avaibale";
                }
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    }
      
    $("#BookingServiceForm").on("submit", function (event) {
        event.preventDefault();
        var slides = document.getElementsByClassName("form-check-input");
            for (var i = 0; i < slides.length; i++){
               var slottime= $('input[name="slot"]:checked').val();
	           var bookings= $('input[name="slot"]:checked').data('bookings');
            }
            if(slottime && bookings){
                $(".loader").show();
                var formdata = new FormData($("#BookingServiceForm")[0]);
                $.ajax({
                    url: `${domainUrl}place-booking`,
                    type: "POST",
                    data: formdata,
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        if(!response.status){
                            $(function () {
                                swal({
                                    title: "Sorry",
                                    text: response.message,
                                    type: "warning",
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Close",
                                    closeOnConfirm: false, 
                                })
                            });
                        }else{
                            $("#BookingServiceModal").modal("hide");
                            $("#BookingServiceForm").trigger("reset");
                            var userurl = `${domainUrl}user-booking-details` + "/" + response.booking_id;
                            var serviceurl = `${domainUrl}booking-invoice` + "/" + response.booking_id;
                            $(function () {
                                swal({
                                    title: "Success",
                                    text: response.message,
                                    type: "success",
                                    showCancelButton: true,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "<a href='" + userurl + "'>Booking Details</a>",
                                    cancelButtonText: "<a href='" + serviceurl + "'>Print</a>",
                                    closeOnConfirm: false, 
                                    customClass: "Custom_Cancel"
                                })
                            });
                        }
                        
                        $(".loader").hide();
                    },
                    error: (error) => {
                        $(".loader").hide();
                        console.log(JSON.stringify(error));
                    },
                });
            }else{
                document.getElementById('slotsNotSelected').textContent="Slots Not Selected";
            }
    });
    

    $("#rechargeWalletForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
        var formdata = new FormData($("#rechargeWalletForm")[0]);
        $.ajax({
            url: `${domainUrl}user-wallet-topup`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".loader").hide();
                $("#rechargeWalletModal").modal("hide");
                
                $(function () {
                    swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        confirmButtonColor: "#DD6B55",
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
    
    $("#blockUser").on("click", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}block-user` + "/" + userId;
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
    
    $("#unblockUser").on("click", function (event) {
        event.preventDefault();
        var id = $(this).attr("rel");
        var url = `${domainUrl}unblock-user` + "/" + userId;
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
