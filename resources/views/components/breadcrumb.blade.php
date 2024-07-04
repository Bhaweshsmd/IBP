@if (!Route::is(['coach-details','lesson-personalinfo','lesson-timedate','lesson-order-confirm','lesson-type','lesson-payment','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate']))
    <section class="breadcrumb breadcrumb-list mb-0">
@endif
@if (Route::is(['coach-details','lesson-personalinfo','lesson-timedate','lesson-order-confirm','lesson-type','lesson-payment','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate']))
    <div class="breadcrumb mb-0">
@endif
<span class="primary-right-round"></span>
<div class="container">
    <h1 class="text-white">{{ $title }}</h1>
    <ul>
        <li><a href="{{ url('/') }}">{{ $li_1 }}</a></li>
        <li>{{ $li_2 }}</li>
        @if (Route::is(['booking-details']))
        <li>{{ $li_3??'' }}</li>
        @endif
    </ul>
</div>
@if (!Route::is(['coach-details','lesson-personalinfo','lesson-timedate','lesson-order-confirm','lesson-type','lesson-payment','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate']))
    </section>
@endif
@if (Route::is(['coach-details','lesson-personalinfo','lesson-timedate','lesson-order-confirm','lesson-type','lesson-payment','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate','coach-order-confirm','coach-payment','coach-personalinfo','coach-timedate']))
    </div>
@endif