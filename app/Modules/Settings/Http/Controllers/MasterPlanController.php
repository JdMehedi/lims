<?php


namespace App\Modules\Settings\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Settings\Models\IndustrialCityList;
use App\Modules\Settings\Models\IndustrialCityMasterPlan;
use Illuminate\Http\Request;

class MasterPlanController extends Controller
{
    public function masterPlanList($city_id, Request $request)
    {
        $search_input = $request->get('search');
        $limit = $request->get('limit');
        $query = IndustrialCityMasterPlan::where([
            'industrial_city_id' => Encryption::decodeId($city_id),
            'status' => 1,
            'is_archive' => 0
        ])
            ->orderBy('id', 'desc');
        if ($search_input) {
            $query->where(function ($query) use ($search_input) {
                $query->where('name', 'like', '%' . $search_input . '%')
                    ->orWhere('name_en', 'like', '%' . $search_input . '%')
                    ->orWhere('remarks', 'like', '%' . $search_input . '%')
                    ->orWhere('remarks_en', 'like', '%' . $search_input . '%');
            });
        }
        $data = $query->paginate($limit);
        $data->getCollection()->transform(function ($masterPlan, $key) {
            return [
                'si' => $key + 1,
                'id' => Encryption::encodeId($masterPlan->id),
                'name' => $masterPlan->name,
                'remarks' => $masterPlan->remarks,
                'document' => $masterPlan->document,
                'status' => $masterPlan->status,
            ];
        });

        return response()->json($data);
    }

    public function createMasterPlan(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $rules = [
            'mp_name_en' => 'required',
            'mp_name_bn' => 'required',
            'mp_remarks_en' => 'required',
            'mp_remarks_bn' => 'required',
            'mp_doc' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:2048',
        ];
        $messages = [
            'mp_name_en.required' => 'Name (English) field is required.',
            'mp_name_bn.required' => 'Name (বাংলা) field is required.',
            'mp_remarks_en.required' => 'Remarks (English) field is required.',
            'mp_remarks_bn.required' => 'Remarks (বাংলা) field is required.',
            'mp_doc.required' => 'Document field is required.',
            'mp_doc.mimes' => 'Document must be a file of type: jpeg, png, jpg, gif, pdf.',
            'mp_doc.max' => 'The Document may not be greater than 2048 kilobytes.'
        ];
        $this->validate($request, $rules, $messages);

        try {

            $master_plan_document = $request->file('mp_doc');
            $path = "uploads/industrial_city";
            if ($request->hasFile('mp_doc')) {
                $document_name = 'city_masater_plan_' . md5(uniqid()) . '.' . $master_plan_document->getClientOriginalExtension();
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $master_plan_document->move($path, $document_name);
                $uploaded_doc_path = $path . '/' . $document_name;
            }

            IndustrialCityMasterPlan::create(
                array(
                    'industrial_city_id' => Encryption::decodeId($request->get('city_id')),
                    'name' => $request->get('mp_name_bn'),
                    'name_en' => $request->get('mp_name_en'),
                    'remarks' => $request->get('mp_remarks_bn'),
                    'remarks_en' => $request->get('mp_remarks_en'),
                    'document' => $uploaded_doc_path
                ));
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
    }

    public function editMasterPlanDetails($masterplan_id)
    {
        $id = Encryption::decodeId($masterplan_id);


       $data['masterPlan'] = IndustrialCityMasterPlan::where('id', $id)->first();
       $data['industrial_city_id'] = Encryption::encodeId($data['masterPlan']->industrial_city_id);
       return $data;
    }

    public function updateMasterPlanDetails(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($request->master_plan_id);

        $rules = [
            'name_en' => 'required',
            'name' => 'required',
            'remarks' => 'required',
            'remarks_en' => 'required',
            'status' => 'required'
        ];
        if ($request->hasFile('mp_doc')) {
            $rules['mp_doc'] = 'mimes:jpeg,png,jpg,gif,pdf|max:2048';
        }

        $messages = [
            'name_en.required' => 'Name (English) field is required.',
            'name.required' => 'Name (বাংলা) field is required.',
            'remarks_en.required' => 'Remarks (English) field is required.',
            'remarks.required' => 'Remarks (বাংলা) field is required.',
            'mp_doc.mimes' => 'Document must be a file of type: jpeg, png, jpg, gif, pdf.',
            'mp_doc.max' => 'The Document may not be greater than 2048 kilobytes.'
        ];

        $this->validate($request, $rules, $messages);
        try {

            $industrialCityMasterPlan = IndustrialCityMasterPlan::Where('id', $id)->first();

            $master_plan_document = $request->file('mp_doc');
            $path = "uploads/industrial_city";
            if ($request->hasFile('mp_doc') && !empty($request->mp_doc)) {
                $document_name = 'city_masater_plan_' . md5(uniqid()) . '.' . $master_plan_document->getClientOriginalExtension();
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $master_plan_document->move($path, $document_name);
                $uploaded_doc_path = $path . '/' . $document_name;
                $industrialCityMasterPlan->document = $uploaded_doc_path;
            }

            $industrialCityMasterPlan->name = $request->get('name');
            $industrialCityMasterPlan->name_en = $request->get('name_en');
            $industrialCityMasterPlan->remarks = $request->get('remarks');
            $industrialCityMasterPlan->remarks_en = $request->get('remarks_en');
            $industrialCityMasterPlan->status = $request->get('status');
            $industrialCityMasterPlan->created_by = CommonFunction::getUserId();
            $industrialCityMasterPlan->save();
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
    }
}
