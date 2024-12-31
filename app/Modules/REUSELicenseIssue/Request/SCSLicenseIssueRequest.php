<?php

namespace App\Modules\REUSELicenseIssue\Request;

use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\HelpText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SCSLicenseIssueRequest {

    private $request;

    public function __construct( Request $request ) {
        $this->request = $request;
    }

    // $this => current instance of Request Class
    public function rules() {
        $request               = $this->request;
        $rules                 = [];
        $app_id                = ( ! empty( $request->get( 'app_id' ) ) ? Encryption::decodeId( $request->get( 'app_id' ) ) : '' );
        $validation_configs = HelpText::where('module','scs-license-issue')
        ->where('help_text','Demo Text')
        ->where('is_active','1')
        ->select([
            'field_id',
            'field_class',
            'validation_class',
        ])->get();

        foreach($validation_configs as $validation_config){
            
            $rules[$validation_config->field_id]   = $validation_config->validation_class;
        }
       

        return $rules;
    }

    public function messages() {

        $validation_configs = HelpText::where('module','scs-license-issue')
        ->where('help_text','Demo Text')
        ->where('is_active','1')
        ->select([
            'field_id',
            'field_class',
            'validation_class',
        ])->get();

        foreach($validation_configs as $validation_config){
            if (str_contains($validation_config->validation_class, 'required')) { 
                $messages[$validation_config->field_id]   = $validation_config->field_id .' is required';
            }
           
        }
       

        return $messages;
    }

}