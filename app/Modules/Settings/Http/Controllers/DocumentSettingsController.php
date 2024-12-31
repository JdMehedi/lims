<?php


namespace App\Modules\Settings\Http\Controllers;


use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Documents\Models\DocumentTypes;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\DocType;
use App\Modules\Settings\Models\DocumentName;
use App\Modules\Settings\Models\DocumentsOfService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use DB;

class DocumentSettingsController {
    protected $aclName;

    public function __construct() {
        $this->aclName = 'settings';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index() {
        return view( 'Settings::document_v2.list' );
    }

    /**
     * @param Request $request
     *
     * @return string
     * @throws \Exception
     */
    public function getDocumentList( Request $request ) {
        if ( ! $request->ajax() ) {
            return 'Sorry! this is a request without proper way.';
        }

        if ( ! ACL::getAccsessRight( $this->aclName, 'V' ) ) {
            abort( '401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.' );
        }


        $document_list = DocumentName::getDocList();

        return DataTables::of( $document_list )
                         ->editColumn( 'simple_file', function ( $document_list ) {
                             return $document_list->simple_file ? "<a class='btn btn-outline-primary' href='".asset( $document_list->simple_file)."' target='_blank' >Download</a>" : "" ;
                         } )
                         ->editColumn( 'status', function ( $document_list ) {
                             if ( $document_list->status == '1' ) {
                                 return '<span class="text-success"><b>Active</b></span>';
                             } else {
                                 return '<span class="text-danger"><b>In-active</b></span>';
                             }
                         } )
                         ->addColumn( 'action', function ( $document_list ) {
                             return "<button type='button' class='btn btn-xs btn-success' onclick='openModal(this)' data-action='" . url( '/settings/document-v2/edit/' . Encryption::encodeId( $document_list->id ) ) . "'><i class='fa fa-edit'></i> Edit</button>";
                         } )
                         ->rawColumns( ['simple_file', 'status', 'action' ] )
                         ->make( true );;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function createDocument() {
        if ( ! ACL::getAccsessRight( $this->aclName, 'A' ) ) {
            abort( '401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.' );
        }

        return view( "Settings::document_v2.create-document" );
    }

    /**
     * @param Request $request
     * @param int $document_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDocument( Request $request, $document_id = 0 ) {
        if ( ! ACL::getAccsessRight( $this->aclName, 'A' ) ) {
            response()->json( [
                'error'  => true,
                'status' => 'You have no access right! This incidence will be reported. Contact with system admin for more information.'
            ] );
        }
        $decoded_document_id = '';
        if ( $document_id ) {
            $decoded_document_id = Encryption::decodeId( $document_id );
        }

        $rules = [
            'name'     => 'required|unique:doc_name,name,' . $decoded_document_id,
            'min_size' => 'required',
            'max_size' => 'required',
            'company_doc_type' => 'required|in:1,2,3',
            'status'   => 'required|in:0,1'
        ];

        $validation = Validator::make( $request->all(), $rules );
        if ( $validation->fails() ) {
            return response()->json( [
                'success' => false,
                'error'   => $validation->errors(),
            ] );
        }
        try {
            $document           = DocumentName::findOrNew( $decoded_document_id );
            if ($request->company_doc_type != 2 && strpos($document->name, '(Proprietorship)') !== false) {
                $document->name = str_replace('(Proprietorship)', '', $document->name);
            } elseif ($request->company_doc_type == 2 && strpos($document->name, '(Proprietorship)') === false) {
                $document->name = $request->name . ' (Proprietorship)';
            } else {
                $document->name = $request->name;
            }
            $document->min_size = $request->min_size;
            $document->max_size = $request->max_size;
            $document->company_doc_type   =  $request->company_doc_type;
            $document->status   = $request->status;

            if ( $request->hasFile( 'simple_file' ) ) {
                $path             = CommonFunction::makeDir( 'documentSimpleFile' );
                $simpleFile       = $request->file( 'simple_file' );
                $simple_file_name = trim( uniqid( 'SIMPLE-FILE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
                $simpleFile->move( $path, $simple_file_name );
                $document->simple_file = $path . $simple_file_name;
            }

            $document->save();

            return response()->json( [
                'success' => true,
                'status'  => 'Data has been saved successfully',
                'link'    => '/settings/document-v2/'
            ] );
        } catch ( \Exception $exception ) {
            return response()->json( [
                'error'  => true,
                'status' => 'Sorry! something went wrong. [DSC-001]'
            ] );
        }
    }

    /**
     * @param $document_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function editDocument( $document_id ) {
        if ( ! ACL::getAccsessRight( $this->aclName, 'E' ) ) {
            abort( '401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.' );
        }

        $data['encoded_doc_id'] = $document_id;
        $data['document_info']  = DocumentName::find( Encryption::decodeId( $document_id ) );

        return view( 'Settings::document_v2.edit-document', $data );
    }

    /**
     * @param Request $request
     *
     * @return string
     * @throws \Exception
     */
    public function getServiceDocumentList( Request $request ) {
        if ( ! $request->ajax() ) {
            return 'Sorry! this is a request without proper way.';
        }

        if ( ! ACL::getAccsessRight( $this->aclName, 'V' ) ) {
            abort( '401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.' );
        }


        $document_list = DocumentsOfService::getAllServiceDocument();

        return DataTables::of( $document_list )
                         ->editColumn( 'status', function ( $document_list ) {
                             if ( $document_list->status == '1' ) {
                                 return '<span class="text-success"><b>Active</b></span>';
                             } else {
                                 return '<span class="text-danger"><b>In-active</b></span>';
                             }
                         } )
                         ->editColumn( 'is_required', function ( $document_list ) {
                             if ( $document_list->is_required == '1' ) {
                                 return 'Mandatory';
                             } else {
                                 return 'Optional';
                             }
                         } )
                         ->addColumn( 'action', function ( $document_list ) {
                             return "<button type='button' class='btn btn-xs btn-success' onclick='openModal(this)' data-action='" . url( '/settings/document-v2/service/edit/' . Encryption::encodeId( $document_list->id ) ) . "'><i class='fa fa-edit'></i> Edit</button>";
                         } )
                         ->rawColumns( [ 'status', 'action' ] )
                         ->make( true );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function createServiceDocument() {
        if ( ! ACL::getAccsessRight( $this->aclName, 'A' ) ) {
            abort( '401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.' );
        }
        $data['document_list'] = [ '' => 'Select One' ] + DocumentName::where( [
                'is_archive' => 0,
                'status'     => 1,
            ] )->pluck( 'name', 'id' )->toArray();
        $data['process_list']  = ProcessType::where( 'status', 1 )->pluck( 'name', 'id' )->toArray();

        return view( "Settings::document_v2.create_service_document", $data );
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDocumentType( Request $request ) {

        $process_type_id         = trim( $request->get( 'process_type_id' ) );
        $doc_type_for_service_id = $request->get( 'doc_type_for_service_id' ) ? Encryption::decode( $request->get( 'doc_type_for_service_id' ) ) : null;
        if ( $doc_type_for_service_id != null ) {
            $doc_type_for_service_id = explode( "-", $doc_type_for_service_id );
        }
//        $attachment_type = DocumentTypes::where([
//            'process_type_id' => $process_type_id,
//            'status' => 1,
//            'is_archive' => 0
//        ])->pluck('name', 'id')->toArray();

        $attachment_type = DocType::where( [
            'process_type_id' => $process_type_id,
            'status'          => 1
        ] )
                                  ->orderBy( 'order' )
                                  ->get();
        $html            = "";
        foreach ( $attachment_type as $key => $row ) {
            $arrayData = $this->runSql( $row->sql );

            $option = "";
            foreach ( $arrayData as $item ) {
                $selected = '';
                if ( ! empty( $doc_type_for_service_id[ $key ] ) ) {
                    if ( $item->id == $doc_type_for_service_id[ $key ] ) {
                        $selected = 'selected';
                    }
                }
                $option .= '<option value="' . $item->id . '" ' . $selected . '>' . $item->value . '</option>';
            }
            $html .= '
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="form-group col-md-12 row">
                                <label for="process_type_id" class="col-md-3  required-star">' . $row->label_name . ': </label>
                                <div class="col-md-9">
                                    <select class="form-control required input-sm"  name="' . $row->input_name . '">
                                    <option selected="selected" value="">নির্বাচন করুন</option>
                                   ' . $option . '
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>';
        }

        return response()->json( [
            'result' => $html
        ] );
    }

    /**
     * @param Request $request
     * @param int $service_doc_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeServiceDocument( Request $request, $service_doc_id = 0 )
    {

        if (!ACL::getAccsessRight($this->aclName, 'A')) {
            response()->json([
                'error' => true,
                'status' => 'You have no access right! This incidence will be reported. Contact with system admin for more information.'
            ]);
        }

        $decoded_document_id = '';
        if ($service_doc_id) {
            $decoded_document_id = Encryption::decodeId($service_doc_id);

            // Check duplicate document for same service and type
//            $count_duplicate = DocumentsOfService::where([
//                'process_type_id' => $request->process_type_id,
//                'doc_id' => $request->doc_id,
//                'doc_type_for_service_id' => $request->doc_type_for_service_id,
//            ])->where('id', '!=', $decoded_document_id)
//                ->count();
//            if ($count_duplicate == 1) {
//                return response()->json([
//                    'error' => true,
//                    'status' => 'Sorry! This document is already listed for same process type and doc type.'
//                ]);
//            }
        }


        $rules = [
            'doc_id' => 'required',
            'process_type_id' => 'required',
            'is_required' => 'required|in:0,1',
            'autosuggest_status' => 'required|in:0,1',
            'status' => 'required|in:0,1'
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }

        $getOrder = DocumentsOfService::where('process_type_id', $request->process_type_id)->pluck('order')->toArray();
        if (in_array($request->order, $getOrder)) {
            return response()->json(['error' => true,
                'status' => $request->order . ' is already exists..',
                'link' => '/settings/document-v2'
            ]);
        } else {
            $getName = DocType::where('process_type_id', $request->process_type_id)->pluck('input_name')->toArray();

            $str = '';
            foreach ($request->all() as $key => $value) {
                if (in_array($key, $getName)) {
                    $str .= $value . '-';
                }

            }
            $str = rtrim($str, '-');
            try {
                $service_doc = DocumentsOfService::findOrNew($decoded_document_id);
                $service_doc->process_type_id = $request->process_type_id;
                $service_doc->doc_id = $request->doc_id;
                $service_doc->doc_type_for_service_id = $str;
                $service_doc->order = $request->order;
                $service_doc->is_required = $request->is_required;
                $service_doc->autosuggest_status = $request->autosuggest_status;
                $service_doc->status = $request->status;
                $service_doc->save();

                return response()->json([
                    'success' => true,
                    'status' => 'Data has been saved successfully',
                    'link' => '/settings/document-v2'
                ]);
            } catch (\Exception $exception) {
                return response()->json([
                    'error' => true,
                    'status' => 'Sorry!  something went wrong: ' . CommonFunction::showErrorPublic($exception->getMessage()) . ' [DSC-002]'
                ]);
            }
        }
    }


    /**
     * @param $service_doc_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function editServiceDocument( $service_doc_id ) {
        if ( ! ACL::getAccsessRight( $this->aclName, 'A' ) ) {
            abort( '401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.' );
        }
        $data['encoded_service_doc_id'] = $service_doc_id;
        $data['service_doc_info']       = DocumentsOfService::find( Encryption::decodeId( $service_doc_id ) );
        $data['document_list']          = DocumentName::where( [
            'is_archive' => 0
        ] )->pluck( 'name', 'id' )->toArray();
        $data['process_list']           = ProcessType::where( 'status', 1 )->pluck( 'name', 'id' )->toArray();

        return view( "Settings::document_v2.edit_service_document", $data );
    }

    protected function runSql( $sql ) {
        return collect( DB::select( "$sql" ) );
//        return collect(DB::select("$sql"))->pluck("value" ,"id")->toArray();
    }
}
