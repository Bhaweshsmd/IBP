<div class="dashboard-section">
    <div class="container-fluid text-center">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-menu">
                    <ul>
                        <li>
                            <a href="{{url('user-dashboard')}}" class="{{ Request::is('user-dashboard') ? 'active' : '' }}">
                                <img src="{{URL::asset('/assets/img/icons/dashboard-icon.svg')}}" alt="Icon">
                                <span>{{__('string.dashboard')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{url('user-bookings')}}" class="{{ Request::is('user-bookings','user-complete','user-ongoing','user-cancelled') ? 'active' : '' }}">
                                <img src="{{URL::asset('/assets/img/icons/booking-icon.svg')}}" alt="Icon">
                                <span>{{__('string.my_bookings')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{url('user-wallet')}}" class="{{ Request::is('user-wallet') ? 'active' : '' }}">
                                <img src="{{URL::asset('/assets/img/icons/wallet-icon.svg')}}" alt="Icon">
                                <span>{{__('string.ibp_account')}}</span>
                            </a>
                        </li>
                         <li>
                            <a href="{{url('user-card')}}" class="{{ Request::is('user-card') ? 'active' : '' }}">
                                <img src="{{URL::asset('/assets/img/icons/credit-card.png')}}" alt="Icon" style="width:22px;height:17px"
>
                                <span>{{__('string.ibp_card')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{url('user-chat')}}" class="{{ Request::is('user-chat') ? 'active' : '' }}">
                                <img src="{{URL::asset('/assets/img/icons/chat-icon.svg')}}" alt="Icon">
                                <span>{{__('string.chat')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{url('user-profile')}}" class="{{ Request::is('user-profile','user-setting-password','user-profile-othersetting') ? 'active' : '' }}">
                                <img src="{{URL::asset('/assets/img/icons/profile-icon.svg')}}" alt="Icon">
                                <span>{{__('string.profile')}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>