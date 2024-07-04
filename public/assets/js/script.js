(function($) {
    "use strict";
    var domainUrl = window.location.origin+"/";
    
    $('.containers').on('click', function () {
        $('.cards').toggleClass('flipped');
    }); 
    
	if($(window).width() <= 991) {
		var Sidemenu = function() {
			this.$menuItem = $('.main-nav a');
		};

		function init() {
			var $this = Sidemenu;
			$('.main-nav a').on('click', function(e) {
				if($(this).parent().hasClass('has-submenu')) {
					e.preventDefault();
				}
				if(!$(this).hasClass('submenu')) {
					$('ul', $(this).parents('ul:first')).slideUp(350);
					$('a', $(this).parents('ul:first')).removeClass('submenu');
					$(this).next('ul').slideDown(350);
					$(this).addClass('submenu');
				} else if($(this).hasClass('submenu')) {
					$(this).removeClass('submenu');
					$(this).next('ul').slideUp(350);
				}
			});
		}
	    init();
	}
    
    $(".checkLogin").on('click', function(e) {
        var url = `${domainUrl}login`;
        $(function () {
            swal({
                title: "Warning",
                text: "Login to Continue",
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

	$(document).ready(function(){
        $("#countries,#country").select2({
            templateResult: formatState
        });
    });
    
    let icons = {
        "+93": domainUrl+"public/flags/af.png",
        "+355": domainUrl+"public/flags/al.png",
        "+0": domainUrl+"public/flags/pn.png",
        "+213": domainUrl+"public/flags/dz.png",
        "+1684": domainUrl+"public/flags/as.png",
        "+376": domainUrl+"public/flags/ad.png",
        "+244": domainUrl+"public/flags/ao.png",
        "+1": domainUrl+"public/flags/vi.png",
        "+994": domainUrl+"public/flags/az.png",
        "+54": domainUrl+"public/flags/ar.png",
        "+61": domainUrl+"public/flags/cx.png",
        "+43": domainUrl+"public/flags/at.png",
        "+973": domainUrl+"public/flags/bh.png",
        "+880": domainUrl+"public/flags/bd.png",
        "+374": domainUrl+"public/flags/am.png",
        "+32": domainUrl+"public/flags/be.png",
        "+1441": domainUrl+"public/flags/bm.png",
        "+975": domainUrl+"public/flags/bt.png",
        "+591": domainUrl+"public/flags/bo.png",
        "+387": domainUrl+"public/flags/ba.png",
        "+267": domainUrl+"public/flags/bw.png",
        "+55": domainUrl+"public/flags/br.png",
        "+501": domainUrl+"public/flags/bz.png",
        "+246": domainUrl+"public/flags/io.png",
        "+677": domainUrl+"public/flags/sb.png",
        "+673": domainUrl+"public/flags/bn.png",
        "+359": domainUrl+"public/flags/bg.png",
        "+95": domainUrl+"public/flags/mm.png",
        "+257": domainUrl+"public/flags/bi.png",
        "+375": domainUrl+"public/flags/by.png",
        "+855": domainUrl+"public/flags/kh.png",
        "+237": domainUrl+"public/flags/cm.png",
        "+238": domainUrl+"public/flags/cv.png",
        "+1345": domainUrl+"public/flags/ky.png",
        "+236": domainUrl+"public/flags/cf.png",
        "+94": domainUrl+"public/flags/lk.png",
        "+235": domainUrl+"public/flags/td.png",
        "+56": domainUrl+"public/flags/cl.png",
        "+86": domainUrl+"public/flags/cn.png",
        "+886": domainUrl+"public/flags/tw.png",
        "+672": domainUrl+"public/flags/nf.png",
        "+57": domainUrl+"public/flags/co.png",
        "+269": domainUrl+"public/flags/yt.png",
        "+242": domainUrl+"public/flags/cd.png",
        "+682": domainUrl+"public/flags/ck.png",
        "+506": domainUrl+"public/flags/cr.png",
        "+385": domainUrl+"public/flags/hr.png",
        "+53": domainUrl+"public/flags/cu.png",
        "+357": domainUrl+"public/flags/cy.png",
        "+420": domainUrl+"public/flags/cz.png",
        "+229": domainUrl+"public/flags/bj.png",
        "+45": domainUrl+"public/flags/dk.png",
        "+593": domainUrl+"public/flags/ec.png",
        "+503": domainUrl+"public/flags/sv.png",
        "+240": domainUrl+"public/flags/gq.png",
        "+251": domainUrl+"public/flags/et.png",
        "+291": domainUrl+"public/flags/er.png",
        "+372": domainUrl+"public/flags/ee.png",
        "+298": domainUrl+"public/flags/fo.png",
        "+500": domainUrl+"public/flags/fk.png",
        "+679": domainUrl+"public/flags/fj.png",
        "+358": domainUrl+"public/flags/fi.png",
        "+33": domainUrl+"public/flags/fr.png",
        "+594": domainUrl+"public/flags/gf.png",
        "+689": domainUrl+"public/flags/pf.png",
        "+253": domainUrl+"public/flags/dj.png",
        "+241": domainUrl+"public/flags/ga.png",
        "+995": domainUrl+"public/flags/ge.png",
        "+220": domainUrl+"public/flags/gm.png",
        "+970": domainUrl+"public/flags/ps.png",
        "+49": domainUrl+"public/flags/de.png",
        "+233": domainUrl+"public/flags/gh.png",
        "+350": domainUrl+"public/flags/gi.png",
        "+686": domainUrl+"public/flags/ki.png",
        "+30": domainUrl+"public/flags/gr.png",
        "+299": domainUrl+"public/flags/gl.png",
        "+590": domainUrl+"public/flags/gp.png",
        "+1671": domainUrl+"public/flags/gu.png",
        "+502": domainUrl+"public/flags/gt.png",
        "+224": domainUrl+"public/flags/gn.png",
        "+592": domainUrl+"public/flags/gy.png",
        "+509": domainUrl+"public/flags/ht.png",
        "+39": domainUrl+"public/flags/it.png",
        "+504": domainUrl+"public/flags/hn.png",
        "+852": domainUrl+"public/flags/hk.png",
        "+36": domainUrl+"public/flags/hu.png",
        "+354": domainUrl+"public/flags/is.png",
        "+91": domainUrl+"public/flags/in.png",
        "+62": domainUrl+"public/flags/id.png",
        "+98": domainUrl+"public/flags/ir.png",
        "+964": domainUrl+"public/flags/iq.png",
        "+353": domainUrl+"public/flags/ie.png",
        "+972": domainUrl+"public/flags/il.png",
        "+225": domainUrl+"public/flags/ci.png",
        "+1876": domainUrl+"public/flags/jm.png",
        "+81": domainUrl+"public/flags/jp.png",
        "+7": domainUrl+"public/flags/kz.png",
        "+962": domainUrl+"public/flags/jo.png",
        "+254": domainUrl+"public/flags/ke.png",
        "+850": domainUrl+"public/flags/kp.png",
        "+82": domainUrl+"public/flags/kr.png",
        "+965": domainUrl+"public/flags/kw.png",
        "+996": domainUrl+"public/flags/kg.png",
        "+856": domainUrl+"public/flags/la.png",
        "+961": domainUrl+"public/flags/lb.png",
        "+266": domainUrl+"public/flags/ls.png",
        "+371": domainUrl+"public/flags/lv.png",
        "+231": domainUrl+"public/flags/lr.png",
        "+218": domainUrl+"public/flags/ly.png",
        "+423": domainUrl+"public/flags/li.png",
        "+370": domainUrl+"public/flags/lt.png",
        "+352": domainUrl+"public/flags/lu.png",
        "+853": domainUrl+"public/flags/mo.png",
        "+261": domainUrl+"public/flags/mg.png",
        "+265": domainUrl+"public/flags/mw.png",
        "+60": domainUrl+"public/flags/my.png",
        "+960": domainUrl+"public/flags/mv.png",
        "+223": domainUrl+"public/flags/ml.png",
        "+356": domainUrl+"public/flags/mt.png",
        "+596": domainUrl+"public/flags/mq.png",
        "+222": domainUrl+"public/flags/mr.png",
        "+230": domainUrl+"public/flags/mu.png",
        "+52": domainUrl+"public/flags/mx.png",
        "+377": domainUrl+"public/flags/mc.png",
        "+976": domainUrl+"public/flags/mn.png",
        "+373": domainUrl+"public/flags/md.png",
        "+212": domainUrl+"public/flags/eh.png",
        "+258": domainUrl+"public/flags/mz.png",
        "+968": domainUrl+"public/flags/om.png",
        "+264": domainUrl+"public/flags/na.png",
        "+674": domainUrl+"public/flags/nr.png",
        "+977": domainUrl+"public/flags/np.png",
        "+31": domainUrl+"public/flags/nl.png",
        "+599": domainUrl+"public/flags/bq.png",
        "+297": domainUrl+"public/flags/aw.png",
        "+687": domainUrl+"public/flags/nc.png",
        "+678": domainUrl+"public/flags/vu.png",
        "+64": domainUrl+"public/flags/nz.png",
        "+505": domainUrl+"public/flags/ni.png",
        "+227": domainUrl+"public/flags/ne.png",
        "+234": domainUrl+"public/flags/ng.png",
        "+683": domainUrl+"public/flags/nu.png",
        "+47": domainUrl+"public/flags/sj.png",
        "+1670": domainUrl+"public/flags/mp.png",
        "+691": domainUrl+"public/flags/fm.png",
        "+692": domainUrl+"public/flags/mh.png",
        "+680": domainUrl+"public/flags/pw.png",
        "+92": domainUrl+"public/flags/pk.png",
        "+507": domainUrl+"public/flags/pa.png",
        "+675": domainUrl+"public/flags/pg.png",
        "+595": domainUrl+"public/flags/py.png",
        "+51": domainUrl+"public/flags/pe.png",
        "+63": domainUrl+"public/flags/ph.png",
        "+48": domainUrl+"public/flags/pl.png",
        "+351": domainUrl+"public/flags/pt.png",
        "+245": domainUrl+"public/flags/gw.png",
        "+670": domainUrl+"public/flags/tl.png",
        "+974": domainUrl+"public/flags/qa.png",
        "+262": domainUrl+"public/flags/re.png",
        "+40": domainUrl+"public/flags/ro.png",
        "+70": domainUrl+"public/flags/ru.png",
        "+250": domainUrl+"public/flags/rw.png",
        "+290": domainUrl+"public/flags/sh.png",
        "+508": domainUrl+"public/flags/pm.png",
        "+378": domainUrl+"public/flags/sm.png",
        "+239": domainUrl+"public/flags/st.png",
        "+966": domainUrl+"public/flags/sa.png",
        "+221": domainUrl+"public/flags/sn.png",
        "+381": domainUrl+"public/flags/cs.png",
        "+248": domainUrl+"public/flags/sc.png",
        "+232": domainUrl+"public/flags/sl.png",
        "+65": domainUrl+"public/flags/sg.png",
        "+421": domainUrl+"public/flags/sk.png",
        "+84": domainUrl+"public/flags/vn.png",
        "+386": domainUrl+"public/flags/si.png",
        "+252": domainUrl+"public/flags/so.png",
        "+27": domainUrl+"public/flags/za.png",
        "+263": domainUrl+"public/flags/zw.png",
        "+34": domainUrl+"public/flags/es.png",
        "+249": domainUrl+"public/flags/sd.png",
        "+597": domainUrl+"public/flags/sr.png",
        "+268": domainUrl+"public/flags/sz.png",
        "+46": domainUrl+"public/flags/se.png",
        "+41": domainUrl+"public/flags/ch.png",
        "+963": domainUrl+"public/flags/sy.png",
        "+992": domainUrl+"public/flags/tj.png",
        "+66": domainUrl+"public/flags/th.png",
        "+228": domainUrl+"public/flags/tg.png",
        "+690": domainUrl+"public/flags/tk.png",
        "+676": domainUrl+"public/flags/to.png",
        "+1868": domainUrl+"public/flags/tt.png",
        "+971": domainUrl+"public/flags/ae.png",
        "+216": domainUrl+"public/flags/tn.png",
        "+90": domainUrl+"public/flags/tr.png",
        "+7370": domainUrl+"public/flags/tm.png",
        "+1649": domainUrl+"public/flags/tc.png",
        "+688": domainUrl+"public/flags/tv.png",
        "+256": domainUrl+"public/flags/ug.png",
        "+380": domainUrl+"public/flags/ua.png",
        "+389": domainUrl+"public/flags/mk.png",
        "+20": domainUrl+"public/flags/eg.png",
        "+44": domainUrl+"public/flags/gb.png",
        "+255": domainUrl+"public/flags/tz.png",
        "+226": domainUrl+"public/flags/bf.png",
        "+598": domainUrl+"public/flags/uy.png",
        "+998": domainUrl+"public/flags/uz.png",
        "+58": domainUrl+"public/flags/ve.png",
        "+681": domainUrl+"public/flags/wf.png",
        "+684": domainUrl+"public/flags/ws.png",
        "+967": domainUrl+"public/flags/ye.png",
        "+260": domainUrl+"public/flags/zm.png",
        "+721": domainUrl+"public/flags/sx.png"
    }
    
    function formatState (state) {
        if (!state.id) { return state.text; }
        console.log(state);
        var $state = $(    `
            <div style="display: flex; align-items: center;">
               <div><img style="display: inline-block;height:30px;width:40px;" src="${icons[state.id]}"  /></div>
               <div style="margin-left: 10px;">
                  ${state.text}
               </div>
            </div>
            `
        );
        return $state;
    }

	$('body').append('<div class="sidebar-overlay"></div>');
	$(document).on('click', '#mobile_btn', function() {
		$('main-wrapper').toggleClass('slide-nav');
		$('.sidebar-overlay').toggleClass('opened');
		$('html').addClass('menu-opened');
		return false;
	});	
	
	$(document).on('click', '.sidebar-overlay', function() {
		$('html').removeClass('menu-opened');
		$(this).removeClass('opened');
		$('main-wrapper').removeClass('slide-nav');
	});
	
	$(document).on('click', '#menu_close', function() {
		$('html').removeClass('menu-opened');
		$('.sidebar-overlay').removeClass('opened');
		$('main-wrapper').removeClass('slide-nav');
	});

	setTimeout(function () {
		$('#global-loader');
		setTimeout(function () {
			$("#global-loader").fadeOut("slow");
		}, 100);
	}, 500);

	var NavBar = $('nav.navbar');
    var didScroll;
    var lastScrollTop = 0;
    var navbarHeight = NavBar.outerHeight();
    $(function() {
        didScroll = true;
    });
    $(window).on('scroll',function(event) {
        didScroll = true;
    });
    setInterval(function() {
        if (didScroll) {
            hasScrolled();
            didScroll = false;
        }
    }, 100);
	
	function hasScrolled() {
        var st = $(document).scrollTop();
        if (st + $(window).height()) {
        	$(".header-trans").css("background" , "#1E425E");
            $(".header-sticky").addClass("fixed-top");
            if (st === 0) {
            	$(".header-trans").css("background" , "transparent");
            	$(".header-sticky").removeClass("fixed-top");
            }
        }
        lastScrollTop = st;
    }
    
	$(document).on('click','.applyCoupon', function(e){
		var coupon_id =  $(this).data('id');
	    var $this = $(this);
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}apply-coupon`,
            type: "post",
            dataType: "json",
            data: {coupon_id:coupon_id},
            success: function (response){
               console.log(response.data)
               if(response.status){
                    $this.text(response.data.statusText);
                    $(".amount_after_discount").text(response.data.amount_after_discount);
                    swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
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
    
	if($('.featured-venues-slider').length > 0) {       
		$('.featured-venues-slider').owlCarousel({
			loop:true,
			margin:24,
			nav:true,
			dots: false,
			autoplay:false,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:1
				},
				768:{
					items:2
				},
				1000:{
					items:3
				}
			}
		})
	}

	if($('.featured-coache-slider').length > 0) {       
		$('.featured-coache-slider').owlCarousel({
			loop:true,
			margin:24,
			nav:true,
			dots: false,
			autoplay:false,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:1
				},
				768:{
					items:2
				},
				1000:{
					items:4
				}
			}
		})
	}
    
    if($('.map-images-slider').length > 0) {       
		$('.map-images-slider').owlCarousel({
			loop:true,
			margin:24,
			nav:true,
			dots: false,
			autoplay:false,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:1
				},
				768:{
					items:1
				},
				1000:{
					items:1
				}
			}
		})
	}
    
	if($('.testimonial-brand-slider').length > 0) {       
		$('.testimonial-brand-slider').owlCarousel({
			loop:true,
			margin:60,
			nav:false,
			dots: false,
			autoplay:true,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:1
				},
				768:{
					items:3
				},
				1000:{
					items:5
				}
			}
		})
	}

	if($('.main-wrapper .aos').length > 0) {
		AOS.init({
			duration:1200,
			once:true
		});
	}

	$(document).on("click",".logo-hide-btn",function () {
		$(this).parent().hide();
	});

	$(window).on('scroll',function() {
		if ($(this).scrollTop() > 150){
			$('.map-right').addClass('map-top');
		} else {
			$('.map-right').removeClass('map-top');
		}
	});

	if ($('.datatable').length > 0) {
		$('.datatable').DataTable({
			retrieve: true,
				lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
			language: {
				search: ' ',
				searchPlaceholder: "Search",
				info:  "Showing _START_ - _END_ of _TOTAL_ entries",
				"lengthMenu": "Show _MENU_",
				paginate: {
				  next: '<i class="feather-chevrons-right"></i>',
				  previous: '<i class="feather-chevrons-left"></i>'  
				}
			},
			"bLengthChange": true,
			"bInfo": false,
			initComplete: (settings, json)=>{
                $('.dataTables_info').appendTo('#tableinfo');
                $('.dataTables_paginate').appendTo('#tablepage');
                $('.dataTables_length').appendTo('#tablelength');
                $('.dataTables_filter').appendTo('#tablefilter');
            },
		});
	} 

	$("#select_days_1").on('change', function() { 
        if( $(this).is(":checked") ){ 
			$('#day-monday').show();
        }
		else {
			$('#day-monday').hide();
		}
    });
	$("#select_days_2").on('change', function() { 
        if( $(this).is(":checked")) { 
			$('#day-tuesday').show();
        }
		else {
			$('#day-tuesday').hide();
		}
    });
	$("#select_days_3").on('change', function() { 
        if( $(this).is(":checked")) { 
			$('#day-wednesday').show();
        }
		else {
			$('#day-wednesday').hide();
		}
    });
	$("#select_days_4").on('change', function() { 
        if( $(this).is(":checked")) { 
			$('#day-thursday').show();
        }
		else {
			$('#day-thursday').hide();
		}
    });
	$("#select_days_5").on('change', function(){ 
        if( $(this).is(":checked")) { 
			$('#day-friday').show();
        }
		else {
			$('#day-friday').hide();
		}
    });
	$("#select_days_6").on('change', function() { 
        if( $(this).is(":checked")) { 
			$('#day-saturday').show();
        }
		else {
			$('#day-saturday').hide();
		}
    });
	$("#select_days_7").on('change', function() { 
        if( $(this).is(":checked")) { 
			$('#day-sunday').show();
        }
		else {
			$('#day-sunday').hide();
		}
    });

	if($('.datetimepicker1').length > 0 ) {
		$('.datetimepicker1').datetimepicker({
			format: 'hh:mm A',
			icons: {
				up: "fas fa-angle-up",
				down: "fas fa-angle-down",
				next: 'fas fa-angle-right',
				previous: 'fas fa-angle-left'
			}
		});
	}

	$('.icon-date').on('click', function() {
		$('#date').focus();
	})

	$('.icon-time').on('click', function() {
		$('#timePicker').focus();
	})

	$('.fav-icon').on('click', function(e) {
	        $(this).toggleClass('selected');
	        var id = $(this).attr("rel");
	        addRemoveFav(id);
	});

	$('.favour-adds').on('click', function() {
		$(this).toggleClass('selected-color');
	});
	  
	$('.addtofavroute').on('click', function(e) {
	  
        $(this).closest('tr').remove();
     if ($(this).text() == "Remove from favourite"){
         $(this).text("Add to favourite")
      }else
       $(this).text("Remove from favourite");
   	});  
	  
	  
	$("#proceedWithdrawButton").on("click", function (event) {
        var amount = $("#withdraw_amount").val();
        var bank_id=$("#bank_id").val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });    

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}withdraw-processing-fee`,
            type: "post",
            dataType: "json",
            data: {amount:amount,bank_id:bank_id},
            success: function (response){
                console.log(response.data)
                if(response.status){
                    $('#request-payment').modal('hide');
                      const list  = ` <div class="wallet-amt">
                        <h5>Amount : $<span id="confirmAmount">${response.data.amount}</span></h5>
                         <h5>Porcessing Fee : $<span id="confirmAmount">${response.data.charge_amount}</span></h5>
                         <h5>Total Amount : $<span id="confirmAmount">${response.data.total_amount}</span></h5>
                    </div>`;
                     document.getElementById("confirmWithdrawwalletAmount").innerHTML += list;
                     $('#confirmWithdrawRequest').modal('show');
               
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
	
    $("#submitSupporticket").on("click", function (event) {
        var subject = $("#subject").val();
        var priority=$("#priority").val();
        var  reason=$("#reason").val();
        var  description=$("#description").val();
     
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });    

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}create-ticket`,
            type: "post",
            dataType: "json",
            data: {subject:subject,priority:priority,reason:reason,description:description},
            success: function (response){
                console.log(response)
                if(response.status){
                    $('#request-payment').modal('hide');
                    swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    }) 
                    setTimeout(function (){
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
    });
	
	$("#confirmWithdrawButton").on("click", function (event) {
        event.preventDefault();
        var amount=$("#withdraw_amount").val();
        var bank_id=$("#bank_id").val();
        $(".loader").show();
        var formdata = new FormData($("#addBannerForm")[0]); 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}create-withdraw-request`,
            type: "post",
            dataType: "json",
            data: {amount:amount,bank_id:bank_id},
          
            success: function (response) {
                if(response.status){
                    $(".payment-modal").modal("hide");
                    swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                    setTimeout(function (){
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
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });

 	$(".changeLang").change(function(){
       window.location.href = `${domainUrl}change-language/`+ $(this).val();
    });
	
	function addRemoveFav(id){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}add-remove-fav-services/`+id,
            type: "get",
            dataType: "json",
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
                document.getElementById("addRemoveContent").innerHTML = "";
                if(response.addrem=="added"){
                    document.getElementById("addRemoveContent").innerHTML = "Remove from favourite";
                    $("#selectedHeart").addClass('text-danger');
                    $(this).addClass('selected');
                }else{
                   document.getElementById("addRemoveContent").innerHTML = "Add to favourite";
                   $("#selectedHeart").removeClass('text-danger');
                   $(this).removeClass('selected');
                }        
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
	}
	
	$('#WalletCheckout').on('click', function(e){
		check = document.getElementById("policy").checked;
		if(!check){
            swal({
                title: "Warning",
                text: "Please accept term and condtions",
                type: "warning",
                confirmButtonColor: "#000",
                confirmButtonText: "Close",
                closeOnConfirm: false, 
            })
            return;
        }
        
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}place-booking`,
            type: "get",
            dataType: "json",
            success: function (response){
                console.log(response)
              
                swal({
                    title: "Success",
                    text: response.message,
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
                
                if(response.status){
                    location.replace(`${domainUrl}`+'booking-success');
                }
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
	});
	
	
	$('#checkOTP').on('click', function(e) {
	    check = document.getElementById("current_otp").value;
	    if(!check){
            swal({
                title: "Warning",
                text: "Please enter OTP",
                type: "warning",
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
            url: `${domainUrl}otp-verification`,
            type: "post",
            dataType: "json",
            data: {otp:check },
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
                    
                    if(response.status){
                        location.replace(`${domainUrl}`+'register-user');
                    }
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
	
	
	$(document).on('click','#cancelBooking', function(e) {
		var booking_id =  $(this).data('bookingid');
			      
	    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });    

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}cancel-booking`,
            type: "post",
            dataType: "json",
            data: {booking_id:booking_id },
            success: function (response){
                console.log(response)
                if(response.status){
                    $(".request-modal").modal('hide');
                    swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                    if(response.status){
                        setTimeout(function (){
                            location.reload();
                        }, 5000);
                    }
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
	
	if ($('.select').length > 0) {
		$('.select').select2({
			minimumResultsForSearch: -1,
			width: '100%'
		});
	}

	if($('.coach-count .counter-up').length > 0) {
		$('.coach-count .counter-up').counterUp({
            delay: 15,
            time: 1500
        });
	}

	if($('.timepicker').length > 0 ) {
		$('.timepicker').datetimepicker({
			format: 'HH:mm A',
			icons: {
				up: "fa-solid fa-chevron-up",
				down: "fa-solid fa-chevron-down",
				next: 'fa-solid fa-chevron-right',
				previous: 'fa-solid fa-chevron-left'
			}
		});
	}
	
	if($('.datetimepicker').length > 0 ) {
	    $('.datetimepicker').datetimepicker({
    	    minDate: new Date().toDateString(),
    		format: 'DD-MM-YYYY',
    		icons: {
    			up: "fa-solid fa-chevron-up",
    			down: "fa-solid fa-chevron-down",
    			next: 'fa-solid fa-chevron-right',
    			previous: 'fa-solid fa-chevron-left'
    		}
     	});
	}
	   
    var service_type=$("#service_type").val();
    var  maximum_quantity=$("#maximum_quantity").val();
    var bookingHours=$("#selectSlots").val();
    var date=$("#date").val();
    var rescheduleDate;

    $(".datetimepicker").on("dp.change", function(e){
        var date=this.value;
        rescheduleDate=this.value;
        var bookingHours=$("#selectSlots").val();
        var currentUrl=window.location.href;
        if(currentUrl==domainUrl+'user-bookings' || currentUrl==domainUrl+'user-ongoing'){
            var  reschedulBookingHours=  $(this).attr("data-reschedulbookinghours");
            var rescheduleslotslist=$(this).attr("data-rescheduleslotslist");
            var maxQauntity=$(this).attr("data-maxQauntity");
            var seviceType=$(this).attr("data-seviceType");
            getRescheduleSlots(rescheduleDate,reschedulBookingHours,rescheduleslotslist,maxQauntity,seviceType);
        }else{
            getSlotsBookingHours(date,bookingHours);
        } 
    }); 
    
    $("#selectSlots").on("change", function(e){
        var date=$("#date").val();
        var bookingHours=this.value;
        getSlots(date,bookingHours);
    }); 
      
    var subtotals=$('#subtotal').text(); 
    var subtotal=0;
    
    function getSlotsBookingHours(date,bookingHours){
        var formdata="";
        subtotal=$('#subtotal').text(); 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}booked-slots`,
            type: "POST",
            dataType: "json",
            data: {date:date,booking_hours:bookingHours },
            success: function (response) {
                document.getElementById("slotslist").innerHTML = "";
                document.getElementById("selectSlots").innerHTML= "";
                if(response.status===true){
                    var slots = response.data.slots;
                    var bookhours=response.data.booking_hours;
                    if(bookhours.length>0){
                        var firstbookinghours = bookhours[0];
                        var list_option  = `<option>Select Booking Hours </option>`;
                        bookhours.forEach(bookingHoursFuntion);
                        var item_hours;
                        function bookingHoursFuntion(item, index){
                            if(item==16){
                                item_hours="Whole Day";
                            }else{
                                item_hours=item+" Hrs";  
                            }
                            
                            list_option  = `<option value="${item}">${item_hours}</option>`;
                            document.getElementById("selectSlots").innerHTML += list_option;
                        }
                        
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: `${domainUrl}booked-slots`,
                            type: "POST",
                            dataType: "json",
                            data: {date:date,booking_hours:firstbookinghours },
                            success: function (response) {
                                document.getElementById("slotslist").innerHTML = "";
                                if(response.status===true){
                                    var slots = response.data.slots;
                                    if(slots.length>0){ 
                                        subtotal = parseFloat(subtotals)*parseFloat(firstbookinghours);
                                        $('#subtotal').text(subtotal.toFixed(2)); 
                                        document.getElementById('adults').value=1;
                                        slots.forEach(slotFuntion);
                                        function slotFuntion(item, index) {
                                           var disabled="";
                                           var className="";
                                           var displayNone="";
                                            var booking_limit=  item.booking_limit;
                                            if(!booking_limit){
                                                disabled="disabled target";
                                                className ="notavailabe";
                                            }else{
                                                className ="availabe";
                                                disabled="notdisabled";
                                            }
                                            
                                            if(service_type==1){
                                                displayNone ="d-none";
                                                booking_limit=maximum_quantity;
                                            }
                                            
                                            const list  = `<div class="form-check-inline visits me-0 ${disabled}" >
                                                             <label class="visit-btns ${className}">
                                                              <input type="radio" class="form-check-input" name="slot" value="${item.time}" data-bookings="${booking_limit}" ${disabled}>
                                                             <span class="visit-rsn" data-bs-toggle="tooltip" title="${item.time}">${item.time}</span>
                                                             <p class="visit-rsn ${displayNone}" data-bs-toggle="tooltip" title="${booking_limit}">${booking_limit} Available </p>
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
                    }else{
                        document.getElementById("selectSlots").innerHTML="<option>No Hours Available</option>";
                        document.getElementById("slotslist").innerHTML="No Time Available" 
                    }
                }else{
                    document.getElementById("selectSlots").innerHTML="No Hours Avaibale";
                    document.getElementById("slotslist").innerHTML="No Time Available" 
                }
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    }

    function disableSiblings(targetClass, number) {
        const targets = document.querySelectorAll(`.${targetClass}`);
        targets.forEach(target => {
            let current = target;
            for (let i = 0; i < number; i++) {
                if (current.previousElementSibling) {
                    current = current.previousElementSibling;
                    current.classList.remove('notdisabled');
                     current.classList.add('disabled');
                      $(".disabled").children().addClass("notavailabe");
                     $(".disabled").children().addClass("notavailabe");
                  $(".notavailabe").children().attr("disabled", "");
                } else {
                    break;
                }
            }
            current = target;

            for (let i = 0; i < number; i++) {
                if (current.nextElementSibling) {
                    current = current.nextElementSibling;
                   current.classList.remove('notdisabled');
                     current.classList.add('disabled');
                      $(".disabled").children().addClass("notavailabe");
                     $(".disabled").children().addClass("notavailabe");
                  $(".notavailabe").children().attr("disabled", "");
                } else {
                    break;
                }
            }
        });
    }

    function getRescheduleSlots(rescheduleDate,bookingHours,rescheduleslotslist,maxQauntity,seviceType){
        var booking_id=rescheduleslotslist
        var maxQauntity=maxQauntity;
        var seviceType=seviceType;
        var formdata="";
        subtotals=$('#subtotal').text(); 
           
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}reschedule-booked-slots`,
            type: "POST",
            dataType: "json",
            data: {date:rescheduleDate,booking_hours:bookingHours,booking_id:booking_id},
            success: function (response){
                document.getElementById(rescheduleslotslist).innerHTML = "";
                console.log(response)
                if(response.status==true){
                    var slots = response.data.slots;
                    console.log(slots.length);
                    if(slots.length>0){
                        var  subtotal = parseFloat(subtotals)*parseFloat(bookingHours);
                        $('#subtotal').text(subtotal.toFixed(2));
                        slots.forEach(slotFuntion);
              
                        function slotFuntion(item, index) {
                            var disabled="";
                            var className="";
                            var displayNone="";
                            var booking_limit=  item.booking_limit;
                            if(!booking_limit){
                                disabled="disabled target";
                                className ="notavailabe";
                            }else{
                                className ="availabe";
                                disabled="notdisabled";
                            }
                            if(service_type==1){
                                displayNone ="d-none";
                                booking_limit=maximum_quantity;
                            }
                            if(seviceType==0){
                                if(maxQauntity>booking_limit){
                                    disabled="disabled target";
                                    className ="notavailabe";
                                }
                            }
                                       
                            const list  = `<div class="form-check-inline visits me-0 ${disabled}" >
                                         <label class="visit-btns ${className}">
                                          <input type="radio" class="form-check-input" name="slot" value="${item.time}" data-bookings="${booking_limit}" ${disabled}>
                                         <span class="visit-rsn" data-bs-toggle="tooltip" title="${item.time}">${item.time}</span>
                                         <p class="visit-rsn ${displayNone}" data-bs-toggle="tooltip" title="${booking_limit}">${booking_limit} Available </p>
                                         </label></div>`;
                            document.getElementById(rescheduleslotslist).innerHTML += list;
                        }
                    }else{
                        document.getElementById(rescheduleslotslist).innerHTML="No Time Avaibale"
                    } 
                }else{
                    document.getElementById(rescheduleslotslist).innerHTML="No Time Avaibale" 
                }
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    }
      
    $(document).on("click",".rescheduleBooking",function(){
        var booking_id = $(this).attr("data-bookingid");
        var slides = document.getElementsByClassName("form-check-input");
        for (var i = 0; i < slides.length; i++){
            var slottime= $('input[name="slot"]:checked').val();
        }
        if(rescheduleDate==="" || rescheduleDate===undefined || booking_id==="" || booking_id===undefined || slottime==="" || slottime===undefined){
            swal({
                title: "Warning",
                text: "Please select Date & Time",
                type: "warning",
                confirmButtonColor: "#000",
                confirmButtonText: "Close",
                closeOnConfirm: false, 
            })
        }else{
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }); 
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: `${domainUrl}reschedule-booking`,
                type: "post",
                dataType: "json",
                data: {date:rescheduleDate,booking_id:booking_id,time:slottime},
                success: function (response){
                    console.log(response)
                    if(response.status){
                        $(".request-modal").modal('hide');
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
    });
      
    function getSlots(date,bookingHours){
        var formdata="";
        subtotals=$('#subtotal').text(); 
           
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}booked-slots`,
            type: "POST",
            dataType: "json",
            data: {date:date,booking_hours:bookingHours },
            success: function (response) {
                document.getElementById("slotslist").innerHTML = "";
                if(response.status==true){
                    var slots = response.data.slots;
                    console.log(slots.length);
                    if(slots.length>0){
                        var  subtotal = parseFloat(subtotals)*parseFloat(bookingHours);
                        $('#subtotal').text(subtotal.toFixed(2));
                        document.getElementById('adults').value=1;
                        slots.forEach(slotFuntion);
                  
                        function slotFuntion(item, index) {
                            var disabled="";
                            var className="";
                            var displayNone="";
                            var booking_limit=  item.booking_limit;
                            if(!booking_limit){
                                disabled="disabled target";
                                className ="notavailabe";
                            }else{
                                className ="availabe";
                                disabled="notdisabled";
                            }
                            if(service_type==1){
                                displayNone ="d-none";
                                booking_limit=maximum_quantity;
                            }
                            const list  = `<div class="form-check-inline visits me-0 ${disabled}" >
                                         <label class="visit-btns ${className}">
                                          <input type="radio" class="form-check-input" name="slot" value="${item.time}" data-bookings="${booking_limit}" ${disabled}>
                                         <span class="visit-rsn" data-bs-toggle="tooltip" title="${item.time}">${item.time}</span>
                                         <p class="visit-rsn ${displayNone}" data-bs-toggle="tooltip" title="${booking_limit}">${booking_limit} Available </p>
                                         </label></div>`;
                            document.getElementById("slotslist").innerHTML += list;
                        }
                    }else{
                        document.getElementById("slotslist").innerHTML="No Time Avaibale"
                    } 
                }else{
                    document.getElementById("slotslist").innerHTML="No Time Avaibale" 
                }
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    }

	$(".inc").on('click', function() {
	    var  slottime=0;
	    var  bookings=0
	    var slides = document.getElementsByClassName("form-check-input");
        for (var i = 0; i < slides.length; i++){
           var slottime= $('input[name="slot"]:checked').val();
           var bookings= $('input[name="slot"]:checked').data('bookings');
        }
	    if (slottime && bookings) {
	        document.getElementById('slotsNotSelected').textContent =" ";
            if(service_type!=1){
	            var subtotalpric=$('#subtotal').text();
                var total = parseFloat(subtotalpric)+parseFloat(subtotal);
                $('#subtotal').text(parseFloat(total).toFixed(2)); 
            }
	        const numberElement = document.getElementById('adults');
	        let currentNumber = parseInt(numberElement.value);
	        if(currentNumber<bookings){
                updateValue(this, 1,bookings,slottime);
	        }
        }else{
           document.getElementById('slotsNotSelected').textContent="Slots Not Selected";
        }
	});
	
	$(".dec").on('click', function() {
        var  slottime=0;
        var  bookings=0
        var slides = document.getElementsByClassName("form-check-input");
        for (var i = 0; i < slides.length; i++){
           var slottime= $('input[name="slot"]:checked').val();
           var bookings= $('input[name="slot"]:checked').data('bookings');
        }
	    if (slottime && bookings) {
	        document.getElementById('slotsNotSelected').textContent =" ";
	        var maxQty= document.getElementById('adults').value;
	        const numberElement = document.getElementById('adults');
	        let currentNumber = parseInt(numberElement.value);
            if(currentNumber>1){
                if(service_type!=1){
                    var subtotalpric=$('#subtotal').text();
                    var  total = parseFloat(subtotalpric)-parseFloat(subtotal);
                    $('#subtotal').text(parseFloat(total).toFixed(2)); 
                }
                 
    	        if(currentNumber<=bookings){
                    updateValue(this, -1,bookings,slottime);
    	        }
            }
	    }else{
	       document.getElementById('slotsNotSelected').textContent ="Slots Not Selected"; 
	    }
	});
	
	function updateValue(obj, delta,bookings=null,slottime=null) {
	    var item = $(obj).parent().find("input");
	    const numberElement = document.getElementById('adults');
        var newValue = parseInt(item.val(), 10) + delta;
        item.val(Math.max(newValue, 1));
        updateSlotsAndQuantity(slottime,item.val());
	}

    $(document).on('click','.visits', function(event){
        var slides = document.getElementsByClassName("form-check-input");
        for (var i = 0; i < slides.length; i++){
           var slottime= $('input[name="slot"]:checked').val();
           var bookings= 1;
        }
        updateSlotsAndQuantity(slottime,bookings);
    });

    function updateSlotsAndQuantity(booking_time,quantity){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `${domainUrl}bookings-slots`,
            type: "POST",
            dataType: "json",
            data: {booking_time:booking_time,quantity:quantity },
            success: function (response){
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });
    }
     
    $(document).on('click','#nextToConfirm', function(event){
        var slides = document.getElementsByClassName("form-check-input");
        var slottime;
        var    bookings=1;
        for (var i = 0; i < slides.length; i++){
            var slottime= $('input[name="slot"]:checked').val();
        }
        if(slottime && bookings){
            location.replace(`${domainUrl}`+'booking-order-confirm');
        }else{
            swal({
                title: "Warning",
                text: "Please fill booking form all field to proceed",
                type: "warning",
                confirmButtonColor: "#000",
                confirmButtonText: "Close",
                closeOnConfirm: false, 
            })
        }
    });
     
	$(".coach-types li").on('click', function() {
	    $(this).toggleClass("active").siblings().removeClass('active');
	    if ($(".coach-types ul li.coach-and-lessons").hasClass('active')){
			$(".coach-types a.change-url").attr("href", "lesson-type")
		}
	});

	$(".booking-date .time-slot.active").on('click', function() {
	    $(this).toggleClass("checked");
	});

	if($('.date-slider').length > 0) {      
		$('.date-slider').owlCarousel({
			loop:true,
			margin:24,
			nav:true,
			dots: false,
			autoplay:false,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:2
				},
				768:{
					items:3
				},
				1000:{
					items:4
				}
			}
		})
	}

	$(".lesson-types li").on('click', function() {
	    $(this).toggleClass("active").siblings().removeClass('active');
	});

	$(".venue-options li").on('click', function() {
	    $(this).toggleClass("active").siblings().removeClass('active');
	});

	$("form .input-group input").on('click', function() {
	    $(this).toggleClass("active").siblings().removeClass('active');
	});

	if($('.main-gallery-slider').length > 0) {   
		$('.main-gallery-slider').owlCarousel({
			loop:true,
			nav:true,
			margin:5,
			dots: false,
			autoplay:false,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:2
				},
				768:{
					items:4
				},
				1000:{
					items:5
				}
			}
		})
	}

	if($('.gallery-slider').length > 0) {   
		$('.gallery-slider').owlCarousel({
			loop:true,
			margin:10,
			nav:true,
			dots: false,
			autoplay:false,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:2
				},
				768:{
					items:3
				},
				1000:{
					items:3
				}
			}
		})
	}

	$(".show-more").on('click', function() {
        if($(".text").hasClass("show-more-height")) {
            $(this).html('<i class="feather-minus-circle"></i>Show Less');
        } else {
            $(this).html('<i class="feather-plus-circle"></i>Show More');
        }

        $(".text").toggleClass("show-more-height");
	});
	
    $("#addRating").on("submit", function (event) {
        event.preventDefault();
        var formdata = new FormData($("#addRating")[0]);
        $.ajax({
            url: `${domainUrl}add-rating`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                console.log(response);
                if(response.status){
                    $(".payment-modal").modal('hide');
                    swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                    $("#addRating").trigger("reset");
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
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
	
	$("#eventInquiries").on("submit", function (event) {
        event.preventDefault();
        var formdata = new FormData($("#eventInquiries")[0]);
        $.ajax({
            url: `${domainUrl}store-enquiry`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                console.log(response);
                if(response.status){
                    swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                    $("#eventInquiries").trigger("reset");
                    setTimeout(function () {
                        location.replace(`${domainUrl}`+'event-enquiry-list');
                    },2000);
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
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });

    $("#editUserDetails").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();
     
        var formdata = new FormData($("#editUserDetails")[0]);
        $.ajax({
            url: `${domainUrl}update-user`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                console.log(response);
                if(!response.status){
                    swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                  $("#editUserDetails").trigger("reset");
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
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
 
    $("#updatePassword").on("submit", function (event) {
        event.preventDefault();
        var formdata = new FormData($("#updatePassword")[0]);
        $.ajax({
            url: `${domainUrl}update-password`,
            type: "POST",
            data: formdata,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                console.log(response);
                if(!response.status){
                    swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        confirmButtonColor: "#000",
                        confirmButtonText: "Close",
                        closeOnConfirm: false, 
                    })
                    return ;
                }
                swal({
                    title: "Success",
                    text: response.message,
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
                $("#updatePassword").trigger("reset");
            },
            error: (error) => {
                $(".loader").hide();
                console.log(JSON.stringify(error));
            },
        });
    });
 
	if($('.input-range').length > 0) {
		$(".input-range").ionRangeSlider({
			type: "single",
			grid: true,
			min: 0,
			max: 100,
			from: 50,
			to: 100       
		});
	}
	
	$('.input-range').on('input', function () {
        $('.demo span').html(this.value);
    });
	
	$(".filterbtn").on('click', function() {
		  $(".showfilter").toggleClass("filter-open");
	});		

	if($('.popup-toggle').length > 0) {
		$(".popup-toggle").on('click', function() {
			$(".toggle-sidebar").addClass("open-sidebar");
		});
		$(".sidebar-closes").on('click', function() {
			$(".toggle-sidebar").removeClass("open-sidebar");
		});
	}

	if ($(window).width() > 767) {
		if($('.theiaStickySidebar').length > 0) {
			$('.theiaStickySidebar').theiaStickySidebar({
			  additionalMarginTop: 30
			});
		}
	}

	if($('.team-slider').length > 0) {      
		$('.team-slider').owlCarousel({
			items: 4,
			loop:true,
			margin:24,
			nav:true,
			dots: false,
			autoplay:false,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:1
				},
				768:{
					items:2
				},
				1000:{
					items:4
				}
			}
		})
	}

	if($('.grid').length > 0) {
		$('.grid').masonry({
			temSelector: '.grid-item',
		    columnWidth: '.grid-sizer',
		    gutter: '.gutter-sizer',
			percentPosition: true,
			horizontalOrder: true,
		});
	}

	if($('.event-sponsors').length > 0) {      
		$('.event-sponsors').owlCarousel({
			items: 6,
			loop:true,
			margin:24,
			nav:true,
			dots: false,
			autoplay:true,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:2
				},
				768:{
					items:3
				},
				991:{
					items:4
				},
				1000:{
					items:6
				}
			}
		})
	}

	if($('.testimonials-slider').length > 0) {      
		$('.testimonials-slider').owlCarousel({
			items: 3,
			loop:true,
			margin:24,
			nav:true,
			dots: false,
			autoplay:false,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:1
				},
				768:{
					items:2
				},
				991:{
					items:2
				},
				1000:{
					items:3
				}
			}
		})
	}

	if ($('.toggle-password').length > 0) {
		$(document).on('click', '.toggle-password', function () {
			$(this).toggleClass("feather-eye feather-eye-off");
			var input = $(".pass-input");
			if (input.attr("type") === "password") {
				input.attr("type", "text");
			} else {
				input.attr("type", "password");
			}
		});
	}

	if ($('.toggle-password-confirm').length > 0) {
		$(document).on('click', '.toggle-password-confirm', function () {
			$(this).toggleClass("feather-eye feather-eye-off");
			var input = $(".pass-confirm");
			if (input.attr("type") === "password") {
				input.attr("type", "text");
			} else {
				input.attr("type", "password");
			}
		});
	}

	var chatAppTarget = $('.chat-window');
    (function() {
        if ($(window).width() > 991)
            chatAppTarget.removeClass('chat-slide');
        $(document).on("click", ".chat-window .chat-users-list a.media", function() {
            if ($(window).width() <= 991) {
                chatAppTarget.addClass('chat-slide');
            }
            return false;
        });
        $(document).on("click", "#back_user_list", function() {
            if ($(window).width() <= 991) {
                chatAppTarget.removeClass('chat-slide');
            }
            return false;
        });
    })();
	
	if($('.blog-slider').length > 0) {      
		$('.blog-slider').owlCarousel({
			items: 3,
			loop:true,
			margin:24,
			nav:true,
			dots: false,
			autoplay:false,
			smartSpeed: 2000,
			navText : ["<i class='feather-chevron-left'></i>","<i class='feather-chevron-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				500:{
					items:1
				},
				768:{
					items:2
				},
				991:{
					items:2
				},
				1000:{
					items:3
				}
			}
		})
	}

	$(document).on("click",".booking-days li",function () {
		$(this).toggleClass("active");
	});
	$(document).on("click",".add-wallet-checkbox input",function () {
		$(this).parent().parent().toggleClass("active");
	});

	var check;
	$(document).on("click","aside.payment-modes .form-check",function () {
	    check = $("aside.payment-modes .form-check input").is(":checked");
	    if(check) {
	        $(this).addClass('active').siblings().removeClass('active');
	    } else {
	    	$(this).removeClass('active').siblings().removeClass('active');
	    }
	});

	$(".venue-options ul li a[href^='#']").on('click', function(e) {
		e.preventDefault();
		var hash = this.hash;
		$('html, body').animate ({
	   		scrollTop: $(hash).offset().top - 85
	 	}, 100, function() {
	 	});
	});

	$(document).on('click', '.add-rules', function() {
		var Rules = $(".rules-option").val();
		if( Rules.length > 0 ) {
			$('.error-add-rule').css("display", "none");
			$("ul.rules-wraper").append('<li><i class="feather-check-square"></i>'+ Rules + '</a></li>');
			document.getElementById('add-rules').value='';
      	} else if( !Rules.value) {
        	$('.error-add-rule').css("display", "block");
      	}

	});
})(jQuery);