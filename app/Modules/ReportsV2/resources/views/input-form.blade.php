{!! Form::open(['url' => url('reportv2/show-report/'.$report_id), 'method' => 'post', 'id'=>'report_form', 'class' => 'form', 'role' => 'form']) !!}
<?php   $results = Session::get("results");?>
<link rel="stylesheet" href="{{ asset('css/admin-oss.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/datatable/dataTables.bootstrap.min.css') }}" />
@foreach($results as $input=>$value)
    <div class="col-md-12">
        <?php
//        $data = explode('|', $value);
        ?>
        @if (substr($input, 0, 5) == 'sess_')
            {!! Form::hidden($input,Session::get($input)) !!}
        @else
            <div class="form-group col-md-6 {{$errors->has($value) ? 'has-error' : ''}}">
                {!! Form::label($value['field'],count($value)>1?($value['caption']?$value['caption']:$value['field']):$value['field']) !!}

                    <?php
//                    if(count($data)==1)
//                        $data[1]=$data[0];
//                    if(count($data)==2)
//                        $data[2]='text';
                    ?>
{{--                @if(count($data)>2)--}}
                    @if($value['data_type']=='string')
                    {!! Form::text($value['field'],Session::get($value['field']),['class'=>'form-control']) !!}
                    @elseif($value['data_type']=='numeric')
                        {!! Form::number($value['field'],Session::get($value['field']),['class'=>'form-control']) !!}
                    @elseif($value['data_type']=='date')
                        <div class="datepicker input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                            {!! Form::text($value['field'],Session::get($value['field']),['class'=>'form-control datepicker']) !!}
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    @elseif($value['data_type']=='list_array')

                        {!! Form::select($value['field'],keyValuePayer($value['param']),Session::get($value['field']),['class'=>'form-control']) !!}
                    @elseif($value['data_type'] == 'list')
                        {!! Form::select($value['field'],getList($value['param']),Session::get($value['field']),['class'=>'form-control']) !!}
                    @elseif($value['data_type']=='datetime')
                    <div class="datetimepicker input-group date">
                        {!! Form::text($value['field'],Session::get($value['field']),['class'=>'form-control']) !!}
                        <span class="input-group-addon date">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                    </div>
                    @elseif($value['data_type']=='time')
                    <div class="timepicker input-group date">
                        {!! Form::text($value['field'],Session::get($value['field']),['class'=>'form-control']) !!}
                        <span class="input-group-addon date">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                    </div>
{{--                    @else--}}
{{--                        {!! Form::text($data[0],Session::get($data[0]),['class'=>'form-control']) !!}--}}
{{--                    @endif--}}
{{--                @else--}}
{{--                    {!! Form::text($data[0],Session::get($data[0]),['class'=>'form-control']) !!}--}}
                @endif
                {!! $errors->first($input,'<span class="help-block">:message</span>') !!}
            </div>
        @endif
    </div>
@endforeach
@if($report_data->is_crystal_report != 0 && $report_data->is_crystal_report != null)
{{--    {!! Form::submit('',['class'=>'btn btn-success','name'=>'crystal_report']) !!}   --}}
    <span id="crystal_report_btn">
        <button class="btn btn-success" id="crystal_report_generate" type="submit">Generate Report</button>
    </span>
@else
    {!! Form::submit('Show',['class'=>'btn btn-primary','name'=>'show_report']) !!}
    {{--{!! Form::submit('Reload',['class'=>'btn btn-success','name'=>'show_report']) !!}--}}
    {!! Form::submit('Download CSV',['class'=>'btn btn-primary','name'=>'export_csv']) !!}
    {{--{!! Form::submit('Download ZIP',['class'=>'btn btn-warning','name'=>'export_csv_zip']) !!}--}}
@endif
{!! Form::close() !!}