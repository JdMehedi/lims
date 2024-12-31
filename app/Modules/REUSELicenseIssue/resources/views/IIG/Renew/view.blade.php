<style>

    .border-header-box {
        padding: 25px 10px 15px;
        margin-bottom: 30px;
    }

    .border-header-txt {
        margin-top: -36px;
        position: absolute;
        background: #fff;
        padding: 0px 15px;
        font-weight: 600;
    }
    .\!font-normal {
        font-weight: normal !important;
    }

    label {
        font-weight: normal;
        font-size: 14px;
    }

    span {
        font-size: 14px;
    }

    .section_head {
        font-size: 24px;
        font-weight: 400;
        margin-top: 25px;
    }

    @media (min-width: 767px) {
        .addressField {
            width: 79.5%;
            float: right
        }
    }

    @media (max-width: 480px) {
        .section_head {
            font-size: 20px;
            font-weight: 400;
            margin-top: 5px;
        }

        label {
            font-weight: normal;
            font-size: 13px;
        }

        span {
            font-size: 13px;
        }

        .panel-body {
            padding: 10px 0 !important;
        }

        .form-group {
            margin: 0;
        }

        .image_mobile {
            width: 100%;
        }
    }
    @media (max-width: 1200px) {
        .responsive-row {
            display: block;
        }
    }
    .table-responsive {
        display: inline-table!important;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>

@if(in_array($appInfo->status_id, [2,5,17,18,19,20,22,30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 47, 48, 49]))
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Related Reports
        </div>
        <div class="card-body" style="padding: 15px 25px;">
            @if(isset($appInfo->dd_file_1))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_1)}}">View Evaluation
                    Report</a>
            @endif
            @if(isset($appInfo->dd_file_2))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_2)}}">View Commission Meeting
                    Agenda</a>
            @endif
            @if(isset($appInfo->dd_file_3))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_3)}}">View Ministry Approval  </a>
            @endif
            @if(isset($appInfo->dd_file_4))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_4)}}">View Inspection
                    Report</a>
            @endif
            @if(isset($appInfo->shortfall_reason))
                <a class="btn btn-primary" data-toggle="modal" data-target="#shortFallModal" href="#">View Shortfall Reason</a>
            @endif
            @if(isset($latter[1]))
            <a class="btn btn-primary" target="_blank" href="{{url($latter[1])}}">Shortfall Letter  </a>
            @endif
            @if(isset($latter[2]))
            <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[2])}}">Request for Payment Letter</a>
            @endif 
        </div>
    </div>
@endif

<div id="paymentPanel"></div>

<div class="card-body" id="applicationForm" style="padding: 0px!important;">

    <fieldset>
        {{-- Company Informaiton --}}
        @includeIf('common.subviews.CompanyInfo', ['mode' => 'view'])
        {{-- Applicant Profile --}}
        @includeIf('common.subviews.ApplicantProfile', ['mode' => 'view'])
        {{-- Name of Authorized Signatory and Contact Person --}}
        @includeIf('common.subviews.ContactPerson', ['mode' => 'view'])
        {{-- Share Holder --}}
        @includeIf('common.subviews.Shareholder', ['mode' => 'view'])

    </fieldset>
    <fieldset>

        {{-- Necessary attachment --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Required Documents for attachment
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div id="">
                    <table class="table-bordered table-responsive table-striped" style="width: 100%;">
                        <thead>
                        <th style="padding: 10px;">Document Name</th>
                        <th style="padding: 10px;">File</th>
                        </thead>
                        <tbody>

                        @if(count($appDynamicDocInfo) > 0)
                            @foreach($appDynamicDocInfo as $docInfo)
                                <tr>
                                    @if($docInfo->uploaded_path)
                                        <td style="padding: 10px;">{{$docInfo->doc_name}}</td>
                                        <td style="padding: 10px;" class="text-center" ><a target="_blank" href="{{url('/').'/uploads/'.$docInfo->uploaded_path}}">View</a> </td>
                                    @else
                                        <td style="padding: 10px;">{{$docInfo->doc_name}}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" style="text-align: center;">No Data found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Declaration --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Declaration
            </div>
            <div class="card-body" style="padding: 15px 25px;">

                <div class="row">
                    <div class="col-md-12">
                        <ol>
                          
                            <li ><span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> declare that all the information furnished in this application form is true and correct. <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> understand that approval from the Commission for this application is based on information as declared in this application Should any of the information as declared be incorrect, then any License granted by the Commission may be</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> also declare that I/we have read, understood and undertake to comply with ad the terms and conditions outlined or referred to in the Commision document ented Regulatory and Licensing Guidelines for invitation of application for granting of Gicense in the country, and those terms and conditions included in the License to be issued to us/me, if this application is approved by the Commission</li>
                        </ol>
                    </div>
                </div>


            </div>
        </div>
    </fieldset>

</div>

<div class="modal fade" id="shortFallModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Shortfall Reason</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! $appInfo->shortfall_reason !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('assets/scripts/custom.min.js') }}"></script>
<script>
    $(document).on('click', '.cancelcounterpayment', function () {
        return confirm('Are you sure?');
    });

    var reg_type_id = "{{ $appInfo->regist_type }}";
    var company_type_id = "{{ $appInfo->org_type }}";
    var industrial_category_id = "{{ $appInfo->ind_category_id }}";
    var investment_type_id = "{{ $appInfo->invest_type }}";

    var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' + investment_type_id;

    loadApplicationDocs('docListDiv', key);



    @if (in_array(Auth::user()->user_type, ['5x505']) && $appInfo->status_id == 15)
    var unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>

    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
        'paymentPanel',
        "{{ $appInfo->applicant_name }}",
        "{{ $appInfo->applicant_email }}",
        "{{ $appInfo->applicant_mobile }}",
        "{{ $appInfo->applicant_address }}"
        , unfixed_amounts);
    @endif
</script>

<script>
    $(document).ready(function () {
        var company_type = "{{$appInfo->company_type}}";

        if( company_type == ""){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        }else if(company_type == 1){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        }else{
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }

        var declaration_q1 = "{{$appInfo->declaration_q1}}";
        var declaration_q2 = "{{$appInfo->declaration_q2}}";
        var declaration_q3 = "{{$appInfo->declaration_q3}}";
        var declaration_q4 = "{{$appInfo->declaration_q4}}";

        if(declaration_q1 == 'Yes') {
            $('#if_declaration_q1_yes').css('display','inline');
        }

        if(declaration_q2 == 'Yes') {
            $('#if_declaration_q2_yes').css('display','inline');
        }

        if(declaration_q3 == 'Yes') {
            $('#if_declaration_q3_yes').css('display','inline');
        }

        if (declaration_q4 == 'Yes') {
            $('#if_declaration_q4_yes').css('display', 'inline');
        }


    });
</script>
