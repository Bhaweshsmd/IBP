<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Banners;
use App\Models\Bookings;
use App\Models\Constants;
use App\Models\Coupons;
use App\Models\FaqCats;
use App\Models\Faqs;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\PlatformEarningHistory;
use App\Models\SalonCategories;
use App\Models\SalonNotifications;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\Salons;
use App\Models\Taxes;
use App\Models\UserNotification;
use App\Models\UserWalletRechargeLogs;
use App\Models\UserWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Google\Client;
use Illuminate\Support\Facades\File;
Use DB;
use App\Models\Language;
use App\Models\LanguageContent;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\Fee;
use App\Models\NotificationTemplate;
use App\Models\SupportTicket;
use App\Models\RevenueSetting;
use App\Models\AdminEmailTemplate;
use App\Models\CardFee;
use App\Models\CardTopup;
use App\Models\SupportTicketMessage;
use App\Models\Users;
use App\Models\Card;
use App\Models\TansactionType;
use Carbon\Carbon;
use App\Models\AdminNotificationTemplate;
use App\Models\MaintenanceFee;
use App\Models\CardmembershipFee;
use App\Models\Device;
use App\Models\Page;

class PageController extends Controller
{
    public function faq_categories(Request $request)
    {
        $rules = [
            'lan_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $faqs = FaqCats::get();
        
        return GlobalFunction::sendDataResponse(true, 'FAQ categories fetched successfully', $faqs);
    }
    
    public function get_faqs(Request $request)
    {
        $rules = [
            'lan_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $lang = $request->lan_id;
        
        if(!empty($lang)){
            $title_lang = 'question_'.$lang;
            $desc_lang = 'answer_'.$lang;
        }else{
            $title_lang = 'question_en';
            $desc_lang = 'answer_en';
        }
        
        $faqs = Faqs::where('status', '1')->get();
        
        foreach($faqs as $k=>$faq){
            $val[] = [
                'id' => $faq->id,
                'category_id' => $faq->category_id,
                'question' => $faq->$title_lang,
                'answer' => $faq->$desc_lang,
                'status' => $faq->status,
            ];
        }
        
        return GlobalFunction::sendDataResponse(true, 'FAQs fetched successfully', $val);
    }
    
    public function faqs(Request $request)
    {
        $rules = [
            'lan_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $lang = $request->lan_id;
        
        if(!empty($lang)){
            $title_lang = 'question_'.$lang;
            $desc_lang = 'answer_'.$lang;
        }else{
            $title_lang = 'question_en';
            $desc_lang = 'answer_en';
        }
        
        $faq_cats = FaqCats::get();
        foreach($faq_cats as $k=>$cat){
            if($lang == 'en'){
                $faqs = Faqs::select('id', 'category_id', 'question_en as question', 'answer_en as answer', 'created_at', 'updated_at')->where('status', '1')->where('category_id', $cat->id)->get();
            }elseif($lang == 'pap'){
                $faqs = Faqs::select('id', 'category_id', 'question_pap as question', 'answer_pap as answer', 'created_at', 'updated_at')->where('status', '1')->where('category_id', $cat->id)->get();
            }elseif($lang == 'nl'){
                $faqs = Faqs::select('id', 'category_id', 'question_nl as question', 'answer_nl as answer', 'created_at', 'updated_at')->where('status', '1')->where('category_id', $cat->id)->get();
            }
            
            $allfaqs[] = [
                'id' => $cat->id,
                'title' => $cat->title,
                'created_at' => $cat->created_at,
                'updated_at' => $cat->updated_at,
                "faqs" => $faqs
            ];
        }
        
        return GlobalFunction::sendDataResponse(true, 'FAQs fetched successfully', $allfaqs);
    }
    
    public function get_pages(Request $request)
    {
        $rules = [
            'lan_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $lang = $request->lan_id;
        
        if(!empty($lang)){
            $title_lang = $lang.'_title';
            $desc_lang = $lang.'_description';
        }else{
            $title_lang = 'en_title';
            $desc_lang = 'en_description';
        }
        
        $pages = Page::where('type', '!=', '1')->where('status', '1')->get();
        
        foreach($pages as $k=>$page){
            $val[] = [
                'id' => $page->id,
                'slug' => $page->slug,
                'title' => $page->$title_lang,
            ];
        }
        
        return GlobalFunction::sendDataResponse(true, 'Pages fetched successfully', $val);
    }
    
    public function page_details(Request $request)
    {
        $rules = [
            'page_id' => 'required',
            'lan_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $lang = $request->lan_id;
        $page = $request->page_id;
        
        if(!empty($lang)){
            $title_lang = $lang.'_title';
            $desc_lang = $lang.'_description';
        }else{
            $title_lang = 'en_title';
            $desc_lang = 'en_description';
        }
        
        $check_page = Page::where('id', $page)->where('type', '!=', '1')->where('status', '1')->first();
        if(empty($check_page)){
            return response()->json(['status' => false, 'message' => 'Page not found.']);
        }
        
        $val['id'] = $check_page->id;
        $val['title'] = $check_page->$title_lang;
        $val['description'] = $check_page->$desc_lang;
        $val['status'] = $check_page->status;
        $val['slug'] = $check_page->slug;
        
        return GlobalFunction::sendDataResponse(true, 'Page fetched successfully', $val);
    }
}