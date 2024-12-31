<?php

namespace App\Libraries;

use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ACL {

    public static function db_reconnect() {
        if ( Session::get( 'DB_MODE' ) == 'PRODUCTION' ) {
//        DB::purge('mysql-main');
//        DB::setDefaultConnection('mysql-main');
//        DB::setDefaultConnection(Session::get('mysql_access'));
        }
    }

    public static function hasOwnCompanyUserModificationRight( $userType, $right, $id ) {
        try {
            $companyId = CommonFunction::getUserCompanyByUserId( $id );
            if ( $companyId == Auth::user()->working_company_id ) {
                return true;
            }

            return false;
        } catch ( \Exception $e ) {
            return false;
        }
    }


    public static function getAccsessRight( $module, $right = '', $id = null ) {
        $accessRight = '';
        $right       = $right ? '-' . str_replace( '-', '', $right ) . '-' : '';
        if ( Auth::user() ) {
            $user_type = Auth::user()->user_type;
        } else {
            die( 'You are not authorized user or your session has been expired!' );
        }
        switch ( $module ) {
            /*case 'processPath':*/
            case 'settings':
                if ( $user_type == '1x101' ) {
                    $accessRight = '-A-V-E-';
                } elseif ( $user_type == '10x101' ) {
                    $accessRight = '-A-V-E-';
                } elseif ( $user_type == '2x202' ) {
                    $accessRight = '-A-V-E-';
                }
                break;

            case 'dashboard':
                if (in_array( $user_type, ['1x101', '10x101' ] )) {
                    $accessRight = '-A-V-E-SERN-';
                } elseif ( $user_type == '5x505' ) {
                    $accessRight = '-A-V-E-SERNH-';
                }
                break;

            case 'CompanyAssociation':
                if ( $user_type == '1x101' ) {
                    $accessRight = '-A-V-UP-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else {
                    $accessRight = '-V-';
                }
                break;

            case 'Documents':
                if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else {
                    $accessRight = '-V-';
                }
                break;

            case 'user':
                if ( in_array( $user_type, [ '1x101', '10x101'  ] ) ) {
                    $accessRight = '-A-V-E-R-APV-';
                } elseif ( in_array( $user_type, [ '5x505' ] ) && in_array( $right, [ '-APV-' ] ) ) {
                    if ( ACL::hasOwnCompanyUserModificationRight( $user_type, $right, $id ) ) {
                        return true;
                    }
                    $accessRight = '-V-R-';
                } else if ( in_array( $user_type, [ '2x202', '4x404' ] ) ) {
                    $accessRight = '-V-R-';
                } else {
                    $accessRight = '-V-';
                }
                if ( $right == "-SPU-" ) {
                    if ( ACL::hasUserModificationRight( $user_type, $right, $id ) ) {
                        return true;
                    }
                }
                break;

            case 'isp_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101' ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'isp_license_renew':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;
            case 'isp_license_ammendment':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'itc_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'isp_license_cancellation':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'call_center_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;
                case 'call_center_license_renew':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;
            case 'call_center_license_ammendment':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;
                case 'call_center_license_cancellation':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;


            case 'nix_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'nix_license_renew':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;
                case 'nix_license_ammendment':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

                case 'nix_license_cancellation':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'vsat_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'iig_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;
            case 'igw_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;


            case 'vsat_license_renew':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'iptsp_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'vts_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;


            case 'tvas_license_issue':

                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'icx_license_issue':

                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'mno_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'mnp_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;
            case 'mnp_license_renew':
            if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                $accessRight = '-V-';
            } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                $accessRight = '-A-V-E-';
            } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                $accessRight = '-V-UP-';
            }
            break;
            case 'mnp_license_amendment':
            if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                $accessRight = '-V-';
            } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                $accessRight = '-A-V-E-';
            } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                $accessRight = '-V-UP-';
            }
            break;
            case 'mnp_license_cancellation':
            if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                $accessRight = '-V-';
            } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                $accessRight = '-A-V-E-';
            } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                $accessRight = '-V-UP-';
            }
            break;

            case 'bwa_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101' ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'bwa_license_renew':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;
            case 'bwa_license_ammendment':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'itc_license_issue':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'bwa_license_cancellation':
                if ( in_array( $user_type, [ '1x101', '2x202', '10x101'  ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;


            case 'payment':
                if ( in_array( $user_type, [ '1x101', '2x202' ] ) ) {
                    $accessRight = '-V-';
                } else if ( in_array( $user_type, [ '5x505' ] ) ) {
                    $accessRight = '-A-V-E-';
                    if ( $id != null && ! ( strpos( $accessRight, $right ) === false ) ) {
                        if ( ACL::hasApplicationModificationRight( 10, $id, $right ) == false ) {
                            return false;
                        }
                    }
                } else if ( in_array( $user_type, [ '4x404' ] ) ) {
                    $accessRight = '-V-UP-';
                }
                break;

            case 'reportv2':
                if (in_array( $user_type,['1x101', '10x101' ]) ) {
                    $accessRight = '-A-V-E-';
                } else if ( $user_type == '4x404' ) {
                    $accessRight = '-V-';
                }else if ( $user_type == '11x101') {
                    $accessRight = '-V-';
                }
                break;

            default:
                $accessRight = '';
        }
        if ( $right != '' ) {
            if ( strpos( $accessRight, $right ) === false ) {
                return false;
            } else {
                return true;
            }
        } else {
            return $accessRight;
        }
    }

    public static function hasUserModificationRight( $userType, $right, $id ) {
        try {
            $userId = CommonFunction::getUserId();
            if ( $userType == '1x101' ) {
                return true;
            }

            if ( $userId == $id ) {
                return true;
            }

            return false;
        } catch ( \Exception $e ) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            #dd(CommonFunction::showErrorPublic($e->getMessage()));
            return false;
        }
    }

    public static function isAllowed( $accessMode, $right ) {
        if ( strpos( $accessMode, $right ) === false ) {
            return false;
        } else {
            return true;
        }
    }

    public static function hasApplicationModificationRight( $processTypeId, $id, $right ) {
        try {
            $companyId = CommonFunction::getUserCompanyWithZero();
            if ( $right != '-E-' ) {
                return true;
            } else {
                $processListData = ProcessList::where( 'ref_id', $id )->where( 'process_type_id', $processTypeId )
                                              ->first( [ 'company_id', 'status_id' ] );
                if ( $processListData == null ) {
                    return false;
                } elseif ( $processListData->company_id == $companyId && in_array( $processListData->status_id, [
                        - 1,
                        5
                    ] ) ) {
                    return true;
                } else {
                    return false;
                }
            }
        } catch ( \Exception $e ) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            return false;
        }
    }

    /*     * **********************************End of Class****************************************** */
}
