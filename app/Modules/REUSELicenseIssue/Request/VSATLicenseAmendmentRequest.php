<?php

namespace App\Modules\REUSELicenseIssue\Request;

use App\Libraries\Encryption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class VSATLicenseAmendmentRequest {

    private $request;

    public function __construct( Request $request ) {
        $this->request = $request;
    }

    // $this => current instance of Request Class
    public function rules() {
        $request               = $this->request;
        $rules                 = [];
        $app_id                = ( ! empty( $request->get( 'app_id' ) ) ? Encryption::decodeId( $request->get( 'app_id' ) ) : '' );

        $rules['license_category']          = 'required';
        $rules['origin_or_satelite']        = 'required';
        $rules['company_name']          = 'required';
        $rules['company_type']              = 'required';
        $rules['reg_office_district']           = 'required';
        $rules['reg_office_thana']     = 'required';
        $rules['reg_office_address']            = 'required';
        $rules['noc_district']       = 'required';
        $rules['noc_thana'] = 'required';
        $rules['noc_address']        = 'required';

        $rules['applicant_name']          = 'required';
        $rules['applicant_telephone']     = 'required';
        $rules['applicant_district']      = 'required';
        $rules['applicant_address']       = 'required';
        $rules['applicant_mobile']        = 'required';
        $rules['applicant_thana'] = 'required';
        $rules['applicant_email']         = 'required';

        $rules['contact_person_name']    = 'required';
        $rules['contact_designation']    = 'required';
        $rules['contact_mobile']         = 'required';
        $rules['contact_person_email']   = 'required';
        $rules['contact_district']       = 'required';
        $rules['contact_thana']          = 'required';
        $rules['contact_person_address'] = 'required';
        $rules['correspondent_contact_photo_base64']   = 'required';

        $rules['shareholder_name']        = 'required';
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
        $rules['shareholder_share_of']    = 'required';

        if ( $request->declaration_q1 == 'Yes' ) {
            $rules['declaration_q1_text'] = 'required';
        }
        if ( $request->declaration_q1 == 'Yes' ) {
            $rules['declaration_q1_text'] = 'required';
        }
        if ( $request->declaration_q2 == 'Yes' ) {
            $rules['declaration_q2_text'] = 'required';
        }
//        if ( $request->declaration_q3 == 'Yes' && $app_id == "" ) {
//            $rules['declaration_q3_images'] = 'required';
//        }

        return $rules;
    }

    public function messages() {
        $messages = [];
        $messages['license_category.required']          = 'License category is required';
        $messages['origin_or_satelite.required']        = 'Origin or satelite type is required';
        $messages['company_name.required']          = 'Company/ organization name is required';
        $messages['company_type.required']              = 'Company Type is required';
        $messages['reg_office_district.required']           = 'Company office district is required';
        $messages['reg_office_thana.required']     = 'Company office upazilla is required';
        $messages['reg_office_address.required']            = 'Company office address is required';
        $messages['noc_district.required']       = 'Company office permanent address is required';
        $messages['noc_thana.required'] = 'Company office permanent address upazila is required';
        $messages['noc_address.required']        = 'Company office permanent address is required';

        $messages['applicant_name.required']          = 'Applicant name is required';
        $messages['applicant_telephone.required']     = 'Applicant telephone no. is required';
        $messages['applicant_district.required']      = 'Applicant district is required';
        $messages['applicant_address.required']       = 'Applicant address is required';
        $messages['applicant_mobile.required']        = 'Applicant mobile is required';
        $messages['applicant_thana.required'] = 'Applicant upazila is required';
        $messages['applicant_email.required']         = 'Applicant email is required';

        $messages['contact_person_name.required']    = 'Contact person name is required';
        $messages['contact_designation.required']    = 'Contact person designation is required';
        $messages['contact_mobile.required']         = 'Contact person mobile required';
        $messages['contact_person_email.required']   = 'Contact person email is required';
        $messages['contact_district.required']       = 'Contact person district is required';
        $messages['contact_thana.required']          = 'Contact person thana is required';
        $messages['contact_person_address.required'] = 'Contact person address is required';
        $messages['correspondent_contact_photo_base64.required']   = 'Contact person\'s photo is required';

        $messages['shareholder_name.required']        = 'Shareholder passport is required';
        $messages['shareholder_nationality.required'] = 'Shareholder passport is required';
        $messages['shareholder_passport.required']    = 'Shareholder passport is required';
        $messages['shareholder_nid.required']         = 'Shareholder nid is required';
        $messages['shareholder_dob.required']         = 'Shareholder dob is required';
        $messages['shareholder_designation.required'] = 'Shareholder designation is required';
        $messages['shareholder_mobile.required']      = 'Shareholder mobile is required';
        $messages['shareholder_email.required']       = 'Shareholder email is required';
        $messages['shareholder_share_of.required']    = 'Shareholder % share of is required';
        $messages['correspondent_photo.required']     = 'Shareholder photo is required';
        return $messages;
    }

}
