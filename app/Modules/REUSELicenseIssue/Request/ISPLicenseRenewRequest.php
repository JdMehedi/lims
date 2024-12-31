<?php

namespace App\Modules\REUSELicenseIssue\Request;

use App\Libraries\Encryption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ISPLicenseRenewRequest {

    private $request;

    public function __construct( Request $request ) {
        $this->request = $request;
    }

    // $this => current instance of Request Class
    public function rules() {
        $request               = $this->request;
        $rules                 = [];
        $app_id                = ( ! empty( $request->get( 'app_id' ) ) ? Encryption::decodeId( $request->get( 'app_id' ) ) : '' );

//        $rules['no_of_individual'] = 'required';
//        $rules['no_of_corporate']  = 'required';
        $rules['corporate_user']   = 'required';
        $rules['branch_user']      = 'required';
        $rules['personal_user']    = 'required';
        $rules['cable_length']     = 'required';

        if ( $request->declaration_q1 == 'Yes' ) {
            $rules['declaration_q1_text'] = 'required';
        }
        if ( $request->declaration_q1 == 'Yes' ) {
            $rules['declaration_q1_text'] = 'required';
        }

        if ( $request->declaration_q2 == 'Yes' ) {
            $rules['declaration_q2_text'] = 'required';
        }

        if ( ($request->declaration_q3 == 'Yes' && empty($request->get('declaration_q3_images_preview')) )  && $app_id == '' ) {
            $rules['declaration_q3_images'] = 'required';
        }

        return $rules;
    }

    public function messages() {

//        $messages['no_of_individual.required']      = 'Number of Individual is required';
//        $messages['no_of_corporate.required']       = 'Number of Corporate is required';
        $messages['corporate_user.required']        = 'Corporate User info is required';
        $messages['branch_user.required']           = 'Branch User info is required';
        $messages['personal_user.required']         = 'Personal User info is required';
        $messages['cable_length.required']          = 'Cable length is required';
        $messages['declaration_q1_text.required']   = 'Attachment & Declaration : Please give data of application and reasons';
        $messages['declaration_q2_text.required']   = 'Attachment & Declaration : Privious issued ISP License informatoin required.';
        $messages['declaration_q3_images.required'] = 'Attachment & Declaration : Operator Licenses informatoin required.';

        return $messages;
    }

}
