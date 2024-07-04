@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/viewSalon.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/viewSalon.css') }}">
@endsection

<style>
    .bank-details span {
        display: block;
        line-height: 18px;
    }
</style>

@section('content')
    <input type="hidden" value="{{ $salon->id }}" id="salonId">

    <div class="card">
        <div class="card-header">
              <div class="">
                    <div class="d-flex mt-1 align-items-center">
                        <img class="rounded-circle owner-img-border" width="40" height="40" src="{{ url('public/storage/'.$salon->owner_photo) }}" alt="">
                        <p class="mt-0 p-data mb-0 ml-2">{{ $salon->owner_name }}</p>
                    </div>
                </div>
            <h4>
                {{ $salon->salon_name }} / {{ $salon->salon_number }}
            </h4>

            @if ($salon->status == $salonStatus['statusSalonPending'])
                <span class="badge bg-warning text-white ">{{ __('Pending Review') }} </span>
            @elseif($salon->status == $salonStatus['statusSalonActive'])
                <span class="badge bg-success text-white ">{{ __('Active') }} </span>
            @elseif($salon->status == $salonStatus['statusSalonBanned'])
                <span class="badge bg-danger text-white ">{{ __('Banned') }} </span>
            @endif

            @if ($salon->top_rated == 1)
                <span class="ml-2 badge bg-topRated bg-primary text-white ">{{ __('Top Rated') }} </span>
            @endif
        </div>
        <div class="card-body">

            <div class="form-row">
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Accepted Bookings') }}</label>
                    <p class="mt-0 p-data">{{ $total_accepted}}</p>
                </div>
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Completed Bookings') }}</label>
                    <p class="mt-0 p-data">{{ $total_completed }}</p>
                </div>
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Cancelled Bookings') }}</label>
                    <p class="mt-0 p-data">{{ $total_cancelled }}</p>
                </div>
            </div>

            <div class="form-row mt-3">
                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Overall Rating') }}</label>
                    <p class="mt-0 p-data">{{ number_format($salon->rating,2)}}</p>
                </div>

                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Mon-Fri Time') }}</label>
                    <p class="mt-0 p-data">{{ $salon->mon_fri_from }} - {{ $salon->mon_fri_to }}</p>
                </div>

                <div class="col-md-2">
                    <label class="mb-0 text-grey" for="">{{ __('Sat-Sun Time') }}</label>
                    <p class="mt-0 p-data">{{ $salon->sat_sun_from }} - {{ $salon->sat_sun_to }}</p>
                </div>

                <div class="col-md-2">
                    <label class="mb-0 text-grey d-block" for="">{{ __('Company Location') }}</label>
                    <a target="_blank" class="badge bg-primary text-white mt-1"
                        href="https://www.google.com/maps/?q={{ $salon->salon_lat }},{{ $salon->salon_long }}">{{ __('Click To Locate') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills border-b  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#tabDetails"
                        aria-controls="home" role="tab" data-toggle="tab">{{ __('Details') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabServices" role="tab"
                        data-toggle="tab">{{ __('Services') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabBookings" role="tab"
                        aria-controls="tabBookings" data-toggle="tab">{{ __('Bookings') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabGallery" role="tab"
                        aria-controls="tabGallery" data-toggle="tab">{{ __('Gallery') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
                
                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabMap" role="tab"
                        aria-controls="tabMap" data-toggle="tab">{{ __('Area Map') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#tabReviews" role="tab"
                        aria-controls="tabReviews" data-toggle="tab">{{ __('Reviews') }}
                        <span class="badge badge-transparent "></span></a>
                </li>

            </ul>

            
           
        </div>
         
        <div class="card-body">
            <div class="tab-content tabs" id="home">
          
                <div role="tabpanel" class="tab-pane active" id="tabDetails">
     
                    @if(has_permission(session()->get('user_type'), 'add_company'))
                        <a data-toggle="modal" data-target="#addSalonImagesModal" href="" id="addSalonImages" class="ml-auto btn btn-primary text-white float-right mb-3"><i class="fa fa-plus"></i> {{ __('Add New Company Images') }}</a>
                    @endif
                    <div class="form-group mt-0">
                        <label for="">{{ __('Images') }}</label>
                        <div class="d-flex mb-2">
                            @foreach ($salon->images as $image)
                                <div class="salon_image">
                                    <img width="100" class="rounded" height="100" src="{{ url('public/storage/'.$image->image) }}" alt="">
                                    <i rel="{{ $image->id }}" class="fas fa-trash img-delete"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <form action="" method="post" enctype="multipart/form-data" class=""
                        id="salonDetailsForm" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" value="{{ $salon->id }}">

                        <div class="form-row ">
                            <div class="form-group col-md-3">
                                <label for="">{{ __('Email') }}</label>
                                <input type="text" class="form-control" name="email" disabled
                                    value="{{ $salon->email }}">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="">{{ __('Company Name') }}</label>
                                <input type="text" class="form-control" name="salon_name"
                                    value="{{ $salon->salon_name }}">
                            </div>


                            <div class="form-group col-md-3">
                                <label for="">{{ __('Company Phone') }}</label>
                                <input type="text" class="form-control" name="salon_phone"
                                    value="{{ $salon->salon_phone }}">
                            </div>
          
                            <div class="form-group col-md-3">
                                <label for="">{{ __('Company Latitude') }}</label>
                                <input type="text" class="form-control" name="salon_lat"
                                    value="{{ $salon->salon_lat }}">
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for="">{{ __('Company Longitude') }}</label>
                                <input type="text" class="form-control" name="salon_long"
                                    value="{{ $salon->salon_long }}">
                            </div>
                            
                             <div class="form-group col-md-4">
                                <label for="">{{ __('Company Profile') }}</label>
                                <input type="file" class="form-control" accept=".png,.jpg,.jpeg" name="owner_photo" value="{{ $salon->owner_photo }}">
                                <a target="_blank" class="badge bg-primary text-white mt-1" href="{{ url('public/storage/'.$salon->owner_photo) }}" target="_black">{{ __('View') }}</a>  
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for="">{{ __('Company banner') }}</label>
                                <input type="file" class="form-control" accept=".png,.jpg,.jpeg" name="banner" value="{{ $salon->banner }}">
                                <a target="_blank" class="badge bg-primary text-white mt-1" href="{{ url('public/storage/'.$salon->banner) }}" target="_black">{{ __('View') }}</a>  
                            </div>
                        </div>

                        <div class="form-row ">
                            <div class="form-group col-md-6">
                                <label for="">{{ __('About Company') }}</label>
                                <textarea type="text" class="form-control" name="salon_about" style="height: 300px !important;">{{ $salon->salon_about }}</textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">{{ __('Company Address') }}</label>
                                <textarea type="text" class="form-control" name="salon_address" style="height: 300px !important;">{{ $salon->salon_address }}</textarea>
                            </div>
                        </div>
                        
                        @if(has_permission(session()->get('user_type'), 'edit_company'))
                            <div class="form-group text-right">
                                <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Update') }}">
                            </div>
                        @endif
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabBookings">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Booking Number') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Service Amount') }}</th>
                                    <th>{{ __('Discount Amount') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Total Tax Amount') }}</th>
                                    <th>{{ __('Payable Amount') }}</th>
                                    <th>{{ __('Placed On') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabSlots">
                    <div class="slote-table table-responsive col-12">
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for="">{{ __('Monday') }}</label>
                            <div class="slot-time-block">
                                @foreach ($slots['mondaySlots'] as $item)
                                    <div class="slot-time-inner">
                                        <span class="slot-time">{{ $item['time'] }}</span>
                                        <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for="">{{ __('Tuesday') }}</label>
                            <div class="slot-time-block">
                                @foreach ($slots['tuesdaySlots'] as $item)
                                    <div class="slot-time-inner">
                                        <span class="slot-time">{{ $item['time'] }}</span>
                                       <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for="">{{ __('Wednesday') }}</label>
                            <div class="slot-time-block">
                                @foreach ($slots['wednesdaySlots'] as $item)
                                    <div class="slot-time-inner">
                                        <span class="slot-time">{{ $item['time'] }}</span>
                                       <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for="">{{ __('Thursday') }}</label>
                            <div class="slot-time-block">
                                @foreach ($slots['thursdaySlots'] as $item)
                                    <div class="slot-time-inner">
                                        <span class="slot-time">{{ $item['time'] }}</span>
                                       <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for="">{{ __('Friday') }}</label>
                            <div class="slot-time-block">
                                @foreach ($slots['thursdaySlots'] as $item)
                                    <div class="slot-time-inner">
                                        <span class="slot-time">{{ $item['time'] }}</span>
                                       <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for="">{{ __('Saturday') }}</label>
                            <div class="slot-time-block">
                                @foreach ($slots['saturdaySlots'] as $item)
                                    <div class="slot-time-inner">
                                        <span class="slot-time">{{ $item['time'] }}</span>
                                       <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-2 d-flex">
                            <label class="mb-0 text-grey" for="">{{ __('Sunday') }}</label>
                            <div class="slot-time-block">
                                @foreach ($slots['sundaySlots'] as $item)
                                    <div class="slot-time-inner">
                                        <span class="slot-time">{{ $item['time'] }}</span>
                                       <span class="total-slot"> {{ $item['booking_limit'] }} {{ __('Slots') }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabServices">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="servicesTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Number') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Time (Minutes)') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th>{{ __('Gender') }}</th>
                                    <th>{{ __('Status (On/Off)') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabGallery">
                     @if(has_permission(session()->get('user_type'), 'add_gallery_images'))
                         <a data-toggle="modal" data-target="#addSalonImagesModalGallery" href="" id="addSalonImagesGallery" class="ml-auto btn btn-primary btn-lg text-white float-right mb-3"><i class="fa fa-plus"></i>  {{ __('Add New Gallery Images') }}</a>
                     @endif
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="galleryTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabMap">
                    @if(has_permission(session()->get('user_type'), 'add_gallery_images'))
                      <a data-toggle="modal" data-target="#addSalonImagesModalMap" href="" id="addSalonImagesMap" class="ml-auto btn btn-primary btn-lg text-white float-right mb-3"><i class="fa fa-plus"></i> {{ __('Add New Area Map Images') }}</a>
                    @endif
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="mapTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabReviews">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="reviewsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Rating') }}</th>
                                    <th>{{ __('Service name') }}</th>
                                    <th>{{ __('User ID') }}</th>
                                    <th>{{ __('Comment') }}</th>
                                    <th>{{ __('Booking') }}</th>
                                    <th>{{ __('Date&Time') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabAwards">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="awardsTable">
                            <thead>
                                <tr>
                                    
                                    <th>{{ __('Award') }}</th>
                                    <th>{{ __('By') }}</th>
                                    <th class="w-70">{{ __('Description') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabWalletMoney">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="walletStatementTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Transaction ID') }}</th>
                                    <th>{{ __('Summary') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Credit/Debit') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabPayOuts">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="salonPayOutsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.') }}</th>
                                    <th>{{ __('Request Number') }}</th>
                                    <th>{{ __('Bank Details') }}</th>
                                    <th>{{ __('Amount & Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Salon') }}</th>
                                    <th>{{ __('Summary') }}</th>
                                    <th>{{ __('Placed On') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="tabEarningHistory">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100 word-wrap" id="earningsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Earning Number') }}</th>
                                    <th>{{ __('Booking Number') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addSalonImagesModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>{{ __('Add New Company Images') }}</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" id="addSalonImagesForm"
                                autocomplete="off">
                                @csrf
                                <input type="hidden" name="id" value="{{ $salon->id }}">

                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">{{ __('Select Images') }}</label>
                                        <input class="form-control" type="file" name="images[]"
                                            accept="image/png, image/gif, image/jpeg" required multiple>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="addSalonImagesModalGallery" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>{{ __('Add New GalleryImages') }}</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" id="addSalonImagesFormGallery"
                                autocomplete="off">
                                @csrf
                                <input type="hidden" name="id" value="{{ $salon->id }}">

                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">{{ __('Select Images') }}</label>
                                        <input class="form-control" type="file" name="images[]"
                                            accept="image/png, image/gif, image/jpeg" required multiple>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="addSalonImagesModalMap" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>{{ __('Add New Area Map Image') }}</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" id="addSalonImagesFormMap"
                                autocomplete="off">
                                @csrf
                                <input type="hidden" name="id" value="{{ $salon->id }}">

                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">{{ __('Select Images') }}</label>
                                        <input class="form-control" type="file" name="images[]"
                                            accept="image/png, image/gif, image/jpeg" required multiple>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="previewGalleryModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>{{ __('Preview Gallery Post') }}</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="descGalleryPreview"></p>
                            <img class="rounded" width="100%" id="imggalleryPreview" src="" alt="">
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>{{ __('Reject Withdrawal') }}</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" id="rejectForm"
                                autocomplete="off">
                                @csrf
                                <input type="hidden" id="rejectId" name="id">
                                <div class="form-group">
                                    <label> {{ __('Summary') }}</label>
                                    <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="completeModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>{{ __('Complete Withdrawal') }}</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="" method="post" enctype="multipart/form-data" id="completeForm"
                                autocomplete="off">
                                @csrf
                                <input type="hidden" id="completeId" name="id">
                                <div class="form-group">
                                    <label> {{ __('Summary') }}</label>
                                    <textarea rows="10" style="height:200px !important;" type="text" name="summary" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            
            @if(session()->has('company_message'))
                <script type="text/javascript">
                    $(function () {
                        swal({
                            title: "Success",
                            text: "{{ session()->get('company_message') }}",
                            type: "success",
                            confirmButtonColor: "#000",
                            confirmButtonText: "Close",
                            closeOnConfirm: false, 
                        })
                    });
                </script>
            @endif
            
            @if(session()->has('review_message'))
                <script type="text/javascript">
                    $(function () {
                        swal({
                            title: "Success",
                            text: "{{ session()->get('review_message') }}",
                            type: "success",
                            confirmButtonColor: "#000",
                            confirmButtonText: "Close",
                            closeOnConfirm: false, 
                        })
                    });
                </script>
            @endif
        @endsection
