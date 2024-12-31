<?php
$user_type = Auth::user()->user_type;
$type = explode('x', $user_type);
$check_association_from = checkCompanyAssociationForm();
$bscicUsers = getBscicUser();
$is_eligibility = 0;
if ($user_type == '5x505') {
    $is_eligibility = \App\Libraries\CommonFunction::checkEligibility();
}
?>
<style>
    .radio_label {
        cursor: pointer;
    }

    .small-box {
        margin-bottom: 0;
        cursor: pointer;
        border-radius: 10px;
        box-shadow: 3px 3px 10px #ccc;
        font-family: poppins, sans-serif;
    }

    .small-box h1{
        color:#452A73;
    }

    .small-box > .inner {
        padding: 20px 10px;
    }

    .border_notice_box{
        border: 1px solid #ccc;
        border-left: 6px solid #0d6aad;
        border-radius: 6px;
        padding: 10px;
        vertical-align: middle;
        margin-bottom: 15px;
    }
    .border_notice_box button{
        height: 37px;
        width: 70px;
        margin: auto 0px;
        border-radius: 5px;
    }

    @media (min-width: 481px) {
        .g_name {
            font-size: 32px;
        }
    }

    @media (max-width: 480px) {
        .g_name {
            font-size: 18px;
        }

        span {
            font-size: 14px;
        }

        label {
            font-size: 14px;
        }
    }

    @media (min-width: 767px) {
        .has_border {
            border-left: 1px solid lightgrey;
        }

        .has_border_right {
            border-right: 1px solid lightgrey;
        }
    }
</style>

@if (isset($services) && in_array($type[0], [1, 4]))

    <!-- <div class="row mb-4">

        @foreach ($services as $service)
        <div class="col-lg-3 col-6">
            <div class="small-box bg-{{ !empty($service->panel) ? $service->panel : 'info' }}">
                    <div class="inner">
                        <h3> {{ !empty($service->totalApplication) ? $service->totalApplication : '0' }}</h3>
                        <p>{{ !empty($service->name_bn) ? $service->name_bn : 'N/A' }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas {{ $service->icon }}"></i>
                    </div>
                    <a class="small-box-footer"
                        href="{{ !empty($service->form_url) && $service->form_url == '/#' ? 'javascript:void(0)' : url($service->form_url . '/list/' . \App\Libraries\Encryption::encodeId($service->id)) }}"
                        {{ !empty($service->form_url) && $service->form_url != '/#' ? 'target="_blank"' : '' }}>
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @endforeach

        </div>-->

    <!-- License Type-->
    <div class="container mb-5">
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box text-center">
                    <div class="inner">
                        <h1>6</h1>
                        <h5>Open License</h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-center">
                    <div class="inner">
                        <h1>7</h1>
                        <h5>Biding License</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard-->
    <div class="container my-5">
        <h4>Dashboard</h4>
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box text-center">
                    <div class="inner px-4 d-flex justify-content-between">
                        <div>
                            <h1>100</h1>
                            <h6>Draft Application</h6>
                        </div>
                        <img src="https://via.placeholder.com/50x60" alt="">
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-center">
                    <div class="inner px-4 d-flex justify-content-between">
                        <div>
                            <h1>150</h1>
                            <h6>Process Application</h6>
                        </div>
                        <img src="https://via.placeholder.com/50x60" alt="">
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-center">
                    <div class="inner px-4 d-flex justify-content-between">
                        <div>
                            <h1>200</h1>
                            <h6>Approved Application</h6>
                        </div>
                        <img src="https://via.placeholder.com/50x60" alt="">
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box text-center">
                    <div class="inner px-4 d-flex justify-content-between">
                        <div>
                            <h1>258</h1>
                            <h6>Total  Application</h6>
                        </div>
                        <img src="https://via.placeholder.com/50x60" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notices-->
    <div class="container mb-5">
        <h4>Notice & Instructions</h4>
        <div class="col-lg-12 col-12 border_notice_box">
            <div class="px-4 d-flex justify-content-between">
                <div class="mt-2">
                    <h5>Notice for Scheduled Maintenance 2</h5>
                    <h6>15 Mar 2021</h6>
                </div>
                <button class="btn btn-sm btn-default">Top &nbsp; <i class="fa fa-caret-right"></i></button>
            </div>
        </div>
        <div class="col-lg-12 col-12 border_notice_box">
            <div class="px-4 d-flex justify-content-between">
                <div class="mt-2">
                    <h5>Notice for Scheduled Maintenance 2</h5>
                    <h6>15 Mar 2021</h6>
                </div>
                <button class="btn btn-sm btn-default">Top &nbsp; <i class="fa fa-caret-right"></i></button>
            </div>
        </div>
        <div class="col-lg-12 col-12 border_notice_box">
            <div class="px-4 d-flex justify-content-between">
                <div class="mt-2">
                    <h5>Notice for Scheduled Maintenance 2</h5>
                    <h6>15 Mar 2021</h6>
                </div>
                <button class="btn btn-sm btn-default">Top &nbsp; <i class="fa fa-caret-right"></i></button>
            </div>
        </div>
        <div class="col-lg-12 col-12 border_notice_box">
            <div class="px-4 d-flex justify-content-between">
                <div class="mt-2">
                    <h5>Notice for Scheduled Maintenance 2</h5>
                    <h6>15 Mar 2021</h6>
                </div>
                <button class="btn btn-sm btn-default">Top &nbsp; <i class="fa fa-caret-right"></i></button>
            </div>
        </div>
    </div>

@endif


@if (Auth::user()->user_type == '5x505')
    <section>

        @if (!empty($userApplicaitons))
            <div class="row">
                <div style='cursor: pointer;' class="form-group col-lg-3 col-md-3 col-6">
                    <div class="small-box" style="background: white; border-radius: 10px; padding: 15px;">

                        {!! Form::open([
                            'url' => '/process/list',
                            'method' => 'POST',
                            'class' => 'draftButtonForm',
                            'id' => '',
                            'role' => 'form',
                        ]) !!}
                        <div class="row" id="draftButton">
                            <div class="col-md-8 col-6">
                                <p
                                    style="color: #452A73; font-size: 34px; font-weight: 600; margin-bottom:0; line-height:60px">
                                    {{ $userApplicaitons['draft'] }}</p>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="small-box"
                                    style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #7C5CF5, #9B8BF7); border-radius: 10px; padding: 10px; height: 100%;margin-bottom:0;max-width:60px">
                                    <img src="/assets/images/notebook.svg" alt="" height="50%">
                                </div>
                            </div>
                            <input type="hidden" name="search_by_keyword" required class="form-control"
                                placeholder="Search by keywords" value="dashboard-search@@@ -1">
                            {{-- Input value dashboard-search@@@ fixed for dashboard search --}}
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p style="color: #452A73; font-size: 16px; font-weight: 600; margin-bottom:0">Draft
                                    Application</p>
                            </div>
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
                <div style='cursor: pointer;' class="form-group col-lg-3 col-md-3 col-6">
                    <div class="small-box" style="background: white; border-radius: 10px; padding: 15px;">
                        {!! Form::open([
                            'url' => '/process/list',
                            'method' => 'POST',
                            'class' => 'progressButtonForm',
                            'id' => '',
                            'role' => 'form',
                        ]) !!}
                        <div class="row" id="progressButton">
                            <div class="col-md-8 col-6">
                                <p
                                    style="color: #452A73; font-size: 34px; font-weight: 600; margin-bottom:0; line-height:60px">
                                    {{ $userApplicaitons['processing'] }}</p>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="small-box"
                                    style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #69D4D4, #6CD2D5); border-radius: 10px; padding: 15px; height: 100%;">
                                    <img src="/assets/images/process.svg" alt="" height="50%">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p style="color: #452A73; font-size: 16px; font-weight: 600;margin-bottom:0">Process
                                    Application</p>
                            </div>
                        </div>
                        <input type="hidden" name="search_by_keyword" required class="form-control"
                            placeholder="Search by keywords"
                            value="dashboard-search@@@ 1, 2, 9, 15, 16, 8, 10, 9">
                        {{-- Input value dashboard-search@@@ fixed for dashboard search --}}
                        {!! Form::close() !!}
                    </div>
                </div>

                <div style='cursor: pointer;' class="form-group col-lg-3 col-md-3 col-6">
                    <div class="small-box" style="background: white; border-radius: 10px; padding: 15px;">
                        {!! Form::open([
                            'url' => '/process/list',
                            'method' => 'POST',
                            'class' => 'approvedButtonForm',
                            'id' => '',
                            'role' => 'form',
                        ]) !!}
                        <div class="row" id="approvedButton">
                            <div class="col-md-8 col-6">
                                <p
                                    style="color: #452A73; font-size: 34px; font-weight: 600; margin-bottom:0; line-height:60px">
                                    {{ $userApplicaitons['approved'] }}</p>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="small-box"
                                    style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #5373DF, #458DDD); border-radius: 10px; padding: 10px; height: 100%;margin-bottom:0;max-width:60px">
                                    <img src="/assets/images/approval.svg" alt="" height="50%">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p style="color: #452A73; font-size: 16px; font-weight: 600;margin-bottom:0">Approved
                                    Application</p>
                            </div>
                        </div>
                        <input type="hidden" name="search_by_keyword" required class="form-control"
                            placeholder="Search by keywords" value="dashboard-search@@@ 25">
                        {{-- Input value dashboard-search@@@ fixed for dashboard search --}}
                        {!! Form::close() !!}
                    </div>
                </div>


                <div style='cursor: pointer;' class="form-group col-lg-3 col-md-3 col-6">
                    <div class="small-box" style="background: white; border-radius: 10px; padding: 15px;">
                        {!! Form::open([
                            'url' => '/process/list',
                            'method' => 'POST',
                            'class' => 'shortfallButtonForm',
                            'id' => '',
                            'role' => 'form',
                        ]) !!}
                        <div class="row" id="shortfallButton">
                            <div class="col-md-8 col-6">
                                <p
                                    style="color: #452A73; font-size: 34px; font-weight: 600; margin-bottom:0; line-height:60px">
                                    {{ $userApplicaitons['shortfallapp'] }}</p>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="small-box"
                                    style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #5373DF, #458DDD); border-radius: 10px; padding: 10px; height: 100%;margin-bottom:0;max-width:60px">
                                    <img src="/assets/images/approval.svg" alt="" height="50%">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p style="color: #452A73; font-size: 16px; font-weight: 600;margin-bottom:0">Shortfall
                                    Application</p>
                            </div>
                        </div>
                        <input type="hidden" name="search_by_keyword" required class="form-control"
                            placeholder="Search by keywords" value="dashboard-search@@@ 5">
                        {{-- Input value dashboard-search@@@ fixed for dashboard search --}}
                        {!! Form::close() !!}
                    </div>
                </div>

                <div style='cursor: pointer;' class="form-group col-lg-3 col-md-3 col-6">
                    <div class="small-box" style="background: white; border-radius: 10px; padding: 15px;">
                        {!! Form::open([
                            'url' => '/process/list',
                            'method' => 'POST',
                            'class' => 'rejectedButtonForm',
                            'id' => '',
                            'role' => 'form',
                        ]) !!}
                        <div class="row" id="rejectedButton">
                            <div class="col-md-8 col-6">
                                <p
                                    style="color: #452A73; font-size: 34px; font-weight: 600; margin-bottom:0; line-height:60px">
                                    {{ $userApplicaitons['rejectapp'] }}</p>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="small-box"
                                    style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #5373DF, #458DDD); border-radius: 10px; padding: 10px; height: 100%;margin-bottom:0;max-width:60px">
                                    <img src="/assets/images/approval.svg" alt="" height="50%">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p style="color: #452A73; font-size: 16px; font-weight: 600;margin-bottom:0">Rejected
                                    Application</p>
                            </div>
                        </div>
                        <input type="hidden" name="search_by_keyword" required class="form-control"
                            placeholder="Search by keywords" value="dashboard-search@@@ 6">
                        {{-- Input value dashboard-search@@@ fixed for dashboard search --}}
                        {!! Form::close() !!}
                    </div>
                </div>


                <div style='cursor: pointer;' class="form-group col-lg-3 col-md-3 col-6">
                    <div class="small-box" style="background: white; border-radius: 10px; padding: 15px;">
                        {!! Form::open([
                            'url' => '/process/list',
                            'method' => 'POST',
                            'class' => 'othersButtonForm',
                            'id' => '',
                            'role' => 'form',
                        ]) !!}
                        <div class="row" id="othersButton">
                            <div class="col-md-8 col-6">
                                <p
                                    style="color: #452A73; font-size: 34px; font-weight: 600; margin-bottom:0; line-height:60px">
                                    {{ $userApplicaitons['totalapp'] }}</p>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="small-box"
                                    style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #EC6060, #FC8170); border-radius: 10px; padding: 10px; height: 100%;margin-bottom:0;max-width:60px">
                                    <img src="/assets/images/list-text.svg" alt="" height="50%">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p style="color: #452A73; font-size: 16px; font-weight: 600;margin-bottom:0">Total
                                    Application</p>
                            </div>
                        </div>
                        <input type="hidden" name="search_by_keyword" required class="form-control"
                            placeholder="Search by keywords"
                            value="dashboard-search@@@ -1,1,2,5,6,25">
                        {{-- Input value dashboard-search@@@ fixed for dashboard search --}}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @endif

        @if ( (Auth::user()->working_company_id == 0 && count($check_association_from) == 0) || $is_eligibility == 0 )

            @include('CompanyAssociation::company-association-form')
        @endif

        @include('CompanyAssociation::pending-approval-panel')

    </section>
@endif


{{-- আবেদন --}}
@if (Auth::user()->user_type == '5x505' &&
    !empty($servicesWiseApplication) &&
    Auth::user()->working_company_id != 0)
    @if($is_eligibility == 1)
        <section>
        <div class="row">
            <div class="col-md-12">
                <p style="font-size: 28px">{!! trans('Dashboard::messages.services') !!}</p>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px;">
            @foreach ($servicesWiseApplication as $item)
                @if ($item->group_name != null)
                    <div class="form-group col-md-3 col-lg-3 col-6">
                        <div class="panel-body text-center"
                            style="background: white; border-radius: 7px; box-shadow: 5px 5px 5px #D4D5D6; padding: 25px">
                            <img src="/assets/images/passport.png" alt="" style="width: 45px"><br>
                            <p class="g_name">{{ $item->group_name }}</p>
                            {{-- <p style="font-size: 22px; color: #9F9FB8">{!!trans('Dashboard::messages.new_application')!!}</p> --}}
                            <br>
                            <a class="btn btn-sm" style="color: white; background: #452A73"
                                href="/client/process/details/{{ \App\Libraries\Encryption::encode($item->group_name) }}">Application</a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>


    </section>
    @endif
@endif


<?php
$desk_id_array = explode(',', \Session::get('user_desk_ids'));
?>

@if (Auth::user()->user_type == '1x101')
    <!-- <div class="row">
        <?php
            $map_script_array = [];
            if ($dashboardObject->isNotEmpty()) {
            foreach ($dashboardObject as $row) {
            $div = 'dbobj_' . $row->db_obj_id;
            ?>
        <div class="col-md-6">
            <?php
            $para1 = DB::select(DB::raw($row->db_obj_para1));

            $para1 = array_map(function ($value) {
                return (array) $value;
            }, $para1);

            ?>
            <div class="card card-success">

                <div class="card-header">
                    <h3 class="card-title">{{ $row->db_obj_title }}</h3>
                </div>

                <div class="box-body p-3">
                    <canvas id="<?php echo $div; ?>" style="width: 100%; height: 350px; text-align:center;">
                        <br /><br />Chart
                        will be loading in 5 sec...
                    </canvas>
                </div>
            </div>
            <?php
            $script = $row->db_obj_para2;
            $datav['charttitle'] = $row->db_obj_title;
            $datav['chartdata'] = json_encode($para1);
            $datav['baseurl'] = url('/');
            $datav['chart_div'] = $div;
            $map_script_array[] = CommonFunction::updateScriptPara($script, $datav);

            ?>
        </div>
        <?php
            }
            }
            ?>
    </div>-->
@endif


@if (Auth::user()->user_type == '5x505')
    {{-- Notice & Instructions End --}}
    {{--
    <div class="notice_instruction">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Notice & Instructions</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                @foreach ($notices as $key => $notice)
                                    <div class="card card-{{ $notice->importance ?? 'default'}} border border-{{ $notice->importance ?? 'default' }}">
                                        <?php
                                        $class = '';
                                        $expend = false;
                                        if ($key == 0) {
                                            $class = ' in show';
                                            $expend = true;
                                        }
                                        ?>
                                        <a href="#notice_{{ $key }}" data-toggle="collapse" role="button"
                                            aria-expanded="{{ $expend }}" class="collapsed">
                                            <div class="card-header bg-{{ $notice->importance ?? 'default' }}">
                                                <p class="section_head card-title"
                                                    style="margin: 0; font-size: 18px; font-weight: 400">
                                                    <i class="fa fa-chevron-down" style="margin-right: 15px;"></i>
                                                    {{ $notice->heading }} &nbsp;&nbsp;&nbsp;&nbsp;
                                                    {{ date('d M Y', strtotime($notice->Date)) }}
                                                </p>
                                            </div>
                                        </a>
                                        <div id="notice_{{ $key }}" class="collapse {{ $class }}"
                                            style="">
                                            <div class="card-body">
                                                {!! $notice->details !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    --}}
@endif


@section('chart_script')
    @if (!empty($map_script_array))
        {{-- @include('partials.amchart-js') --}}
        <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>

        @foreach ($map_script_array as $script)
            <script type="text/javascript">
                $(function() {
                    <?php echo $script; ?>
                });
            </script>
        @endforeach
    @endif

@endsection

<script>
    function approveAndRejectCompanyAssoc(e, key) {
        var r = confirm("Are you sure?");
        if (r !== true) {
            return false;
        }
        e.disabled = true;
        const button_text = e.innerText;
        const loading_sign = '...<i class="fa fa-spinner fa-spin"></i>';

        var companyAssocId = e.value;
        var type = $("input:radio[name='userType']:checked").val()

        $.ajax({
            url: "{{ url('client/company-association/approve-reject') }}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                companyAssocId: companyAssocId,
                type: type,
                key: key,
            },
            beforeSend: function() {
                e.innerHTML = button_text + loading_sign;
            },
            success: function(response) {
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.warning('Yor approval was not successful!');
                console.log(errorThrown);
            },
            complete: function() {
                toastr.success('Yor approval was successful!');
            }
        });
    }
</script>

<script type="text/javascript" src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>


<script type="text/javascript">
    $('#draftButton').click(function() {
        $('.draftButtonForm').submit();
    });

    $('#progressButton').click(function() {
        $('.progressButtonForm').submit();
    });

    $('#approvedButton').click(function() {
        $('.approvedButtonForm').submit();
    });
    $('#othersButton').click(function() {
        $('.othersButtonForm').submit();
    });

    $('#shortfallButton').click(function() {
        $('.shortfallButtonForm').submit();
    });

    $('#rejectedButton').click(function() {
        $('.rejectedButtonForm').submit();
    });

</script>
