<?php

namespace App\Modules\REUSELicenseIssue\Request;

use App\Libraries\Encryption;
use Illuminate\Http\Request;

class IPTSPLicenseIssueRequest
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // $this => current instance of Request Class
    public function rules()
    {
        $request = $this->request;
        $rules   = [];
        $app_id  = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');

        $rules['company_name']           = 'required';
        $rules['company_type']           = 'required';
        $rules['reg_office_district']    = 'required';
        $rules['reg_office_thana']       = 'required';
        $rules['reg_office_address']     = 'required';

        $rules['op_office_district']     = 'required';
        $rules['op_office_thana']        = 'required';
        $rules['op_office_address']      = 'required';

        $rules['applicant_name']         = 'required';
        $rules['applicant_mobile']       = 'required';
        $rules['applicant_email']        = 'required';
        $rules['applicant_district']     = 'required';
        $rules['applicant_thana']        = 'required';
        $rules['applicant_address']      = 'required';

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
        if ($request->shareholder_nationality == 18) {
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

        // Name of Authorized Signatory
        $rules['contact_signatory_person_name']    = 'required';
        $rules['contact_signatory_designation']    = 'required';
        $rules['contact_signatory_mobile']         = 'required';
        $rules['contact_signatory_person_email']   = 'required';
        $rules['contact_signatory_district']       = 'required';
        $rules['contact_signatory_thana']          = 'required';
        $rules['contact_signatory_person_address'] = 'required';
//            $rules['contact_signatory_photo_base64'] = 'required';


        // Details of Existing ISP License
//        $rules['isp_license_number'] = 'required';
//        $rules['isp_license_date_of_expire'] = 'required';
//        $rules['types_of_isp_license'] = 'required';

        //Investment Information
        $rules['local_investment']                  = 'required';
        $rules['present_value_of_total_investment'] = 'required';
        $rules['total_investment']                  = 'required';
        $rules['foreign_investment']                = 'required';
        $rules['gross_revenue_eamed_in_last_year']  = 'required';
        $rules['gross_revenue_eamed_in_last_year_img']  = 'required|file|mimes:jpeg,png,jpg,gif,svg,application/pdf';

        // Employee Information
        $rules['employee_information'] = 'required';
        $rules['total_it_specialist']  = 'required';

        //Types of ISP License
        $rules['type_of_iptsp_licensese'] = 'required';
        if ($rules['type_of_iptsp_licensese'] == "Divisional") $rules['iptsp_licensese_area_division'] = 'required';

        // Coverage Area Information
        $rules['coverage_area']       = 'required';
        $rules['coverage_district']   = 'required';
        $rules['total_coverage_area'] = 'required';

        //Coverage Out of Area In Rural Area Information
        $rules['coverage_out_of_area']       = 'required';
        $rules['coverage_out_of_district']   = 'required';
        $rules['total_coverage_out_of_area'] = 'required';

        // Date of Commencement of the Service
        $rules['commencement_date'] = 'required';

        //  Information of Existing Subscriber Level
        $rules['existing_subscriber_dial_up']                                       = 'required';
        $rules['existing_subscriber_corporate']                                     = 'required';
        $rules['existing_subscriber_individual']                                    = 'required';
        $rules['existing_subscriber_broadband']                                     = 'required';
        $rules['existing_subscriber_name_with_corporate_clients_subscriber_number'] = 'required';

        //Average Minimam Growth Rate of Subscribers for per year Information
        $rules['subscriber_individual'] = 'required';
        $rules['subscriber_corporate']  = 'required';

        //POP Information
        $rules['no_of_POP'] = 'required';

        //Per Subscriber Average width
        $rules['per_subscriber_individual'] = 'required';
        $rules['per_subscriber_corporate']  = 'required';


        if ($request->declaration_q1 == 'Yes') {
            $rules['declaration_q1_date'] = 'required';
            $rules['declaration_q1_text'] = 'required';
        }
        if ($request->declaration_q2 == 'Yes') {
            $rules['declaration_q2_service_list']      = 'required';
            $rules['declaration_q2_company_name']      = 'required';
            $rules['declaration_q2_license_number']    = 'required';
            $rules['declaration_q2_share_holder_name'] = 'required';
        }
        if ($request->declaration_q3 == 'Yes') {
            $rules['declaration_q3_date'] = 'required';
            $rules['declaration_q3_text'] = 'required';
        }
        if ($request->declaration_q4 == 'Yes') {
            $rules['declaration_q4_case_no']                 = 'required';
            $rules['declaration_q4_amount']                  = 'required';
            $rules['declaration_q4_cheque_or_bank_draft_no'] = 'required';
            $rules['declaration_q4_given_commision']         = 'required';
        }
        $rules['accept_terms'] = 'required';

        return $rules;
    }

    public function messages()
    {
        $messages                                       = [];
        $messages['company_name.required']          = 'Company/ organization name is required';
        $messages['company_type.required']              = 'Company Type is required';
        $messages['office_district.required']           = 'Company office district is required';
        $messages['office_upazilla_thana.required']     = 'Company office upazilla is required';
        $messages['office_address.required']            = 'Company office address is required';
        $messages['par_office_district.required']       = 'Company office permanent address is required';
        $messages['par_office_upazilla_thana.required'] = 'Company office permanent address upazila is required';
        $messages['par_office_address.required']        = 'Company office permanent address is required';

        $messages['applicant_name.required']               = 'Applicant name is required';
        $messages['applicant_telephone.required']          = 'Applicant telephone no. is required';
        $messages['applicant_district.required']           = 'Applicant district is required';
        $messages['applicant_address.required']            = 'Applicant address is required';
        $messages['applicant_mobile.required']             = 'Applicant mobile is required';
        $messages['applicant_upazila_thana_list.required'] = 'Applicant upazila is required';
        $messages['applicant_email.required']              = 'Applicant email is required';

        $messages['contact_person_name.required']    = 'Contact person name is required';
        $messages['contact_designation.required']    = 'Contact person designation is required';
        $messages['contact_mobile.required']         = 'Contact person mobile number is required';
        $messages['contact_person_email.required']   = 'Contact person email is required';
        $messages['contact_website.required']        = 'Contact person website is required';
        $messages['contact_district.required']       = 'Contact person district is required';
        $messages['contact_thana.required']          = 'Contact person thana is required';
        $messages['contact_person_address.required'] = 'Contact person address is required';

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


        // Name of Authorized Signatory
        $messages['contact_signatory_person_name.required']    = 'Contact Signatory person_name is Required';
        $messages['contact_signatory_designation.required']    = 'Contact Signatory designation is Required';
        $messages['contact_signatory_mobile.required']         = 'Contact Signatory mobile is Required';
        $messages['contact_signatory_person_email.required']   = 'Contact Signatory person_email is Required';
        $messages['contact_signatory_district.required']       = 'Contact Signatory district is Required';
        $messages['contact_signatory_thana.required']          = 'Contact Signatory thana is Required';
        $messages['contact_signatory_person_address.required'] = 'Contact Signatory person_address is Required';
//            $messages['contact_signatory_photo_base64.required'] = 'Contact Signatory photo_base64 is Required';

        // Details of Existing ISP License
        $messages['isp_license_number.required']         = 'ISP License number is Required';
        $messages['isp_license_date_of_expire.required'] = 'ISP license date of expire is Required';
        $messages['types_of_isp_license.required']       = 'Types of ISP License is Required';

        //Investment Information
        $messages['local_investment.required']                  = 'Local Investment is Required';
        $messages['present_value_of_total_investment.required'] = 'Present value of total investment is Required';
        $messages['total_investment.required']                  = 'total investment is Required';
        $messages['foreign_investment.required']                = 'foreign investment is Required';
        $messages['gross_revenue_eamed_in_last_year.required']  = 'gross revenue eamed in last year is Required';
        $messages['gross_revenue_eamed_in_last_year_img']    ='only supported jpeg,png,jpg ,pdf';

        // Employee Information
        $messages['employee_information.required'] = 'employee_information is Required';
        $messages['total_it_specialist.required']  = 'total IT specialist is Required';

        //Types of ISP License
        $messages['type_of_iptsp_licensese.required']       = 'type of IPTSP licensese is Required';
        $messages['iptsp_licensese_area_division.required'] = 'type of IPTSP licensese is Required';

        // Coverage Area Information
        $messages['coverage_area.required']       = 'ISP licensese area division is Required';
        $messages['coverage_district.required']   = 'coverage area is Required';
        $messages['total_coverage_area.required'] = 'coverage district is Required';

        //Coverage Out of Area In Rural Area Information
        $messages['coverage_out_of_area.required']       = 'total coverage area is Required';
        $messages['coverage_out_of_district.required']   = 'coverage out of area is Required';
        $messages['total_coverage_out_of_area.required'] = 'coverage out of district is Required';

        // Date of Commencement of the Service
        $messages['commencement_date.required'] = 'total coverage out of area is Required';

        //  Information of Existing Subscriber Level
        $messages['existing_subscriber_dial_up.required']                                       = 'commencement_date is Required';
        $messages['existing_subscriber_corporate.required']                                     = 'existing_subscriber_dial_up is Required';
        $messages['existing_subscriber_individual.required']                                    = 'corporate is Required';
        $messages['existing_subscriber_broadband.required']                                     = 'individual is Required';
        $messages['existing_subscriber_name_with_corporate_clients_subscriber_number.required'] = 'broadband is Required';

        //Average Minimam Growth Rate of Subscribers for per year Information
        $messages['subscriber_individual.required'] = 'name with corporate clients subscriber number is Required';
        $messages['subscriber_corporate.required']  = 'subscriber individual is Required';

        //POP Information
        $messages['no_of_POP.required'] = 'subscriber corporate is Required';

        //Per Subscriber Average width
        $messages['per_subscriber_individual.required'] = 'no of POP is Required';
        $messages['per_subscriber_corporate.required']  = 'per subscriber individual is Required';

        $messages['declaration_q1_date.required']              = 'declaration q1 date is required';
        $messages['declaration_q1_text.required']              = 'declaration q1 text is required';
        $messages['declaration_q2_service_list.required']      = 'declaration q2 service list is required';
        $messages['declaration_q2_company_name.required']      = 'declaration q2 company name is required';
        $messages['declaration_q2_license_number.required']    = 'declaration q2 license_n mber is required';
        $messages['declaration_q2_share_holder_name.required'] = 'declaration q2 share_holder name is required';
        $messages['declaration_q3_date.required']              = 'declaration q3 date is required';
        $messages['declaration_q3_text.required']              = 'declaration q3 text is required';

        $messages['accept_terms.required'] = 'Please Agree with Terms and Conditions';
        return $messages;
    }

}
