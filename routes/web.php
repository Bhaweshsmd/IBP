<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\MaintainanceController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\TicketsController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\EmailtemplateController;
use App\Http\Controllers\Admin\NotificationtemplateController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\DepositsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\BookingsController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SalonController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\UserWithdrawController;
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\DepositController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ChatController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\ServicesController;

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::group(['middleware' => ['checkWebsite']], function ()
{
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('service-map', [HomeController::class, 'service_map'])->name('service-map');
    Route::get('contact-us', [HomeController::class, 'contact_us'])->name('contact-us');
    Route::post('submit-contact-us', [HomeController::class, 'submit_contact_us'])->name('submit.contact.us');
    Route::get('categories/{slug}', [HomeController::class, 'categories'])->name('categories');
    Route::get('items-facilities', [HomeController::class, 'items_facilities'])->name('coaches-grid');
    Route::get('items-facilities-list', [HomeController::class, 'items_facilities_list'])->name('coaches-list');
    Route::get('item-details/{slug}', [HomeController::class, 'item_details'])->name('venue-details');
    Route::get('company-details', [HomeController::class, 'company_details'])->name('company-details');

    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::get('register-user', [AuthController::class, 'register_user'])->name('register.user');
    Route::get('otp-verification', [AuthController::class, 'otp_verification'])->name('otp-verification');
    Route::post('otp-verify', [AuthController::class, 'otp_verify'])->name('otp-verify');
    Route::post('resend-otp', [AuthController::class, 'resend_otp'])->name('resend.otp');
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('user-login', [AuthController::class, 'user_login'])->name('user.login');
    Route::get('user-logout', [AuthController::class, 'logout'])->name('user.logout');
    Route::match(['get', 'post'],'forgot-password', [AuthController::class, 'forgot_password'])->name('forgot-password');
    Route::match(['get', 'post'],'forgot-otp-verification', [AuthController::class, 'forgot_otp_verification'])->name('forgot-otp-verification');
    Route::match(['get', 'post'],'update-forgot-password', [AuthController::class, 'update_forgot_password'])->name('update-forgot-password');
    
    Route::get('change-language/{lang}', [WebController::class,'change_language'])->name('change.language');
});

Route::group(['middleware' => ['checkLogin']], function ()
{
    Route::get('user-profile', [ProfileController::class, 'user_profile'])->name('user-profile');
    Route::post('update-user', [ProfileController::class, 'update_user'])->name('update.user');
    Route::get('user-setting-password', [ProfileController::class, 'user_password'])->name('user-setting-password');
    Route::post('update-password', [ProfileController::class, 'update_password'])->name('update.password');
    
    Route::get('user-banks', [UserWithdrawController::class, 'index'])->name('user.banks');
    Route::get('user-create-bank', [UserWithdrawController::class, 'create'])->name('user.create.bank');
    Route::post('user-store-bank', [UserWithdrawController::class, 'store'])->name('user.store.bank');
    Route::get('user-edit-bank/{id}', [UserWithdrawController::class, 'edit'])->name('user.edit.bank');
    Route::post('user-update-bank/{id}', [UserWithdrawController::class, 'update'])->name('user.update.bank');
    Route::get('user-delete-bank/{id}', [UserWithdrawController::class, 'delete'])->name('user.delete.bank');
    Route::post('create-withdraw-request', [UserWithdrawController::class, 'create_withdraw_request'])->name('create.withdraw.request');
    
    Route::get('event-enquiry-list', [EventController::class, 'index'])->name('event-enquiry-list');
    Route::get('event-enquiry', [EventController::class, 'view'])->name('event-enquiry');
    Route::post('store-enquiry', [EventController::class, 'store'])->name('store.enquiry');
    
    Route::post('wallet-topup', [DepositController::class, 'wallet_topup'])->name('wallet.topup');
    Route::get('user-wallet/{type?}', [DepositController::class, 'user_wallet'])->name('user-wallet');
    
    Route::get('user-chat', [ChatController::class, 'user_chat'])->name('user-chat');
    Route::post('user-get-messages/{userid}', [ChatController::class, 'get_message'])->name('user.get.message');
    Route::post('user-send-message', [ChatController::class, 'send_message'])->name('user.send.message');
    
    Route::get('booking-details/{itemSlug}', [BookingController::class, 'booking_details'])->name('booking-details');
    Route::get('booking-order-confirm', [BookingController::class, 'booking_confirmation'])->name('booking-order-confirm');
    Route::get('booking-checkout', [BookingController::class, 'booking_confirmed'])->name('booking-checkout');
    Route::get('place-booking', [BookingController::class, 'place_booking'])->name('place-booking');
    Route::get('user-bookings', [BookingController::class, 'user_booking'])->name('user-bookings');
    Route::get('user-ongoing', [BookingController::class, 'user_ongoing'])->name('user-ongoing');
    Route::get('user-complete', [BookingController::class, 'user_complete'])->name('user-complete');
    Route::get('user-cancelled', [BookingController::class, 'user_cancelled'])->name('user-cancelled');
    Route::match(['get', 'post'],'reschedule-booking', [BookingController::class, 'reschedule_booking'])->name('reschedule-booking');
    Route::match(['get', 'post'],'cancel-booking', [BookingController::class, 'cancel_booking'])->name('cancel-booking');
    Route::match(['get', 'post'],'booking-success', [BookingController::class, 'booking_success'])->name('booking-success');
    Route::post('bookings-slots', [BookingController::class, 'bookings_slots']);
    Route::post('booked-slots', [BookingController::class, 'booked_slots']);
    Route::post('reschedule-booked-slots', [BookingController::class, 'reschedule_booked_slots']);
    
    Route::get('favourite-services', [ServicesController::class, 'favourite_services'])->name('favourite-services');
    Route::get('add-remove-fav-services/{service_id}', [ServicesController::class, 'add_remove_fav_services'])->name('add.remove.fav.services');
    
    Route::get('tickets',[TicketController::class,'index'])->name('tickets');
    Route::post('create-ticket',[TicketController::class,'create'])->name('tickets.create');
    Route::get('view-ticket/{id}',[TicketController::class,'view'])->name('tickets.view');
    Route::post('reply-ticket',[TicketController::class,'reply'])->name('tickets.reply');
    
    Route::get('user-dashboard', [WebController::class, 'user_dashboard'])->name('user-dashboard');
    Route::post('apply-coupon', [WebController::class, 'apply_coupon'])->name('apply.coupon');
    Route::get('user-notification', [WebController::class, 'user_notification'])->name('user-notification');
    Route::post('add-rating', [WebController::class, 'add_rating'])->name('add-rating');
    Route::get('user-card', [WebController::class, 'user_cards'])->name('user-card');
    Route::post('processing-fee', [WebController::class, 'processing_fee'])->name('processing-fee');
    Route::post('withdraw-processing-fee', [WebController::class, 'withdraw_processing_fee'])->name('withdraw-processing-fee');
    Route::match(['get', 'post'],'account-verification-otp', [WebController::class, 'account_verification_otp'])->name('account-verification-otp');
    Route::match(['get', 'post'],'account-verification', [WebController::class, 'account_verification'])->name('account-verification');
    Route::post('otp-verification', [WebController::class, 'otp_verification'])->name('otp-verification');
});

Route::group(['middleware' => ['checkMaintainance']], function ()
{
    Route::get('maintainance-break/{id}', [MaintainanceController::class, 'break'])->name('maintainance.break');
});

Route::match(['get', 'post'],'admin', [LoginController::class, 'login'])->name('admin');
Route::match(['get', 'post'],'admin-forgot-password', [LoginController::class, 'admin_forgot_password'])->name('admin-forgot-password');
Route::match(['get', 'post'],'verify-forgot-password', [LoginController::class, 'verify_forgot_password'])->name('verify-forgot-password');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('topup-invoice/{id}', [CardController::class, 'topup_invoice'])->name('topup.invoice');
Route::get('assign-invoice/{id}', [CardController::class, 'assign_invoice'])->name('assign.invoice');
Route::get('booking-invoice/{id}', [BookingsController::class, 'booking_invoice'])->name('booking.invoice');

Route::group(['middleware' => ['checkAdmin']], function ()
{
    Route::get('admins', [AdminsController::class, 'index'])->name('admins');
    Route::get('admin-create', [AdminsController::class, 'create'])->name('admins.create');
    Route::post('admin-store', [AdminsController::class, 'store'])->name('admins.store');
    Route::post('admin-list', [AdminsController::class, 'list'])->name('admins.list');
    Route::get('admin-edit/{id}', [AdminsController::class, 'edit'])->name('admins.edit');
    Route::post('admin-update/{id}', [AdminsController::class, 'update'])->name('admins.update');
    Route::get('admin-delete/{id}', [AdminsController::class, 'delete'])->name('admins.delete');
    Route::get('admin-profile', [AdminsController::class, 'profile'])->name('admins.profile');
    Route::post('admin-profile-update/{id}', [AdminsController::class, 'profile_update'])->name('admins.profile.update');
    Route::post('admin-password-update', [AdminsController::class, 'password_update'])->name('admins.password.update');
    Route::get('dashboard', [AdminsController::class, 'dashboard'])->name('dashboard');

    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('user-create', [UserController::class, 'create'])->name('users.create');
    Route::post('user-store', [UserController::class, 'store'])->name('users.store');
    Route::get('user-edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('user-update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('users-list', [UserController::class, 'list'])->name('users.list');
    Route::get('block-user/{id}', [UserController::class, 'block'])->name('users.block');
    Route::get('unblock-user/{id}', [UserController::class, 'unblock'])->name('users.unblock');
    Route::get('user-profile/{id}', [UserController::class, 'profile'])->name('users.profile');
    Route::post('users-bookings-list', [UserController::class, 'bookings_list'])->name('users.bookings.list');
    Route::post('user-wallet-statements', [UserController::class, 'wallet_statements'])->name('users.wallet.statements');
    Route::post('user-recharge-logs', [UserController::class, 'recharge_logs'])->name('users.recharge.logs');
    Route::post('user-card-statements', [UserController::class, 'card_statements'])->name('users.card.statements');
    
    Route::get('roles', [RoleController::class, 'index'])->name('roles');
    Route::get('role-create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('role-store', [RoleController::class, 'store'])->name('roles.store');
    Route::post('roles-list', [RoleController::class, 'list'])->name('roles.list');
    Route::get('role-edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('roles-update/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::get('role-delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');
    
    Route::get('events', [EventsController::class, 'index'])->name('events');
    Route::post('events-list', [EventsController::class, 'list'])->name('events.list');
    Route::post('store-event', [EventsController::class, 'store'])->name('store.event');
    Route::post('update-event', [EventsController::class, 'update'])->name('update.event');
    Route::get('delete-event/{id}', [EventsController::class, 'delete'])->name('delete.event');
    
    Route::get('deposits', [DepositsController::class, 'index'])->name('deposits');
    Route::post('deposits-list', [DepositsController::class, 'list'])->name('deposits.list');
    Route::post('user-wallet-topup', [DepositsController::class, 'user_wallet_topup'])->name('user.wallet.topup');
    
    Route::get('withdrawls', [WithdrawController::class, 'index'])->name('adminWithdraws');
    Route::post('pending-withdrawls-request', [WithdrawController::class, 'pending'])->name('pending.withdrawls.request');
    Route::post('completed-withdrawls-request', [WithdrawController::class, 'completed'])->name('completed.withdrawls.request');
    Route::post('rejected-withdrawls-request', [WithdrawController::class, 'rejected'])->name('rejected.withdrawls.request');
    Route::post('store-withdrawls-request', [WithdrawController::class, 'store_request'])->name('store.withdrawls.request');
    Route::post('complete-withdrawls-request', [WithdrawController::class, 'complete_request'])->name('complete.withdrawls.request');
    Route::post('reject-withdrawls-request', [WithdrawController::class, 'reject_request'])->name('reject.withdrawls.request');
    Route::get('user-withdrawls', [WithdrawController::class, 'user_withdraws'])->name('userWithdraws');
    Route::post('user-pending-withdrawls-request', [WithdrawController::class, 'user_pending'])->name('user.pending.withdrawls.request');
    Route::post('user-completed-withdrawls-request', [WithdrawController::class, 'user_completed'])->name('user.completed.withdrawls.request');
    Route::post('user-rejected-withdrawls-request', [WithdrawController::class, 'user_rejected'])->name('user.rejected.withdrawls.request');
    Route::post('user-complete-withdrawls-request', [WithdrawController::class, 'user_complete_request'])->name('user.complete.withdrawls.request');
    Route::post('user-reject-withdrawls-request', [WithdrawController::class, 'user_reject_request'])->name('user.reject.withdrawls.request');
    Route::post('user-withdraw-requests', [WithdrawController::class, 'withdraw_requests'])->name('user.withdraw.requests');
    
    Route::get('faqs', [FaqController::class, 'index'])->name('faqs');
    Route::post('faqs-list', [FaqController::class, 'list'])->name('faqs.list');
    Route::get('faqs-create', [FaqController::class, 'create'])->name('faqs.create');
    Route::post('faqs-store', [FaqController::class, 'store'])->name('faqs.store');
    Route::get('faqs-edit/{id}', [FaqController::class, 'edit'])->name('faqs.edit');
    Route::post('faqs-update/{id}', [FaqController::class, 'update'])->name('faqs.update');
    Route::get('faqs-delete/{id}', [FaqController::class, 'delete'])->name('faqs.delete');
    Route::get('faq-categories', [FaqController::class, 'faqs_cat'])->name('faqs.cat');
    Route::post('faqs-cat-list', [FaqController::class, 'faqs_cat_list'])->name('faqs.cat.list');
    Route::post('faqs-cat-store', [FaqController::class, 'faqs_cat_store'])->name('faqs.cat.store');
    Route::post('faqs-cat-update', [FaqController::class, 'faqs_cat_update'])->name('faqs.cat.update');
    Route::get('faqs-cat-delete/{id}', [FaqController::class, 'faqs_cat_delete'])->name('faqs.cat.delete');
    
    Route::get('coupons', [CouponController::class, 'index'])->name('coupons');
    Route::post('coupons-list', [CouponController::class, 'list'])->name('coupons.list');
    Route::post('store-coupon', [CouponController::class, 'store'])->name('store-coupon');
    Route::post('update-coupon', [CouponController::class, 'update'])->name('update-coupon');
    Route::get('delete-coupon/{id}', [CouponController::class, 'delete'])->name('delete-coupon');
    
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews');
    Route::post('list-reviews', [ReviewController::class, 'list'])->name('list.reviews');
    Route::get('edit-reviews/{id}', [ReviewController::class, 'edit'])->name('edit.reviews');
    Route::post('update-reviews/{id}', [ReviewController::class, 'update'])->name('update.reviews');
    Route::get('approve-reviews/{id}', [ReviewController::class, 'approve'])->name('approve.reviews');
    Route::get('reject-reviews/{id}', [ReviewController::class, 'reject'])->name('reject.reviews');
    Route::get('delete-reviews/{id}', [ReviewController::class, 'delete'])->name('delete.reviews');
    
    Route::get('pages', [PagesController::class, 'index'])->name('pages');
    Route::post('pages-list', [PagesController::class, 'pages_list'])->name('pages.list');
    Route::get('page-create', [PagesController::class, 'create'])->name('page.create');
    Route::post('page-store', [PagesController::class, 'store'])->name('page.store');
    Route::get('page-edit/{id}', [PagesController::class, 'edit'])->name('page.edit');
    Route::post('page-update/{id}', [PagesController::class, 'update'])->name('page.update');
    Route::get('page-delete/{id}', [PagesController::class, 'delete'])->name('page.delete');

    Route::get('blogs', [BlogController::class, 'index'])->name('blogs');
    Route::post('blogs-list', [BlogController::class, 'list'])->name('blogs.list');
    Route::get('blog-create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('blog-store', [BlogController::class, 'store'])->name('blog.store');
    Route::get('blog-edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
    Route::post('blog-update/{id}', [BlogController::class, 'update'])->name('blog.update');
    Route::get('blog-delete/{id}', [BlogController::class, 'delete'])->name('blog.delete');
    
    Route::get('chat', [AdminChatController::class, 'chat'])->name('chat');
    Route::post('getmessage/{userid}', [AdminChatController::class, 'get_message'])->name('chat.message');
    Route::post('send-message', [AdminChatController::class, 'send_message'])->name('send.message');
    
    Route::get('banks', [BankController::class, 'index'])->name('banks');
    Route::post('list-banks', [BankController::class, 'list'])->name('list.banks');
    Route::get('create-bank', [BankController::class, 'create'])->name('create.bank');
    Route::post('store-bank', [BankController::class, 'store'])->name('store.bank');
    Route::get('edit-bank/{id}', [BankController::class, 'edit'])->name('edit.bank');
    Route::post('update-bank/{id}', [BankController::class, 'update'])->name('update.bank');
    Route::get('delete-bank/{id}', [BankController::class, 'delete'])->name('delete.bank');
    
    Route::get('maintainance', [MaintainanceController::class, 'index'])->name('maintainance');
    Route::post('list-maintainance', [MaintainanceController::class, 'list'])->name('list.maintainance');
    Route::get('create-maintainance', [MaintainanceController::class, 'create'])->name('create.maintainance');
    Route::post('store-maintainance', [MaintainanceController::class, 'store'])->name('store.maintainance');
    Route::get('edit-maintainance/{id}', [MaintainanceController::class, 'edit'])->name('edit.maintainance');
    Route::post('update-maintainance/{id}', [MaintainanceController::class, 'update'])->name('update.maintainance');
    Route::get('delete-maintainance/{id}', [MaintainanceController::class, 'delete'])->name('delete.maintainance');
    Route::get('remind-maintainance/{id}', [MaintainanceController::class, 'remind'])->name('remind.maintainance');
    
    Route::get('banners', [BannerController::class, 'banners'])->name('banners');
    Route::post('banners-list', [BannerController::class, 'list'])->name('banners.list');
    Route::post('store-banner', [BannerController::class, 'store'])->name('store.banner');
    Route::get('delete-banner/{id}', [BannerController::class, 'delete'])->name('delete.banner');
    Route::get('web-banners', [BannerController::class, 'web_banners'])->name('web.banners');
    Route::post('web-banner-list', [BannerController::class, 'web_banner_list'])->name('web.banner.list');
    Route::post('add-web-banner', [BannerController::class, 'add_web_banner'])->name('add.web.banner');
    Route::get('delete-web-banner/{id}', [BannerController::class, 'delete_web_banner'])->name('delete.web.banner');
    
    Route::get('all-cards', [CardController::class, 'index'])->name('cards');
    Route::post('card-store', [CardController::class, 'store'])->name('cards.store');
    Route::post('card-list', [CardController::class, 'list'])->name('cards.list');
    Route::get('card-edit/{id}', [CardController::class, 'edit'])->name('cards.edit');
    Route::post('card-status-update/{id}', [CardController::class, 'status_update'])->name('cards.status.update');
    Route::post('cards-details', [CardController::class, 'cards_details'])->name('cards.details');
    Route::post('export-cards', [CardController::class, 'export_cards'])->name('export.cards');
    Route::get('card-transactions', [CardController::class, 'transactions'])->name('card.transactions');
    Route::post('card-transactions-list', [CardController::class, 'transactions_list'])->name('card.transactions.list');
    Route::get('card-transaction/{id}', [CardController::class, 'transaction'])->name('card.transaction');
    Route::post('card-transaction-list/{id}', [CardController::class, 'transaction_list'])->name('card.transaction.list');
    Route::get('card-topups', [CardController::class, 'card_topups'])->name('cards.topups');
    Route::post('card-topups-list', [CardController::class, 'card_topups_list'])->name('cards.topups.list');
    Route::post('card-topup-store', [CardController::class, 'card_topup_store'])->name('cards.topups.store');
    Route::get('card-topup-edit/{id}', [CardController::class, 'card_topup_edit'])->name('cards.topup.edit');
    Route::get('card-assign', [CardController::class, 'card_assign'])->name('cards.assign');
    Route::get('card-unassign/{id}', [CardController::class, 'card_unassign'])->name('cards.unassign');
    Route::post('assign-card-list', [CardController::class, 'assign_card_list'])->name('assign.cards.list');
    Route::post('assign-cards-store', [CardController::class, 'assign_card_store'])->name('assign.cards.store');
    Route::post('cards-loyality-point', [CardController::class, 'cards_loyality_point'])->name('cards.loyality.point');
    Route::get('scan-card', [CardController::class, 'scan_card'])->name('scan.card');
    Route::get('card-memberships', [CardController::class, 'memberships'])->name('card.memberships');
    Route::post('card-memberships-list', [CardController::class, 'memberships_list'])->name('card.memberships.list');
    
    Route::get('tickets', [TicketsController::class, 'index'])->name('tickets');
    Route::post('tickets-list', [TicketsController::class, 'list'])->name('tickets.list');
    Route::get('view-ticket/{id}', [TicketsController::class, 'view'])->name('view.ticket');
    Route::post('reply-ticket/{id}', [TicketsController::class, 'reply'])->name('reply.ticket');
    Route::post('ticket-status/{id}', [TicketsController::class, 'status'])->name('ticket.status'); 
    
    Route::get('taxes', [TaxController::class, 'index'])->name('taxes');
    Route::post('taxes-list', [TaxController::class, 'list'])->name('taxes.list');
    Route::post('store-tax', [TaxController::class, 'store'])->name('taxes.store');
    Route::post('update-tax', [TaxController::class, 'update'])->name('taxes.update');
    Route::get('delete-tax/{id}', [TaxController::class, 'delete'])->name('taxes.delete');
    Route::get('change-status/{id}/{value}', [TaxController::class, 'status'])->name('taxes.change');
    
    Route::get('fees', [FeeController::class, 'index'])->name('fees');
    Route::post('fees-list', [FeeController::class, 'list'])->name('fees.list');
    Route::post('update-fee', [FeeController::class, 'update'])->name('fees.update');
    Route::post('calculate-fees', [FeeController::class, 'calculate_fees'])->name('calculate.fees');
    Route::get('assign-card-fees', [FeeController::class, 'assign_card_fees'])->name('assign.card.fees');
    Route::post('assign-card-fees-list', [FeeController::class, 'assign_card_fees_list'])->name('assign.card.fees.list');
    Route::post('assign-card-fees-store', [FeeController::class, 'assign_card_fees_store'])->name('assign.card.fees.store');
    Route::get('maintenance-card-fees', [FeeController::class, 'maintenance_card_fees'])->name('maintenance.card.fees');
    Route::post('maintenance-card-fees-list', [FeeController::class, 'maintenance_card_fees_list'])->name('maintenance.card.fees.list');
    Route::post('maintenance-card-fees-store', [FeeController::class, 'maintenance_card_fees_store'])->name('maintenance.card.fees.store');
    
    Route::get('language', [LanguageController::class, 'index'])->name('language');
    Route::post('language-list', [LanguageController::class, 'list'])->name('language.list');
    Route::post('store-language', [LanguageController::class, 'store'])->name('language.store');
    Route::post('update-language', [LanguageController::class, 'update'])->name('update-language');
    Route::get('delete-langauge/{id}', [LanguageController::class, 'delete'])->name('delete.langauge');
    Route::get('contents', [LanguageController::class, 'contents'])->name('contents');
    Route::post('language-contents', [LanguageController::class, 'contents_list'])->name('contents.list');
    Route::post('store-language-content', [LanguageController::class, 'contents_store'])->name('contents.store');
    Route::post('update-language-content', [LanguageController::class, 'contents_update'])->name('contents.update');
    Route::get('delete-langauge-content/{id}', [LanguageController::class, 'contents_delete'])->name('contents.delete');
    
    Route::get('email-templates', [EmailtemplateController::class, 'index'])->name('email.templates');
    Route::post('email-templates-list', [EmailtemplateController::class, 'list'])->name('email.templates.list');
    Route::any('email-templates-store', [EmailtemplateController::class, 'store'])->name('email.templates.store');
    Route::any('email-templates-update/{id}', [EmailtemplateController::class, 'update'])->name('email.templates.update');
    Route::get('admin-email-templates', [EmailtemplateController::class, 'admin_email'])->name('admin.email.templates');
    Route::post('admin-email-templates-list', [EmailtemplateController::class, 'admin_email_list'])->name('admin.email.templates.list');
    Route::any('admin-email-templates-store', [EmailtemplateController::class, 'admin_email_store'])->name('admin.email.templates.store');
    Route::any('admin-email-templates-update/{id}', [EmailtemplateController::class, 'admin_email_update'])->name('admin.email.templates.update');
    
    Route::get('notification-templates', [NotificationtemplateController::class, 'index'])->name('notification.templates');
    Route::post('notification-templates-list', [NotificationtemplateController::class, 'list'])->name('notification.templates.list');
    Route::any('notification-templates-store', [NotificationtemplateController::class, 'store'])->name('notification.templates.store');
    Route::any('notification-templates-update/{id}', [NotificationtemplateController::class, 'update'])->name('notification.templates.update');
    Route::get('admin-notification-templates', [NotificationtemplateController::class, 'admin_notification'])->name('admin.notification.templates');
    Route::post('admin-notification-templates-list', [NotificationtemplateController::class, 'admin_notification_list'])->name('admin.notification.templates.list');
    Route::any('admin-notification-templates-store', [NotificationtemplateController::class, 'admin_notification_store'])->name('admin.notification.templates.store');
    Route::any('admin-notification-templates-update/{id}', [NotificationtemplateController::class, 'admin_notification_update'])->name('admin.notification.templates.update');
    
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('notifications-list', [NotificationController::class, 'list'])->name('notifications.list');
    Route::post('notifications-store', [NotificationController::class, 'store'])->name('notifications.store');
    Route::post('notifications-update', [NotificationController::class, 'update'])->name('notifications.update');
    Route::get('notifications-delete/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::post('platform-notification-list', [NotificationController::class, 'platform_notification_list'])->name('platform.notification.list');
    Route::get('platform-notification-delete/{id}', [NotificationController::class, 'platform_notification_delete'])->name('platform.notification.delete');
    
    Route::get('general', [SettingsController::class, 'index'])->name('general');
    Route::post('update-settings', [SettingsController::class, 'update'])->name('update.settings');
    Route::get('gateways', [SettingsController::class, 'gateways'])->name('gateways');
    Route::post('update-gateways', [SettingsController::class, 'update_gateways'])->name('update.gateways');
    Route::get('loyality-points', [SettingsController::class, 'loyality_points'])->name('loyality.points');
    Route::get('revenue-setting', [SettingsController::class, 'revenue_setting'])->name('revenue.setting');
    Route::post('revenue-setting-update', [SettingsController::class, 'revenue_setting_update'])->name('revenue.setting.update');
    Route::post('revenue-setting-list', [SettingsController::class, 'revenue_setting_list'])->name('revenue.setting.list');
    Route::get('app-settings', [SettingsController::class, 'app_settings'])->name('app.settings');
    Route::post('app-settings-update', [SettingsController::class, 'app_settings_update'])->name('app.settings.update');
    Route::get('platform-earnings', [SettingsController::class, 'platform_earnings'])->name('platform.earnings');
    Route::post('platform-earnings-list', [SettingsController::class, 'platform_earnings_list'])->name('platform.earnings.list');
    Route::get('email-settings', [SettingsController::class, 'email_settings'])->name('email.settings');
    Route::post('email-settings-update', [SettingsController::class, 'email_settings_update'])->name('email.settings.update');
    
    Route::get('categories', [CategoryController::class, 'index'])->name('all.categories');
    Route::post('categories-list', [CategoryController::class, 'list'])->name('categories.list');
    Route::post('categories-store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('categories-update', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('categories-delete/{id}', [CategoryController::class, 'delete'])->name('categories.delete');
    Route::get('categories-status/{id}/{status}', [CategoryController::class, 'status'])->name('categories.status');
    
    Route::get('bookings', [BookingsController::class, 'index'])->name('bookings');
    Route::post('bookings-list', [BookingsController::class, 'list'])->name('bookings.list');
    Route::post('pending-bookings-list', [BookingsController::class, 'pending'])->name('pending.bookings.list');
    Route::post('accepted-bookings-list', [BookingsController::class, 'accepted'])->name('accepted.bookings.list');
    Route::post('completed-bookings-list', [BookingsController::class, 'completed'])->name('completed.bookings.list');
    Route::post('cancelled-bookings-list', [BookingsController::class, 'cancelled'])->name('cancelled.bookings.list');
    Route::post('declined-bookings-list', [BookingsController::class, 'declined'])->name('declined.bookings.list');
    Route::get('user-booking-details/{id}', [BookingsController::class, 'view'])->name('bookings.view');
    Route::get('booking-status/{booking_id}/{status}', [BookingsController::class, 'status'])->name('bookings.status');
    Route::get('admin-service-booking/{id}', [BookingsController::class, 'admin_service_booking'])->name('admin.service.booking');
    Route::post('available-bookings', [BookingsController::class, 'available'])->name('bookings.available');
    Route::post('place-booking', [BookingsController::class, 'place_booking'])->name('place.booking');
    
    Route::post('fetch-services', [ServiceController::class, 'fetch_services'])->name('fetch.services');
    Route::get('services', [ServiceController::class, 'index'])->name('services');
    Route::post('services-list', [ServiceController::class, 'list'])->name('services.list');
    Route::get('add-service', [ServiceController::class, 'create'])->name('addService');
    Route::post('preview-service', [ServiceController::class, 'preview'])->name('preview.services');
    Route::post('store-service', [ServiceController::class, 'store'])->name('addServiceToSalonWeb');
    Route::get('edit-service/{id}', [ServiceController::class, 'edit'])->name('viewService');
    Route::post('update-services', [ServiceController::class, 'update'])->name('updateService_Admin');
    Route::get('delete-services/{id}', [ServiceController::class, 'delete'])->name('services.delete');
    Route::get('services-status/{id}/{status}', [ServiceController::class, 'status'])->name('services.status');
    Route::get('delete-service-image/{id}', [ServiceController::class, 'delete_service_image'])->name('delete.service.image');
    Route::get('delete-service-map-image/{id}', [ServiceController::class, 'delete_service_map_image'])->name('delete.service.map.image');
    Route::get('categorised-services/{id}', [ServiceController::class, 'categorised_services'])->name('categorised.services');
    Route::post('categorised-services-list/{id}', [ServiceController::class, 'categorised_services_list'])->name('categorised.services.list');
    Route::post('add-booking-slots', [ServiceController::class, 'add_booking_slots'])->name('add.booking.slots');
    Route::get('delete-booking-slots/{id}', [ServiceController::class, 'delete_booking_slots'])->name('delete.booking.slots');
    
    Route::get('platforms', [SalonController::class, 'index'])->name('platforms');
    Route::post('platforms-list', [SalonController::class, 'list'])->name('platforms.list');
    Route::get('platform-edit/{id}', [SalonController::class, 'edit'])->name('platforms.edit');
    Route::post('platform-update', [SalonController::class, 'update'])->name('platforms.update');
    Route::post('platform-bookings-list', [SalonController::class, 'bookings_list'])->name('platform.bookings.list');
    Route::post('platform-services-list', [SalonController::class, 'services_list'])->name('platform.services.list');
    Route::post('add-platform-image', [SalonController::class, 'add_images'])->name('add.platform.image');
    Route::get('delete-platform-image/{id}', [SalonController::class, 'delete_images'])->name('delete.platform.image');
    Route::post('gallery-list', [SalonController::class, 'gallery_list'])->name('platform.gallery.list');
    Route::post('add-platform-gallery', [SalonController::class, 'add_gallery'])->name('add.platform.gallery');
    Route::get('delete-platform-gallery/{id}', [SalonController::class, 'delete_gallery'])->name('delete.platform.gallery');
    Route::post('map-list', [SalonController::class, 'map_list'])->name('platform.map.list');
    Route::post('add-platform-map', [SalonController::class, 'add_map'])->name('add.platform.map');
    Route::get('delete-platform-map/{id}', [SalonController::class, 'delete_map'])->name('delete.platform.map');
    Route::post('reviews-list', [SalonController::class, 'reviews_list'])->name('platform.reviews.list');
    
    Route::get('countries', [CountryController::class, 'index'])->name('countries');
    Route::post('countries-list', [CountryController::class, 'list'])->name('countries.list');
    Route::post('store-country', [CountryController::class, 'store'])->name('countries.store');
    Route::post('update-country', [CountryController::class, 'update'])->name('countries.update');
    Route::get('delete-country/{id}', [CountryController::class, 'delete'])->name('countries.delete');
});

Route::group(['middleware' => ['checkWebsite']], function ()
{
    Route::get('all-faqs',[HomeController::class,'all_faqs'])->name('web.faqs');
    Route::get('all-blogs',[HomeController::class,'all_blogs'])->name('web.blogs');
    Route::get('{slug}',[HomeController::class,'web_pages'])->name('web.pages');
});
