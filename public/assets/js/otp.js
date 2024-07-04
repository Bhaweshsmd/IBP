(function($) {
    "use strict";
    var domainUrl = window.location.origin+"/";
  let timer;
  let countdown = 120; // Set the countdown duration in seconds
  document.getElementById('resendBtn').style.display = "none";
  document.getElementById('resendBtn').disabled = true;
  timer = setInterval(updateTimer, 1000);
	$('#resendBtn').on('click', function(e) {
    var check;
     $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });    
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}resend-otp`,
            type: "post",
            dataType: "json",
            data: {otp:check },
            success: function (response){
               console.log(response)
               if(response.status){
                   iziToast.success({
                        title: response.status,
                        message: response.message,
                        position: "topRight",
                    });
                     document.getElementById('resendBtn').style.display = "none";
                    document.getElementById('resendBtn').disabled = true;
                   timer = setInterval(updateTimer, 1000);
                    
               }else{
                   iziToast.error({
                        message: response.message,
                        position: "topRight",
                    }); 
               }          
            },
            error: (error) => {
                // $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    
    
});

function updateTimer() {
    const timerElement = document.getElementById('timer');
    
    if (countdown > 0) {
        timerElement.textContent = `Resend in ${countdown} seconds`;
        countdown--;
    } else {
        // Enable the button when the countdown reaches zero
         document.getElementById('resendBtn').style.display = "block";
        document.getElementById('resendBtn').disabled = false;
        timerElement.textContent = '';
        
        // Reset countdown for the next attempt
        countdown = 120;
        
        // Stop the timer
        clearInterval(timer);
    }
}
})(jQuery);