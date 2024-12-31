<?php

namespace App\Http\Controllers;

use App\HomePageArticle;
use App\Libraries\CommonFunction;
use App\Modules\Faq\Models\Faq;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\ServiceDetails;
use App\Modules\Settings\Models\UserManual;
use App\Modules\Settings\Models\WhatsNew;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
    }

    public function aboutQuickServicePortal(Request $request)
    {
//        $contents = HomePageArticle::where('page_name', 'about_bida_quick_service_portal')->pluck('page_content');

//        $redirect_url = CommonFunction::getOssPidRedirectUrl();
//        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();

        // for home page view log
//        CommonFunction::createHomePageViewLog('AboutQuickServicePortal', $request);
        $configuration = Configuration::whereIn('caption', ['SUPPORT_EMAIL','SUPPORT_MOBILE'])->get(['value'])->toArray();

        return view('articles.support', compact('configuration'));
    }

    /*
     * user support
     */
    public function support()
    {
        $faqs = Faq::leftJoin('faq_multitypes', 'faq.id', '=', 'faq_multitypes.faq_id')
            ->leftJoin('faq_types', 'faq_multitypes.faq_type_id', '=', 'faq_types.id')
            ->where('status', 'public')
            ->where('faq_types.name', 'login')
            ->get(['question', 'answer', 'status', 'faq_type_id as types', 'name as faq_type_name', 'faq.id as id']);

        return view("articles.support", compact('faqs'));
    }

}
