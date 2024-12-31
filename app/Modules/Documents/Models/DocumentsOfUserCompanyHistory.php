<?php


namespace App\Modules\Documents\Models;


use App\Libraries\CommonFunction;
use App\Modules\Settings\Models\DocumentName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentsOfUserCompanyHistory extends Model
{
    protected $table = 'doc_of_user_company_history';

}