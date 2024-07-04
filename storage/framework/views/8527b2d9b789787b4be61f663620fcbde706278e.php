<footer class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="row">
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title"><?php echo e(__('string.contact_us')); ?></h4>
                        <div class="footer-address-blk">
                            <div class="footer-call">
                                <span><?php echo e(__('string.customer_care_number')); ?></span>
                                <p>+599-7172926</p>
                            </div>
                            <div class="footer-call">
                                <span><?php echo e(__('string.need_support')); ?></span>
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
                        <h4 class="footer-title"><?php echo e(__('string.quick_links')); ?></h4>
                        <ul>
                            <li>
                                <a href="<?php echo e(route('web.pages', 'about-us')); ?>"><?php echo e(__('string.about_us')); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo e(url('items-facilities')); ?>"><?php echo e(__('string.Item_facilities')); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('event-enquiry')); ?>"><?php echo e(__('string.event_booking')); ?></a>
                            </li>
                           
                            <li>
                                <a href="<?php echo e(url('service-map')); ?>"><?php echo e(__('string.ibp_map')); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('web.blogs')); ?>"><?php echo e(__('string.blogs')); ?></a>
                            </li>
                            <li>    
                                <a href="<?php echo e(route('web.pages', 'park-rules')); ?>"><?php echo e(__('string.parking_rules')); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title"><?php echo e(__('string.support')); ?></h4>
                        <ul>
                            <li>
                                <a href="<?php echo e(url('contact-us')); ?>"><?php echo e(__('string.contact_us')); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('web.faqs')); ?>"><?php echo e(__('string.faqs')); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('web.pages', 'privacy-policy')); ?>"><?php echo e(__('string.privacy_policy')); ?></a>
                            </li>
                            <li>    
                                <a href="<?php echo e(route('web.pages', 'terms-of-use')); ?>"><?php echo e(__('string.terms_of_use')); ?></a>
                            </li>
                            <li>    
                                <a href="<?php echo e(route('web.pages', 'history')); ?>"><?php echo e(__('string.history')); ?></a>
                            </li>
                             <li>    
                                <a href="<?php echo e(route('web.pages', 'cancellation-refund-policy')); ?>"><?php echo e(__('string.cancellation_refund_Policy')); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title"><?php echo e(__('string.download')); ?></h4>
                        <ul>
                            <li>
                                <a href="#"><img src="<?php echo e(URL::asset('/assets/img/icons/icon-apple.svg')); ?>" alt="Apple"></a>
                            </li>
                            <li>
                                <a href="https://play.google.com/store/apps/details?id=com.isidelbeachpark.booking&pli=1"><img src="<?php echo e(URL::asset('/assets/img/icons/google-icon.svg')); ?>" alt="Google"></a>
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
                            <p class="mb-0">&copy; <script>document.write(new Date().getFullYear())</script><a href="https://isidelbeachpark.com/"> <?php echo e(__('string.App_Name')); ?> </a> - <?php echo e(__('string.all_rights_reserved')); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dropdown-blk">
                            <ul class="navbar-nav selection-list">
                                <li class="nav-item dropdown">
                                    <div class="lang-select">
                                        <span class="select-icon"><i class="feather-globe"></i></span>
                                        <select class="select changeLang" id="changeLang">
                                            <option value="en" <?php echo e(session()->get('locale') == 'en' ? 'selected' : ''); ?>>English (US)</option>
                                            <option value="pap" <?php echo e(session()->get('locale') == 'pap' ? 'selected' : ''); ?>>Papiamentu</option>
                                            <option value="nl" <?php echo e(session()->get('locale') == 'nl' ? 'selected' : ''); ?>>Dutch</option>
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
</footer><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/layout/partials/footer.blade.php ENDPATH**/ ?>