<?php
namespace App\Modules\Documents\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Documents\Models\DocumentsOfUserCompany;
use App\Modules\Documents\Models\DocumentsOfUserCompanyHistory;
use App\Modules\Settings\Models\DocumentName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class UserCompanyDocuments extends Controller
{
    protected $aclName;

    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        $this->aclName = 'Documents';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function documentList(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');

        return view('Documents::index');
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function getUserDocuments(Request $request)
    {
        // check ajax req
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $user_documents = DocumentsOfUserCompany::getUserCompanyDocuments();
        return Datatables::of($user_documents)
            ->editColumn('sl', function ($user_documents) {
                return $user_documents->sl;
            })
            ->addColumn('mergeColumn', function ($user_documents) {
                $name = $user_documents->name;
                $updateddate = '';
                if(!empty($user_documents->updated_at)){
                    $updateddate = "<span class='' style='font-size: 12px'>Last Updated: ".Carbon::parse($user_documents->updated_at)->format('d-m-Y')."</span>";
                }
                $html = "<span style='font-size: 16px'>".$name."</span><br>".$updateddate;
                return $html;
            })

            ->editColumn('action', function ($user_documents) {
                $content = '';
                if ($user_documents->uploaded_path) {
                    $viewBtn = "<a target='_blank' class='btn btn-xs btn-info' href='" . CommonComponent()->fileUrlEncode('/uploads/' . $user_documents->uploaded_path) . "'><i class='fa fa-file-pdf-o' aria-hidden='true'></i> View File</a>";
                    $updateBtn = ' <button type="button" class="btn btn-success btn-xs" onclick="openModal(this)" data-action="/documents/upload-user-document/' . Encryption::encodeId($user_documents->id) . '"> <i class="fa fa-edit"></i> Update </button>';
                    $user_documents_history = DocumentsOfUserCompanyHistory::where('user_doc_id', $user_documents->u_d_id)->get();
//                    dd($user_documents_history[0]->user_doc_id);
                    if (count($user_documents_history) > 1){
                        $showHistoryBtn = ' <button type="button" class="btn btn-primary btn-xs" onclick="getHistory(this)" value="'.Encryption::encodeId($user_documents_history[0]->user_doc_id).'"> <i class="fa fa-eye"></i> Show History </button>';
                    }else{
                        $showHistoryBtn = '';
                    }

                    $content .= $viewBtn.$updateBtn.$showHistoryBtn;
                }else{
                    $content .= ' <button type="button" class="btn btn-success btn-xs" onclick="openModal(this)" data-action="/documents/upload-user-document/' . Encryption::encodeId($user_documents->id) . '"> <i class="fa fa-upload"></i> New Upload </button>';
                }

                return $content;
            })
            ->rawColumns(['attachment', 'action','updated_at', 'mergeColumn'])
            ->make(true);
    }

    /**
     * @param $document_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function userDocUploadModal($document_id)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');

        $data['encoded_doc_id'] = $document_id;
        $data['document_info'] = DocumentName::find(Encryption::decodeId($document_id));
        return view('Documents::update_usr_doc', $data);
    }

    /**
     * @param Request $request
     * @param $encoded_document_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserDocument(Request $request, $encoded_document_id)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');

        $rules = [
            'attachment' => 'required'
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }

        try {
            $document = DocumentsOfUserCompany::firstOrNew([
                'user_id' => Auth::user()->id,
                'company_id' => CommonFunction::getUserCompanyWithZero(),
                'doc_id' => Encryption::decodeId($encoded_document_id)
            ]);
            $document->uploaded_path = $request->attachment;
            $document->save();
            return response()->json([
                'success' => true,
                'status' => 'Data has been saved successfully',
                'link' => '/documents/lists'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => true,
                'status' => 'Sorry! something went wrong. [UCD-001]'
            ]);
        }
    }

    public function getDocumentHistory(Request $request){

        $user_doc_id = Encryption::decodeId($request->user_doc_id);

        $historyInfo = DocumentsOfUserCompanyHistory::where('user_doc_id', $user_doc_id)->get();
//        dd($historyInfo);
        return response()->json($historyInfo);
    }
}
