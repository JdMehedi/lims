<?php
use Carbon\Carbon;
$user_type = Auth::user()->user_type;
$type = explode('x', $user_type);
$check_association_from = checkCompanyAssociationForm();
$bscicUsers = getBscicUser();
$is_eligibility = 0;
if ($user_type == '5x505') {
    $is_eligibility = \App\Libraries\CommonFunction::checkEligibility();
}

$filtered_services = DB::select(DB::raw("SELECT * FROM (SELECT id, NAME, name_bn, panel, form_url, icon, SUM(total_app) totalApplication, group_name FROM (SELECT
    pt.*,
    COUNT(pl.id) total_app
  FROM
    `process_type` pt
    LEFT JOIN `process_list` pl
      ON pt.`id` = pl.`process_type_id`
	WHERE pl.status_id NOT IN(-1)
  GROUP BY pt.id ORDER BY id ASC) AS process_data
  GROUP BY group_name) pa"));

$from = Carbon::now();
$to = Carbon::now();
// applicant 4 years and other desk users 6 months of data will be shown by default
$previous_month = (in_array($user_type, ['5x505', '6x606']) ? 36 : 6);
$from->subMonths($previous_month); //maximum 5month data selection by default

$desk_services = DB::select(DB::raw("SELECT * FROM (SELECT id, NAME, name_bn, panel, form_url, icon, SUM(total_app) totalApplication, group_name FROM (SELECT
pt.*,
COUNT(pl.id) total_app
FROM
`process_type` pt
LEFT JOIN `process_list` pl
  ON pt.`id` = pl.`process_type_id`
WHERE pl.status_id NOT IN(-1,3) AND pl.created_at between '" . $from . "' and '" . $to  . "'
AND pl.desk_id IN( '" . Auth::user()->desk_id . "')
AND pl.user_id IN (0,'" . Auth::user()->id . "')
GROUP BY pt.id ORDER BY id ASC) AS process_data
GROUP BY group_name) pa"));

// $isp_services = DB::select(DB::raw("SELECT * FROM (SELECT id, NAME, name_bn, panel, form_url, icon, SUM(total_app) totalApplication, group_name FROM (SELECT
// pt.*,
// COUNT(pl.id) total_app
// FROM
// `process_type` pt
// LEFT JOIN `process_list` pl
//   ON pt.`id` = pl.`process_type_id`
// WHERE pl.process_type_id IN(1,2) AND pl.status_id IN(-1,25,3)
// AND pl.created_at between '" . $from . "' and '" . $to  . "'
// AND pl.desk_id IN( '" . Auth::user()->desk_id . "')
// GROUP BY pt.id ORDER BY id ASC) AS process_data
// GROUP BY group_name) pa"));

$desk_sum=0;
$count=0;
?>
<style>
    .input_disabled {
        pointer-events: none;
    }

    .radio_label {
        /*cursor: pointer;*/
    }

    .small-box {
        margin-bottom: 0;
        /*cursor: pointer;*/
    }

    .d-grid {
        display: grid;
    }

    .grid-col-12 {
        grid-template-columns: repeat(8, 1fr);
        gap: 10px;
        margin-bottom: 10px;
    }

    .h-150 {
        height: 160px;
    }

    .h-125 {
        height: 125px;
    }

    .panel-body.text-center {
        min-height: 225px;
        max-height: 225px;
    }

    @media (max-width: 1200px) {
        .grid-col-12 {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    @media (max-width: 1000px) {
        .grid-col-12 {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 500px) {
        .grid-col-12 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 481px) {
        .g_name {
            font-size: 30px;
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
@foreach ($desk_services as $desk_service)
            <?php
            $desk_sum = $desk_sum + ($desk_service->totalApplication);
            ?>
@endforeach

@if(Auth::user()->user_type == '4x404' )

<div style="border: #28a745 solid 5px;margin-bottom:10px;">

<div class="col-md-2" style="background-color: #28a745;margin:15px;border-radius: 25px;" id="see_my_desk" >

                <a href="#" class="small-box-footer" style="color:white !important;">
                    <div class=" bg h-125" style="text-align:center;border-radius: 25px !important;padding-top:10px;">
                        <div class="inner h-105">
                            <!-- @if(!empty($isp_services))
                            <h3> {{ $desk_sum + $isp_services[0]->totalApplication}}</h3>
                            @else -->
                            <h3> {{ $desk_sum}}  </h3>
                            <!-- @endif -->

                            <p>My desk</p>
                        </div>

                        <a href="#" class="small-box-footer" style="color:white !important;">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>

                    </div>
                </a>


            </div>

            <div class="col-md-2" style="background-color: #28a745;margin:15px;display: none;border-radius: 25px;" id="hide_my_desk" >

            <a href="#" class="small-box-footer" style="color:white !important;">
                    <div class="bg h-125" style="text-align:center;border-radius: 25px !important;padding-top:10px;">
                        <div class="inner h-105">
                            <!-- @if(!empty($isp_services))
                            <h3> {{ $desk_sum + $isp_services[0]->totalApplication}}</h3>
                            @else -->
                            <h3> {{ $desk_sum}}</h3>
                            <!-- @endif -->

                            <p>My desk</p>
                        </div>


                        <a href="#" class="small-box-footer" style="color:white !important;">
                            Less info <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </a>


            </div>

</div>



            <div id="my_desk" style="display: none; border: #28a745 solid 5px;">

            <strong style="margin:10px;"> My desk</strong>
            <div class="d-grid grid-col-12" style="margin:10px;">

            @foreach ($desk_services as $desk_service)
            <?php $count++; ?>

            <div>
                <a class="small-box-footer" style="color:white !important;"
                   href="{{ !empty($desk_service->form_url) && $desk_service->form_url == '/#' ? 'javascript:void(0)' : url($desk_service->form_url . '/list/' . \App\Libraries\Encryption::encodeId($desk_service->id)) }}"
                    {{ !empty($desk_service->form_url) && $desk_service->form_url != '/#' ? '' : '' }}>
                    <div class=" bg-{{ !empty($desk_service->panel) ? $desk_service->panel : 'info' }} h-125 " style="text-align:center;border-radius: 25px !important;padding-top:20px;" >
                        <div class="inner h-105">
                            <!-- @if( !empty($isp_services) &&($count==1 || $count==2))
                            <h3> {{ !empty($desk_service->totalApplication) ? ($desk_service->totalApplication) + ($isp_services[0]->totalApplication) : '0' }}</h3>
                            @else -->
                            <h3> {{ !empty($desk_service->totalApplication) ? $desk_service->totalApplication : '0' }}</h3>
                            <!-- @endif -->

                            <p>{{ !empty($desk_service->name_bn) ? (strpos($desk_service->name_bn, "Issue") ? substr($desk_service->name_bn, 0, strlen($desk_service->name_bn)-5) : $desk_service->name_bn) : 'N/A' }}</p>
                        </div>

                        <!-- <a class="small-box-footer"
                           href="{{ !empty($desk_service->form_url) && $desk_service->form_url == '/#' ? 'javascript:void(0)' : url($desk_service->form_url . '/list/' . \App\Libraries\Encryption::encodeId($desk_service->id)) }}"
                            {{ !empty($desk_service->form_url) && $desk_service->form_url != '/#' ? '' : '' }}>
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a> -->
                    </div>
                </a>
            </div>

            @endforeach
            </div>

            </div>


@endif










@if (isset($filtered_services) && in_array($type[0], [1,10, 4]))
    
    <div class="d-grid grid-col-12">

        {{--        @foreach ($services as $service)--}}
        @foreach ($filtered_services as $service)

            <div>
                <a class="small-box-footer"
                   href="{{ !empty($service->form_url) && $service->form_url == '/#' ? 'javascript:void(0)' : url($service->form_url . '/list/' . \App\Libraries\Encryption::encodeId($service->id)) }}"
                    {{ !empty($service->form_url) && $service->form_url != '/#' ? '' : '' }}>
                    <div class="small-box bg-{{ !empty($service->panel) ? $service->panel : 'info' }} h-150">
                        <div class="inner h-125">
                            <h3> {{ !empty($service->totalApplication) ? $service->totalApplication : '0' }}</h3>
                            <p>{{ !empty($service->name_bn) ? (strpos($service->name_bn, "Issue") ? substr($service->name_bn, 0, strlen($service->name_bn)-5) : $service->name_bn) : 'N/A' }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas {{ $service->icon }}"></i>
                        </div>
                        <a class="small-box-footer"
                           href="{{ !empty($service->form_url) && $service->form_url == '/#' ? 'javascript:void(0)' : url($service->form_url . '/list/' . \App\Libraries\Encryption::encodeId($service->id)) }}"
                            {{ !empty($service->form_url) && $service->form_url != '/#' ? '' : '' }}>
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </a>
            </div>

        @endforeach

    </div>

@endif
@if (Auth::user()->user_type == '5x505')
    <section>

        @if ( (Auth::user()->working_company_id == 0 && count($check_association_from) == 0) || $is_eligibility == 0 )

            @include('CompanyAssociation::company-association-form')

        @else
            @if (!empty($userApplicaitons))
                <div class="row">
                    <div style='' class="form-group col-lg-3 col-md-3 col-6 input_disabled">
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
                    <div style='' class="form-group col-lg-3 col-md-3 col-6 input_disabled">
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

                    <div style='' class="form-group col-lg-3 col-md-3 col-6 input_disabled">
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
                                    <p style="color: #452A73; font-size: 16px; font-weight: 600;margin-bottom:0">
                                        Approved
                                        Application</p>
                                </div>
                            </div>
                            <input type="hidden" name="search_by_keyword" required class="form-control"
                                   placeholder="Search by keywords" value="dashboard-search@@@ 25">
                            {{-- Input value dashboard-search@@@ fixed for dashboard search --}}
                            {!! Form::close() !!}
                        </div>
                    </div>


                    <div style='' class="form-group col-lg-3 col-md-3 col-6 input_disabled">
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
                                    <p style="color: #452A73; font-size: 16px; font-weight: 600;margin-bottom:0">
                                        Shortfall
                                        Application</p>
                                </div>
                            </div>
                            <input type="hidden" name="search_by_keyword" required class="form-control"
                                   placeholder="Search by keywords" value="dashboard-search@@@ 5">
                            {{-- Input value dashboard-search@@@ fixed for dashboard search --}}
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div style='' class="form-group col-lg-3 col-md-3 col-6 input_disabled">
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
                                    <p style="color: #452A73; font-size: 16px; font-weight: 600;margin-bottom:0">
                                        Rejected
                                        Application</p>
                                </div>
                            </div>
                            <input type="hidden" name="search_by_keyword" required class="form-control"
                                   placeholder="Search by keywords" value="dashboard-search@@@ 6">
                            {{-- Input value dashboard-search@@@ fixed for dashboard search --}}
                            {!! Form::close() !!}
                        </div>
                    </div>


                    <div style='' class="form-group col-lg-3 col-md-3 col-6 input_disabled">
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

                                <a class="btn btn-sm" style="color: white; background: #452A73"
                                   href="/client/{{ $item->form_url  }}/list/{{ \App\Libraries\Encryption::encodeId($item->id) }}">Application</a>
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

@if (Auth::user()->user_type == '1x101' || Auth::user()->user_type == '10x101')
    <div class="row">
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
                    return (array)$value;
                }, $para1);

                ?>
            <div class="card card-success">

                <div class="card-header">
                    <h3 class="card-title">{{ $row->db_obj_title }}</h3>
                </div>

                <div class="box-body p-3">
                    <canvas id="<?php echo $div; ?>" style="width: 100%; height: 350px; text-align:center;">
                        <br/><br/>Chart
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
    </div>
@endif


{{--@if (Auth::user()->user_type == '5x505')--}}
{{--    --}}{{-- Notice & Instructions End --}}
{{--    <div class="notice_instruction">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}
{{--                <div class="card card-default">--}}
{{--                    <div class="card-header">Notice & Instructions</div>--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-12">--}}

{{--                                @foreach ($notices as $key => $notice)--}}
{{--                                    <div class="card card-{{ $notice->importance ?? 'default'}} border border-{{ $notice->importance ?? 'default' }}">--}}
{{--                                        <?php--}}
{{--                                        $class = '';--}}
{{--                                        $expend = false;--}}
{{--                                        if ($key == 0) {--}}
{{--                                            $class = ' in show';--}}
{{--                                            $expend = true;--}}
{{--                                        }--}}
{{--                                        ?>--}}
{{--                                        <a href="#notice_{{ $key }}" data-toggle="collapse" role="button"--}}
{{--                                            aria-expanded="{{ $expend }}" class="collapsed">--}}
{{--                                            <div class="card-header bg-{{ $notice->importance ?? 'default' }}">--}}
{{--                                                <p class="section_head card-title"--}}
{{--                                                    style="margin: 0; font-size: 18px; font-weight: 400">--}}
{{--                                                    <i class="fa fa-chevron-down" style="margin-right: 15px;"></i>--}}
{{--                                                    {{ $notice->heading }} &nbsp;&nbsp;&nbsp;&nbsp;--}}
{{--                                                    {{ date('d M Y', strtotime($notice->Date)) }}--}}
{{--                                                </p>--}}
{{--                                            </div>--}}
{{--                                        </a>--}}
{{--                                        <div id="notice_{{ $key }}" class="collapse {{ $class }}"--}}
{{--                                            style="">--}}
{{--                                            <div class="card-body">--}}
{{--                                                {!! $notice->details !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}


@section('chart_script')
    @if (!empty($map_script_array))
        {{-- @include('partials.amchart-js') --}}
        <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>

        @foreach ($map_script_array as $script)
            <script type="text/javascript">
                $(function () {
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
            beforeSend: function () {
                e.innerHTML = button_text + loading_sign;
            },
            success: function (response) {
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.warning('Yor approval was not successful!');
                console.log(errorThrown);
            },
            complete: function () {
                toastr.success('Yor approval was successful!');
            }
        });
    }
</script>

<script type="text/javascript" src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>


<script type="text/javascript">
    // import {successorWeights} from "../../../../../public/assets/plugins/dagrejs/dagre-d3.min";

    $('#see_my_desk').click(function () {
        $('#my_desk').show();
        $('#hide_my_desk').show();
        $('#see_my_desk').hide();
    });
    $('#hide_my_desk').click(function () {
        $('#my_desk').hide();
        $('#hide_my_desk').hide();
        $('#see_my_desk').show();
    });

    $('#draftButton').click(function () {
        $('.draftButtonForm').submit();
    });
    $('#progressButton').click(function () {
        $('.progressButtonForm').submit();
    });

    $('#approvedButton').click(function () {
        $('.approvedButtonForm').submit();
    });
    $('#othersButton').click(function () {
        $('.othersButtonForm').submit();
    });

    $('#shortfallButton').click(function () {
        $('.shortfallButtonForm').submit();
    });

    $('#rejectedButton').click(function () {
        $('.rejectedButtonForm').submit();
    });

</script>
