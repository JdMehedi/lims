<?php

namespace App\Modules\SonaliPayment\Services;

use App\Libraries\CommonFunction;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Support\Facades\Auth;
use App\Modules\Users\Models\Users;

trait OnlinePaymentPostProcessing
{
    private function applicationRelatedTasks($process_name, $process_info, $paymentInfo)
    {
        $applicantEmailPhone = CommonFunction::geCompanyUsersEmailPhone($process_info->company_id);

        $appInfo = [
            'app_id' => $process_info->ref_id,
            'status_id' => $process_info->status_id,
            'process_type_id' => $process_info->process_type_id,
            'tracking_no' => $process_info->tracking_no,
            'process_type_name' => $process_name,
            'remarks' => ''
        ];

        switch ($process_info->id) {
            case 1: // ISP license Issue
                if ($process_info->status_id === 1) {
                    CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
                } elseif ($process_info->status_id === 3) {
                    $process_list_data = ProcessList::find($process_info->id);
                    if ($paymentInfo->payment_step_id == 1) {
                        $process_list_data->update([
                            'status_id' => 1,
                            'desk_id' => 1
                        ]);
                    } elseif ($paymentInfo->payment_step_id == 2) {
                        $process_list_data->update([
                            'status_id' => 16,
                            'desk_id' => 6
                        ]);
                    } elseif ($paymentInfo->payment_step_id == 3) {
                        $process_list_data->update([
                            'status_id' => 50,
                            'desk_id' => 5
                        ]);
                    } elseif ($paymentInfo->payment_step_id == 4) {
                        $process_list_data->update([
                            'status_id' => 51,
                            'desk_id' => 5
                        ]);
                    } elseif ($paymentInfo->payment_step_id == 5) {
                        $process_list_data->update([
                            'status_id' => 52,
                            'desk_id' => 5
                        ]);
                    } elseif ($paymentInfo->payment_step_id == 6) {
                        $process_list_data->update([
                            'status_id' => 53,
                            'desk_id' => 5
                        ]);
                    } elseif ($paymentInfo->payment_step_id == 7) {
                        $process_list_data->update([
                            'status_id' => 62,
                            'desk_id' => 5
                        ]);
                    }
                }
                break;
                // The functionality for new process type will go here
        }
    }
}
