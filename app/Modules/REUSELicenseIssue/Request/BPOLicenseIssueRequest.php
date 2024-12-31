<?php

namespace App\Modules\REUSELicenseIssue\Request;

use App\Libraries\Encryption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BPOLicenseIssueRequest {

    private $request;

    public function __construct( Request $request ) {
        $this->request = $request;
    }

    // $this => current instance of Request Class
    public function rules() {
        $request               = $this->request;
        $rules                 = [];
        $app_id                = ( ! empty( $request->get( 'app_id' ) ) ? Encryption::decodeId( $request->get( 'app_id' ) ) : '' );

        $rules['company_name']        = 'required';
        $rules['company_type']        = 'required';
        $rules['reg_office_district'] = 'required';
        $rules['reg_office_thana']    = 'required';
        $rules['reg_office_address']  = 'required';
//        $rules['reg_office_address2']  = 'required';
        $rules['op_office_district']        = 'required';
        $rules['op_office_thana']           = 'required';
        $rules['op_office_address']         = 'required';
//        $rules['op_office_address2']         = 'required';

        $rules['applicant_name']      = 'required';
        $rules['applicant_district']  = 'required';
        $rules['applicant_thana']     = 'required';
        $rules['applicant_address']   = 'required';
//        $rules['applicant_address2']   = 'required';
        $rules['applicant_email']     = 'required';
        $rules['applicant_mobile']    = 'required';
//        $rules['applicant_telephone'] = 'required';

        $rules['contact_person_name']           = 'required';
        $rules['contact_district']       = 'required';
        $rules['contact_thana']          = 'required';
        $rules['contact_person_address'] = 'required';
        $rules['contact_mobile']         = 'required';
        $rules['contact_designation']    = 'required';
        $rules['contact_person_email']   = 'required';

        $rules['present_business_actives'] = 'required';
        $rules['proposal_service']         = 'required';
        $rules['proposal_service_type']    = 'required';

        $rules['proposal_district'] = 'required';
        $rules['proposal_thana']    = 'required';
        $rules['proposal_address']  = 'required';
        $rules['local'] = 'required';
        $rules['expatriate'] = 'required';

//        $rules['shareholder_name'] = 'required';
//
//        $rules['shareholder_nationality'] = 'required';
//        if ( $request->shareholder_nationality == 18 ) {
//            $rules['shareholder_nid'] = 'required';
//        } else {
//            $rules['shareholder_passport'] = 'required';
//        }
//        $rules['shareholder_dob']            = 'required';
//        $rules['shareholder_designation']    = 'required';
//        $rules['shareholder_mobile']         = 'required';
//        $rules['shareholder_email']          = 'required';
//        $rules['shareholder_share_of']       = 'required';
        $rules['correspondent_photo_base64'] = 'required';

        if ( $request->declaration_q1 == 'Yes' ) {
            $rules['declaration_q1_application_date'] = 'required';
            $rules['declaration_q1_text']             = 'required';
        }
        if ( $request->declaration_q2 == 'Yes' ) {
            $rules['declaration_q2_text'] = 'required';
        }
        if ( $request->declaration_q3 == 'Yes' ) {
            $rules['declaration_q3_text']             = 'required';
            // $rules['declaration_q3_images'] = 'required';
        }

        return $rules;
    }

    public function messages() {
        $messages                                 = [];
        $messages['company_name.required']        = 'Company name is required';
        $messages['company_type.required']        = 'Company type is required';
        $messages['reg_office_district.required'] = 'Registered office district is required';
        $messages['reg_office_thana.required']    = 'Registered office thana is required';
        $messages['reg_office_address.required']  = 'Registered office address 1 is required';
//        $messages['reg_office_address2.required']  = 'Registered office address 2 is required';
        $messages['op_office_district.required']        = 'Operational district is required';
        $messages['op_office_thana.required']           = 'Operational thana is required';
        $messages['op_office_address.required']         = 'Operational address 1 is required';
//        $messages['op_office_address2.required']         = 'Operational address 2 is required';

        $messages['applicant_name.required']      = 'Applicant name is required';
        $messages['applicant_mobile.required']    = 'Applicant mobile number is required';
//        $messages['applicant_telephone.required'] = 'Telephone number is required';
        $messages['applicant_email.required']     = 'Email is required';
        $messages['applicant_district.required']  = 'District is required';
        $messages['applicant_thana.required']     = 'Thana is required';
        $messages['applicant_address.required']   = 'Applicant address 1 is required';
//        $messages['applicant_address2.required']   = 'Applicant address 2 is required';

        $messages['contact_person_name.required']           = 'Contact person name is required';
        $messages['contact_mobile.required']         = 'Contact person mobile number is required';
        $messages['contact_district.required']       = 'Contact person district is required';
        $messages['contact_thana.required']          = 'Contact person thana is required';
        $messages['contact_person_address.required'] = 'Contact person address is required';
        $messages['contact_designation.required']  = 'Contact designation is required.';
        $messages['contact_person_name.required'] = 'Contact email is required';

        $messages['present_business_actives.required'] = 'Present business actives field required';

        $messages['proposal_service.required']      = 'Proposal service is required';
        $messages['proposal_service_type.required'] = 'Proposal service type is required';
        $messages['proposal_district.required']     = 'Proposal service district is required';
        $messages['proposal_thana.required']        = 'Proposal service thana is required';
        $messages['proposal_address.required']      = 'Proposal service address is required';
        $messages['local.required'] = 'Proposal local is required';
        $messages['expatriate.required'] = 'Proposal expatriate required';

//        $messages['shareholder_name.required']           = 'Shareholder name is required';
//        $messages['shareholder_passport.required']       = 'Shareholder passport is required';
//        $messages['shareholder_nid.required']            = 'Shareholder nid is required';
//        $messages['shareholder_dob.required']            = 'Shareholder dob is required';
//        $messages['shareholder_nationality.required']    = 'Shareholder nationality is required';
//        $messages['shareholder_designation.required']    = 'Shareholder designation is required';
//        $messages['shareholder_mobile.required']         = 'Shareholder mobile is required';
//        $messages['shareholder_email.required']          = 'Shareholder email is required';
        $messages['shareholder_share_of.required']       = 'Shareholder % share of is required';
        $messages['correspondent_photo_base64.required'] = 'Shareholder photo is required';
        $messages['declaration_q3_images.required'] = 'Declaration q3 image is required.';

        return $messages;
    }

}
