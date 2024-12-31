<?php

namespace App\Modules\SonaliPayment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Modules\REUSELicenseIssue\Models\IPTSP\issue\IPTSPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IPTSP\renew\IPTSPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\ISP\amendment\ISPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenew;
use App\Modules\NIXLicenseIssue\Models\NIXLicenseIssue;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\BankBranch;
use App\Modules\SonaliPayment\Models\AnnualFeeInfo;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\PayOrderInfo;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use Illuminate\Http\Request;
use App\Libraries\Encryption;
use App\Modules\Dashboard\Models\DynamicPayment;
use App\Modules\IndustryNew\Http\Controllers\IndustryNewController;
use App\Modules\IndustryNew\Models\IndustryNew;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\SonaliPayment\Models\PaymentDistributionType;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Modules\SonaliPayment\Models\PayOrderPayment;
use Illuminate\Support\Facades\Log;

class PaymentPanelController extends Controller {

    use SPPaymentManager, SPAfterPaymentManager;

    public function __construct() {
        // this will need for a function inside SPPaymentManager
        $this->process_type_id = 0;
    }

    public function getPaymentPanel( Request $request ) {

        $process_type_id = $request->get( 'process_type_id' );
        $payment_step_id = $request->get( 'payment_step_id' );

        $form_data['app_status_id'] = - 1;

        if ( ! empty( $request->get( 'app_id' ) ) ) {
            $process_info                         = ProcessList::where( [
                'ref_id'          => $request->get( 'app_id' ),
                'process_type_id' => $request->get( 'process_type_id' )
            ] )->first( [
                'ref_id',
                'process_type_id',
                'company_id',
                'status_id',
            ] );
            $form_data['app_status_id']           = $process_info->status_id;
            $form_data['encoded_process_type_id'] = Encryption::encodeId( $process_info->process_type_id );
            $form_data['encoded_app_id']          = Encryption::encodeId( $process_info->ref_id );
        }

        $form_data['encoded_payment_step_id'] = Encryption::encodeId( $payment_step_id );

        // Need to validate process_type_id and payment_step_id

        // if (is_object($request->get('unfixed_amount_array')) === false) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Error: Unfixed amounts variable should be an object, ' . gettype($request->get('unfixed_amount_array')) . ' given'
        //     ]);
        // }

        $unfixed_amount_array = json_decode( $request->get( 'unfixed_amount_array' ), true );

        // Checking the payment configuration for this service
        $payment_config = PaymentConfiguration::leftJoin(
            'sp_payment_steps',
            'sp_payment_steps.id',
            '=',
            'sp_payment_configuration.payment_step_id'
        )
                                              ->where( [
                                                  'sp_payment_configuration.process_type_id' => $process_type_id,
                                                  'sp_payment_configuration.payment_step_id' => $payment_step_id,
                                                  'sp_payment_configuration.status'          => 1,
                                                  'sp_payment_configuration.is_archive'      => 0
                                              ] )->first( [
                'sp_payment_configuration.*',
                'sp_payment_steps.id as payment_step_id',
                'sp_payment_steps.name as step_name'
            ] );
        if ( empty( $payment_config ) ) {
            return response()->json( [
                'status'  => false,
                'message' => 'Error: payment configuration not found'
            ] );
        }


        // Checking the payment distributor under payment configuration
        $stakeDistribution = PaymentDistribution::where( 'sp_pay_config_id', $payment_config->id )
                                                ->where( 'status', 1 )
                                                ->where( 'is_archive', 0 )
                                                ->get( [
                                                    'id',
                                                    'stakeholder_ac_no',
                                                    'pay_amount',
                                                    'purpose',
                                                    'purpose_sbl',
                                                    'fix_status',
                                                    'distribution_type'
                                                ] );
        if ( $stakeDistribution->isEmpty() ) {
            return response()->json( [
                'status'  => false,
                'message' => 'Error: payment distribution not found for this configuration'
            ] );
        }

        $form_data['payment_name']    = $payment_config->payment_name;
        $form_data['contact_name']    = $request->get( 'contact_name' );
        $form_data['contact_email']   = $request->get( 'contact_email' );
        $form_data['contact_phone']   = $request->get( 'contact_phone' );
        $form_data['contact_address'] = $request->get( 'contact_address' );

        $unfixed_amount_calculation = $this->unfixedAmountsForPayment( $stakeDistribution, $unfixed_amount_array );

        $form_data['pay_amount']        = $unfixed_amount_calculation['pay_amount_total'];
        $form_data['vat_on_pay_amount'] = $unfixed_amount_calculation['vat_on_pay_amount_total'];
        $form_data['total_amount']      = $form_data['pay_amount'] + $form_data['vat_on_pay_amount'];


        $data['html'] = (string) view( 'SonaliPayment::payment-ui.create-payment', $form_data );

        return response()->json( [
            'status' => true,
            'data'   => $data
        ] );
    }

    public function getPaymentPanelV2( Request $request ) {

        $process_type_id = $form_data['process_type_id'] = $request->get( 'process_type_id' );
        $payment_step_id = $request->get( 'payment_step_id' );

        $form_data['app_status_id'] = 0;

        try {

            if ( ! empty( $request->get( 'app_id' ) ) ) {
                $process_info                         = ProcessList::where( [
                    'ref_id'          => $request->get( 'app_id' ),
                    'process_type_id' => $request->get( 'process_type_id' )
                ] )->first( [
                    'ref_id',
                    'process_type_id',
                    'company_id',
                    'status_id',
                ] );
                $form_data['app_status_id']           = $process_info->status_id;
                $form_data['encoded_process_type_id'] = Encryption::encodeId( $process_info->process_type_id );
                $form_data['encoded_app_id']          = Encryption::encodeId( $process_info->ref_id );
            }

            $form_data['encoded_payment_step_id'] = Encryption::encodeId( $payment_step_id );

            // Need to validate process_type_id and payment_step_id

            // if (is_object($request->get('unfixed_amount_array')) === false) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Error: Unfixed amounts variable should be an object, ' . gettype($request->get('unfixed_amount_array')) . ' given'
            //     ]);
            // }

            $unfixed_amount_array = json_decode( $request->get( 'unfixed_amount_array' ), true );

            // Checking the payment configuration for this service
            $payment_config = PaymentConfiguration::leftJoin(
                'sp_payment_steps',
                'sp_payment_steps.id',
                '=',
                'sp_payment_configuration.payment_step_id'
            )->where( [
                                                      'sp_payment_configuration.process_type_id' => $process_type_id,
                                                      'sp_payment_configuration.payment_step_id' => $payment_step_id,
                                                      'sp_payment_configuration.status'          => 1,
                                                      'sp_payment_configuration.is_archive'      => 0
                                                  ] )->first( [
                    'sp_payment_configuration.*',
                    'sp_payment_steps.id as payment_step_id',
                    'sp_payment_steps.name as step_name'
                ] );
            if ( empty( $payment_config ) ) {
                return response()->json( [
                    'status'  => false,
                    'message' => 'Error: payment configuration not found'
                ] );
            }


            // Checking the payment distributor under payment configuration
            $stakeDistribution = PaymentDistribution::where( 'sp_pay_config_id', $payment_config->id )
                                                    ->where( 'status', 1 )
                                                    ->where( 'is_archive', 0 )
                                                    ->get( [
                                                        'id',
                                                        'stakeholder_ac_no',
                                                        'pay_amount',
                                                        'purpose',
                                                        'purpose_sbl',
                                                        'fix_status',
                                                        'distribution_type'
                                                    ] );
            if ( $stakeDistribution->isEmpty() ) {
                return response()->json( [
                    'status'  => false,
                    'message' => 'Error: payment distribution not found for this configuration'
                ] );
            }

            $form_data['payment_name']       = $payment_config->payment_name;
            $form_data['contact_name']       = $request->get( 'contact_name' );
            $form_data['contact_email']      = $request->get( 'contact_email' );
            $form_data['contact_phone']      = $request->get( 'contact_phone' );
            $form_data['contact_address']    = $request->get( 'contact_address' );
//            $form_data['pay_order_info']     = json_decode( $request->get( 'pay_order_info' ) );
            $form_data['current_pay_info']     = json_decode( $request->get( 'current_pay_info' ) );
//            dd($form_data['current_pay_info']);

            $form_data['multiple_pay_order'] = json_decode( $request->get( 'pay_order_info' ) );

            $form_data['payment_info']     = json_decode($request->get('payment_info'));

            $unfixed_amount_calculation = $this->unfixedAmountsForPayment( $stakeDistribution, $unfixed_amount_array, $payment_step_id, $process_type_id, $request->get( 'app_id' ));

            if (isset($form_data['payment_info']->withOneYearAnnualFee) && ($form_data['payment_info']->withOneYearAnnualFee == 1)) {
                $form_data['annual_fee'] = $unfixed_amount_calculation['annual_fee'] + $unfixed_amount_calculation['annual_vat_fee'];
                $form_data['pay_amount'] = $unfixed_amount_calculation['pay_amount_total'] - $unfixed_amount_calculation['annual_fee'];
                $form_data['vat_on_pay_amount'] = $unfixed_amount_calculation['vat_on_pay_amount_total'] - $unfixed_amount_calculation['annual_vat_fee'];
            } else {
                $form_data['pay_amount'] = $unfixed_amount_calculation['pay_amount_total'];
                $form_data['vat_on_pay_amount'] = $unfixed_amount_calculation['vat_on_pay_amount_total'];
            }

            $form_data['pay_amount'] = $form_data['pay_amount'] - $unfixed_amount_calculation['delay_fee'];
            $form_data['vat_on_pay_amount'] = $form_data['vat_on_pay_amount'] - $unfixed_amount_calculation['delay_vat_fee'];

            //Total delay fee
            $form_data['delay_fee'] = $unfixed_amount_calculation['delay_fee'] + $unfixed_amount_calculation['delay_vat_fee'];

            //Total fee
            $form_data['total_amount'] = $unfixed_amount_calculation['pay_amount_total'] + $unfixed_amount_calculation['vat_on_pay_amount_total'];

            $form_data['bank_list'] = Bank::orderBy( 'name' )->where( 'is_active', 1 )->pluck( 'name', 'id' )->toArray();
            $form_data['payment_step_id'] = intval( $payment_step_id );

            if(in_array($process_type_id,[1]) && in_array($form_data['app_status_id'],['60', '64'])){
                $paymentJson = ProcessType::where('id', $process_type_id)->get('process_desk_status_json');
                $isp_license_type = ISPLicenseIssue::where('id', $request->get( 'app_id' ))->value('isp_license_type');
                $getPaymentJson = json_decode($paymentJson, true);
                $feesJson = json_decode($getPaymentJson[0]['process_desk_status_json'], true);
                $bg_object = $feesJson['bg_object'];
                if($isp_license_type == '1'){
                    $form_data['bank_guarantee_amount'] = $bg_object['nationwide'];
                } elseif($isp_license_type == '2'){
                    $form_data['bank_guarantee_amount'] = $bg_object['divisional'];
                } elseif($isp_license_type == '3'){
                    $form_data['bank_guarantee_amount'] = $bg_object['district'];
                } elseif($isp_license_type == '4'){
                    $form_data['bank_guarantee_amount'] = $bg_object['thana'];
                }
            }

            if(in_array($process_type_id,[2]) && in_array($form_data['app_status_id'],['60', '64'])){
                $paymentJson = ProcessType::where('id', $process_type_id)->get('process_desk_status_json');
                $isp_license_type = ISPLicenseRenew::where('id', $request->get( 'app_id' ))->value('isp_license_type');
                $getPaymentJson = json_decode($paymentJson, true);
                $feesJson = json_decode($getPaymentJson[0]['process_desk_status_json'], true);
                $bg_object = $feesJson['bg_object'];
                if($isp_license_type == '1'){
                    $form_data['bank_guarantee_amount'] = $bg_object['nationwide'];
                } elseif($isp_license_type == '2'){
                    $form_data['bank_guarantee_amount'] = $bg_object['divisional'];
                } elseif($isp_license_type == '3'){
                    $form_data['bank_guarantee_amount'] = $bg_object['district'];
                } elseif($isp_license_type == '4'){
                    $form_data['bank_guarantee_amount'] = $bg_object['thana'];
                }
            }

            $data['html'] = (string) view( 'SonaliPayment::payment-ui.create-payment-V2', $form_data );

            return response()->json( [
                'status' => true,
                'data'   => $data,
                'multiple_pay_order' => $form_data['multiple_pay_order']
            ] );

        } catch ( \Exception $e ) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");

            return response()->json( [
                'status'  => false,
                'data'    => [],
                'message' => 'Something went wrong ! [PPV2]'
            ] );
        }
    }

    public static function unfixedAmountsForPayment( $stakeDistribution, $unfixed_amount_array, $payment_step_id = 0, $process_type_id = 0, $app_id = 0) {
        $pay_amount_total        = 0;
        $vat_on_pay_amount_total = 0;
        $annual_fee = 0;
        $annual_vat_fee = 0;
        $delay_fee =  0;
        $delay_vat_fee =  0;

        $fix_status = 1;
        foreach ( $stakeDistribution as $stakeholder ) {
            $fix_status *= $stakeholder->fix_status;
            if (in_array( $stakeholder->distribution_type, [ 4, 5, 6, 9, 10]) ) {
                $vat_on_pay_amount_total += empty( $stakeholder->pay_amount ) ? $unfixed_amount_array[ $stakeholder->distribution_type ] : $stakeholder->pay_amount;
            } else {
                $pay_amount_total += empty( $stakeholder->pay_amount ) ? $unfixed_amount_array[ $stakeholder->distribution_type ] : $stakeholder->pay_amount;
            }

            if (in_array( $stakeholder->distribution_type, [7])) {
                $annual_fee += empty( $stakeholder->pay_amount ) ? $unfixed_amount_array[ $stakeholder->distribution_type ] : $stakeholder->pay_amount;
            } elseif (in_array( $stakeholder->distribution_type, [9])) {
                $annual_vat_fee += empty( $stakeholder->pay_amount ) ? $unfixed_amount_array[ $stakeholder->distribution_type ] : $stakeholder->pay_amount;
            } elseif (in_array( $stakeholder->distribution_type, [8])) {
                $delay_fee += empty( $stakeholder->pay_amount ) ? $unfixed_amount_array[ $stakeholder->distribution_type ] : $stakeholder->pay_amount;
            } elseif (in_array( $stakeholder->distribution_type, [10])) {
                $delay_vat_fee += empty( $stakeholder->pay_amount ) ? $unfixed_amount_array[ $stakeholder->distribution_type ] : $stakeholder->pay_amount;
            }
        }

        if ($fix_status) {
            if ($payment_step_id == 2) {
                //TODO:: delay fee calculation
                $submissionPaymentData = SonaliPayment::where([
                    'app_id' => $app_id,
                    'process_type_id' => $process_type_id,
                    'payment_step_id' => 1,
                    'payment_status' => 1
                ])->first(['updated_at']); // Submission payment date

                $submissionPaymentDateTime = !empty($submissionPaymentData->updated_at) ? date('Y-m-d', strtotime($submissionPaymentData->updated_at)) : date('Y-m-d');
                $currentDateTime = date('Y-m-d', strtotime('-1 year'));

                if ($currentDateTime < $submissionPaymentDateTime) {
                    $delay_fee = 0;
                    $delay_vat_fee = 0;
                }
            } elseif (in_array($payment_step_id, [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16])) {
                //TODO::Delay fee calculation
                $annualFeeData = AnnualFeeInfo::where([
                    'process_type_id' => $process_type_id,
                    'app_id' => $app_id,
                    'payment_step' => $payment_step_id,
                    'status' => 0
                ])->first();
                $paymentLastDate = strval($annualFeeData->payment_due_date);
                $currentDateTime = date('Y-m-d');
                if ($currentDateTime < $paymentLastDate) {
                    $delay_fee = 0;
                    $delay_vat_fee = 0;
                }
            }
        }

        return [
            'pay_amount_total'        => $pay_amount_total,
            'vat_on_pay_amount_total' => $vat_on_pay_amount_total,
            'annual_fee'              => round($annual_fee,2),
            'annual_vat_fee'          => round($annual_vat_fee,2),
            'delay_fee'               => round($delay_fee, 2),
            'delay_vat_fee'           => round($delay_vat_fee, 2)
        ];
    }

    public function getViewPaymentPanel( $process_type_id, $app_id ) {
        $process_type_id    = Encryption::decodeId( $process_type_id );
        $app_id             = Encryption::decodeId( $app_id );
        $data['app_status'] = ProcessList::where( [
            'process_type_id' => $process_type_id,
            'ref_id'          => $app_id,
        ] )->value( 'status_id' );

        $process_type_data = ProcessType::find($process_type_id );

        if($process_type_data->is_special==1){
            $data['payment_info']               = DynamicPayment::leftjoin('bank','bank.id','=','dynamic_service_payment.bank_id')
            ->leftjoin('bank_branches','bank_branches.id','=','dynamic_service_payment.branch_id')
            ->where( 'app_id', $app_id )
            ->where( 'process_type_id', $process_type_id )
            ->groupBy( 'id' )
            ->get( [
                'dynamic_service_payment.*',
                'bank.name',
                'bank_branches.branch_name',
            ] );
            $data['is_special'] = 1;



        }else{
            $data['is_special'] = 0;
            $data['payment_info']               = SonaliPayment::leftJoin( 'sp_payment_configuration', 'sp_payment_configuration.id', '=', 'sp_payment.payment_config_id' )
            ->leftJoin( 'sp_payment_details', 'sp_payment_details.sp_payment_id', '=', 'sp_payment.id' )
            ->where( 'sp_payment.app_id', $app_id )
            ->where( 'sp_payment.process_type_id', $process_type_id )
            ->groupBy( 'sp_payment.id' )
            ->get( [
                'sp_payment.*',
             //    'sp_payment.id',
                'sp_payment_configuration.payment_name',
             //    'sp_payment.contact_name',
             //    'sp_payment.contact_email',
             //    'sp_payment.contact_no',
             //    'sp_payment.address',
             //    'sp_payment.pay_amount',
             //    'sp_payment.vat_on_pay_amount',
             //    'sp_payment.transaction_charge_amount',
             //    'sp_payment.vat_on_transaction_charge',
             //    'sp_payment.total_amount',
             //    'sp_payment.payment_status',
             //    'sp_payment.pay_mode',
             //    'sp_payment.pay_mode_code',
                DB::raw( 'GROUP_CONCAT(CONCAT_WS(", ", sp_payment_details.receiver_ac_no, sp_payment_details.distribution_type, sp_payment_details.pay_amount) SEPARATOR ";") as payment_details' )
            ] );

            foreach ($data['payment_info'] as $key => $payment_info) {
                $data['payment_info'][$key]['multiple_pay_order_info'] = PayOrderInfo::where('pay_order_info_id', $payment_info->id)->get();
                foreach ($data['payment_info'][$key]['multiple_pay_order_info'] as $pay_info) {
                    $pay_info->bank_name = Bank::where('id', $pay_info->bank_id)->value('name');
                    $pay_info->branch_name = BankBranch::where('id', $pay_info->branch_id)->value('branch_name');
                }
            }

        }
        // $pay_order          = SonaliPayment::where( [
        //     'app_id'          => $app_id,
        //     'process_type_id' => $process_type_id
        // ] )->first();

        // if ($pay_order->payment_type === 'pay_order') {
        //     $data['payment_info'] = SonaliPayment::where( [
        //         'app_id'          => $app_id,
        //         'process_type_id' => $process_type_id
        //     ] )->get()->toArray();
        //     foreach ( $data['payment_info'] as $key => $payment ) {
        //         $data['payment_info'][ $key ]['bank_name']               = DB::table( 'bank' )->where( [ 'id' => $payment['bank_id'] ] )->value( 'name' );
        //         $data['payment_info'][ $key ]['branch_name']             = DB::table( 'bank_branches' )->where( [ 'id' => $payment['branch_id'] ] )->value( 'branch_name' );
        //         $data['payment_info'][ $key ]['bg_bank_name']            = DB::table( 'bank' )->where( [ 'id' => $payment['bg_bank_id'] ] )->value( 'name' );
        //         $data['payment_info'][ $key ]['bg_branch_name']          = DB::table( 'bank_branches' )->where( [ 'id' => $payment['bg_branch_id'] ] )->value( 'branch_name' );
        //         $data['payment_info'][ $key ]['bg_expire_formated_date'] = date_format( date_create( $payment['bg_expire_date'] ), 'd-m-Y' );
        //         $data['payment_info'][ $key ]['pay_order_formated_date'] = date_format( date_create( $payment['pay_order_date'] ), 'd-m-Y' );
        //     }
        //     $data['payment_info'] = json_decode( json_encode( $data['payment_info'] ) );
        //     $content              = strval( view( 'SonaliPayment::payment-ui.view-paymentV2', $data ) );
        // } else {

            $data['payment_distribution_types'] = PaymentDistributionType::pluck( 'name', 'id' )->toArray();

            foreach ($data['payment_info'] as $payment_data) {
                $payment_data->bg_bank_name = Area::where('area_id', $payment_data->bg_bank_id)->value('area_nm');
                $payment_data->bg_branch_name = Area::where('area_id', $payment_data->bg_branch_id)->value('area_nm');
            }



            $content                            = strval( view( 'SonaliPayment::payment-ui.view-payment', $data ) );
        // }

        return response()->json( [ 'response' => $content ] );
    }

    public function getViewPaymentPanelVue( $process_type_id, $app_id ) {
        $process_type_id      = Encryption::decodeId( $process_type_id );
        $app_id               = Encryption::decodeId( $app_id );
        $data['app_status']   = ProcessList::where( [
            'process_type_id' => $process_type_id,
            'ref_id'          => $app_id,
        ] )->value( 'status_id' );
        $data['payment_info'] = SonaliPayment::leftJoin( 'sp_payment_configuration', 'sp_payment_configuration.id', '=', 'sp_payment.payment_config_id' )
                                             ->leftJoin( 'sp_payment_details', 'sp_payment_details.sp_payment_id', '=', 'sp_payment.id' )
                                             ->where( 'sp_payment.app_id', $app_id )
                                             ->where( 'sp_payment.process_type_id', $process_type_id )
                                             ->groupBy( 'sp_payment.id' )
                                             ->get( [
                                                 'sp_payment.id',
                                                 'sp_payment_configuration.payment_name',
                                                 'sp_payment.contact_name',
                                                 'sp_payment.contact_email',
                                                 'sp_payment.contact_no',
                                                 'sp_payment.address',
                                                 'sp_payment.pay_amount',
                                                 'sp_payment.vat_on_pay_amount',
                                                 'sp_payment.transaction_charge_amount',
                                                 'sp_payment.vat_on_transaction_charge',
                                                 'sp_payment.total_amount',
                                                 'sp_payment.payment_status',
                                                 'sp_payment.pay_mode',
                                                 'sp_payment.pay_mode_code',
                                                 DB::raw( 'GROUP_CONCAT(CONCAT_WS(", ", sp_payment_details.receiver_ac_no, sp_payment_details.distribution_type, sp_payment_details.pay_amount) SEPARATOR ";") as payment_details' )
                                             ] );

        foreach ( $data['payment_info'] as $payment ) {
            $payment->voucher_url = url( '/spg/' . ( $payment->pay_mode_code == 'A01' ? 'counter-' : '' ) . 'payment-voucher/' . Encryption::encodeId( $payment->id ) );

            if ( $payment->payment_status == 3 && $data['app_status'] == 3 && in_array(
                    Auth::user()->user_type,
                    [ '5x505', '6x606' ]
                ) ) {
                $payment->counter_payment_cancel_url  = url( '/spg/counter-payment-check/' . Encryption::encodeId( $payment->id ) . '/' . Encryption::encodeId( 0 ) );
                $payment->counter_payment_confirm_url = url( '/spg/counter-payment-check/' . Encryption::encodeId( $payment->id ) . '/' . Encryption::encodeId( 1 ) );
            } else {
                $payment->counter_payment_cancel_url  = '';
                $payment->counter_payment_confirm_url = '';
            }
        }

        $data['payment_distribution_types'] = PaymentDistributionType::pluck( 'name', 'id' )->toArray();

        return response()->json( $data );
    }


    public function submitPayment( Request $request ) {
        try {
            DB::beginTransaction();

            $app_id          = Encryption::decodeId( $request->get( 'encoded_app_id' ) );
            $process_type_id = Encryption::decodeId( $request->get( 'encoded_process_type_id' ) );
            $payment_step_id = Encryption::decodeId( $request->get( 'encoded_payment_step_id' ) );
//            dd($payment_step_id, $process_type_id, $app_id);
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0, // Govt-Vendor-Vat-Fee
                7 => 0, // Govt-Annual-Fee
                8 => 0, // Govt-Delay-Fee
                9 => 0, // Govt-Annual-Vat-Fee
                10 => 0 // Govt-Delay-Vat-Fee
            ];

            switch ( $process_type_id ) {
                // ISP License issue
                case 1:
                    $license_type_id = ISPLicenseIssue::where( 'id', $app_id )->value( 'isp_license_type' );
                    $isp_controller  = new ISPLicenseIssue();
                    if ( $payment_step_id == 2 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 3 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 4 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 5 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 6 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 7 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    }
                    break;

                // ISP License Renew
                case 2:
                    $license_type_id = ISPLicenseRenew::where( 'id', $app_id )->value( 'isp_license_type' );
                    $isp_controller  = new ISPLicenseRenew();
                    if ( $payment_step_id == 2 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 3 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 4 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 5 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 6 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 7 ) {
                        $unfixed_amount_array = $isp_controller->unfixedAmountsForGovtServiceFee($license_type_id, $payment_step_id, $app_id, $process_type_id);
                    }
                    break;

                // IPTSP License Issue
                case 21:
                    $iptsp_app = IPTSPLicenseIssue::where( 'id', $app_id )->select( ['isptspli_type','isptspli_area_div'] )->first();
                    $license_type_id = $iptsp_app->isptspli_type;
                    $division_id = $iptsp_app->isptspli_area_div;
                    $iptsp_controller  = new IPTSPLicenseIssue();
                    if ( $payment_step_id == 2 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 3 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 4 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 5 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 6 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 7 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 8 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 9 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 10 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 11 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 12 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 13 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 14 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 15 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    } elseif ( $payment_step_id == 16 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id, $division_id);
                    }
                    break;
                case 22:
                    $license_type_id = IPTSPLicenseRenew::where( 'id', $app_id )->value( 'isptspli_type' );
                    $iptsp_controller  = new IPTSPLicenseRenew();
                    if ( $payment_step_id == 2 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 3 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 4 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 5 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 6 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 7 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 8 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 9 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 10 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 11 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 12 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 13 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 14 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 15 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    } elseif ( $payment_step_id == 16 ) {
                        $unfixed_amount_array = $iptsp_controller->unfixedAmountsForGovtServiceFee( $license_type_id, $payment_step_id, $app_id, $process_type_id);
                    }
                    break;
            }

            $contact_info = [
                'contact_name'    => $request->get( 'contact_name' ),
                'contact_email'   => $request->get( 'contact_email' ),
                'contact_no'      => $request->get( 'contact_no' ),
                'contact_address' => $request->get( 'contact_address' ),
            ];

            if(!empty($request->get('annual_or_bg')) && $request->get('annual_or_bg') =='bg_fee'){
                $this->storeBankGuaranteeInfo($app_id, $payment_step_id,$process_type_id,$request);
            }else{
                $this->process_type_id = $process_type_id;
                $check_payment_type    = ( ! empty( $request->get( 'payment_type' ) ) && $request->get( 'payment_type' ) === 'pay_order' );

                $payment_id            = ! $check_payment_type ?
                    $this->storeSubmissionFeeData( $app_id, $payment_step_id, $contact_info, $unfixed_amount_array, $request ) :
                    $this->storeSubmissionFeeDataV2( $app_id, $payment_step_id, $contact_info, $unfixed_amount_array, $request );

            }
            DB::commit();
            if($app_id){
                $processData = ProcessList::where('ref_id',$app_id)->where('process_type_id',$process_type_id)->first();
                CommonFunction::DNothiRequest($processData->id,'payment');
            }
            if(!empty($request->get('annual_or_bg')) && $request->get('annual_or_bg') =='bg_fee') {
                $form_url = ProcessType::find($process_type_id)->form_url;
                return redirect("client/$form_url/list/" . Encryption::encodeId($process_type_id));
            }else{
                if ($request->get('payment_type') !== 'pay_order') {
                    return SonaliPaymentController::RedirectToPaymentPortal(Encryption::encodeId($payment_id));
                } else {
                    Session::flash('success', 'Payment Submitted Successfully');
                    $form_url = ProcessType::find($this->process_type_id)->form_url;

                    return redirect("client/$form_url/list/" . Encryption::encodeId($this->process_type_id));
                }
            }
        } catch ( \Exception $e ) {
            DB::rollback();
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash( 'error', "Sorry something went wrong [PPC-001]" );

            return redirect()->back()->withInput();
        }
    }
}
