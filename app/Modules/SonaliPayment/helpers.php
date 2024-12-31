<?php

use App\Modules\SonaliPayment\Models\PayMode;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

function getCurrentUserId()
{
    if (Auth::user()) {
        return Auth::user()->id;
    } else {
        return 0;
    }
}

function getPaymentModeCodeMsg($pay_mode_code)
{
    $returnData = [
        'pay_mode_msg' => 'Not found',
    ];

    $pay_mode = PayMode::where(['pay_mode_code' => trim($pay_mode_code), 'status' => 1])->value('pay_mode');
    if (!empty($pay_mode)) {
        $returnData['pay_mode_msg'] = $pay_mode;
    }

    return $returnData;
}

function getUserFullName()
{
    if (Auth::user()) {
        return Auth::user()->user_first_name . ' ' . Auth::user()->user_middle_name . ' ' . Auth::user()->user_last_name;
    } else {
        return 'Invalid Login Id';
    }
}

function convert_number_to_words($number)
{
    $hyphen = '-';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'fourty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion',
        1000000000000 => 'trillion',
        1000000000000000 => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string)$fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

function showAuditLog($updated_at = '', $updated_by = '')
{
    $update_was = 'Unknown';
    if ($updated_at && $updated_at > '0') {
        $update_was = Carbon::createFromFormat('Y-m-d H:i:s', $updated_at)->diffForHumans();
    }

    $user_name = 'Unknown';
    if ($updated_by) {
        $name = Users::where('id', $updated_by)->first(['user_first_name', 'user_middle_name', 'user_last_name']);
        if ($name) {
            $user_name = $name->user_first_name . ' ' . $name->user_middle_name . ' ' . $name->user_last_name;
        }
    }
    return '<span class="help-block">Last updated : <i>' . $update_was . '</i> by <b>' . $user_name . '</b></span>';
}
