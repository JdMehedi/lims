<?php

namespace App\Modules\REUSELicenseIssue\Request;

use App\Libraries\Encryption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class NTTNLicenseAmendmentRequest {

    private $request;

    public function __construct( Request $request ) {
        $this->request = $request;
    }

    // $this => current instance of Request Class
    public function rules() {
        $request               = $this->request;
        $rules                 = [];
        $app_id                = ( ! empty( $request->get( 'app_id' ) ) ? Encryption::decodeId( $request->get( 'app_id' ) ) : '' );

        $rules['company_name'] = 'required';
        $rules['company_type'] = 'required';
        $rules['reg_office_district'] = 'required';
        $rules['reg_office_thana']    = 'required';
        $rules['reg_office_address']  = 'required';
//        $rules['op_office_district'] = 'required';
//        $rules['op_office_thana']    = 'required';
//        $rules['op_office_address']  = 'required';
        $rules['applicant_name']     = 'required';
        $rules['applicant_mobile']   = 'required';
        $rules['applicant_email']    = 'required';
        $rules['applicant_district'] = 'required';
        $rules['applicant_thana']    = 'required';
        $rules['applicant_address']  = 'required';
        $rules['contact_person_name']    = 'required';
        $rules['contact_designation']    = 'required';
        $rules['contact_mobile']         = 'required';
        $rules['contact_person_email']   = 'required';
        $rules['contact_website']        = 'required';
        $rules['contact_district']       = 'required';
        $rules['contact_thana']          = 'required';
        $rules['contact_person_address'] = 'required';


        $rules['shareholder_name'] = 'required';

        $rules['shareholder_nationality'] = 'required';
        if ( $request->shareholder_nationality == 18 ) {
            $rules['shareholder_nid'] = 'required';
        } else {
            $rules['shareholder_passport'] = 'required';
        }

        $rules['shareholder_dob']         = 'required';
        $rules['shareholder_designation'] = 'required';
        $rules['shareholder_mobile']      = 'required';
        $rules['shareholder_email']       = 'required';
        $rules['total_no_of_share']       = 'required';
        $rules['total_share_value']       = 'required';
        $rules['no_of_share']             = 'required';
        $rules['share_value']             = 'required';

        return $rules;
    }

    public function messages() {
        $messages                                 = [];
        $messages['company_name.required']    = 'Company name is required';
        $messages['company_type.required']        = 'Company type is required';
        $messages['applicant_mobile.required']    = 'Mobile number is required';
        $messages['applicant_telephone.required'] = 'Telephone number is required';
        $messages['applicant_email.required']     = 'Email is required';
        $messages['applicant_district.required']  = 'District is required';
        $messages['applicant_thana.required']     = 'Thana is required';
        $messages['applicant_address.required']   = 'Address is required';


        $messages['contact_person_name.required']    = 'Contact person name is required';
        $messages['contact_designation.required']    = 'Contact person designation is required';
        $messages['contact_mobile.required']         = 'Contact person mobile number is required';
        $messages['contact_person_email.required']   = 'Contact person email is required';
        $messages['contact_website.required']        = 'Contact person website is required';
        $messages['contact_district.required']       = 'Contact person district is required';
        $messages['contact_thana.required']          = 'Contact person thana is required';
        $messages['contact_person_address.required'] = 'Contact person address is required';

        $messages['type_of_isp_licensese.required']       = 'Type of isp license field required';
        $messages['isp_licensese_area_division.required'] = 'License division field required';
        $messages['isp_licensese_area_district.required'] = 'License district field required';
        $messages['isp_licensese_area_thana.required']    = 'License thana field required';

        $messages['declaration_q1_text.required']   = 'Attachment & Declaration : Please give data of application and reasons';
        $messages['declaration_q2_text.required']   = 'Attachment & Declaration : Privious issued ISP License informatoin required.';
        $messages['declaration_q3_images.required'] = 'Attachment & Declaration : Operator Licenses informatoin required.';

        $messages['shareholder_name.required']        = 'Shareholder name is required';
        $messages['shareholder_nationality.required'] = 'Shareholder nationality is required';
        $messages['shareholder_passport.required']    = 'Shareholder passport is required';
        $messages['shareholder_nid.required']         = 'Shareholder nid is required';
        $messages['shareholder_dob.required']         = 'Shareholder dob is required';
        $messages['shareholder_designation.required'] = 'Shareholder designation is required';
        $messages['shareholder_mobile.required']      = 'Shareholder mobile is required';
        $messages['shareholder_email.required']       = 'Shareholder email is required';
        $messages['shareholder_share_of.required']    = 'Shareholder % share of is required';
        $messages['total_no_of_share.required']       = 'Total number of share is required';
        $messages['total_share_value.required']       = 'Total number of share is required';
        $messages['no_of_share.required']             = 'Number of share is required';
        $messages['share_value.required']             = 'Share value is required';

        return $messages;
    }

}
