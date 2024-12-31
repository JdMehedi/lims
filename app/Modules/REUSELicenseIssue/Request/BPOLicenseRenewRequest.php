<?php

namespace App\Modules\REUSELicenseIssue\Request;

use Illuminate\Http\Request;

class BPOLicenseRenewRequest {

    private $request;

    public function __construct( Request $request ) {
        $this->request = $request;
    }

    public function rules() {
        return [
            'existing_district.*'         => 'required',
            'existing_thana.*'            => 'required',
            'nature_of_center.*'          => 'required',
//            'type_of_center.*'            => 'required',
            'name_call_center_provider.*' => 'required|string',
            'existing_license_no.*'       => 'required|string',
            'date_of_license.*'           => 'required|date_format:d-M-Y',
            'starting_date_of_service.*'  => 'required|date_format:d-M-Y',
            'no_of_agents.*'              => 'required|integer',
            'bandwidth.*'                 => 'required|numeric',
            'name_of_clients.*'           => 'required',
            'type_of_activity.*'          => 'required',
            'bandwidth_call_center'       => 'required',
            'address_of_foreign'          => 'required',
            'existing_bandwidth_iplc'     => 'required|numeric',
            'existing_bandwidth_backup'   => 'required|numeric',
            'bandwidth_provider_iplc'     => 'required|numeric',
            'bandwidth_provider_backup'   => 'required|numeric',
            'local_employee'              => 'required|integer',
            'foreign_employee'            => 'required|integer',
            'financial_years.*'           => 'required|string',
            'financial_amount.*'          => 'required|numeric',
        ];
    }

    public function messages() {
        return [];
    }

}
