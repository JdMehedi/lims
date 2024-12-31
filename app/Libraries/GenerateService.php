<?php

namespace App\Libraries;

use App\Modules\ProcessPath\Models\ProcessPath;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class GenerateService {


    public static function generateServices( $request  ) {

      
        DB::beginTransaction();
            
        
        $index= ProcessType::orderBy('id', 'desc')->pluck('id')->first();
            
            foreach($request->get('services') as $services){
                $process_data = ProcessType::find($services);
                $process_path = ProcessPath::where('process_type_id',$services)->get();
                $process_status = ProcessStatus::where('process_type_id',$services)->get();
                
                $name=null;
                if($services==1){
                    $name=' Issue';
                }elseif($services==2){
                    $name=' Renew';
                }elseif($services==3){
                    $name=' Ammendment';
                }elseif($services==4){
                    $name=' Surrender';
                }

                $newProcessType = new ProcessType();
                $newProcessType->id = $index+1;
                $newProcessType->name = $request->get('service_name').$name;
                $newProcessType->name_bn = $request->get('service_name').$name;
                $newProcessType->drop_down_label = $request->get('service_short_name').' '.$name;
                $newProcessType->group_name = $request->get('group_name');
                $newProcessType->status = 1;
                $newProcessType->is_special = 1;
                $newProcessType->type = $services;
                
                $newProcessType->final_status = $process_data->final_status;
                $newProcessType->type_key = $process_data->type_key;
                $newProcessType->active_menu_for = $process_data->active_menu_for;
                $newProcessType->panel = $process_data->panel;
                $newProcessType->icon = $process_data->icon;
                $newProcessType->menu_name = $process_data->menu_name;
                $newProcessType->acl_name = $process_data->acl_name;

                $newProcessType->order = $process_data->order;
                $newProcessType->form_url = str_replace(" ","",  strtolower($newProcessType->group_name).'-license-'.strtolower( $name));

                $form_array= [];
                $form_array['add'] = $newProcessType->form_url.'/add' ;
                $form_array['edit'] = $newProcessType->form_url.'/edit/' ;
                $form_array['view'] = $newProcessType->form_url.'/view/' ;
                $newProcessType->form_id = json_encode($form_array,JSON_UNESCAPED_SLASHES);

                $newProcessType->table_name = $process_data->table_name;
                $newProcessType->process_desk_status_json = $process_data->process_desk_status_json;
                $newProcessType->observational_statuses = $process_data->observational_statuses;
                $newProcessType->suggested_status_json = $process_data->suggested_status_json;
                $newProcessType->service_code = $process_data->service_code;
                $newProcessType->master_order_id = $process_data->master_order_id;
                $newProcessType->configured_at = $process_data->configured_at;
                $newProcessType->license_type = $process_data->license_type;
                $newProcessType->child_order_id = $process_data->child_order_id;
                $newProcessType->save();

                foreach($process_status as $status){
                    $newProcessStatus = new ProcessStatus();
                    $newProcessStatus->id = $status->id;
                    $newProcessStatus->status_name = $status->status_name;
                    $newProcessStatus->process_type_id = $index+1;
                    $newProcessStatus->color = $status->color;
                    $newProcessStatus->status = $status->status;
                    $newProcessStatus->save();

                }

                foreach($process_path as $path){
                    $newProcessPath = new ProcessPath();
                    $newProcessPath->process_type_id = $index+1;
                    $newProcessPath->desk_from = $path->desk_from;
                    $newProcessPath->desk_to = $path->desk_to;
                    $newProcessPath->status_from = $path->status_from;
                    $newProcessPath->status_to = $path->status_to;
                    $newProcessPath->suggested_status = $path->suggested_status;
                    $newProcessPath->save();

                }

                $index++;
            }
            
            


            DB::commit();
    }


}
