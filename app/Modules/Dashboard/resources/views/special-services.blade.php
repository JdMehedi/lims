<?php
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
?>
<?php
$desk_id_array = explode(',', \Session::get('user_desk_ids'));
?>
@extends('layouts.admin')
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




@section('content')


@if ( (Auth::user()->user_type == '5x505' || Auth::user()->user_type == '4x404') && !empty($servicesWiseApplication) )
   
        <section>
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
                            <div class="row" id="draftButton">
                                <div class="col-md-8 col-6">
                                    <p
                                        style="color: #452A73; font-size: 34px; font-weight: 600; margin-bottom:0; line-height:60px">
                                        {{ $userApplicaitons['processing'] }}</p>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="small-box"
                                         style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #69D4D4, #6CD2D5); border-radius: 10px; padding: 10px; height: 100%;margin-bottom:0;max-width:60px">
                                        <img src="/assets/images/process.svg" alt="" height="50%">
                                    </div>
                                </div>
                                <input type="hidden" name="search_by_keyword" required class="form-control"
                                       placeholder="Search by keywords" value="dashboard-search@@@ -1">
                               
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p style="color: #452A73; font-size: 16px; font-weight: 600; margin-bottom:0">Process Application</p>
                                </div>
                            </div>
                            {!! Form::close() !!}

                        </div>
                    </div>

                    <!-- <div style='' class="form-group col-lg-3 col-md-3 col-6 input_disabled">
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
                                    <p style="color: #452A73; font-size: 16px; font-weight: 600;margin-bottom:0">Process Application</p>
                                </div>
                            </div>
                            <input type="hidden" name="search_by_keyword" required class="form-control"
                                   placeholder="Search by keywords"
                                   value="dashboard-search@@@ 1, 2, 9, 15, 16, 8, 10, 9">
                           
                            {!! Form::close() !!}
                        </div>
                    </div> -->

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
        </section>


        <section>
            <div class="row">
                <div class="col-md-12">
                    <p style="font-size: 28px">Other Licenses</p>
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
                               
                                <a class="btn btn-sm" style="color: white; background: #452A73"
                                   href="/special_service/service-list/{{ \App\Libraries\Encryption::encodeId($item->id) }}">Application</a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>


        </section>
   
@endif
@endsection


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




<script type="text/javascript" src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

