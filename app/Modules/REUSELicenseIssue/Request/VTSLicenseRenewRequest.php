<?php

namespace App\Modules\REUSELicenseIssue\Request;

use App\Libraries\Encryption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class VTSLicenseRenewRequest {

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
        $rules['company_type']     = 'required';

        $rules['reg_office_district'] = 'required';
        $rules['reg_office_thana']    = 'required';
        $rules['reg_office_address']  = 'required';

        $rules['op_office_district']  = 'required';
        $rules['op_office_thana']  = 'required';
        $rules['op_office_address']  = 'required';

        $rules['applicant_name']         = 'required';
        $rules['applicant_mobile']    = 'required';
        $rules['applicant_telephone'] = 'required';
        $rules['applicant_email']        = 'required';
        $rules['applicant_district']     = 'required';
        $rules['applicant_thana']        = 'required';
        $rules['applicant_address']      = 'required';


        $rules['contact_person_name']           = 'required';
        $rules['contact_designation']    = 'required';
        $rules['contact_mobile']         = 'required';
        $rules['contact_person_email']          = 'required';
//            $rules['contact_image']          = 'required';
        $rules['contact_district']       = 'required';
        $rules['contact_thana']          = 'required';
        $rules['contact_person_address'] = 'required';
        $rules['correspondent_contact_photo_base64'] = 'required';

        $rules['shareholder_nationality'] = 'required';
        $rules['shareholder_dob'] = 'required';
        $rules['shareholder_name'] = 'required';

        $rules['shareholder_nid'] = 'required';
        $rules['shareholder_share_of'] = 'required';
        $rules['shareholder_designation'] = 'required';
        $rules['shareholder_mobile']      = 'required';
        $rules['shareholder_email']       = 'required';
        $rules['correspondent_photo_base64']       = 'required';

        return $rules;
    }

    public function messages() {
        $messages = [];
        $messages['company_name.required'] = 'Company name is required';
        $messages['Company_type.required']     = 'Company type is required';


        $messages['reg_office_district.required'] = 'Registered office district is required';
        $messages['reg_office_thana.required']    = 'Registered office thana is required';
        $messages['reg_office_address.required']  = 'Registered office address is required';

        $messages['permanent_office_district.required'] = 'Permanent office district is required';
        $messages['permanent_office_thana.required']    = 'Permanent office thana is required';
        $messages['permanent_office_address.required']  = 'Permanent office address is required';


        $messages['applicant_name.required']         = 'Name is required';
        $messages['applicant_mobile_no.required']    = 'Mobile number is required';
        $messages['applicant_telephone_no.required'] = 'Telephone number is required';
        $messages['applicant_email.required']        = 'Email is required';
        $messages['applicant_district.required']     = 'District is required';
        $messages['applicant_thana.required']        = 'Thana is required';
        $messages['applicant_address.required']      = 'Address is required';


        $messages['contact_name.required']        = 'Contact person name is required';
        $messages['contact_designation.required'] = 'Contact person designation is required';
        $messages['contact_mobile.required']      = 'Contact person mobile number is required';
        $messages['contact_email.required']       = 'Contact person email is required';
        $messages['contact_website.required']     = 'Contact person website is required';
        $messages['contact_district.required']    = 'Contact person district is required';
        $messages['contact_thana.required']       = 'Contact person thana is required';
        $messages['contact_address.required']     = 'Contact person address is required';

        $messages['shareholder_name.required']        = 'Shareholder name is required';
        $messages['shareholder_nid.required']         = 'Shareholder nid is required';
        $messages['shareholder_dob.required']         = 'Shareholder dob is required';
        $messages['shareholder_designation.required'] = 'Shareholder designation is required';
        $messages['shareholder_mobile.required']      = 'Shareholder mobile is required';
        $messages['shareholder_email.required']       = 'Shareholder email is required';
        $messages['shareholder_share_of.required']    = 'Shareholder % share of is required';


        $messages['correspondent_photo.required'] = 'Shareholder photo is required';

        return $messages;
    }

}