<?php

namespace App\Modules\SonaliPayment\Library;

use Illuminate\Support\Facades\Auth;

class SonaliPaymentACL
{
    public static function getAccessRight($module, $right = '', $id = null)
    {
        $accessRight = '';
        if (Auth::user()) {
            $user_type = Auth::user()->user_type;
        } else {
            die('You are not authorized user or your session has been expired!');
        }
        switch ($module) {

            case 'SonaliPayment':
                if (in_array($user_type, ['1x101', '2x202'])) {
                    $accessRight = '-A-V-E-';
                } elseif (in_array($user_type, ['5x505', '6x606'])) {
                    /*
                     * CPC = Counter Payment Cancel
                     * CPCR = Counter Payment Confirmation Request
                     */
                    $accessRight = '-CPC-CPCR-';
                }
                break;
            default:
                $accessRight = '';
        }
        if ($right != '') {
            if (strpos($accessRight, $right) === false) {
                return false;
            } else {
                return true;
            }
        } else {
            return $accessRight;
        }
    }

    public static function isAllowed($accessMode, $right)
    {
        if (strpos($accessMode, $right) === false) {
            return false;
        } else {
            return true;
        }
    }
}
