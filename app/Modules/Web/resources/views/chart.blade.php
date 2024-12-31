<div class="row">
    <!-- Map -->
    <?php
    if ($dashboardObjectChart->isNotEmpty()) {
    foreach ($dashboardObjectChart as $graph) {

    $div_id = 'dbobj_' . $graph->db_obj_id;
    ?>
    <div class="col-md-12">
        <?php
        $sql_query = $graph->db_obj_para1;

        switch ($graph->db_obj_type) {
        case 'MAP':
        $graph_data = DB::select(DB::raw($sql_query));
        ?>

        <style>
            #{{$div_id}} {
                            width: 100%;
                            height: 500px;
                        }
        </style>

        <div class="box box-success">
            <div class="box-header with-border text-center">
                <h3 class="box-title">{{ $graph->db_obj_title }}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div id="{{$div_id}}"></div>
            </div>
        </div>

        <?php
        $script = $graph->db_obj_para2;
        $data_array['chart_title'] = $graph->db_obj_title;
        $data_array['chart_data'] = json_encode($graph_data);
        $data_array['chart_div'] = $div_id;

        $map_script_array = [];
        $map_script_array[] = CommonFunction::updateScriptPara($script, $data_array);
        break;

        default:
            break;
        }
        ?>
    </div>
    <?php
    }
    }
    ?>
</div>

<div class="row">
    <?php
    if ($dashboardObjectChart->isNotEmpty()) {
    foreach ($dashboardObjectChart as $graph) {

    $div_id = 'dbobj_' . $graph->db_obj_id;
    ?>
    <div class="col-md-6">
        <?php
        $sql_query = $graph->db_obj_para1;

        switch ($graph->db_obj_type) {
        case 'PIE_CHART':
        $graph_data = DB::select(DB::raw($sql_query));
        ?>

        <style>
            #{{$div_id}} {
                            width: 100%;
                            height: 500px;
                        }
        </style>

        <div class="box box-danger">
            <div class="box-header with-border text-center">
                <h3 class="box-title">{{ $graph->db_obj_title }}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div id="{{$div_id}}"></div>
            </div>
        </div>

        <?php
        $script = $graph->db_obj_para2;
        $data_array['chart_title'] = $graph->db_obj_title;
        $data_array['chart_data'] = json_encode($graph_data);
        $data_array['chart_div'] = $div_id;
        $map_script_array[] = CommonFunction::updateScriptPara($script, $data_array);
        //echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $data_array) . '</script>';
        break;

        case 'BAR':
        $graph_data = DB::select(DB::raw($sql_query));
        ?>

        <style>
            #{{$div_id}} {
                            width: 100%;
                            height: 500px;
                        }
        </style>

        <div class="box box-info">
            <div class="box-header with-border text-center">
                <h3 class="box-title">{{ $graph->db_obj_title }}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div id="{{$div_id}}"></div>
            </div>
        </div>

        <?php
        $script = $graph->db_obj_para2;
        $data_array['chart_title'] = $graph->db_obj_title;
        $data_array['chart_data'] = json_encode($graph_data);
        $data_array['chart_div'] = $div_id;
        $map_script_array[] = CommonFunction::updateScriptPara($script, $data_array);
        //echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $data_array) . '</script>';
        break;

        default:
            break;
        }
        ?>
    </div>
    <?php
    }
    }
    ?>
</div>

<div class="row">
    <!-- Line Graph -->
    <?php
    if ($dashboardObjectChart->isNotEmpty()) {
    foreach ($dashboardObjectChart as $graph) {

    $div_id = 'dbobj_' . $graph->db_obj_id;
    ?>
    <div class="col-md-12">
        <?php
        $sql_query = $graph->db_obj_para1;

        switch ($graph->db_obj_type) {

        case 'LINE_GRAPH':

        $filtered_query = str_replace('{$consumption_year}', '2018', $sql_query);
        $graph_data = DB::select(DB::raw($filtered_query));
        ?>

        <style>
            #{{$div_id}} {
                            width: 100%;
                            height: 300px;
                        }
        </style>

        <div class="box box-warning">
            <div class="box-header with-border text-center">
                <h3 class="box-title">{{ $graph->db_obj_title }}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div id="{{$div_id}}"></div>
            </div>
        </div>

        <?php
        $script = $graph->db_obj_para2;
        $data_array['chart_title'] = $graph->db_obj_title;
        $data_array['chart_data'] = json_encode($graph_data);
        $data_array['chart_div'] = $div_id;
        $map_script_array[] = CommonFunction::updateScriptPara($script, $data_array);
        //echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $data_array) . '</script>';
        break;

        case '3D_BAR':
        $graph_data = DB::select(DB::raw($sql_query));
        ?>

        <style>
            #{{$div_id}} {
                            width: 100%;
                            height: 250px;
                        }
        </style>

        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $graph->db_obj_title }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <div id="{{$div_id}}">
                    </div>
                </div>
            </div>
        </div>

        <?php
        $script = $graph->db_obj_para2;
        $data_array['chart_title'] = $graph->db_obj_title;
        $data_array['chart_data'] = json_encode($graph_data);
        $data_array['chart_div'] = $div_id;
        $map_script_array[] = CommonFunction::updateScriptPara($script, $data_array);
        //echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $data_array) . '</script>';
        break;

        default:
            break;
        }
        ?>
    </div>
    <?php
    }
    }
    ?>
</div>

<div class="row">
    <!-- Map -->
    <?php
    if ($dashboardObjectChart->isNotEmpty()) {
    foreach ($dashboardObjectChart as $graph) {

    $div_id = 'dbobj_' . $graph->db_obj_id;
    ?>
    <div class="col-md-12">
        <?php
        $sql_query = $graph->db_obj_para1;


        switch ($graph->db_obj_type) {
        case 'Area':
        $graph_data = DB::select(DB::raw($sql_query));
        ?>

        <style>
            #{{$div_id}} {
                            width: 100%;
                            height: 500px;
                        }
        </style>

        <div class="box box-default">
            <div class="box-header with-border text-left">
                <h3 class="box-title">{{ $graph->db_obj_title }}</h3>
                <div class="box-tools pull-right">
{{--                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i--}}
{{--                                class="fa fa-minus"></i>--}}
{{--                    </button>--}}
                </div>
            </div>
            <div class="box-body">
                <div id="{{$div_id}}"></div>
            </div>
        </div>
        <?php
        $script = $graph->db_obj_para2;
        $data_array['chart_title'] = $graph->db_obj_title;
        $data_array['chart_data'] = json_encode($graph_data);
        $data_array['chart_div'] = $div_id;

        $map_script_array = [];
        $map_script_array[] = CommonFunction::updateScriptPara($script, $data_array);

        break;

        default:
            break;
        }
        ?>
    </div>
    <?php
    }
    }
    ?>
</div>

<div class="clearfix"></div>

@if(!empty($map_script_array))
    @include('partials.amchart-js')

    @foreach($map_script_array as $script)
        <script type="text/javascript">
            $(function () {
                <?php echo $script; ?>
            });
        </script>
    @endforeach
@endif