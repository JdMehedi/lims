<?php namespace App\Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use DB;

class VerifyDocController extends Controller
{

    public function verifyDoc($docId, $pdfType='')
    {
//        $pdfCertificate = PdfPrintRequestQueue::where('doc_id', $docId)->first();

        $pdfCertificate = collect(DB::select(DB::raw("SELECT pdf_print_requests_queue.certificate_link AS certificate_link,
       process_type.name_bn as process_name,process_type.group_name, submitted_at.process_id, submitted_at.company_id,
            applicant.user_first_name AS applicant_name, applicant.user_email AS applicant_email,
            submitted_at.hash_value AS applicant_hash, submitted_at.updated_at as applicant_time, submitted_at.json_object, applicant.user_mobile as applicant_phone,
            approver.user_first_name AS approver_name, approver.user_email AS approver_email,
            approved_at.hash_value AS approver_hash, approved_at.updated_at as approval_time, approver.user_mobile as approver_phone
            FROM pdf_print_requests_queue
            LEFT JOIN process_type on process_type.id = pdf_print_requests_queue.process_type_id
            LEFT JOIN process_list_hist as submitted_at on submitted_at.ref_id = pdf_print_requests_queue.app_id and submitted_at.process_type_id = pdf_print_requests_queue.process_type_id and submitted_at.status_id = 1
            LEFT JOIN process_list_hist as approved_at on approved_at.ref_id = pdf_print_requests_queue.app_id and approved_at.process_type_id = pdf_print_requests_queue.process_type_id and approved_at.status_id = 25
            LEFT JOIN users as applicant ON applicant.id = submitted_at.updated_by
            LEFT JOIN users as approver ON approver.id = pdf_print_requests_queue.signatory
            WHERE pdf_print_requests_queue.doc_id = '$docId'
            order by submitted_at.id desc limit 1")))->first();

        $printRequest = DB::table('pdf_print_requests_queue')
            ->where('doc_id', '=', $docId)
            ->first(['process_type_id', 'app_id']);

        $company = DB::table('process_list')
            ->where('ref_id', '=', $printRequest->app_id)
            ->where('process_type_id', '=', $printRequest->process_type_id)
            ->first(['company_id', 'tracking_no']);

        $pdfCertificate->company_name = DB::table('company_info')->where('id', '=',$pdfCertificate->company_id )->value('org_nm');

        $processType = DB::table('process_type')
            ->where('id', '=', $printRequest->process_type_id)
            ->value('acl_name');

        $masterTableName = implode('_', array_slice(explode('_', $processType), 0, 2)) . '_master';

        $IssueAndExpireDate = DB::table($masterTableName)
            ->where('company_id', '=', $company->company_id)
            ->where('issue_tracking_no', '=', $company->tracking_no)
            ->first(['license_issue_date', 'expiry_date']);


        return view('Web::view-certificate', compact(['pdfCertificate','IssueAndExpireDate']));
    }

//    public function verifyDoc1()
//    {
//        return view('Web::view-certificate');
//    }
}


