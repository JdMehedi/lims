<?php

namespace App\Modules\SonaliPayment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\SonaliPayment\Library\SonaliPaymentACL;
use App\Modules\SonaliPayment\Models\PaymentCategory;
use App\Modules\SonaliPayment\Models\PaymentStep;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\PaymentDistributionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Session;

class PaymentConfigController extends Controller
{
    public function paymentConfiguration()
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        return view('SonaliPayment::PaymentConfig.list');
    }

    public function getPaymentConfiguration()
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        DB::statement(DB::raw('set @rownum=0'));
        $details = PaymentConfiguration::leftJoin('process_type', 'process_type.id', '=', 'sp_payment_configuration.process_type_id')
            ->leftJoin('sp_payment_steps', 'sp_payment_steps.id', '=', 'sp_payment_configuration.payment_step_id')
            ->where('sp_payment_configuration.is_archive', 0)
            ->orderBy('sp_payment_configuration.id', 'desc')->get([
                'sp_payment_configuration.id',
                'process_type.name as process_type_name',
                'process_type.drop_down_label',
                'sp_payment_configuration.payment_name',
                'sp_payment_steps.name as payment_step',
                'sp_payment_configuration.amount',
                'sp_payment_configuration.vat_tax_percent',
                'sp_payment_configuration.trans_charge_percent',
                'sp_payment_configuration.trans_charge_max_amount',
                'sp_payment_configuration.trans_charge_min_amount',
                'sp_payment_configuration.status',
                DB::raw('@rownum  := @rownum  + 1 AS sl_no')
            ]);

        return Datatables::of($details)
            ->addColumn('action', function ($details) {
                return '<a href="' . url('/spg/edit-payment-configuration/' . Encryption::encodeId($details->id)) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>';
            })
            ->editColumn('status', function ($details) {
                if ($details->status == 1) {
                    return '<span class="text-success"><b>Active</b></span>';
                } else {
                    return '<span class="text-danger"><b>Inactive</b></span>';
                }
            })
            ->removeColumn('id')
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function paymentConfigurationCreate()
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $processTypes = ['' => 'Select One'] + ProcessType::pluck('drop_down_label', 'id')->all();
        // $paymentCategories = ['' => 'Select One'] + PaymentCategory::where('status', 1)->pluck('name', 'id')->all();
        $paymentCategories = ['' => 'Select One'];
        $paymentSteps = ['' => 'Select One'] + PaymentStep::where('status', 1)->pluck('name', 'id')->all();
        return view('SonaliPayment::PaymentConfig.create', compact('processTypes', 'paymentCategories', 'paymentSteps'));
    }

    public function paymentConfigurationStore(Request $request)
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'process_type_id' => 'required',
            // 'payment_category_id' => 'required',
            'payment_step_id' => 'required',
            'payment_name' => 'required',
            'amount' => 'required|numeric',
            // 'vat_tax_percent' => 'numeric',
            // 'trans_charge_percent' => 'numeric',
            // 'trans_charge_min_amount' => 'numeric',
            // 'trans_charge_max_amount' => 'numeric',
            'status' => 'required'
        ]);

        try {
            // check duplicate value with same process type and same payment category
            // $existInfo = PaymentConfiguration::where([
            //     'process_type_id' => trim($request->get('process_type_id')),
            //     'payment_category_id' => trim($request->get('payment_category_id'))
            // ])->where('is_archive', 0)->count();

            $existInfo = PaymentConfiguration::where([
                'process_type_id' => trim($request->get('process_type_id')),
                'payment_step_id' => trim($request->get('payment_step_id'))
            ])->where('is_archive', 0)->count();


            if ($existInfo > 0) {
                Session::flash('error', 'Duplicate value with same process type and payment Step!');
                return \redirect()->back()->withInput();
            }
            PaymentConfiguration::create(
                array(
                    'process_type_id' => $request->get('process_type_id'),
                    // 'payment_category_id' => $request->get('payment_category_id'),
                    'payment_step_id' => $request->get('payment_step_id'),
                    'payment_name' => $request->get('payment_name'),
                    'amount' => $request->get('amount'),
                    // 'vat_tax_percent' => $request->get('vat_tax_percent'),
                    // 'trans_charge_percent' => $request->get('trans_charge_percent'),
                    // 'trans_charge_min_amount' => $request->get('trans_charge_min_amount'),
                    // 'trans_charge_max_amount' => $request->get('trans_charge_max_amount'),
                    //'status' => $request->get('status'),
                    'status' => 0, // default 0 because need to configuration payment distribution
                    'created_by' => getCurrentUserId()
                )
            );

            Session::flash('success', 'Data is stored successfully!');
            return \redirect('spg/payment-configuration')->withInput();
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong. [PCC-2545]');
            return Redirect::back()->withInput();
        }
    }

    public function editPaymentConfiguration($encrypted_id)
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $id = Encryption::decodeId($encrypted_id);
        $processTypes = ProcessType::pluck('name', 'id');
        $data = PaymentConfiguration::where('id', $id)->first();
        $paymentSteps = ['' => 'Select One'] + PaymentStep::where('status', 1)->pluck('name', 'id')->all();

        return view("SonaliPayment::PaymentConfig.edit", compact('data', 'encrypted_id', 'processTypes', 'paymentSteps'));
    }

    public function updatePaymentConfiguration(Request $request, $id)
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {
            $id = Encryption::decodeId($id);

            // check duplicate value with same process type and same payment category but not current item
            // $existInfo = PaymentConfiguration::where([
            //     'process_type_id' => trim($request->get('process_type_id')),
            //     'payment_category_id' => trim($request->get('payment_category_id'))
            // ])->where('id', '!=', $id)
            //     ->where('is_archive', 0)
            //     ->count();

            $existInfo = PaymentConfiguration::where([
                'process_type_id' => trim($request->get('process_type_id')),
                'payment_step_id' => trim($request->get('payment_step_id'))
            ])->where('id', '!=', $id)
                ->where('is_archive', 0)
                ->count();

            if ($existInfo > 0) {
                Session::flash('error', 'Duplicate configuration with same process type and payment Step!');
                return \redirect()->back()->withInput();
            }

            //Stakeholder amount distribution check
            $stakeholder_distribution = PaymentDistribution::where('sp_pay_config_id', $id)
                ->where('status', 1)
                ->where('is_archive', 0)
                ->select(DB::raw('sum(pay_amount) as sum, process_type_id'))
                ->get();

            $stakeholder_amount = (!empty($stakeholder_distribution)) ? $stakeholder_distribution[0]->sum : 0;
            if (($request->get('status') == 1) && ($stakeholder_amount < $request->get('amount'))) {
                Session::flash('error', 'The amount of money in the configuration and the total amount of money of all stakeholders are not equal.');
                return \redirect()->back();
            }

            //Stakeholder amount distribution check end

            $this->validate($request, [
                'process_type_id' => 'required',
                // 'payment_category_id' => 'required',
                'payment_step_id' => 'required',
                'payment_name' => 'required',
                'amount' => 'required|numeric',
                // 'vat_tax_percent' => 'numeric',
                // 'trans_charge_percent' => 'numeric',
                // 'trans_charge_min_amount' => 'numeric',
                // 'trans_charge_max_amount' => 'numeric',
                'status' => 'required'
            ]);

            PaymentConfiguration::where('id', $id)->update([
                'process_type_id' => $request->get('process_type_id'),
                // 'payment_category_id' => $request->get('payment_category_id'),
                'payment_step_id' => $request->get('payment_step_id'),
                'payment_name' => $request->get('payment_name'),
                'amount' => $request->get('amount'),
                // 'vat_tax_percent' => $request->get('vat_tax_percent'),
                // 'trans_charge_percent' => $request->get('trans_charge_percent'),
                // 'trans_charge_min_amount' => $request->get('trans_charge_min_amount'),
                // 'trans_charge_max_amount' => $request->get('trans_charge_max_amount'),
                //'status' => $request->get('status'),
                'status' => $request->get('status'),
                'updated_by' => getCurrentUserId()
            ]);

            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/spg/payment-configuration');
        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Sorry! Something Wrong. [PCC-2546]');
            return Redirect::back()->withInput();
        }
    }

    public function getPaymentDistributionData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = SonaliPaymentACL::getAccessRight('SonaliPayment', 'V');
        $decodedPayConfigId = Encryption::decodeId($request->get('pay_config_id'));

        DB::statement(DB::raw('set @rownum=0'));
        $data = PaymentDistribution::leftJoin('sp_payment_distribution_type', 'sp_payment_distribution_type.id', '=', 'sp_payment_distribution.distribution_type')
            ->where('sp_payment_distribution.is_archive', 0)
            ->where('sp_payment_distribution.sp_pay_config_id', $decodedPayConfigId)
            ->orderBy('sp_payment_distribution.created_at', 'desc')
            ->get([
                DB::raw('@rownum := @rownum+1 AS sl'),
                'sp_payment_distribution.id',
                'sp_payment_distribution.stakeholder_ac_name',
                'sp_payment_distribution.stakeholder_ac_no as account_no',
                'sp_payment_distribution.purpose_sbl',
                'sp_payment_distribution.purpose',
                'sp_payment_distribution.pay_amount as amount',
                'sp_payment_distribution.fix_status',
                'sp_payment_distribution.status',
                'sp_payment_distribution_type.name as distribution_type_name'
            ]);

        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    return "<a class='subSectorEditBtn btn btn-xs btn-info' data-toggle='modal' data-target='#myModal' onclick='openModal(this)' data-action='" . url('/spg/stakeholder-distribution-edit/' . Encryption::encodeId($data->id)) . "'><i class='fa fa-edit'></i> Edit</a> ";
                }
            })
            ->editColumn('fix_status', function ($data) {
                if ($data->fix_status == 1) {
                    return "<label class='btn btn-xs btn-success'>Fixed</label>";
                } else {
                    return "<label class='btn btn-xs btn-danger'>Unfixed</label>";
                }
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    return "<label class='btn btn-xs btn-success'>Active</label>";
                } else {
                    return "<label class='btn btn-xs btn-danger'>Inactive</label>";
                }
            })
            ->rawColumns(['fix_status', 'status', 'action'])
            ->make(true);
    }

    public function stakeholderDistribution($payConfigID)
    {
        $decodedConfigId = Encryption::decodeId($payConfigID);
        $paymentConfig = PaymentConfiguration::find($decodedConfigId);
        $distribution_types = PaymentDistributionType::where('status', 1)
            ->where('is_archive', 0)
            ->pluck('name', 'id')
            ->toArray();
        return view('SonaliPayment::PaymentConfig.stakeholder-distribution', compact('paymentConfig', 'distribution_types'));
    }

    public function stakeholderDistributionStore(Request $request, $distributionId = '')
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', 'A')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Please contact system administration for more information',
            ]);
        }

        $rules = [
            'stakeholder_ac_name' => 'required',
            'stakeholder_ac_no' => 'required',
            'purpose_sbl' => 'required',
            'status' => 'required',
            'fix_status' => 'required',
            'pay_amount' => 'required_if:fix_status,1',
            'distribution_type' => 'required',
        ];
        $messages = [
            'pay_amount.required_if' => 'The pay amount field is required when fix status is Fixed.',
            'distribution_type.required' => 'The distribution type is required.'
        ];

        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }

        try {
            $decodedDistributionId = '';
            if ($distributionId) {
                $decodedDistributionId = Encryption::decodeId($distributionId);
            }

            $decode_pay_config_id = Encryption::decodeId($request->get('pay_config_id'));
            $pay_amount = $request->get('pay_amount');
            if ($request->get('fix_status') == 0) {
                $pay_amount = 0;
            }

            /*
             * One Payment Configuration can not have more than one unfixed distribution
             */
            //            if ($request->get('fix_status') == 0) {
            //                $pay_amount = 0;
            //                $unfixedStakeholders = PaymentDistribution::where('sp_pay_config_id', $decode_pay_config_id)
            //                    ->where('id', '!=', $decodedDistributionId)
            //                    ->where('fix_status', 0)
            //                    ->where('status', 1)
            //                    ->where('is_archive', 0)
            //                    ->count();
            //                if ($unfixedStakeholders > 0) {
            //                    return response()->json([
            //                        'error' => true,
            //                        'status' => 'There should be only one unfixed stakeholder under one payment configuration.',
            //                    ]);
            //                }
            //            }

            /*
             * One Payment Configuration can not have duplicate distribution type
             */
            $count_distribution_data = PaymentDistribution::where('sp_pay_config_id', $decode_pay_config_id)
                ->where('id', '!=', $decodedDistributionId)
                ->where('distribution_type', $request->get('distribution_type'))
                ->where('status', 1)
                ->where('is_archive', 0)
                ->count();
            if ($count_distribution_data > 0) {
                return response()->json([
                    'error' => true,
                    'status' => 'Sorry! Duplicate distribution type is not allowed.',
                ]);
            }


            //            if ($request->get('fix_status') == 0) {
            //                $pay_amount = 0;
            //                $count_distribution_data = PaymentDistribution::where('sp_pay_config_id', $decode_pay_config_id)
            //                    ->where('id', '!=', $decodedDistributionId)
            //                    ->where('fix_status', 0)
            //                    ->where('distribution_type', $request->get('distribution_type'))
            //                    ->where('status', 1)
            //                    ->where('is_archive', 0)
            //                    ->count();
            //                if ($count_distribution_data > 0) {
            //                    return response()->json([
            //                        'error' => true,
            //                        'status' => 'There should be only one unfixed distribution by type under one payment configuration.',
            //                    ]);
            //                }
            //            }


            $pay_config = PaymentConfiguration::find($decode_pay_config_id);

            /*
             * A total amount of all distributors of a configuration can not exceed
             * the total amount of configuration
             * $stakeholder_previous = total amount of all active distributors without current distributor id
             */
            $stakeholder_previous = PaymentDistribution::where('sp_pay_config_id', $decode_pay_config_id)
                ->where('status', 1)
                ->where('id', '!=', $decodedDistributionId)
                ->where('is_archive', 0)
                ->select(DB::raw('sum(pay_amount) as sum'))
                ->get();

            $stakeholder_previous_amount = (!empty($stakeholder_previous)) ? $stakeholder_previous[0]->sum : 0;
            // $total_stakeholder_amount = $stakeholder_previous + amount of current distributor
            $total_stakeholder_amount = $stakeholder_previous_amount + $pay_amount;
            if ($total_stakeholder_amount > $pay_config->amount) {
                return response()->json([
                    'error' => true,
                    'status' => 'Total stakeholder amount will not be greater then configuration pay amount.',
                ]);
            }

            DB::beginTransaction();

            $ac_no = trim($request->get('stakeholder_ac_no'));

            /*
             * Check duplicate stakeholder by same account no, if exists than rollback
             */
            //            $checkExistingAccount = PaymentDistribution::where('stakeholder_ac_no', $ac_no)
            //                ->where('sp_pay_config_id', $decode_pay_config_id)
            //                ->where('id', '!=', $decodedDistributionId)
            //                ->where('is_archive', 0)
            //                ->count();
            //            if($checkExistingAccount > 0){
            //                return response()->json([
            //                    'error' => true,
            //                    'status' => 'Stakeholder already exist with same account number !',
            //                ]);
            //            }

            $distribution = PaymentDistribution::findOrNew($decodedDistributionId);
            $distribution->process_type_id = $pay_config->process_type_id;
            $distribution->sp_pay_config_id = $decode_pay_config_id;
            $distribution->stakeholder_ac_name = $request->get('stakeholder_ac_name');
            $distribution->stakeholder_ac_no = $ac_no;
            $distribution->purpose = $request->get('purpose');
            $distribution->purpose_sbl = $request->get('purpose_sbl');
            $distribution->pay_amount = $pay_amount;
            $distribution->distribution_type = $request->get('distribution_type');
            $distribution->fix_status = $request->get('fix_status');
            $distribution->status = $request->get('status');
            $distribution->save();

            /* Business Logic
             * 1st condition
             * if (Current distributors status is inactive)
             * and (Total amount of distributors without current distributor) is less than(Total amount of payment configuration) then,
             * inactive the current payment configuration.
             *
             * 2nd condition
             * if (Total amount of all active distributors with current distributor id)
             * is less than (Total amount of payment configuration) then,
             * inactive the current payment configuration.
             */
            //$edited_total = $total_stakeholder_amount - $distribution->pay_amount;
            if (($distribution->status == 0 && $stakeholder_previous_amount < $pay_config->amount) || $total_stakeholder_amount < $pay_config->amount) {
                PaymentConfiguration::where('id', $decode_pay_config_id)->update(['status' => 0]);
                DB::commit();

                return response()->json([
                    'success' => true,
                    'status' => 'Data has been saved successfully & Payment config got inactive.',
                    'link' => '/spg/edit-payment-configuration/' . Encryption::encodeId($decode_pay_config_id)
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => 'Data has been saved successfully.',
                'link' => '/spg/edit-payment-configuration/' . Encryption::encodeId($decode_pay_config_id)
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'status' => $e->getMessage() . " <br>" . $e->getLine()
            ]);
        }
    }

    public function editStakeholderDistribution($distributionId)
    {
        $decodedDistId = Encryption::decodeId($distributionId);
        $stakeholderDistribution = PaymentDistribution::find($decodedDistId);
        $distribution_types = PaymentDistributionType::where('status', 1)
            ->where('is_archive', 0)
            ->pluck('name', 'id')
            ->toArray();
        return view('SonaliPayment::PaymentConfig.stakeholder-distribution-edit', compact('stakeholderDistribution', 'distribution_types'));
    }
}
