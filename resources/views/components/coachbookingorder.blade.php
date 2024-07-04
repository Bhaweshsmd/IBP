<section class="booking-steps py-30">
    <div class="container">
        <ul class="d-xl-flex justify-content-center align-items-center">
            <li class="{{ Request::is('coach-details') ? 'active' : '' }}">
                <h5><a href="{{ url('coach-details') }}"><span>1</span>Type of Booking</a></h5>
            </li>
            <li class="{{ Request::is('coach-order-confirm') ? 'active' : '' }}">
                <h5><a href="{{ url('coach-order-confirm') }}"><span>4</span>Order Confirmation</a></h5>
            </li>
            <li class="{{ Request::is('coach-payment') ? 'active' : '' }}">
                <h5><a href="{{ url('coach-payment') }}"><span>5</span>Payment</a></h5>
            </li>
        </ul>
    </div>
</section>
