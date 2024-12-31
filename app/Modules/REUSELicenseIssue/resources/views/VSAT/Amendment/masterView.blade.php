<div id="paymentPanel"></div>
<div class="card" style="border-radius: 10px;" id="applicationForm">
    {!! Form::open([
        'url' => url('isp-license-issue/store'),
        'method' => 'post',
        'class' => 'form-horizontal',
        'id' => 'application_form',
        'enctype' => 'multipart/form-data',
        'files' => 'true',
        'onSubmit' => 'enablePath()',
    ]) !!}
    @csrf
    <fieldset>
        @if(in_array($appInfo->status_id, [2,5,16,17,18,22,30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44]))
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Related Reports
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    @if(isset($appInfo->dd_file_1))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_1)}}">View Evaluation
                            Report</a>
                    @endif
                    @if(isset($appInfo->dd_file_2))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_2)}}">View Commission
                            Meeting Agenda</a>
                    @endif
                    @if(isset($appInfo->dd_file_3))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_3)}}">View Ministry
                            Report</a>
                    @endif
                    @if(isset($appInfo->dd_file_4))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_4)}}">View Inspection
                            Report</a>
                    @endif
                    @if(isset($appInfo->shortfall_reason))
                        <a class="btn btn-primary m-1" data-toggle="modal" data-target="#shortFallModal" href="#">View Shortfall Reason</a>
                    @endif
                    @if(isset($latter[1]))
                    <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[1])}}">Shortfall Letter  </a>
                    @endif
                    @if(isset($latter[2]))
                    <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[2])}}">Request for Payment Letter</a>
                    @endif
                    @if(isset($latter[3]))
                    <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[3])}}">BPO/Call Center Registration Certificate</a>
                    @endif
                    @if(isset($latter[4]))
                    <a class="btn btn-primary m-1
                    " target="_blank" href="{{url($latter[4])}}">BG Payment Letter</a>
                    @endif
                </div>
            </div>
        @endif

            @includeIf('common.subviews.CompanyInfo', ['mode' => 'view'])

            @includeIf('common.subviews.ApplicantProfile', ['mode' => 'view'])

            @includeIf('common.subviews.ContactPerson', ['mode' => 'view'])

            @includeIf('common.subviews.Shareholder', ['mode' => 'view'])



            {{--            --}}{{-- Equipment List --}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                SatelliteCommunication Service Provider Information
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">

                                <table class="table table-bordered" id="equipment_tbl">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SL No.</th>
                                        <th>Service Provider Name</th>
                                        <th>Service Detials</th>
                                        <th>Location</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(isset($vsat_service_provider) && count($vsat_service_provider) > 0)

                                        @foreach($vsat_service_provider as $index=>$value)

                                            <tr row_id="equipment_{{$index+1}}">
                                                <td>{{$index+1}}</td>
                                                <td> {{ $value->service_provider_ame }} </td>
                                                <td> {{ $value->service_detials }} </td>
                                                <td> {{ $value->location }} </td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No available data.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

{{--                        vsat hub operator--}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                SatelliteCommunication Service Provider Information
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">

                                <table class="table table-bordered" id="equipment_tbl">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SL No.</th>
                                        <th>Place Name (Installed/Existing)</th>
                                        <th>Geographical Location (Measured by Set)</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(isset($vsat_hub_info) && count($vsat_hub_info) > 0)

                                        @foreach($vsat_hub_info as $index=>$value)

                                            <tr row_id="equipment_{{$index+1}}">
                                                <td>{{$index+1}}</td>
                                                <td> {{ $value->place_name }} </td>
                                                <td> {{ $value->geographical_location }} </td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No available data.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

{{--                        technical Specification/ Catalogue--}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Technical Specification/ Catalogue
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">

                                <table class="table table-bordered" id="equipment_tbl">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SL No.</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Manufacturer</th>
                                        <th>Country of Origin</th>
                                        <th>Power Output</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($vsat_technical_specification) && count($vsat_technical_specification) > 0)

                                        @foreach($vsat_technical_specification as $index=>$value)

                                            <tr row_id="equipment_{{$index+1}}">
                                                <td>{{$index+1}}</td>
                                                <td> {{ $value->name }} </td>
                                                <td> {{ $value->type }} </td>
                                                <td> {{ $value->manufacturer }} </td>
                                                <td> {{ $value->country_of_origin }} </td>
                                                <td> {{ $value->power_output }} </td>


                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No available data.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

{{--                        List of Equipment for Monitoring--}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                List of Equipment for Monitoring
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">

                                <table class="table table-bordered" id="equipment_tbl">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SL No.</th>
                                        <th>Equipment Name</th>
                                        <th>Storage Capacity</th>
                                        <th>Data</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(isset($vsat_equipment_list) && count($vsat_equipment_list) > 0)

                                        @foreach($vsat_equipment_list as $index=>$value)

                                            <tr row_id="equipment_{{$index+1}}">
                                                <td>{{$index+1}}</td>
                                                <td> {{ $value->equipment_name }} </td>
                                                <td> {{ $value->storage_capacity }} </td>
                                                <td> {{ $value->data }} </td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No available data.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </fieldset>


            <fieldset>
            {{-- Declaration --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Declaration
                </div>
                <div class="card-body" style="padding: 15px 25px;">

                    <div class="row">
                        <div class="col-md-12">
                            <ol>
                                <li>
                                    Has any Application for License of VSAT-HUB Operator been rejected before?
                                    <div style="margin-top: 20px;">
                                        {{ Form::radio('declaration_q1', 'Yes', $appInfo->declaration_q1=='Yes'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes','disabled']) }}
                                        {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                        {{ Form::radio('declaration_q1', 'No', $appInfo->declaration_q1=='No'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no','disabled']) }}
                                        {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                    </div>
                                    <div style="margin-top: 20px;">
                                        {{ Form::textarea('declaration_q1_text',$appInfo->declaration_q1_text, array('class' =>'form-control input required', 'id'=>'if_declaration_q1_yes', 'style'=>'display:none;', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'readonly'))}}
                                    </div>

                                </li>
                                <li>
                                    Has any Application for License of VSAT-HUB Operator been rejected before?

                                    <div style="margin-top: 20px;">
                                        {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2=='Yes'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes','disabled']) }}
                                        {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                        {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2=='No'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no','disabled']) }}
                                        {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                    </div>
                                    <div style="margin-top: 20px;">
                                        {{ Form::textarea('declaration_q2_text', $appInfo->declaration_q2_text, array('class' =>'form-control input required', 'id'=>'if_declaration_q2_yes', 'style'=>'display:none;', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '' ,'readonly'))}}
                                    </div>

                                </li>

                                <li>
                                    Do the Applicant(s) any Share Holder(s) Partners) hold any other Operator from the
                                    Commision?

                                    <div style="margin-top: 20px;">
                                        {{ Form::radio('declaration_q3', 'Yes', $appInfo->declaration_q3=='Yes'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes','disabled']) }}
                                        {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                        {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3=='No'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no','disabled']) }}
                                        {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                    </div>
                                    <div
                                        style="margin-top: 20px; {{($appInfo->declaration_q3=='No')?'display:  none;':'' }}"
                                        id="if_declaration_q3_yes">
                                        <a class="btn btn-file" target="_blank"
                                           href="{{ asset($appInfo->declaration_q3_doc) }}"><i class="fa fa-file"></i> View
                                            Document</a>
                                    </div>
                                </li>
                                @php $subject = ($appInfo->org_type == 3) ? 'I' : 'We' @endphp
                                <li><span class="i_we_dynamic">{{ $subject }}</span> hereby certify that <span
                                        class="i_we_dynamic">{{ $subject }}</span> have carefully read the guidelines/terms
                                    and conditions, for the license and <span class="i_we_dynamic">{{ $subject }}</span>
                                    undertake to comply with the terms and conditions therein. (Terms and Conditions of
                                    License Guidelines for VSAT-HUB Operator are available at www.btrc.gov.bd)
                                </li>
                                <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $subject }}</span> hereby
                                    certify that <span class="i_we_dynamic">{{ $subject }}</span> have carefully read the
                                    section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span
                                        class="i_we_dynamic">{{ $subject }}</span> are not disqualified from obtaining the
                                    license.
                                </li>
                                <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $subject }}</span> understand
                                    that this application if found incomplete in any respect and or if found with
                                    conditional compliance shall be summarily rejected.
                                </li>
                                <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $subject }}</span> understand
                                    that if at any time any information furnished for obtaining the license is found
                                    incorrect then the license if granted on the basis of such application shall deemed to
                                    be cancelled and shall be liable for action as per Bangladesh Telecommunication
                                    Regulation Act, 2001.
                                </li>

                            </ol>
                        </div>
                    </div>


                </div>
            </div>
        </fieldset>

</div>


<script src="{{ asset('assets/scripts/custom.min.js') }}"></script>
<script>

    @if($appInfo->declaration_q1 == 'Yes')

    $('#if_declaration_q1_yes').css('display', 'block');
    @endif

    @if($appInfo->declaration_q2 == 'Yes')
    $('#if_declaration_q2_yes').css('display', 'block');
    @endif

    @if($appInfo->declaration_q3 == 'Yes')
    $('#if_declaration_q3_yes').css('display', 'block');
    @endif

    $("#if_declaration_q1_yes").change(function () {
        alert($(this).is(":checked"));
    });

    $("#declaration_q1_no").on('click', function () {
        $('#if_declaration_q1_yes').css('display', 'none');
    });

    $("#declaration_q2_yes").on('click', function () {
        $('#if_declaration_q2_yes').css('display', 'inline');
    });
    $("#declaration_q2_no").on('click', function () {
        $('#if_declaration_q2_yes').css('display', 'none');
    });

    $("#declaration_q3_yes").on('click', function () {
        $('#if_declaration_q3_yes').css('display', 'inline');
    });
    $("#declaration_q3_no").on('click', function () {
        $('#if_declaration_q3_yes').css('display', 'none');
    });


    $(document).on('click', '.cancelcounterpayment', function () {
        return confirm('Are you sure?');
    });

    var reg_type_id = "{{ $appInfo->regist_type }}";
    var company_type_id = "{{ $appInfo->org_type }}";
    var industrial_category_id = "{{ $appInfo->ind_category_id }}";
    var investment_type_id = "{{ $appInfo->invest_type }}";
    var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' + investment_type_id;

    loadApplicationDocs('docListDiv', key);

    // display payment panel
    @if(in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [15]))
    let fixed_amounts = {
        1: 0,
        2: 0,
        3: 0,
        4: 0,
        5: 0,
        6: 0
    };
    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '2',
        'paymentPanel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}",
        fixed_amounts);
    @endif
</script>
