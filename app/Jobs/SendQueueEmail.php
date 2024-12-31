<?php

namespace App\Jobs;

use App\Libraries\NotificationWebService;
use App\Modules\Settings\Models\EmailQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendQueueEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $listOfData;

    public function __construct($listOfData)
    {
        $this->listOfData = $listOfData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        $arr = [
//            $this->listOfData
//        ];
//        Log::info('Log Data', $arr);

        $NotificationWebService   = new NotificationWebService();
        if(!empty($this->listOfData)) {
            foreach($this->listOfData as $each) {
                $emailq = new EmailQueue();

                $email_queue = $each['email_queue'];
                $email_sending_response       = $NotificationWebService->sendEmail($each['email_content']);

                $emailq->email_response = $email_sending_response['msg'] ?? null;
                if ($email_sending_response['status'] === 1) {
                    $emailq->email_status = 1;
                    $emailq->email_response_id = $email_sending_response['message_id'] ?? null;
                }
                $emailq->process_type_id = $email_queue['process_type_id'] ?? null;
                $emailq->app_id = $email_queue['app_id'] ?? null;
                $emailq->status_id = $email_queue['status_id'] ?? null;
                $emailq->email_content = $email_queue['email_content'] ?? null;
                $emailq->email_to = $email_queue['email_to'] ?? null;
                $emailq->email_cc = $email_queue['email_cc'] ?? null;
                $emailq->email_subject = $email_queue['email_subject'] ?? null;
                $emailq->sms_content = $email_queue['sms_content'] ?? null;
                $emailq->sms_to = $email_queue['sms_to'] ?? null;
                $emailq->attachment = $email_queue['attachment'] ?? null;
                $emailq->attachment_certificate_name = $email_queue['attachment_certificate_name'] ?? null;
                $emailq->created_at = $email_queue['created_at'] ?? null;
                $emailq->updated_at = $email_queue['updated_at'] ?? null;

                $emailq->save();
            }

        }
    }

}
