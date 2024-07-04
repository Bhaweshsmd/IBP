(function($) {
    "use strict";
    var domainUrl = window.location.origin+"/";

	var stripe_publishable_key = document.getElementById('stripe_publishable_key').value;
    var amount = 0;
    var handler = StripeCheckout.configure({
        key: stripe_publishable_key,
        image: `${domainUrl}assets/img/isidel.png`,
        locale: 'auto',
        token: function(token){
            console.log(token);
            if(token.id){
                var transaction_id = token.id;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }); 
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: `${domainUrl}wallet-topup`,
                    type: "post",
                    dataType: "json",
                    data: {amount:amount,token_id:transaction_id},
                    success: function (response){
                        console.log(response)
                        if(response.status){
                            swal({
                                title: "Success",
                                text: response.message,
                                type: "success",
                                confirmButtonColor: "#000",
                                confirmButtonText: "Close",
                                closeOnConfirm: false, 
                            })
                            setTimeout(function () {
                                location.reload();
                            }, 5000);
                        }else{
                            swal({
                                title: "Success",
                                text: response.message,
                                type: "success",
                                confirmButtonColor: "#000",
                                confirmButtonText: "Close",
                                closeOnConfirm: false, 
                            }) 
                        }          
                    },
                    error: (error) => {
                        console.log(JSON.stringify(error));
                    },
                });
            }
        }
    });

    document.getElementById('confirmButton').addEventListener('click', function(e){
        var   min = $("#amount").attr('min');
        var   max = $("#amount").attr('max');
        amount = $("#amount").val();
        var msg="Amount should be minimum $"+parseFloat(min).toFixed(2)+" and maximum $"+parseFloat(max).toFixed(2);
        if(!parseInt(amount) || parseInt(amount) < parseInt(min) || parseInt(amount) > parseInt(max)){
            swal({
                title: "Success",
                text: msg,
                type: "success",
                confirmButtonColor: "#000",
                confirmButtonText: "Close",
                closeOnConfirm: false, 
            })              
            return;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });   
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}processing-fee`,
            type: "post",
            dataType: "json",
            data: {amount:amount},
            success: function (response){
                console.log(response.data)
                if(response.status){
                    amount =response.data.total_amount
                    $('#add-payment').modal('hide');
                    const list  = ` <div class="wallet-amt">
                        <h5>Amount : $<span id="confirmAmount">${response.data.amount}</span></h5>
                        <h5>Porcessing Fee : $<span id="confirmAmount">${response.data.charge_amount}</span></h5>
                        <h5>Total Amount : $<span id="confirmAmount">${response.data.total_amount}</span></h5>
                    </div>`;
                    document.getElementById("confirmwalletAmount").innerHTML += list;
                    $('#confirmPayment').modal('show');
                }else{
                    swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                }          
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    });

    document.getElementById('customButton').addEventListener('click', function(e){
        $('#confirmPayment').modal('hide');
        $('#add-payment').modal('hide');
        handler.open({
            name: 'Isidel Beach Park',
            description: '',
            amount: amount*100
        });
        e.preventDefault();
    });

    window.addEventListener('popstate', function() {
        handler.close();
    });

})(jQuery);