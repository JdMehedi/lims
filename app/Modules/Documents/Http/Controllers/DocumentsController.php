<?php

namespace App\Modules\Documents\Http\Controllers;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\Documents\Models\DocumentsOfUserCompany;
use App\Modules\Settings\Models\DocumentsOfService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class DocumentsController extends Controller
{

    /**
     * @param int $process_type_id
     * @param int $doc_type_id
     * @param int $app_id
     * @param $request
     */
    public static function storeAppDocuments(int $process_type_id, $doc_type_id, int $app_id, $request)
    {
        $doc_row = DocumentsOfService::leftJoin('doc_name', 'doc_name.id', '=', 'doc_list_for_service.doc_id')
            ->where([
                'doc_list_for_service.status' => 1,
                'doc_list_for_service.process_type_id' => $process_type_id,
//                'doc_list_for_service.doc_type_for_service_id' => $doc_type_id
            ])->orderBy('doc_list_for_service.order')
            ->get([
                'doc_name.id as doc_name_id',
                'doc_list_for_service.id as doc_list_for_service_id',
                'doc_name.name as doc_name',
                'doc_list_for_service.is_required',
                'doc_list_for_service.autosuggest_status'
            ]);
        $documentsArr = [];
        if (count($doc_row) > 0) {
            foreach ($doc_row as $docs) {
                $app_doc = ApplicationDocuments::firstOrNew([
                    'process_type_id' => $process_type_id,
                    'ref_id' => $app_id,
                    'doc_list_for_service_id' => $docs->doc_list_for_service_id
                ]);
                $app_doc->doc_name = $docs->doc_name;
                $app_doc->uploaded_path = $request->get('validate_field_' . $docs->doc_list_for_service_id);
                $app_doc->save();


                // Update the User document if this attachment is new uploaded
                if ($docs->autosuggest_status == 1) {
                    $user_doc = DocumentsOfUserCompany::firstOrNew([
                        'user_id' => Auth::user()->id,
                        'company_id' => CommonFunction::getUserCompanyWithZero(),
                        'doc_id' => $docs->doc_name_id
                    ]);
                    if (!empty($app_doc->uploaded_path) && $app_doc->uploaded_path != $user_doc->uploaded_path) {
                        $user_doc->uploaded_path = $app_doc->uploaded_path;
                        $user_doc->save();
                    }
                }

                $convertedDoc = $app_doc->toArray();
                unset($convertedDoc['uploaded_path'], $convertedDoc['updated_at'], $convertedDoc['created_at'], $convertedDoc['created_by'], $convertedDoc['updated_by']);
                $documentsArr[] = $convertedDoc;
            }
            return $documentsArr;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAppDocuments(Request $request)
    {
        // Check AJAX req
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $decoded_process_type_id = Encryption::decodeId($request->encoded_process_type_id);
        $doc_type_id = $request->doc_type_key;
        $viewMode = $data['viewMode'] = $request->view_mode;
        $openMode = $request->openMode;
        $user_id = 0;
        $company_id = 0;
        if (in_array($openMode, ['add', 'edit'])) {
            $user_id = CommonFunction::getUserId();
            $company_id = CommonFunction::getUserCompanyWithZero();
        }

//        $doc_type_id = 0;
//        if ($doc_type_key) {
//            $doc_type_id = DocumentTypes::where('key', $doc_type_key)->value('id');
//        }

        $organization = CompanyInfo::where('id',$company_id)->first();
        if ($organization->org_type != 1){
            if ($openMode === 'add') {

                $data['document'] = DocumentsOfService::leftJoin('doc_name', 'doc_name.id', '=', 'doc_list_for_service.doc_id')
                    ->whereIn('doc_name.company_doc_type', [1,3])
                    ->where([
                        'doc_list_for_service.status' => 1,
                        'doc_list_for_service.process_type_id' => $decoded_process_type_id,
//                    'doc_list_for_service.doc_type_for_service_id' => $doc_type_id
                    ])->orderBy('doc_list_for_service.order', 'ASC')
                    ->get([
                        'doc_name.id as doc_name_id',
                        'doc_name.max_size as doc_max_size',
                        'doc_name.min_size as doc_min_size',
                        'doc_name.simple_file',
                        'doc_list_for_service.id as doc_list_for_service_id',
                        'doc_name.name as doc_name',
                        'doc_name.company_doc_type as company_doc_type',
                        'doc_list_for_service.is_required',
                        'doc_list_for_service.autosuggest_status'
                    ]);

                $this->addSuggestedDocs($user_id, $company_id, $data['document']);
            } elseif ($openMode === 'edit') {

                $decoded_app_id = Encryption::decodeId($request->encoded_app_id);
                $data['document'] = DocumentsOfService::leftJoin('doc_of_applications', function ($on) use ($decoded_app_id) {
                    $on->on('doc_of_applications.doc_list_for_service_id', '=', 'doc_list_for_service.id')
                        ->where('doc_of_applications.ref_id', '=', $decoded_app_id);
                })
                    ->leftJoin('doc_name', 'doc_name.id', '=', 'doc_list_for_service.doc_id')
                    ->whereIn('doc_name.company_doc_type', [1, 3])
                    ->where([
                        'doc_list_for_service.status' => 1,
//                        'doc_list_for_service.doc_type_for_service_id' => $doc_type_id,
                        'doc_list_for_service.process_type_id' => $decoded_process_type_id
                    ])->orderBy('doc_list_for_service.order', 'ASC')
                    ->get([
                        'doc_list_for_service.id as doc_list_for_service_id',
                        'doc_name.name as doc_name',
                        'doc_name.company_doc_type as company_doc_type',
                        'doc_name.id as doc_name_id',
                        'doc_name.max_size as doc_max_size',
                        'doc_name.min_size as doc_min_size',
                        'doc_name.simple_file',
                        'doc_list_for_service.is_required',
                        'doc_list_for_service.autosuggest_status',
                        'doc_of_applications.uploaded_path',
                    ]);
                $this->addSuggestedDocs($user_id, $company_id, $data['document']);
            } else {

                //TODO get document only from doc_of_applications table for view page
                $decoded_app_id = Encryption::decodeId($request->encoded_app_id);
                $data['document'] = DocumentsOfService::leftJoin('doc_of_applications', function ($on) use ($decoded_app_id) {
                    $on->on('doc_of_applications.doc_list_for_service_id', '=', 'doc_list_for_service.id')
                        ->where('doc_of_applications.ref_id', '=', $decoded_app_id);
                })
                    ->where([
                        'doc_list_for_service.doc_type_for_service_id' => $doc_type_id,
                        'doc_list_for_service.process_type_id' => $decoded_process_type_id
                    ])
                    ->get([
                        'doc_list_for_service.id as doc_list_for_service_id',
                        'doc_of_applications.doc_name',
                        'doc_list_for_service.is_required',
                        'doc_of_applications.uploaded_path',
                    ]);
            }
        } else{
            if ($openMode === 'add') {

                $data['document'] = DocumentsOfService::leftJoin('doc_name', 'doc_name.id', '=', 'doc_list_for_service.doc_id')
                    ->whereIn('doc_name.company_doc_type', [2, 3])
                    ->where([
                        'doc_list_for_service.status' => 1,
                        'doc_list_for_service.process_type_id' => $decoded_process_type_id,
//                    'doc_list_for_service.doc_type_for_service_id' => $doc_type_id
                    ])->orderBy('doc_list_for_service.order', 'ASC')
                    ->get([
                        'doc_name.id as doc_name_id',
                        'doc_name.max_size as doc_max_size',
                        'doc_name.min_size as doc_min_size',
                        'doc_name.simple_file',
                        'doc_list_for_service.id as doc_list_for_service_id',
                        'doc_name.name as doc_name',
                        'doc_name.company_doc_type as company_doc_type',
                        'doc_list_for_service.is_required',
                        'doc_list_for_service.autosuggest_status'
                    ]);

                $this->addSuggestedDocs($user_id, $company_id, $data['document']);
            } elseif ($openMode === 'edit') {

                $decoded_app_id = Encryption::decodeId($request->encoded_app_id);
                $data['document'] = DocumentsOfService::leftJoin('doc_of_applications', function ($on) use ($decoded_app_id) {
                    $on->on('doc_of_applications.doc_list_for_service_id', '=', 'doc_list_for_service.id')
                        ->where('doc_of_applications.ref_id', '=', $decoded_app_id);
                })
                    ->leftJoin('doc_name', 'doc_name.id', '=', 'doc_list_for_service.doc_id')
                    ->whereIn('doc_name.company_doc_type', [2, 3])
                    ->where([
                        'doc_list_for_service.status' => 1,
//                        'doc_list_for_service.doc_type_for_service_id' => $doc_type_id,
                        'doc_list_for_service.process_type_id' => $decoded_process_type_id
                    ])->orderBy('doc_list_for_service.order','ASC')
                    ->get([
                        'doc_list_for_service.id as doc_list_for_service_id',
                        'doc_name.name as doc_name',
                        'doc_name.company_doc_type as company_doc_type',
                        'doc_name.id as doc_name_id',
                        'doc_name.max_size as doc_max_size',
                        'doc_name.min_size as doc_min_size',
                        'doc_name.simple_file',
                        'doc_list_for_service.is_required',
                        'doc_list_for_service.autosuggest_status',
                        'doc_of_applications.uploaded_path',
                    ]);
                $this->addSuggestedDocs($user_id, $company_id, $data['document']);
            } else {

                //TODO get document only from doc_of_applications table for view page
                $decoded_app_id = Encryption::decodeId($request->encoded_app_id);
                $data['document'] = DocumentsOfService::leftJoin('doc_of_applications', function ($on) use ($decoded_app_id) {
                    $on->on('doc_of_applications.doc_list_for_service_id', '=', 'doc_list_for_service.id')
                        ->where('doc_of_applications.ref_id', '=', $decoded_app_id);
                })
                    ->where([
                        'doc_list_for_service.doc_type_for_service_id' => $doc_type_id,
                        'doc_list_for_service.process_type_id' => $decoded_process_type_id
                    ])
                    ->get([
                        'doc_list_for_service.id as doc_list_for_service_id',
                        'doc_of_applications.doc_name',
                        'doc_list_for_service.is_required',
                        'doc_of_applications.uploaded_path',
                    ]);
            }
        }


        $html = strval(view("Documents::app-documents", $data));
        return response()->json(['html' => $html]);
    }

    /**
     * @return mixed
     */
    public function uploadDocument()
    {
        return View::make('Documents::ajaxUploadFile');
    }

    /**
     * @param $user_id
     * @param $company_id
     * @param $documents
     */
    private function addSuggestedDocs($user_id, $company_id, &$documents)
    {
        $suggested_docs = DocumentsOfUserCompany::where([
            'user_id' => $user_id,
            'company_id' => $company_id
        ])->pluck('uploaded_path', 'doc_id')->toArray();
        $documents->map(function ($doc) use ($suggested_docs) {
            if ($doc->autosuggest_status == 1
                && empty($doc->uploaded_path)) {
                $doc['uploaded_path'] = isset($suggested_docs[$doc->doc_name_id]) ? $suggested_docs[$doc->doc_name_id] : '';
            }
            return $doc;
        });
    }
}
