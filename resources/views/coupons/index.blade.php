@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/coupons.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Coupons') }}</h4>
            @if(has_permission(session()->get('user_type'), 'add_coupons'))
                <a data-toggle="modal" data-target="#addCouponModal" href="" class="ml-auto btn btn-primary text-white"><i class="fa fa-plus"></i> {{ __('Add New Coupon') }}</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="couponsTable">
                    <thead>
                        <tr>
                            <th>{{ __('Sr. No.') }}</th>
                            <th>{{ __('Coupon Code') }}</th>
                            <th>{{ __('Percentage') }}</th>
                            <th>{{ __('Expiry Date') }}</th>
                            <th>{{ __('No. of Coupons') }}</th>
                            <th>{{ __('Limit Per Customer') }}</th>
                            <th>{{ __('Max. Discount Amount') }}</th>
                            <th>{{ __('Min. Order Amount') }}</th>
                            <th>{{ __('Coupon Title') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCouponModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Edit Coupon') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editCouponForm" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editCouponId">

                        <div class="form-group">
                            <label> {{ __('Coupon Code') }}</label>
                            <input id="editCoupon" type="text" name="coupon" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Heading') }}</label>
                            <input id="editHeading" type="text" name="heading" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Percentage') }}</label>
                            <input id="editPercentage" type="number" name="percentage" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <input id="editDescription" type="text" name="description" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Expiry Date') }}</label>
                            <input type="date" name="expiry_date" id="editExpirydate" class="form-control" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label> {{ __('No. of Coupons') }}</label>
                                <input id="editAvailable" type="number" name="available" class="form-control" required>
                            </div>
                            <div class="form-group col-6">
                                <label> {{ __('Limit Per Customer') }}</label>
                                <input id="editAvailableusr" type="number" name="available_user" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>{{ __('Max. Discount Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            {{ $settings->currency }}
                                        </div>
                                    </div>
                                    <input id="editMaxDiscAmount" name="max_discount_amount" type="number"
                                        class="form-control currency" required>
                                </div>
                            </div>

                            <div class="form-group col-6">
                                <label> {{ __('Min. Order Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            {{ $settings->currency }}
                                        </div>
                                    </div>
                                    <input id="editMinOrderAmount" name="min_order_amount" type="number"
                                        class="form-control currency" required>
                                </div>
                            </div>
                        </div>
                        @if(has_permission(session()->get('user_type'), 'edit_coupons'))
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Update') }}" >
                            </div>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addCouponModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add New Coupon') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addCouponForm" autocomplete="off" >
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Coupon Code') }}</label>
                            <input type="text" name="coupon" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Heading') }}</label>
                            <input type="text" name="heading" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Percentage') }}</label>
                            <input type="number" name="percentage" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Description') }}</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label> {{ __('Expiry Date') }}</label>
                            <input type="date" name="expiry_date" id="addcoupDate" class="form-control" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label> {{ __('No. of Coupons') }}</label>
                                <input type="number" name="available" class="form-control" required>
                            </div>
                            <div class="form-group col-6">
                                <label> {{ __('Limit Per Customer') }}</label>
                                <input type="number" name="available_user" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>{{ __('Max. Discount Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            {{ $settings->currency }}
                                        </div>
                                    </div>
                                    <input name="max_discount_amount" type="number" class="form-control currency" required>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label> {{ __('Min. Order Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            {{ $settings->currency }}
                                        </div>
                                    </div>
                                    <input name="min_order_amount" type="number" class="form-control currency" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}"  >
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    
    @if(session()->has('coupon_message'))
        <script type="text/javascript">
            $(function () {
                swal({
                    title: "Success",
                    text: "{{ session()->get('coupon_message') }}",
                    type: "success",
                    confirmButtonColor: "#000",
                    confirmButtonText: "Close",
                    closeOnConfirm: false, 
                })
            });
        </script>
    @endif
    
    <script>
        $(function(){
            var dtToday = new Date();
            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();
            
            var maxDate = year + '-' + month + '-' + day;
            $('#addcoupDate').attr('min', maxDate);
            $('#editExpirydate').attr('min', maxDate);
        });
    </script>
@endsection
