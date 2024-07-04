    <div class="dashboard-section coach-dash-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard-menu coaurt-menu-dash">
                        <ul>
                            <li>
                                <a href="{{ url('coach-dashboard') }}"
                                    class="{{ Request::is('coach-dashboard') ? 'active' : '' }}">
                                    <img src="{{ URL::asset('/assets/img/icons/dashboard-icon.svg') }}" alt="Icon">
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('all-court') }}" class="{{ Request::is('all-court','court-active','court-inactive') ? 'active' : '' }}">
                                    <img src="{{ URL::asset('/assets/img/icons/court-icon.svg') }}" alt="Icon">
                                    <span> Items</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('coach-request') }}"
                                    class="{{ Request::is('coach-request') ? 'active' : '' }}">
                                    <img src="{{ URL::asset('/assets/img/icons/request-icon.svg') }}" alt="Icon">
                                    <span>Requests</span>
                                    <span class="court-notify">03</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('coach-booking') }}"
                                    class="{{ Request::is('coach-booking','booking-cancelled','booking-completed') ? 'active' : '' }}">
                                    <img src="{{ URL::asset('/assets/img/icons/booking-icon.svg') }}" alt="Icon">
                                    <span>Bookings</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('coach-chat') }}"
                                    class="{{ Request::is('coach-chat') ? 'active' : '' }}">
                                    <img src="{{ URL::asset('/assets/img/icons/chat-icon.svg') }}" alt="Icon">
                                    <span>Chat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('coach-earning') }}"
                                    class="{{ Request::is('coach-earning','earning-coaching') ? 'active' : '' }}">
                                    <img src="{{ URL::asset('/assets/img/icons/invoice-icon.svg') }}" alt="Icon">
                                    <span>Earnings</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('coach-wallet') }}"
                                    class="{{ Request::is('coach-wallet') ? 'active' : '' }}">
                                    <img src="{{ URL::asset('/assets/img/icons/wallet-icon.svg') }}" alt="Icon">
                                    <span>IBP Account</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('coach-profile') }}"
                                    class="{{ Request::is('coach-profile','appointment-details','setting-password','setting-lesson','setting-availability','profile-othersetting','my-profile') ? 'active' : '' }}">
                                    <img src="{{ URL::asset('/assets/img/icons/profile-icon.svg') }}" alt="Icon">
                                    <span>Profile Setting</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
