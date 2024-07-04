<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\WithdrawController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\BookingsController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PageController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['checkHeader']], function ()
{
    Route::post('register', [AuthController::class, 'register']);
    Route::post('send-otp', [AuthController::class, 'send_otp']);
    Route::post('verify-otp', [AuthController::class, 'verify_otp']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgot_password']);
    Route::get('get-countries', [AuthController::class, 'get_countries']);
    Route::post('check-user', [AuthController::class, 'check_user']);
    Route::post('check-phone', [AuthController::class, 'check_phone']);
    Route::post('check-email', [AuthController::class, 'check_email']);
    Route::post('langauage-contents', [ProfileController::class, 'langauage_contents']);
    Route::post('check-app-update', [ProfileController::class, 'check_app_update']);
});

Route::group(['middleware' => ['checkAuth']], function ()
{
    Route::post('user-profile', [ProfileController::class, 'user_profile']);
    Route::post('update-profile', [ProfileController::class, 'update_profile']);
    Route::get('get-langauages', [ProfileController::class, 'get_langauages']);
    Route::post('update-language', [ProfileController::class, 'update_language']);
    Route::post('change-password', [ProfileController::class, 'change_password']);
    Route::post('notifications-list', [ProfileController::class, 'notifications_list']);
    Route::post('remove-account', [ProfileController::class, 'remove_account']);
    Route::post('card-details', [ProfileController::class, 'card_details']);
    
    Route::post('add-fund', [DepositController::class, 'add_fund']);
    Route::post('fetchWalletStatement', [DepositController::class, 'fetchWalletStatement']);
    
    Route::Post('bank-list', [WithdrawController::class, 'index']);
    Route::post('add-bank', [WithdrawController::class, 'store']);
    Route::post('bank-details', [WithdrawController::class, 'details']);
    Route::post('update-bank', [WithdrawController::class, 'update']);
    Route::post('delete-bank', [WithdrawController::class, 'delete']);
    Route::post('withdraw-requests', [WithdrawController::class, 'withdraw_requests']);
    Route::post('create-withdraw-requests', [WithdrawController::class, 'create_withdraw_requests']);
    
    Route::get('get-events', [EventController::class, 'events']);
    Route::post('events-list', [EventController::class, 'index']);
    Route::post('create-event', [EventController::class, 'create']);
    
    Route::get('ticket-reasons',[TicketController::class,'ticket_reasons']);
    Route::post('tickets-list',[TicketController::class,'index']);
    Route::post('create-ticket',[TicketController::class,'create']);
    Route::post('ticket-details',[TicketController::class,'details']);
    Route::post('reply-ticket',[TicketController::class,'reply']);
    
    Route::post('place-booking', [BookingsController::class, 'place_booking']);
    Route::post('booking-details', [BookingsController::class, 'booking_details']);
    Route::post('cancel-booking', [BookingsController::class, 'cancel_booking']);
    Route::post('all-bookings', [BookingsController::class, 'all_bookings']);
    Route::post('bookings-by-date', [BookingsController::class, 'bookings_by_date']);
    Route::post('reschedule-booking', [BookingsController::class, 'reschedule_booking']);
    Route::post('get-coupons', [BookingsController::class, 'get_coupons']);
    Route::post('add-ratings', [BookingsController::class, 'add_ratings']);
    
    Route::post('get-message', [ChatController::class, 'get_message']);
    Route::post('send-message', [ChatController::class, 'send_message']);
    
    Route::post('get-services', [ServiceController::class, 'get_services']);
    Route::post('service-details', [ServiceController::class, 'service_details']);
    Route::post('favorite-services', [ServiceController::class, 'favorite_services']);
    Route::post('add-favorite-services', [ServiceController::class, 'add_favorite_services']);
    Route::post('remove-favorite-services', [ServiceController::class, 'remove_favorite_services']);
    
    Route::post('home-data', [HomeController::class, 'home_data']);
    Route::post('services-by-category', [HomeController::class, 'services_by_category']);
    Route::post('search-services', [HomeController::class, 'search_services']);
    Route::post('search-categories', [HomeController::class, 'search_categories']);
    Route::post('search-by-coordinates', [HomeController::class, 'search_by_coordinates']);
    Route::post('filter-services', [HomeController::class, 'filter_services']);
    Route::post('search-platform', [HomeController::class, 'search_platform']);
    Route::post('platform-details', [HomeController::class, 'platform_details']);
    Route::post('service-reviews', [HomeController::class, 'service_reviews']);
    Route::post('company-reviews', [HomeController::class, 'company_reviews']);
    Route::post('platform-categories', [HomeController::class, 'platform_categories']);
    Route::post('get-settings', [HomeController::class, 'get_settings']);
    
    Route::post('faq-categories', [PageController::class, 'faq_categories']);
    Route::post('get-faqs', [PageController::class, 'get_faqs']);
    Route::post('faqs', [PageController::class, 'faqs']);
    Route::post('get-pages', [PageController::class, 'get_pages']);
    Route::post('page-details', [PageController::class, 'page_details']);
    
    Route::post('register-device', [AuthController::class, 'register_device']);
    Route::post('logout', [AuthController::class, 'logout']);
});
