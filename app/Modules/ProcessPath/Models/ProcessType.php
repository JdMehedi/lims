<?php namespace App\Modules\ProcessPath\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class   ProcessType extends Model
{

    protected $table = 'process_type';
    public $timestamps = false;
    protected $fillable = array(
        'id',
        'name',
        'name_bn',
        'status',
        'final_status',
        'type_key',
        'type',
        'is_special',
        'active_menu_for',
        'panel',
        'icon',
        'menu_name',
        'form_url',
        'process_key',
        'ref_fields',
        'suggested_status_json',
        'guideline_details',
        'guideline_file',
        'guideline_status'
    );


    protected static $process_type_table_list = [
        1 => "isp_license_issue",
        2 => "isp_license_renew",
        3 => "isp_license_amendment",
        4 => "isp_license_surrender",
        5 => "call_center_issue",
        6 => "call_center_renew",
        7 => "call_center_amendment",
        8 => "call_center_surrender",
        9 => "nix_license_issue",
        10 => "nix_license_renew",
        11 => "nix_license_amendment",
        12 => "nix_license_surrender", //
        13 => "vsat_license_issue",
        14 => "vsat_license_renew",
        15 => "vsat_license_amendment",
        16 => "vsat_license_cancellation", //
        17 => "iig_license_issue",
        18 => "iig_license_renew",
        19 => "iig_license_amendment",
        20 => "iig_license_cancellation",
        23 => "iptsp_license_amendment",
        24 => "iptsp_license_surrender",
        25 => "tvas_license_issue",
        26 => "tvas_license_renew",
        27 => "tvas_license_amendment",
        28 => "tvas_license_surrender",
        29 => "vts_license_issue",
        21 => "iptsp_license_issue",
        22 => "iptsp_license_renew",
        34 => "icx_license_renew", //
        35 => "icx_license_amendment", //
        33 => "icx_license_issue",
        36 => "icx_license_surrender", //
        37 => "igw_license_issue",
        38 => "igw_license_renew",
        39 => "igw_license_amendment",
        40 => "igw_license_cancellation",
        50 => "nttn_license_issue",
        51 => "nttn_license_renew",
        52 => "nttn_license_amendment", //
        53 => "nttn_license_surrender", //
        54 => "itc_license_issue",
        55 => "itc_license_renew", //
        56 => "itc_license_amendment", //
        57 => "itc_license_cancellation", //
        58 => "mno_license_issue",
        59 => "mno_license_renew", //
        60 => "mno_license_amendment", //
        61 => "mno_license_cancellation", //
        62 => "scs_license_issue",
        63 => "scs_license_renew", //
        64 => "scs_license_amendment", //
        65 => "scs_license_surrender", //
        66 => "tc_license_issue", //
        67 => "tc_license_renew", //
        68 => "tc_license_amendment", //
        69 => "tc_license_cancellation", //
        70 => "mnp_license_issue",
        71 => "mnp_license_renew",
        72 => "mnp_license_amendment",
        73 => "mnp_license_surrender",
        74 => "bwa_license_issue",
        75 => "bwa_license_renew",
        76 => "bwa_license_amendment",
        77 => "bwa_license_surrender",
        78 => "ss_license_issue",
        79 => "ss_license_renew",
        80 => "ss_license_amendment",
        81 => "ss_license_surrender",
        83 => "vts_license_amendment", //
        84 => "vts_license_surrender", //
        30 => "vts_license_renew",
    ];
    public static function process_type_table_by_id($id) {
        return self::$process_type_table_list[$id] ?? null;
    }
}

