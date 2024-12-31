<style>
    .modal {
        text-align: center;
        padding: 0!important;
    }

    .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px;
    }

    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }

    .skip-button {
        background-color: #1a73e8;
        border-color: rgba(0,0,0,0.1);
        min-width: 80%;
        color: white;
        border-radius: 5px;
        height: 34px;
        margin-top: 20px;
        transition: 0.5s;
    }

    .skip-button:hover {
        background-color: #5496ff;
    }

    h5 {
        width: 77%;
        text-align: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.2);
        line-height: 0.1em;
        margin: 20px 45px 20px;
    }

    h5 span {
        background:#fff;
        padding:0 5px;
    }

    .select2-container--default .select2-selection--single {
        height: 34px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 34px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px !important;
    }

    .submit-button {
        width: 90%;
        margin-top: 20px;
    }

    .btn-primary {
        background-color: #1a73e8 !important;
    }
</style>
<link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">


<div class="row">

    <!-- Trigger the modal with a button -->
    <button type="button" style="display: none" id="myModalBtn" class="btn btn-info btn-lg" data-toggle="modal"
            data-target="#myModal" data-backdrop="static" data-keyboard="false">Open Modal
    </button>


    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog" style="width: 600px;">
            <!-- Modal content-->
            <div class="modal-content">
                {!! Form::open(array('url' => 'company-association/update-working-company', 'method' => 'post','id'=>'formId')) !!}
                <div class="modal-body" style="text-align: center; margin-bottom: 20px">
                    <div class="row">
                        <div class="col-md-10 m-auto">
                            <h4 class="text-center">{!!trans('Dashboard::messages.select_working_company')!!}</h4>
                            @if(!empty(Auth::user()->working_company_id))
                                <button type="button" class="skip-button" id="steps_modal" data-dismiss="modal" value="skip" style="border:0; padding:0;">Continue with ({{ CommonFunction::getCompanyNameById(Auth::user()->working_company_id) }}) company</button>
                            @endif
                            <h5><span>{!!trans('Dashboard::messages.or')!!}</span></h5>

                            <div class="col-md-12">
                                <div class="control-group">
                                    <select name="requested_company_id" class="form-control required limitedNumbSelect2" data-placeholder="Select working company" style="width: 80%;">
                                        @foreach($companyList as $company)
                                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('requested_company_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-11">
                            <div class="submit-button text-right">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

</div>
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">



@section('footer-script')
    <script>

        $(window).on('load', function () {
            document.getElementById('myModalBtn').click();
        });

    </script>
    <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
    <script>
        $(document).ready(function () {
            //Select2
            $(".limitedNumbSelect2").select2(
            );

            $('#steps_modal').click(function () {
                window.location.replace('{!! config('app.PROJECT_ROOT') !!}/company-association/skip');
            })

        });
    </script>
@endsection
