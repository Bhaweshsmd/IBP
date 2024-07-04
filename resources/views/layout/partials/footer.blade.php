<footer class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="row">
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">{{__('string.contact_us')}}</h4>
                        <div class="footer-address-blk">
                            <div class="footer-call">
                                <span>{{__('string.customer_care_number')}}</span>
                                <p>+599-7172926</p>
                            </div>
                            <div class="footer-call">
                                <span>{{__('string.need_support')}}</span>
                                <p>help@isidelbeachpark.com</p>
                            </div>
                        </div>
                        <div class="social-icon">
                            <ul>
                                <li>
                                    <a href="https://www.facebook.com/IsidelBeachPark" class="facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/isidelbeachpark/" class="instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">{{__('string.quick_links')}}</h4>
                        <ul>
                            <li>
                                <a href="{{ route('web.pages', 'about-us') }}">{{__('string.about_us')}}</a>
                            </li>
                            <li>
                                <a href="{{ url('items-facilities') }}">{{__('string.Item_facilities')}}</a>
                            </li>
                            <li>
                                <a href="{{ route('event-enquiry') }}">{{__('string.event_booking')}}</a>
                            </li>
                           
                            <li>
                                <a href="{{ url('service-map') }}">{{__('string.ibp_map')}}</a>
                            </li>
                            <li>
                                <a href="{{ route('web.blogs') }}">{{__('string.blogs')}}</a>
                            </li>
                            <li>    
                                <a href="{{ route('web.pages', 'park-rules') }}">{{__('string.parking_rules')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">{{__('string.support')}}</h4>
                        <ul>
                            <li>
                                <a href="{{ url('contact-us') }}">{{__('string.contact_us')}}</a>
                            </li>
                            <li>
                                <a href="{{ route('web.faqs') }}">{{__('string.faqs')}}</a>
                            </li>
                            <li>
                                <a href="{{ route('web.pages', 'privacy-policy') }}">{{__('string.privacy_policy')}}</a>
                            </li>
                            <li>    
                                <a href="{{ route('web.pages', 'terms-of-use') }}">{{__('string.terms_of_use')}}</a>
                            </li>
                            <li>    
                                <a href="{{ route('web.pages', 'history') }}">{{__('string.history')}}</a>
                            </li>
                             <li>    
                                <a href="{{ route('web.pages', 'cancellation-refund-policy') }}">{{__('string.cancellation_refund_Policy')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">{{__('string.download')}}</h4>
                        <ul>
                            <li>
                                <a href="#"><img src="{{ URL::asset('/assets/img/icons/icon-apple.svg') }}" alt="Apple"></a>
                            </li>
                            <li>
                                <a href="https://play.google.com/store/apps/details?id=com.isidelbeachpark.booking&pli=1"><img src="{{ URL::asset('/assets/img/icons/google-icon.svg') }}" alt="Google"></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="copyright">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="copyright-text">
                            <p class="mb-0">&copy; <script>document.write(new Date().getFullYear())</script><a href="https://isidelbeachpark.com/"> {{__('string.App_Name')}} </a> - {{__('string.all_rights_reserved')}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dropdown-blk">
                            <ul class="navbar-nav selection-list">
                                <li class="nav-item dropdown">
                                    <div class="lang-select">
                                        <span class="select-icon"><i class="feather-globe"></i></span>
                                        <select class="select changeLang" id="changeLang">
                                            <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}>English (US)</option>
                                            <option value="pap" {{ session()->get('locale') == 'pap' ? 'selected' : '' }}>Papiamentu</option>
                                            <option value="nl" {{ session()->get('locale') == 'nl' ? 'selected' : '' }}>Dutch</option>
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>