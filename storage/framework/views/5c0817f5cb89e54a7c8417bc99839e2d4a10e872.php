<div class="dashboard-section">
    <div class="container-fluid text-center">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-menu">
                    <ul>
                        <li>
                            <a href="<?php echo e(url('user-dashboard')); ?>" class="<?php echo e(Request::is('user-dashboard') ? 'active' : ''); ?>">
                                <img src="<?php echo e(URL::asset('/assets/img/icons/dashboard-icon.svg')); ?>" alt="Icon">
                                <span><?php echo e(__('string.dashboard')); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('user-bookings')); ?>" class="<?php echo e(Request::is('user-bookings','user-complete','user-ongoing','user-cancelled') ? 'active' : ''); ?>">
                                <img src="<?php echo e(URL::asset('/assets/img/icons/booking-icon.svg')); ?>" alt="Icon">
                                <span><?php echo e(__('string.my_bookings')); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('user-wallet')); ?>" class="<?php echo e(Request::is('user-wallet') ? 'active' : ''); ?>">
                                <img src="<?php echo e(URL::asset('/assets/img/icons/wallet-icon.svg')); ?>" alt="Icon">
                                <span><?php echo e(__('string.ibp_account')); ?></span>
                            </a>
                        </li>
                         <li>
                            <a href="<?php echo e(url('user-card')); ?>" class="<?php echo e(Request::is('user-card') ? 'active' : ''); ?>">
                                <img src="<?php echo e(URL::asset('/assets/img/icons/credit-card.png')); ?>" alt="Icon" style="width:22px;height:17px"
>
                                <span><?php echo e(__('string.ibp_card')); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('user-chat')); ?>" class="<?php echo e(Request::is('user-chat') ? 'active' : ''); ?>">
                                <img src="<?php echo e(URL::asset('/assets/img/icons/chat-icon.svg')); ?>" alt="Icon">
                                <span><?php echo e(__('string.chat')); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('user-profile')); ?>" class="<?php echo e(Request::is('user-profile','user-setting-password','user-profile-othersetting') ? 'active' : ''); ?>">
                                <img src="<?php echo e(URL::asset('/assets/img/icons/profile-icon.svg')); ?>" alt="Icon">
                                <span><?php echo e(__('string.profile')); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/components/user-dashboard.blade.php ENDPATH**/ ?>