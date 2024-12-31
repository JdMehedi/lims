<style>
    .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
    }
</style>
@if($mode === "add")
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Existing Call Centers (If Applicable)
        </div>
        <div class="card-body" style="padding: 15px 25px;">
            {{-- Addresses of all the existing centers, if any, under other license (S) --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Addresses Of All The Existing Centers, If Any, Under Other License (S)
                </div>
                <div class="card-body" style="padding: 15px 25px;">

                    <table class="table-responsive"
                           style="width: 100%;     display: inline-table!important;"
                           id="existing_call_center">
                        <input type="hidden" id="existing_call_center_count" name="existing_call_center_count"
                               value="1"/>
                        <tr id="single_call_center_1" class="single_call_center">
                            <td>
                                <div class="card card-magenta border border-magenta">
                                    <div class="card-header">
                                        Existing Call Center Details
                                        <span style="float: right; cursor: pointer;"
                                              class="addExistingCallCenter">
                                                                    <i style="font-size: 20px;"
                                                                       class="fa fa-plus-square" aria-hidden="true"></i>
                                                                </span>
                                    </div>
                                    <div class="card-body" style="padding: 15px 25px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('existing_district_1', 'District', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('existing_district') ? 'has-error' : '' }}">
                                                        {!! Form::select("existing_district[1]", $districts, '', ['class' => 'form-control aa_district existing_district', 'id' => 'existing_district_1', 'onchange' => "getThanaByDistrictId('existing_district_1', this.value, 'existing_thana_1',0)"]) !!}
                                                        {!! $errors->first('existing_district', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('existing_thana_1', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('existing_thana') ? 'has-error' : '' }}">
                                                        {!! Form::select("existing_thana[1]", [], '', ['class' => 'form-control aa_thana existing_thana', 'placeholder' => 'Select district at first', 'id' => 'existing_thana_1']) !!}
                                                        {!! $errors->first('existing_thana', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    {!! Form::label('existing_address_1', 'Address', ['class' => 'col-md-2']) !!}
                                                    <div
                                                        class="col-md-10 {{ $errors->has('proposal_address') ? 'has-error' : '' }}">
                                                        {!! Form::text("existing_address[1]",'', ['class' => 'form-control existing_address', 'placeholder' => 'Enter The Address', 'id' => 'existing_address_1']) !!}
                                                        {!! $errors->first('existing_address', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('nature_of_center_1', 'Nature of Center', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('nature_of_center_1') ? 'has-error' : '' }}">
                                                        {!! Form::select("nature_of_center[1]",[ "Domestic","International" ], '', ['class' => 'form-control nature_of_center', 'placeholder' => 'Select', 'id' => 'nature_of_center_1']) !!}
                                                        {!! $errors->first('nature_of_center_1', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

{{--                                            <div class="col-md-6">--}}
{{--                                                <div class="form-group row">--}}
{{--                                                    {!! Form::label('type_of_center_1', 'Type of Center', ['class' => 'col-md-4']) !!}--}}
{{--                                                    <div--}}
{{--                                                        class="col-md-8 {{ $errors->has('type_of_center') ? 'has-error' : '' }}">--}}
{{--                                                        {!! Form::select("type_of_center[1]", [ "HCCSP","HOST Call Center", "Call Center" ], '', ['class' => 'form-control type_of_center', 'placeholder' => 'Select', 'id' => 'type_of_center_1']) !!}--}}
{{--                                                        {!! $errors->first('type_of_center', '<span class="help-block">:message</span>') !!}--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>
                                        {{-- <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    {!! Form::label('name_call_center_provider_1', "Name of the 'Hosted Call Center Service Provider'", ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('name_call_center_provider_1') ? 'has-error' : '' }}">
                                                        {!! Form::text("name_call_center_provider[1]",'', ['class' => 'form-control name_call_center_provider', 'placeholder' => "Enter name of the 'Hosted Call Center Serive  Provider' ", 'id' => 'name_call_center_provider_1']) !!}
                                                        {!! $errors->first('name_call_center_provider_1', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                        </div> --}}

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('existing_license_no_1', 'Number of Registration Certificate. ', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('existing_license_no') ? 'has-error' : '' }}">
                                                        {!! Form::text("existing_license_no[1]","", ['class' => 'form-control existing_license_no', 'placeholder' => '', 'id' => 'existing_license_no_1']) !!}
                                                        {!! $errors->first('existing_license_no', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('date_of_license_1', 'Date of Registration Certificate', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('date_of_license') ? 'has-error' : '' }}">
                                                        <div class="input-group date datetimepicker4"
                                                             id="date_of_license"
                                                             data-target-input="nearest">
                                                            {!! Form::text("date_of_license[1]",'', ['class' => 'form-control date_of_license ', 'id' => 'date_of_license_1', 'placeholder' => 'Date of Registration Certificate']) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#date_of_license"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                            {!! $errors->first('date_of_license', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('no_of_agents_1', 'No. of Agents/ Seats as on Date', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('no_of_agents') ? 'has-error' : '' }}">
                                                        {!! Form::number("no_of_agents[1]","", ['class' => 'form-control no_of_agents', 'placeholder' => '', 'id' => 'no_of_agents_1']) !!}
                                                        {!! $errors->first('no_of_agents', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('bandwidth_1', 'Bandwidth', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('bandwidth') ? 'has-error' : '' }}">
                                                        {!! Form::number("bandwidth[1]","", ['class' => 'form-control bandwidth', 'placeholder' => '', 'id' => 'bandwidth_1']) !!}
                                                        {!! $errors->first('bandwidth', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('name_of_clients_1', 'Name of the Clients', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('name_of_clients') ? 'has-error' : '' }}">
                                                        {!! Form::text("name_of_clients[1]","", ['class' => 'form-control name_of_clients', 'placeholder' => '', 'id' => 'name_of_clients_1']) !!}
                                                        {!! $errors->first('name_of_clients', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('starting_date_of_service_1', 'Starting Date of Service', ['class' => 'col-md-4']) !!}

                                                    <div
                                                        class="col-md-8 {{ $errors->has('starting_date_of_service') ? 'has-error' : '' }}">
                                                        <div class="input-group date datetimepicker4"  id="starting_date_of_service"
                                                             data-target-input="nearest">
                                                            {!! Form::text("starting_date_of_service[1]",'', ['class' => 'form-control starting_date_of_service ', 'id' => 'starting_date_of_service_1', 'placeholder' => 'Starting Date Of Service']) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#starting_date_of_service"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                            {!! $errors->first('starting_date_of_service', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

{{--                                            <div class="col-md-6">--}}
{{--                                                <div class="form-group row">--}}
{{--                                                    {!! Form::label('type_of_activity_1', 'Type of Activity', ['class' => 'col-md-4']) !!}--}}
{{--                                                    <div--}}
{{--                                                        class="col-md-8 {{ $errors->has('type_of_activity') ? 'has-error' : '' }}">--}}
{{--                                                        {!! Form::text("type_of_activity[1]","", ['class' => 'form-control type_of_activity', 'placeholder' => '', 'id' => 'type_of_activity_1']) !!}--}}
{{--                                                        {!! $errors->first('type_of_activity', '<span class="help-block">:message</span>') !!}--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Existing International Bandwidth connectivity detail, if any--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Existing International Bandwidth Connectivity Detail, If Any
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" id="connectivity_tbl">
                                    <tr>
                                        <th rowspan="2" width="25%" style="text-align: center; vertical-align: middle" >Call Center address in Bangladesh</th>
                                        <th rowspan="2" width="25%" style="text-align: center; vertical-align: middle" > Address of Foreign End PoP</th>
                                        <th colspan="3" align="center" width="50%"  style="text-align: center; vertical-align: middle">Existing bandwith Information</th>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <th>Bandwidth Provider</th>
                                        <th>(Kpbs/Mbps)</th>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" ><input type="text" class="form-control bandwidth_call_center"
                                                                name="bandwidth_call_center"
                                                                placeholder=""></td>
                                        <td rowspan="2" ><input type="text" class="form-control address_of_foreign"
                                                                name="address_of_foreign"
                                                                placeholder=""></td>
                                        <th>IPLC</th>
                                        <td><input type="number" class="form-control existing_bandwidth_iplc"
                                                   name="existing_bandwidth_iplc"
                                                   placeholder=""></td>
                                        <td><input type="text" class="form-control bandwidth_provider_iplc"
                                                   name="bandwidth_provider_iplc"
                                                   placeholder=""></td>
                                    </tr>
                                    <tr>
                                        <th>Backup</th>
                                        <td><input type="number" class="form-control existing_bandwidth_backup"
                                                   name="existing_bandwidth_backup"
                                                   placeholder=""></td>
                                        <td>
                                            <input type="text" class="form-control bandwidth_provider_backup"
                                                   name="bandwidth_provider_backup"
                                                   placeholder="">
                                        </td>
                                    </tr>
                                </table>
                        </div>
                    </div>
                </div>
            </div>

            {{--Person employed in call center (Local and Foreign)--}}
{{--            <div class="card card-magenta border border-magenta">--}}
{{--                <div class="card-header">--}}
{{--                    Person Employed In Call center (Local And Foreign)--}}
{{--                </div>--}}
{{--                <div class="card-body" style="padding: 15px 25px;">--}}

{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('local_employee', 'Local Employee', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div class="col-md-8 {{ $errors->has('local_employee') ? 'has-error' : '' }}">--}}
{{--                                    {!! Form::number('local_employee','',--}}
{{--                                        ['class' => 'form-control local_employee',--}}
{{--                                        'placeholder' => '', 'id' => 'local_employee']) !!}--}}
{{--                                    {!! $errors->first('local_employee', '<span class="help-block">:message</span>') !!}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('foreign_employee', 'Foreign Employee', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div--}}
{{--                                    class="col-md-8 {{ $errors->has('foreign_employee') ? 'has-error' : '' }}">--}}
{{--                                    {!! Form::number('foreign_employee','', ['class' => 'form-control foreign_employee', 'placeholder' => '', 'id' => 'foreign_employee']) !!}--}}
{{--                                    {!! $errors->first('foreign_employee', '<span class="help-block">:message</span>') !!}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}


            {{-- Combined Turnover of Call Centers of the Company suring last 2 Financial Years--}}
{{--            <div class="card card-magenta border border-magenta">--}}
{{--                <div class="card-header">--}}
{{--                    Combined Turnover Of Call Centers Of The Company Suring last 2 Financial Years--}}
{{--                </div>--}}
{{--                <div class="card-body" style="padding: 15px 25px;">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <table class="table table-bordered">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">Year</th>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">Turnover (In Taka)</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        <select class="form-control financial_years" name="financial_years[1]">--}}
{{--                                            <option value="" selected>select</option>--}}
{{--                                            <option value="2021-2022">2021-2022</option>--}}
{{--                                            <option value="2022-2023">2022-2023</option>--}}
{{--                                        </select>--}}
{{--                                    </td>--}}
{{--                                    <td><input type="text" class="form-control financial_amount"--}}
{{--                                               name="financial_amount[1]"--}}
{{--                                               placeholder="0.00"></td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        <select class="form-control financial_years" name="financial_years[2]">--}}
{{--                                            <option value="" selected>select</option>--}}
{{--                                            <option value="2021-2022">2021-2022</option>--}}
{{--                                            <option value="2022-2023">2022-2023</option>--}}
{{--                                        </select>--}}
{{--                                    </td>--}}
{{--                                    <td><input type="text" class="form-control financial_amount"--}}
{{--                                               name="financial_amount[2]"--}}
{{--                                               placeholder="0.00"></td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>
    </div>
@elseif($mode === "edit")
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Existing Call Centers (If Applicable)
        </div>
        <div class="card-body" style="padding: 15px 25px;">
            {{-- Addresses of all the existing centers, if any, under other license (S) --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Addresses Of All The Existing Centers, If Any, Under Other License (S)
                </div>
                <div class="card-body" style="padding: 15px 25px;">

                    <table class="table-responsive"
                           style="width: 100%;     display: inline-table!important;"
                           id="existing_call_center">
                        <input type="hidden" id="existing_call_center_count" name="existing_call_center_count"
                               value="1"/>
                        @foreach($existingCallCenterDetails as $index => $item)
                        <tr id="single_call_center_1" class="single_call_center">
                            <td>
                                <div class="card card-magenta border border-magenta">
                                    <div class="card-header">
                                        Existing Call Center Details
                                        <span style="float: right; cursor: pointer;"
                                              class="addExistingCallCenter">
                                                                <i style="font-size: 20px;"
                                                                   class="fa fa-plus-square" aria-hidden="true"></i>
                                                            </span>
                                    </div>
                                    <div class="card-body" style="padding: 15px 25px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('existing_district_'.$index, 'District', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('existing_district') ? 'has-error' : '' }}">
                                                        @php
                                                            $dis_id = 'existing_district_'.$index;
                                                            $thana_id = 'existing_thana_'.$index;
                                                        @endphp
                                                        {!! Form::select("existing_district[$index]", $districts, $item->district, ['class' => 'form-control aa_district existing_district', 'id' => 'existing_district_'.$index, 'onchange' => "getThanaByDistrictId(\"existing_district_$index\", this.value,\"existing_thana_$index\",0)"]) !!}
                                                        {!! $errors->first('existing_district', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('existing_thana_'.$index, 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('existing_thana') ? 'has-error' : '' }}">
                                                        {!! Form::select("existing_thana[$index]", [], $item->thana, ['class' => 'form-control aa_thana existing_thana', 'placeholder' => 'Select district at first', 'id' => 'existing_thana_'.$index]) !!}
                                                        {!! $errors->first('existing_thana', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    {!! Form::label('existing_address_'.$index, 'Address', ['class' => 'col-md-2']) !!}
                                                    <div
                                                        class="col-md-10 {{ $errors->has('proposal_address') ? 'has-error' : '' }}">
                                                        {!! Form::text("existing_address[$index]",$item->address, ['class' => 'form-control existing_address', 'placeholder' => 'Enter The Address', 'id' => 'existing_address_'.$index]) !!}
                                                        {!! $errors->first('existing_address', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('nature_of_center_'.$index, 'Nature of Center', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('nature_of_center_'.$index) ? 'has-error' : '' }}">
                                                        {!! Form::select("nature_of_center[$index]",[ "Domestic","International" ], $item->nature_of_center, ['class' => 'form-control nature_of_center', 'placeholder' => 'Select', 'id' => 'nature_of_center_'.$index]) !!}
                                                        {!! $errors->first('nature_of_center_'.$index, '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

{{--                                            <div class="col-md-6">--}}
{{--                                                <div class="form-group row">--}}
{{--                                                    {!! Form::label('type_of_center_'.$index, 'Type of Center', ['class' => 'col-md-4']) !!}--}}
{{--                                                    <div--}}
{{--                                                        class="col-md-8 {{ $errors->has('type_of_center') ? 'has-error' : '' }}">--}}
{{--                                                        {!! Form::select("type_of_center[$index]", [ "HCCSP","HOST Call Center", "Call Center" ], $item->type_of_center, ['class' => 'form-control type_of_center', 'placeholder' => 'Select', 'id' => 'type_of_center_'.$index]) !!}--}}
{{--                                                        {!! $errors->first('type_of_center', '<span class="help-block">:message</span>') !!}--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>
                                        {{-- <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    {!! Form::label('name_call_center_provider_'.$index, "Name of the 'Hosted Call Center Service Provider'", ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('name_call_center_provider_'.$index) ? 'has-error' : '' }}">
                                                        {!! Form::text("name_call_center_provider[$index]",$item->name_call_center_provider, ['class' => 'form-control name_call_center_provider', 'placeholder' => "Enter name of the 'Hosted Call Center Serive  Provider' ", 'id' => 'name_call_center_provider_'.$index]) !!}
                                                        {!! $errors->first('name_call_center_provider_'.$index, '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                        </div> --}}

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('existing_license_no_'.$index, 'Number of Registration Certificate.', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('existing_license_no') ? 'has-error' : '' }}">
                                                        {!! Form::text("existing_license_no[$index]",$item->existing_license_no, ['class' => 'form-control existing_license_no', 'placeholder' => '', 'id' => 'existing_license_no_'.$index]) !!}
                                                        {!! $errors->first('existing_license_no', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('date_of_license_'.$index, 'Date of Registration Certificate', ['class' => 'col-md-4']) !!}

                                                    <div
                                                        class="col-md-8 {{ $errors->has('date_of_license') ? 'has-error' : '' }}">
                                                        <div class="input-group date datetimepicker4"
                                                             id="date_of_license"
                                                             data-target-input="nearest">
                                                            {!! Form::text("date_of_license[$index]",!empty($item->date_of_license)? \App\Libraries\CommonFunction::changeDateFormat($item->date_of_license):'', ['class' => 'form-control date_of_license ', 'id' => 'date_of_license_'.$index, 'placeholder' => 'Enter Date of Registration Certificate']) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#date_of_license"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                            {!! $errors->first('date_of_license', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('no_of_agents_'.$index, 'No. of Agents/ Seats as on Date', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('no_of_agents') ? 'has-error' : '' }}">
                                                        {!! Form::number("no_of_agents[$index]",$item->no_of_agents, ['class' => 'form-control no_of_agents', 'placeholder' => '', 'id' => 'no_of_agents_'.$index]) !!}
                                                        {!! $errors->first('no_of_agents', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('bandwidth_'.$index, 'Bandwidth', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('bandwidth') ? 'has-error' : '' }}">
                                                        {!! Form::number("bandwidth[$index]",$item->bandwidth, ['class' => 'form-control bandwidth', 'placeholder' => '', 'id' => 'bandwidth_'.$index]) !!}
                                                        {!! $errors->first('bandwidth', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('name_of_clients_'.$index, 'Name of the Clients', ['class' => 'col-md-4']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('name_of_clients') ? 'has-error' : '' }}">
                                                        {!! Form::text("name_of_clients[$index]",$item->name_of_clients, ['class' => 'form-control name_of_clients', 'placeholder' => '', 'id' => 'name_of_clients_'.$index]) !!}
                                                        {!! $errors->first('name_of_clients', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('starting_date_of_service_'.$index, 'Starting Date of Service', ['class' => 'col-md-4']) !!}

                                                    <div
                                                        class="col-md-8 {{ $errors->has('starting_date_of_service') ? 'has-error' : '' }}">
                                                        <div class="input-group date datetimepicker4"
                                                             id="starting_date_of_service"
                                                             data-target-input="nearest">
                                                            {!! Form::text("date_of_license[$index]",!empty($item->starting_date_of_service)? \App\Libraries\CommonFunction::changeDateFormat($item->starting_date_of_service):'', ['class' => 'form-control starting_date_of_service ', 'id' => 'starting_date_of_service_'.$index, 'placeholder' => 'Enter starting date of service']) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#starting_date_of_service"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                            {!! $errors->first('starting_date_of_service', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



{{--                                            <div class="col-md-6">--}}
{{--                                                <div class="form-group row">--}}
{{--                                                    {!! Form::label('type_of_activity_'.$index, 'Type of Activity', ['class' => 'col-md-4']) !!}--}}
{{--                                                    <div--}}
{{--                                                        class="col-md-8 {{ $errors->has('type_of_activity') ? 'has-error' : '' }}">--}}
{{--                                                        {!! Form::text("type_of_activity[$index]",$item->type_of_activity, ['class' => 'form-control type_of_activity', 'placeholder' => '', 'id' => 'type_of_activity_'.$index]) !!}--}}
{{--                                                        {!! $errors->first('type_of_activity', '<span class="help-block">:message</span>') !!}--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            {{-- Existing International Bandwidth connectivity detail, if any--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Existing International Bandwidth Connectivity Detail, If any
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    <div class="row">
                        <div class="col-md-12">
{{--                            <table class="table table-bordered" id="connectivity_tbl">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th rowspan="2" width="27%" style="text-align: center; vertical-align: middle">Call--}}
{{--                                        Call Center address in Bangladesh--}}
{{--                                    </th>--}}
{{--                                    <th rowspan="2" width="27%" style="text-align: center; vertical-align: middle">--}}
{{--                                        Address Of Foreign End PoP--}}
{{--                                    </th>--}}
{{--                                    <th width="29%" colspan="2" style="text-align: center; vertical-align: middle">--}}
{{--                                        Existing Bandwidth (Kbps/ Mbps)--}}
{{--                                    </th>--}}
{{--                                    <th width="28%" colspan="2" style="text-align: center; vertical-align: middle">--}}
{{--                                        Bandwidth provider--}}
{{--                                    </th>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">IPLC</th>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">Backup</th>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">IPLC</th>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">Backup</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td><input type="text" class="form-control bandwidth_call_center"--}}
{{--                                               name="bandwidth_call_center"--}}
{{--                                               placeholder=""--}}
{{--                                               value="{{ $appInfo->bandwidth_call_center }}"--}}
{{--                                        ></td>--}}
{{--                                    <td><input type="text" class="form-control address_of_foreign"--}}
{{--                                               name="address_of_foreign"--}}
{{--                                               placeholder=""--}}
{{--                                               value="{{ $appInfo->bandwidth_call_center }}"--}}
{{--                                        ></td>--}}
{{--                                    <td><input type="number" class="form-control existing_bandwidth_iplc"--}}
{{--                                               name="existing_bandwidth_iplc"--}}
{{--                                               placeholder=""--}}
{{--                                               value="{{ $appInfo->existing_bandwidth_iplc }}"--}}
{{--                                        ></td>--}}
{{--                                    <td><input type="number" class="form-control existing_bandwidth_backup"--}}
{{--                                               name="existing_bandwidth_backup"--}}
{{--                                               placeholder=""--}}
{{--                                               value="{{ $appInfo->existing_bandwidth_backup }}"--}}
{{--                                        ></td>--}}
{{--                                    <td><input type="text" class="form-control bandwidth_provider_iplc"--}}
{{--                                               name="bandwidth_provider_iplc"--}}
{{--                                               placeholder=""--}}
{{--                                               value="{{ $appInfo->bandwidth_provider_iplc }}"--}}
{{--                                        ></td>--}}
{{--                                    <td><input type="text" class="form-control bandwidth_provider_backup"--}}
{{--                                               name="bandwidth_provider_backup"--}}
{{--                                               placeholder=""--}}
{{--                                               value="{{ $appInfo->bandwidth_provider_backup }}"--}}
{{--                                        ></td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
                            <table class="table table-bordered" id="connectivity_tbl">
                                <tr>
                                    <th rowspan="2" width="25%" style="text-align: center; vertical-align: middle" >Call Center address in Bangladesh</th>
                                    <th rowspan="2" width="25%" style="text-align: center; vertical-align: middle" > Address of Foreign End PoP</th>
                                    <th colspan="3" align="center" width="50%"  style="text-align: center; vertical-align: middle">Existing bandwith Information</th>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <th>Bandwidth Provider</th>
                                    <th>(Kpbs/Mbps)</th>
                                </tr>
                                <tr>
                                    <td rowspan="2" ><input type="text" class="form-control bandwidth_call_center"
                                                            name="bandwidth_call_center"
                                                            placeholder="" value="{{ $appInfo->bandwidth_call_center }}"></td>
                                    <td rowspan="2" ><input type="text" class="form-control address_of_foreign"
                                                            name="address_of_foreign"
                                                            placeholder="" value="{{ $appInfo->address_of_foreign }}"></td>
                                    <th>IPLC</th>
                                    <td><input type="number" class="form-control existing_bandwidth_iplc"
                                               name="existing_bandwidth_iplc"
                                               placeholder="" value="{{ $appInfo->existing_bandwidth_iplc }}"></td>
                                    <td><input type="text" class="form-control bandwidth_provider_iplc"
                                               name="bandwidth_provider_iplc"
                                               placeholder="" value="{{ $appInfo->bandwidth_provider_iplc }}"></td>
                                </tr>
                                <tr>
                                    <th>Backup</th>
                                    <td><input type="number" class="form-control existing_bandwidth_backup"
                                               name="existing_bandwidth_backup"
                                               placeholder="" value="{{ $appInfo->existing_bandwidth_backup }}"></td>
                                    <td>
                                        <input type="text" class="form-control bandwidth_provider_backup"
                                               name="bandwidth_provider_backup"
                                               placeholder="" value="{{ $appInfo->bandwidth_provider_backup }}">
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            {{--Person employed in call center (Local and Foreign)--}}
{{--            <div class="card card-magenta border border-magenta">--}}
{{--                <div class="card-header">--}}
{{--                    Person Employed In Call Center (Local and Foreign)--}}
{{--                </div>--}}
{{--                <div class="card-body" style="padding: 15px 25px;">--}}

{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('local_employee', 'Local Employee', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div class="col-md-8 {{ $errors->has('local_employee') ? 'has-error' : '' }}">--}}
{{--                                    {!! Form::number('local_employee',$appInfo->local_employee,--}}
{{--                                        ['class' => 'form-control local_employee',--}}
{{--                                        'placeholder' => '', 'id' => 'local_employee']) !!}--}}
{{--                                    {!! $errors->first('local_employee', '<span class="help-block">:message</span>') !!}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('foreign_employee', 'Foreign Employee', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div--}}
{{--                                    class="col-md-8 {{ $errors->has('foreign_employee') ? 'has-error' : '' }}">--}}
{{--                                    {!! Form::number('foreign_employee',$appInfo->foreign_employee, ['class' => 'form-control foreign_employee', 'placeholder' => '', 'id' => 'foreign_employee']) !!}--}}
{{--                                    {!! $errors->first('foreign_employee', '<span class="help-block">:message</span>') !!}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}


            {{-- Combined Turnover of Call Centers of the Company suring last 2 Financial Years--}}
{{--            <div class="card card-magenta border border-magenta">--}}
{{--                <div class="card-header">--}}
{{--                    Combined Turnover Of Call Centers Of The Company Suring Last 2 Financial Years--}}
{{--                </div>--}}
{{--                <div class="card-body" style="padding: 15px 25px;">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <table class="table table-bordered">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">Year</th>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">Turnover (In Taka)</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        <select class="form-control financial_years" name="financial_years[1]">--}}
{{--                                            <option value="" selected>select</option>--}}
{{--                                            <option {{ $appInfo->fast_financial_years=='2021-2022'?'selected':'' }} value="2021-2022">2021-2022</option>--}}
{{--                                            <option {{ $appInfo->fast_financial_years=='2022-2023'?'selected':'' }} value="2022-2023">2022-2023</option>--}}
{{--                                        </select>--}}
{{--                                    </td>--}}
{{--                                    <td><input type="text" class="form-control financial_amount"--}}
{{--                                               name="financial_amount[1]"--}}
{{--                                               placeholder="0.00"--}}
{{--                                               value="{{ $appInfo->fast_financial_amount }}"--}}
{{--                                        ></td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        <select class="form-control financial_years" name="financial_years[2]">--}}
{{--                                            <option value="" selected>select</option>--}}
{{--                                            <option {{ $appInfo->second_financial_years=='2021-2022'?'selected':'' }} value="2021-2022">2021-2022</option>--}}
{{--                                            <option {{ $appInfo->second_financial_years=='2022-2023'?'selected':'' }} value="2022-2023">2022-2023</option>--}}
{{--                                        </select>--}}
{{--                                    </td>--}}
{{--                                    <td><input type="text" class="form-control financial_amount"--}}
{{--                                               name="financial_amount[2]"--}}
{{--                                               placeholder="0.00"--}}
{{--                                               value="{{ $appInfo->second_financial_amount }}"--}}
{{--                                        ></td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>
    </div>
@elseif($mode === "view")
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Existing Call Centers (If Applicable)
        </div>
        <div class="card-body" style="padding: 15px 25px;">
            {{-- Addresses of all the existing centers, if any, under other license (S) --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Addresses Of All The Existing Centers, If Any, Under Other License (S)
                </div>
                <div class="card-body" style="padding: 15px 25px;">

                    <table class="table-responsive"
                           style="width: 100%;     display: inline-table!important;"
                           id="existing_call_center">
                        <input type="hidden" id="existing_call_center_count" name="existing_call_center_count"
                               value="1"/>
                        @foreach($existingCallCenterData as $index => $item)
                            <tr id="single_call_center_1" class="single_call_center">
                                <td>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="card-header">
                                            Existing Call Center Details
                                        </div>
                                        <div class="card-body" style="padding: 15px 25px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_district_1', 'District', ['class' => 'col-md-4']) !!}
                                                        <div class="col-md-8">
                                                            <span>{{ $item->district  }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_thana_1', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8">
                                                            <span>{{ $item->thana  }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_address_1', 'Address', ['class' => 'col-md-2']) !!}
                                                        <div class="col-md-10">
                                                            <span>{{ $item->address  }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('nature_of_center_1', 'Nature of Center', ['class' => 'col-md-4']) !!}
                                                        <div class="col-md-8 ">
                                                            @php($nof_center = ["Domestic","International"])
                                                            <span>{{ $nof_center[$item->nature_of_center] ?? ''  }}</span>
                                                        </div>
                                                    </div>
                                                </div>

{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group row">--}}
{{--                                                        {!! Form::label('type_of_center_1', 'Type of Center', ['class' => 'col-md-4']) !!}--}}
{{--                                                        <div class="col-md-8 ">--}}
{{--                                                            @php($tof_center = [ "HCCSP","HOST Call Center", "Call Center" ])--}}
{{--                                                            <span>{{ $tof_center[$item->type_of_center] ?? ''  }}</span>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                            </div>
                                            {{-- <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        {!! Form::label('name_call_center_provider_1', "Name of the 'Hosted Call Center Service Provider'", ['class' => 'col-md-4']) !!}
                                                        <div class="col-md-8">
                                                            <span>{{ $item->name_call_center_provider  }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div> --}}

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_license_no_1', 'Number of Registration Certificate. .', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8">
                                                            <span>{{ $item->existing_license_no  }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('date_of_license_1', 'Date of Registration Certificate', ['class' => 'col-md-4']) !!}

                                                        <div
                                                            class="col-md-8">
                                                            @if($item->date_of_license!='0000-00-00')
                                                            <span>{{ date('d-M-Y',strtotime($item->date_of_license) ) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('no_of_agents_1', 'No. of Agents/ Seats as on Date', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8">
                                                            <span>{{ $item->no_of_agents }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('bandwidth_1', 'Bandwidth', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('bandwidth') ? 'has-error' : '' }}">
                                                            <span>{{ $item->bandwidth }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('name_of_clients_1', 'Name of the Clients', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('name_of_clients') ? 'has-error' : '' }}">
                                                            <span>{{ $item->name_of_clients }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('starting_date_of_service_1', 'Starting Date of Service', ['class' => 'col-md-4']) !!}

                                                        <div
                                                            class="col-md-8">
                                                            @if($item->starting_date_of_service!="0000-00-00")
                                                            <span>{{ date('d-M-Y',strtotime($item->starting_date_of_service) ) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group row">--}}
{{--                                                        {!! Form::label('type_of_activity_1', 'Type of Activity', ['class' => 'col-md-4']) !!}--}}
{{--                                                        <div--}}
{{--                                                            class="col-md-8 {{ $errors->has('type_of_activity') ? 'has-error' : '' }}">--}}
{{--                                                            <span>{{ $item->type_of_activity }}</span>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            {{-- Existing International Bandwidth connectivity detail, if any--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Existing International Bandwidth Connectivity Detail, If Any
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    <div class="row">
                        <div class="col-md-12">
{{--                            <table class="table table-bordered" id="connectivity_tbl">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th rowspan="2" width="27%" style="text-align: center; vertical-align: middle">Call--}}
{{--                                        Call Center address in Bangladesh--}}
{{--                                    </th>--}}
{{--                                    <th rowspan="2" width="27%" style="text-align: center; vertical-align: middle">--}}
{{--                                        Address of Foreign End PoP--}}
{{--                                    </th>--}}
{{--                                    <th width="29%" colspan="2" style="text-align: center; vertical-align: middle">--}}
{{--                                        Existing Bandwidth (Kbps/ Mbps)--}}
{{--                                    </th>--}}
{{--                                    <th width="28%" colspan="2" style="text-align: center; vertical-align: middle">--}}
{{--                                        Bandwidth Provider--}}
{{--                                    </th>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">IPLC</th>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">Backup</th>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">IPLC</th>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">Backup</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td align="middle" >{{ $appInfo->bandwidth_call_center }}</td>--}}
{{--                                    <td align="middle" >{{ $appInfo->address_of_foreign }}</td>--}}
{{--                                    <td align="middle" >{{ $appInfo->existing_bandwidth_iplc }}</td>--}}
{{--                                    <td align="middle" >{{ $appInfo->existing_bandwidth_backup }}</td>--}}
{{--                                    <td align="middle" >{{ $appInfo->bandwidth_provider_iplc }}</td>--}}
{{--                                    <td align="middle" >{{ $appInfo->bandwidth_provider_backup }}</td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
                            <table class="table table-bordered" id="connectivity_tbl">
                                <tr>
                                    <th rowspan="2" width="25%" style="text-align: center; vertical-align: middle" >Call Center address in Bangladesh</th>
                                    <th rowspan="2" width="25%" style="text-align: center; vertical-align: middle" > Address of Foreign End PoP</th>
                                    <th colspan="3" align="center" width="50%"  style="text-align: center; vertical-align: middle">Existing bandwith Information</th>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <th>Bandwidth Provider</th>
                                    <th>(Kpbs/Mbps)</th>
                                </tr>
                                <tr>
                                    <td rowspan="2" align="middle">{{ $appInfo->bandwidth_call_center }}</td>
                                    <td rowspan="2" align="middle">{{ $appInfo->address_of_foreign }}</td>
                                    <th>IPLC</th>
                                    <td align="middle" >{{ $appInfo->existing_bandwidth_iplc }}</td>
                                    <td align="middle" >{{ $appInfo->bandwidth_provider_iplc }}</td>
                                </tr>
                                <tr>
                                    <th>Backup</th>
                                    <td align="middle" >{{ $appInfo->existing_bandwidth_backup }}</td>
                                    <td align="middle" >{{ $appInfo->bandwidth_provider_backup }}
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            {{--Person employed in call center (Local and Foreign)--}}
{{--            <div class="card card-magenta border border-magenta">--}}
{{--                <div class="card-header">--}}
{{--                    Person Employed In Call Center (Local and Foreign)--}}
{{--                </div>--}}
{{--                <div class="card-body" style="padding: 15px 25px;">--}}

{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--  --}}
{{--                                {!! Form::label('local_employee', 'Local Employee', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div class="col-md-8">--}}
{{--                                    {{ $appInfo->local_employee }}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('foreign_employee', 'Foreign Employee', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div--}}
{{--                                    class="col-md-8 ">--}}
{{--                                    {{ $appInfo->foreign_employee }}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}


            {{-- Combined Turnover of Call Centers of the Company suring last 2 Financial Years--}}
{{--            <div class="card card-magenta border border-magenta">--}}
{{--                <div class="card-header">--}}
{{--                    Combined Turnover of Call Centers Of The Company Suring Last 2 Financial Years--}}
{{--                </div>--}}
{{--                <div class="card-body" style="padding: 15px 25px;">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <table class="table table-bordered">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">Year</th>--}}
{{--                                    <th style="text-align: center; vertical-align: middle">Turnover (In Taka)</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td align="middle">--}}
{{--                                        {{ $appInfo->fast_financial_years }}--}}
{{--                                    </td>--}}
{{--                                    <td align="middle"> {{ $appInfo->fast_financial_amount }}</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td align="middle">--}}
{{--                                        {{ $appInfo->second_financial_years }}--}}
{{--                                    </td>--}}
{{--                                    <td align="middle"> {{ $appInfo->second_financial_amount }}</td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>
    </div>
@elseif($mode === "amendment")
    <div class="card card-magenta border border-magenta" >
        <div class="card-header d-flex justify-content-between areaAddress">
            <div>Existing Call Centers (If Applicable)</div>
            <div>
                <label>
                    <input type="checkbox" id="existingEditBtn"/>
                    EDIT
                </label>
            </div>
        </div>
        <div class="card-body" style="padding: 15px 25px;"id="readOnly">
            {{-- Addresses of all the existing centers, if any, under other license (S) --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Addresses Of All The Existing Centers, If Any, Under Other License (S)
                </div>
                <div class="card-body" style="padding: 15px 25px;">

                    <table class="table-responsive"
                           style="width: 100%;     display: inline-table!important;"
                           id="existing_call_center">
                        <input type="hidden" id="existing_call_center_count" name="existing_call_center_count"
                               value="1"/>
                        @foreach($existingCallCenterDetails as $index => $item)
                            <tr id="single_call_center_1" class="single_call_center">
                                <td>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="card-header">
                                            Existing Call Center Details
                                            <span style="float: right; cursor: pointer;"
                                                  class="addExistingCallCenter">
                                                                <i style="font-size: 20px;"
                                                                   class="fa fa-plus-square" aria-hidden="true"></i>
                                                            </span>
                                        </div>
                                        <div class="card-body" style="padding: 15px 25px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_district_'.$index, 'District', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('existing_district') ? 'has-error' : '' }}">
                                                            <?php
                                                                $dis_id = 'existing_district_'.$index;
                                                                $thana_id = 'existing_thana_'.$index;
                                                            ?>
                                                            {!! Form::select("existing_district[$index]", $districts, $item->district, ['class' => 'form-control aa_district existing_district', 'id' => 'existing_district_'.$index, 'onchange' => "getThanaByDistrictId(\"existing_district_$index\", this.value,\"existing_thana_$index\",0)"]) !!}
                                                            {!! $errors->first('existing_district', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_thana_'.$index, 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('existing_thana') ? 'has-error' : '' }}">
                                                            {!! Form::select("existing_thana[$index]", [], $item->thana, ['class' => 'form-control aa_thana existing_thana', 'placeholder' => 'Select district at first', 'id' => 'existing_thana_'.$index]) !!}
                                                            {!! $errors->first('existing_thana', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_address_'.$index, 'Address', ['class' => 'col-md-2']) !!}
                                                        <div
                                                            class="col-md-10 {{ $errors->has('proposal_address') ? 'has-error' : '' }}">
                                                            {!! Form::text("existing_address[$index]",$item->address, ['class' => 'form-control existing_address', 'placeholder' => 'Enter The Address', 'id' => 'existing_address_'.$index]) !!}
                                                            {!! $errors->first('existing_address', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('nature_of_center_'.$index, 'Nature of Center', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('nature_of_center_'.$index) ? 'has-error' : '' }}">
                                                            {!! Form::select("nature_of_center[$index]",[ "Domestic","International" ], $item->nature_of_center, ['class' => 'form-control nature_of_center', 'placeholder' => 'Select', 'id' => 'nature_of_center_'.$index]) !!}
                                                            {!! $errors->first('nature_of_center_'.$index, '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group row">--}}
{{--                                                        {!! Form::label('type_of_center_'.$index, 'Type of Center', ['class' => 'col-md-4']) !!}--}}
{{--                                                        <div--}}
{{--                                                            class="col-md-8 {{ $errors->has('type_of_center') ? 'has-error' : '' }}">--}}
{{--                                                            {!! Form::select("type_of_center[$index]", [ "HCCSP","HOST Call Center", "Call Center" ], $item->type_of_center, ['class' => 'form-control type_of_center', 'placeholder' => 'Select', 'id' => 'type_of_center_'.$index]) !!}--}}
{{--                                                            {!! $errors->first('type_of_center', '<span class="help-block">:message</span>') !!}--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                            </div>
                                            {{-- <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        {!! Form::label('name_call_center_provider_'.$index, "Name of the 'Hosted Call Center Service Provider'", ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('name_call_center_provider_'.$index) ? 'has-error' : '' }}">
                                                            {!! Form::text("name_call_center_provider[$index]",$item->name_call_center_provider, ['class' => 'form-control name_call_center_provider', 'placeholder' => "Enter name of the 'Hosted Call Center Serive  Provider' ", 'id' => 'name_call_center_provider_'.$index]) !!}
                                                            {!! $errors->first('name_call_center_provider_'.$index, '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div> --}}

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_license_no_'.$index, 'Number of Registration Certificate. .', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('existing_license_no') ? 'has-error' : '' }}">
                                                            {!! Form::text("existing_license_no[$index]",$item->existing_license_no, ['class' => 'form-control existing_license_no', 'placeholder' => '', 'id' => 'existing_license_no_'.$index]) !!}
                                                            {!! $errors->first('existing_license_no', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('date_of_license_'.$index, 'Date of Registration Certificate', ['class' => 'col-md-4']) !!}

                                                        <div
                                                            class="col-md-8 {{ $errors->has('date_of_license') ? 'has-error' : '' }}">
                                                            <div class="input-group date datetimepicker4"
                                                                 id="date_of_license"
                                                                 data-target-input="nearest">
                                                                {!! Form::text("date_of_license[$index]",!empty($item->date_of_license)? \App\Libraries\CommonFunction::changeDateFormat($item->date_of_license):'', ['class' => 'form-control date_of_license ', 'id' => 'date_of_license_'.$index, 'placeholder' => 'Enter Date of Registration Certificate']) !!}
                                                                <div class="input-group-append"
                                                                     data-target="#date_of_license"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                                {!! $errors->first('date_of_license', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('no_of_agents_'.$index, 'No. of Agents/ Seats as on Date', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('no_of_agents') ? 'has-error' : '' }}">
                                                            {!! Form::number("no_of_agents[$index]",$item->no_of_agents, ['class' => 'form-control no_of_agents', 'placeholder' => '', 'id' => 'no_of_agents_'.$index]) !!}
                                                            {!! $errors->first('no_of_agents', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('bandwidth_'.$index, 'Bandwidth', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('bandwidth') ? 'has-error' : '' }}">
                                                            {!! Form::number("bandwidth[$index]",$item->bandwidth, ['class' => 'form-control bandwidth', 'placeholder' => '', 'id' => 'bandwidth_'.$index]) !!}
                                                            {!! $errors->first('bandwidth', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('name_of_clients_'.$index, 'Name of the Clients', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('name_of_clients') ? 'has-error' : '' }}">
                                                            {!! Form::text("name_of_clients[$index]",$item->name_of_clients, ['class' => 'form-control name_of_clients', 'placeholder' => '', 'id' => 'name_of_clients_'.$index]) !!}
                                                            {!! $errors->first('name_of_clients', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('starting_date_of_service_'.$index, 'Starting Date of Service', ['class' => 'col-md-4']) !!}

                                                        <div
                                                            class="col-md-8 {{ $errors->has('starting_date_of_service') ? 'has-error' : '' }}">
                                                            <div class="input-group date datetimepicker4"
                                                                 id="starting_date_of_service_1"
                                                                 data-target-input="nearest">
                                                                {!! Form::text("starting_date_of_service_[$index]",!empty($item->starting_date_of_service
)? \App\Libraries\CommonFunction::changeDateFormat($item->starting_date_of_service
):'', ['class' => 'form-control starting_date_of_service_1', 'id' => 'starting_date_of_service_'.$index, 'placeholder' => 'Enter Starting Date of Service']) !!}
                                                                <div class="input-group-append"
                                                                     data-target="#starting_date_of_service"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                                {!! $errors->first('starting_date_of_service', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

{{--                                                <div class="col-md-6">--}}
{{--                                                    <div class="form-group row">--}}
{{--                                                        {!! Form::label('type_of_activity_'.$index, 'Type of Activity', ['class' => 'col-md-4']) !!}--}}
{{--                                                        <div--}}
{{--                                                            class="col-md-8 {{ $errors->has('type_of_activity') ? 'has-error' : '' }}">--}}
{{--                                                            {!! Form::text("type_of_activity[$index]",$item->type_of_activity, ['class' => 'form-control type_of_activity', 'placeholder' => '', 'id' => 'type_of_activity_'.$index]) !!}--}}
{{--                                                            {!! $errors->first('type_of_activity', '<span class="help-block">:message</span>') !!}--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            {{-- Existing International Bandwidth connectivity detail, if any--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Existing International Bandwidth Connectivity Detail, If Any
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" id="connectivity_tbl">
                                <thead>
                                <tr>
                                    <th rowspan="2" width="27%" style="text-align: center; vertical-align: middle">Call
                                        Call Center address in Bangladesh
                                    </th>
                                    <th rowspan="2" width="27%" style="text-align: center; vertical-align: middle">
                                        Address of Foreign End PoP
                                    </th>
                                    <th width="29%" colspan="2" style="text-align: center; vertical-align: middle">
                                        Existing Bandwidth (Kbps/ Mbps)
                                    </th>
                                    <th width="28%" colspan="2" style="text-align: center; vertical-align: middle">
                                        Bandwidth Provider
                                    </th>
                                </tr>
                                <tr>
                                    <th style="text-align: center; vertical-align: middle">IPLC</th>
                                    <th style="text-align: center; vertical-align: middle">Backup</th>
                                    <th style="text-align: center; vertical-align: middle">IPLC</th>
                                    <th style="text-align: center; vertical-align: middle">Backup</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" class="form-control bandwidth_call_center"
                                               name="bandwidth_call_center"
                                               placeholder=""
                                               value="{{ $appInfo->bandwidth_call_center }}"
                                        ></td>
                                    <td><input type="text" class="form-control address_of_foreign"
                                               name="address_of_foreign"
                                               placeholder=""
                                               value="{{ $appInfo->bandwidth_call_center }}"
                                        ></td>
                                    <td><input type="number" class="form-control existing_bandwidth_iplc"
                                               name="existing_bandwidth_iplc"
                                               placeholder=""
                                               value="{{ $appInfo->existing_bandwidth_iplc }}"
                                        ></td>
                                    <td><input type="number" class="form-control existing_bandwidth_backup"
                                               name="existing_bandwidth_backup"
                                               placeholder=""
                                               value="{{ $appInfo->existing_bandwidth_backup }}"
                                        ></td>
                                    <td><input type="text" class="form-control bandwidth_provider_iplc"
                                               name="bandwidth_provider_iplc"
                                               placeholder=""
                                               value="{{ $appInfo->bandwidth_provider_iplc }}"
                                        ></td>
                                    <td><input type="text" class="form-control bandwidth_provider_backup"
                                               name="bandwidth_provider_backup"
                                               placeholder=""
                                               value="{{ $appInfo->bandwidth_provider_backup }}"
                                        ></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{--Person employed in call center (Local and Foreign)--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Person Employed in Call Center (Local and Foreign)
                </div>
                <div class="card-body" style="padding: 15px 25px;">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('local_employee', 'Local Employee', ['class' => 'col-md-4 ']) !!}
                                <div class="col-md-8 {{ $errors->has('local_employee') ? 'has-error' : '' }}">
                                    {!! Form::number('local_employee',$appInfo->local_employee,
                                        ['class' => 'form-control local_employee',
                                        'placeholder' => '', 'id' => 'local_employee']) !!}
                                    {!! $errors->first('local_employee', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('foreign_employee', 'Foreign Employee', ['class' => 'col-md-4 ']) !!}
                                <div
                                    class="col-md-8 {{ $errors->has('foreign_employee') ? 'has-error' : '' }}">
                                    {!! Form::number('foreign_employee',$appInfo->foreign_employee, ['class' => 'form-control foreign_employee', 'placeholder' => '', 'id' => 'foreign_employee']) !!}
                                    {!! $errors->first('foreign_employee', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            {{-- Combined Turnover of Call Centers of the Company suring last 2 Financial Years--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Combined Turnover of Call Centers of the Company Suring Last 2 Financial Years
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="text-align: center; vertical-align: middle">Year</th>
                                    <th style="text-align: center; vertical-align: middle">Turnover (In Taka)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control financial_years" name="financial_years[1]">
                                            <option value="" selected>select</option>
                                            <option {{ $appInfo->fast_financial_years=='2021-2022'?'selected':'' }} value="2021-2022">2021-2022</option>
                                            <option {{ $appInfo->fast_financial_years=='2022-2023'?'selected':'' }} value="2022-2023">2022-2023</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control financial_amount"
                                               name="financial_amount[1]"
                                               placeholder="0.00"
                                               value="{{ $appInfo->fast_financial_amount }}"
                                        ></td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control financial_years" name="financial_years[2]">
                                            <option value="" selected>select</option>
                                            <option {{ $appInfo->second_financial_years=='2021-2022'?'selected':'' }} value="2021-2022">2021-2022</option>
                                            <option {{ $appInfo->second_financial_years=='2022-2023'?'selected':'' }} value="2022-2023">2022-2023</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control financial_amount"
                                               name="financial_amount[2]"
                                               placeholder="0.00"
                                               value="{{ $appInfo->second_financial_amount }}"
                                        ></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@elseif($mode === "surrender")
    <div class="card card-magenta border border-magenta" id="readOnly">
        <div class="card-header">
            Existing Call Centers (If Applicable)
        </div>
        <div class="card-body" style="padding: 15px 25px;">
            {{-- Addresses of all the existing centers, if any, under other license (S) --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Addresses of All The Existing Centers, If Any, Under Other License (S)
                </div>
                <div class="card-body" style="padding: 15px 25px;">

                    <table class="table-responsive"
                           style="width: 100%;     display: inline-table!important;"
                           id="existing_call_center">
                        <input type="hidden" id="existing_call_center_count" name="existing_call_center_count"
                               value="1"/>
                        @foreach($existingCallCenterDetails as $index => $item)
                            <tr id="single_call_center_1" class="single_call_center">
                                <td>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="card-header">
                                            Existing Call Center Details
                                            <span style="float: right; cursor: pointer;"
                                                  class="addExistingCallCenter">
                                                                <i style="font-size: 20px;"
                                                                   class="fa fa-plus-square" aria-hidden="true"></i>
                                                            </span>
                                        </div>
                                        <div class="card-body" style="padding: 15px 25px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_district_'.$index, 'District', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('existing_district') ? 'has-error' : '' }}">
                                                                <?php
                                                                $dis_id = 'existing_district_'.$index;
                                                                $thana_id = 'existing_thana_'.$index;
                                                                ?>
                                                            {!! Form::select("existing_district[$index]", $districts, $item->district, ['class' => 'form-control aa_district existing_district', 'id' => 'existing_district_'.$index, 'onchange' => "getThanaByDistrictId(\"existing_district_$index\", this.value,\"existing_thana_$index\",0)"]) !!}
                                                            {!! $errors->first('existing_district', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_thana_'.$index, 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('existing_thana') ? 'has-error' : '' }}">
                                                            {!! Form::select("existing_thana[$index]", [], $item->thana, ['class' => 'form-control aa_thana existing_thana', 'placeholder' => 'Select district at first', 'id' => 'existing_thana_'.$index]) !!}
                                                            {!! $errors->first('existing_thana', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_address_'.$index, 'Address', ['class' => 'col-md-2']) !!}
                                                        <div
                                                            class="col-md-10 {{ $errors->has('proposal_address') ? 'has-error' : '' }}">
                                                            {!! Form::text("existing_address[$index]",$item->address, ['class' => 'form-control existing_address', 'placeholder' => 'Enter The Address', 'id' => 'existing_address_'.$index]) !!}
                                                            {!! $errors->first('existing_address', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('nature_of_center_'.$index, 'Nature of Center', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('nature_of_center_'.$index) ? 'has-error' : '' }}">
                                                            {!! Form::select("nature_of_center[$index]",[ "Domestic","International" ], $item->nature_of_center, ['class' => 'form-control nature_of_center', 'placeholder' => 'Select', 'id' => 'nature_of_center_'.$index]) !!}
                                                            {!! $errors->first('nature_of_center_'.$index, '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('type_of_center_'.$index, 'Type of Center', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('type_of_center') ? 'has-error' : '' }}">
                                                            {!! Form::select("type_of_center[$index]", [ "HCCSP","HOST Call Center", "Call Center" ], $item->type_of_center, ['class' => 'form-control type_of_center', 'placeholder' => 'Select', 'id' => 'type_of_center_'.$index]) !!}
                                                            {!! $errors->first('type_of_center', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        {!! Form::label('name_call_center_provider_'.$index, "Name of the 'Hosted Call Center Service Provider'", ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('name_call_center_provider_'.$index) ? 'has-error' : '' }}">
                                                            {!! Form::text("name_call_center_provider[$index]",$item->name_call_center_provider, ['class' => 'form-control name_call_center_provider', 'placeholder' => "Enter name of the 'Hosted Call Center Serive  Provider' ", 'id' => 'name_call_center_provider_'.$index]) !!}
                                                            {!! $errors->first('name_call_center_provider_'.$index, '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div> --}}

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('existing_license_no_'.$index, 'Number of Registration Certificate. .', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('existing_license_no') ? 'has-error' : '' }}">
                                                            {!! Form::text("existing_license_no[$index]",$item->existing_license_no, ['class' => 'form-control existing_license_no', 'placeholder' => '', 'id' => 'existing_license_no_'.$index]) !!}
                                                            {!! $errors->first('existing_license_no', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('date_of_license_'.$index, 'Date of Registration Certificate', ['class' => 'col-md-4']) !!}

                                                        <div
                                                            class="col-md-8 {{ $errors->has('date_of_license') ? 'has-error' : '' }}">
                                                            <div class="input-group date datetimepicker4"
                                                                 id="date_of_license"
                                                                 data-target-input="nearest">
                                                                {!! Form::text("date_of_license[$index]",!empty($item->date_of_license)? \App\Libraries\CommonFunction::changeDateFormat($item->date_of_license):'', ['class' => 'form-control date_of_license ', 'id' => 'date_of_license_'.$index, 'placeholder' => 'Enter Date of Registration Certificate']) !!}
                                                                <div class="input-group-append"
                                                                     data-target="#date_of_license"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                                {!! $errors->first('date_of_license', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('no_of_agents_'.$index, 'No. of Agents/ Seats as on Date', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('no_of_agents') ? 'has-error' : '' }}">
                                                            {!! Form::number("no_of_agents[$index]",$item->no_of_agents, ['class' => 'form-control no_of_agents', 'placeholder' => '', 'id' => 'no_of_agents_'.$index]) !!}
                                                            {!! $errors->first('no_of_agents', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('bandwidth_'.$index, 'Bandwidth', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('bandwidth') ? 'has-error' : '' }}">
                                                            {!! Form::number("bandwidth[$index]",$item->bandwidth, ['class' => 'form-control bandwidth', 'placeholder' => '', 'id' => 'bandwidth_'.$index]) !!}
                                                            {!! $errors->first('bandwidth', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('name_of_clients_'.$index, 'Name of the Clients', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('name_of_clients') ? 'has-error' : '' }}">
                                                            {!! Form::text("name_of_clients[$index]",$item->name_of_clients, ['class' => 'form-control name_of_clients', 'placeholder' => '', 'id' => 'name_of_clients_'.$index]) !!}
                                                            {!! $errors->first('name_of_clients', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('type_of_activity_'.$index, 'Type of Activity', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('type_of_activity') ? 'has-error' : '' }}">
                                                            {!! Form::text("type_of_activity[$index]",$item->type_of_activity, ['class' => 'form-control type_of_activity', 'placeholder' => '', 'id' => 'type_of_activity_'.$index]) !!}
                                                            {!! $errors->first('type_of_activity', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            {{-- Existing International Bandwidth connectivity detail, if any--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Existing International Bandwidth Connectivity Detail, If Any
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" id="connectivity_tbl">
                                <thead>
                                <tr>
                                    <th rowspan="2" width="27%" style="text-align: center; vertical-align: middle">Call
                                        Call Center address in Bangladesh
                                    </th>
                                    <th rowspan="2" width="27%" style="text-align: center; vertical-align: middle">
                                        Address of Foreign End PoP
                                    </th>
                                    <th width="29%" colspan="2" style="text-align: center; vertical-align: middle">
                                        Existing Bandwidth (Kbps/ Mbps)
                                    </th>
                                    <th width="28%" colspan="2" style="text-align: center; vertical-align: middle">
                                        Bandwidth Provider
                                    </th>
                                </tr>
                                <tr>
                                    <th style="text-align: center; vertical-align: middle">IPLC</th>
                                    <th style="text-align: center; vertical-align: middle">Backup</th>
                                    <th style="text-align: center; vertical-align: middle">IPLC</th>
                                    <th style="text-align: center; vertical-align: middle">Backup</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" class="form-control bandwidth_call_center"
                                               name="bandwidth_call_center"
                                               placeholder=""
                                               value="{{ $appInfo->bandwidth_call_center }}"
                                        ></td>
                                    <td><input type="text" class="form-control address_of_foreign"
                                               name="address_of_foreign"
                                               placeholder=""
                                               value="{{ $appInfo->bandwidth_call_center }}"
                                        ></td>
                                    <td><input type="number" class="form-control existing_bandwidth_iplc"
                                               name="existing_bandwidth_iplc"
                                               placeholder=""
                                               value="{{ $appInfo->existing_bandwidth_iplc }}"
                                        ></td>
                                    <td><input type="number" class="form-control existing_bandwidth_backup"
                                               name="existing_bandwidth_backup"
                                               placeholder=""
                                               value="{{ $appInfo->existing_bandwidth_backup }}"
                                        ></td>
                                    <td><input type="text" class="form-control bandwidth_provider_iplc"
                                               name="bandwidth_provider_iplc"
                                               placeholder=""
                                               value="{{ $appInfo->bandwidth_provider_iplc }}"
                                        ></td>
                                    <td><input type="text" class="form-control bandwidth_provider_backup"
                                               name="bandwidth_provider_backup"
                                               placeholder=""
                                               value="{{ $appInfo->bandwidth_provider_backup }}"
                                        ></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{--Person employed in call center (Local and Foreign)--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Person Employed in Call Center (Local and Foreign)
                </div>
                <div class="card-body" style="padding: 15px 25px;">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('local_employee', 'Local Employee', ['class' => 'col-md-4 ']) !!}
                                <div class="col-md-8 {{ $errors->has('local_employee') ? 'has-error' : '' }}">
                                    {!! Form::number('local_employee',$appInfo->local_employee,
                                        ['class' => 'form-control local_employee',
                                        'placeholder' => '', 'id' => 'local_employee']) !!}
                                    {!! $errors->first('local_employee', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('foreign_employee', 'Foreign Employee', ['class' => 'col-md-4 ']) !!}
                                <div
                                    class="col-md-8 {{ $errors->has('foreign_employee') ? 'has-error' : '' }}">
                                    {!! Form::number('foreign_employee',$appInfo->foreign_employee, ['class' => 'form-control foreign_employee', 'placeholder' => '', 'id' => 'foreign_employee']) !!}
                                    {!! $errors->first('foreign_employee', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            {{-- Combined Turnover of Call Centers of the Company suring last 2 Financial Years--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Combined Turnover of Call Centers of the Company Suring Last 2 Financial Years
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="text-align: center; vertical-align: middle">Year</th>
                                    <th style="text-align: center; vertical-align: middle">Turnover (In Taka)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control financial_years" name="financial_years[1]">
                                            <option value="" selected>select</option>
                                            <option {{ $appInfo->fast_financial_years=='2021-2022'?'selected':'' }} value="2021-2022">2021-2022</option>
                                            <option {{ $appInfo->fast_financial_years=='2022-2023'?'selected':'' }} value="2022-2023">2022-2023</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control financial_amount"
                                               name="financial_amount[1]"
                                               placeholder="0.00"
                                               value="{{ $appInfo->fast_financial_amount }}"
                                        ></td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control financial_years" name="financial_years[2]">
                                            <option value="" selected>select</option>
                                            <option {{ $appInfo->second_financial_years=='2021-2022'?'selected':'' }} value="2021-2022">2021-2022</option>
                                            <option {{ $appInfo->second_financial_years=='2022-2023'?'selected':'' }} value="2022-2023">2022-2023</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control financial_amount"
                                               name="financial_amount[2]"
                                               placeholder="0.00"
                                               value="{{ $appInfo->second_financial_amount }}"
                                        ></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@else
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Existing Call Centers (If Applicable)
        </div>
        <div class="card-body" style="padding: 15px 25px;">

            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Addresses Of All The Existing Centers, If Any, Under Other License (S)
                </div>
                <div class="card-body" style="padding: 15px 25px;">

                    <table class="table-responsive"
                           style="width: 100%;     display: inline-table!important;"
                           id="areaAddressRow">
                        <input type="hidden" id="areaAddressDataCount" name="areaAddressDataCount"
                               value="{{ count($existingCallCenterDetails) }}"/>
                        @foreach($existingCallCenterDetails as $index=>$areaData)
                            <tr id="aa_r_{{$index+1}}">
                                <td>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="card-header">
                                            Existing Call Center Details
                                            @if($index == 0)
                                                <span style="float: right; cursor: pointer;"
                                                      class="addAreaAddressRow">
                                                                    <i style="font-size: 20px;"
                                                                       class="fa fa-plus-square" aria-hidden="true"></i>
                                                                </span>
                                            @endif
                                        </div>
                                        <div class="card-body" style="padding: 15px 25px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('proposal_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('proposal_district') ? 'has-error' : '' }}">
                                                            {!! Form::select("proposal_district[$index]", $districts, $areaData->proposal_district, ['class' => 'form-control aa_district proposal_district', 'id' => 'proposal_district_'.($index+(int)1), 'onchange' => "getThanaByDistrictId('proposal_district_1', this.value, 'proposal_thana_1',0)"]) !!}
                                                            {!! $errors->first('proposal_district', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('proposal_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('proposal_thana') ? 'has-error' : '' }}">
                                                            {!! Form::select("proposal_thana[$index]", [], $areaData->proposal_thana, ['class' => 'form-control aa_thana proposal_thana', 'data-id'=>$areaData->proposal_thana, 'placeholder' => 'Select district at first', 'id' => 'proposal_thana_'.($index+(int)1)]) !!}
                                                            {!! $errors->first('proposal_thana', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        {!! Form::label('proposal_address_'.($index+(int)1), 'Address', ['class' => 'col-md-2 required-star']) !!}
                                                        <div
                                                            class="col-md-10 {{ $errors->has('proposal_address') ? 'has-error' : '' }}">
                                                            {!! Form::text("proposal_address[$index]", $areaData->proposal_address, ['class' => 'form-control proposal_address', 'placeholder' => 'Enter The Address', 'id' => 'proposal_address_'.($index+(int)1)]) !!}
                                                            {!! $errors->first('proposal_address', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('proposal_no_of_seats_'.($index+(int)1), 'No.of Seats', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('proposal_no_of_seats') ? 'has-error' : '' }}">
                                                            {!! Form::number("proposal_no_of_seats[$index]", $areaData->proposal_no_of_seats, ['class' => 'form-control', 'placeholder' => 'Enter the No. of Seats', 'id' => 'proposal_no_of_seats_'.($index+(int)1)]) !!}
                                                            {!! $errors->first('proposal_no_of_seats', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('proposal_employee_'.($index+(int)1), 'Proposed of Employee', ['class' => 'col-md-4']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('proposal_employee') ? 'has-error' : '' }}">
                                                            {!! Form::number("proposal_employee[$index]", $areaData->proposal_employee, ['class' => 'form-control', 'placeholder' => 'Enter The Proposed of Employee', 'id' => 'proposal_employee_'.($index+(int)1)]) !!}
                                                            {!! $errors->first('proposal_employee', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('local_'.($index+(int)1), 'Local', ['class' => 'col-md-4 required-star']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('local') ? 'has-error' : '' }}">
                                                            {!! Form::text("local[$index]", $areaData->local, ['class' => 'form-control', 'placeholder' => 'Enter Local', 'id' => 'local_'.($index+(int)1)]) !!}
                                                            {!! $errors->first('local', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('expatriate_'.($index+(int)1), 'Expatriate', ['class' => 'col-md-4 required-star']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('proposal_employee') ? 'has-error' : '' }}">
                                                            {!! Form::text("expatriate[$index]", $areaData->expatriate, ['class' => 'form-control', 'placeholder' => 'Enter Expatriate', 'id' => 'expatriate_'.($index+(int)1)]) !!}
                                                            {!! $errors->first('expatriate', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>


        </div>
    </div>
@endif

<script>
    @if(in_array($mode, ['amendment', 'surrender']))
    // Contact Information
    @isset($existingCallCenterDetails)
    @foreach($existingCallCenterDetails as $index => $ccdetails)
    getThanaByDistrictId('existing_district_{{$index}}', {{ $ccdetails->district ?? ''}},
        'existing_thana_{{$index}}', {{ $ccdetails->thana ?? '' }});
    @endforeach
    @endisset
    @endif

    $(document).ready(function() {
        @if(in_array($mode, ['add', 'edit', 'amendment']))
            $(".addExistingCallCenter").on('click', function() {
                let lastRowId =  document.getElementsByClassName('single_call_center').length;
                let updateRowId = parseInt(lastRowId)+1;

                $('#existing_call_center').append(` <tr id='single_call_center_${updateRowId}' class="single_call_center" >
                                    <td>
                                        <div class="card card-magenta border border-magenta">
                                            <div class="card-header">
                                                Existing Call Center Details
                                                <span style="float: right; cursor: pointer;">
                                             <button type="button" onclick="deleteExistingCallCenterRow(single_call_center_${updateRowId})" class="btn btn-danger btn-sm shareholderRow cross-button"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                        </span>
                                            </div>
                                            <div class="card-body" style="padding: 15px 25px;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('existing_district_${updateRowId}', 'District', ['class' => 'col-md-4']) !!}
                <div
                    class="col-md-8 {{ $errors->has('existing_district') ? 'has-error' : '' }}">
                                                                {!! Form::select('existing_district[${updateRowId}]', $districts, '', ['class' => 'form-control aa_district existing_district', 'id' => 'existing_district_${updateRowId}', 'onchange' => 'getThanaByDistrictId("existing_district_${updateRowId}", this.value, "existing_thana_${updateRowId}",0)']) !!}
                {!! $errors->first('existing_district', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
        {!! Form::label('existing_thana_${updateRowId}', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                <div
                    class="col-md-8 {{ $errors->has('existing_thana') ? 'has-error' : '' }}">
                                                                {!! Form::select('existing_thana[${updateRowId}]', [], '', ['class' => 'form-control aa_thana existing_thana', 'placeholder' => 'Select district at first', 'id' => 'existing_thana_${updateRowId}']) !!}
                {!! $errors->first('existing_thana', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
        {!! Form::label('existing_address_${updateRowId}', 'Address', ['class' => 'col-md-2']) !!}
                <div
                    class="col-md-10 {{ $errors->has('proposal_address') ? 'has-error' : '' }}">
                                                                {!! Form::text('existing_address[${updateRowId}]','', ['class' => 'form-control existing_address', 'placeholder' => 'Enter The Address', 'id' => 'existing_address_${updateRowId}']) !!}
                {!! $errors->first('existing_address', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        </div>
        <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
        {!! Form::label('nature_of_center_${updateRowId}', 'Nature of Center', ['class' => 'col-md-4']) !!}
                <div
                    class="col-md-8 {{ $errors->has('nature_of_center_1') ? 'has-error' : '' }}">
                                                                {!! Form::select('nature_of_center_[${updateRowId}]',[ "Domestic","International" ], '', ['class' => 'form-control nature_of_center', 'placeholder' => 'Select', 'id' => 'nature_of_center_${updateRowId}']) !!}
                {!! $errors->first('nature_of_center_1', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        {{--<div class="col-md-6">--}}
        {{--    <div class="form-group row">--}}
        {{--{!! Form::label('type_of_center_${updateRowId}', 'Type of Center', ['class' => 'col-md-4']) !!}--}}
        {{--        <div--}}
        {{--            class="col-md-8 {{ $errors->has('type_of_center') ? 'has-error' : '' }}">--}}
        {{--                                                        {!! Form::select('type_of_center[${updateRowId}]', [ "HCCSP","HOST Call Center", "Call Center" ], '', ['class' => 'form-control type_of_center', 'placeholder' => 'Select', 'id' => 'type_of_center_${updateRowId}']) !!}--}}
        {{--        {!! $errors->first('type_of_center', '<span class="help-block">:message</span>') !!}--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--</div>--}}
        </div>

        <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
        {!! Form::label('existing_license_no_{updateRowId}', 'Number of Registration Certificate. .', ['class' => 'col-md-4']) !!}
                <div
                    class="col-md-8 {{ $errors->has('existing_license_no') ? 'has-error' : '' }}">
                                                                {!! Form::text('existing_license_no[${updateRowId}]',"", ['class' => 'form-control existing_license_no', 'placeholder' => '', 'id' => 'existing_license_no_{updateRowId}']) !!}
                {!! $errors->first('existing_license_no', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
        {!! Form::label('date_of_license_${updateRowId}', 'Date of Registration Certificate', ['class' => 'col-md-4']) !!}

                <div
                    class="col-md-8 {{ $errors->has('date_of_license') ? 'has-error' : '' }}">
                                                                <div class="input-group date datetimepicker4"
                                                                     id='date_of_license${updateRowId}'
                                                                     data-target-input="nearest">
                                                                    {!! Form::text('date_of_license[${updateRowId}]','', ['class' => 'form-control date_of_license ', 'id' => 'date_of_license_${updateRowId}', 'placeholder' => 'Enter Date of Registration Certificate']) !!}
                <div class="input-group-append"
                     data-target="#date_of_license${updateRowId}"
                     data-toggle="datetimepicker">
                    <div class="input-group-text"><i
                            class="fa fa-calendar"></i></div>
                </div>
        {!! $errors->first('date_of_license', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        </div>
        </div>

        <div class="row">
        <div class="col-md-6">
        <div class="form-group row">
        {!! Form::label('no_of_agents_${updateRowId}', 'No. of Agents/ Seats as on Date', ['class' => 'col-md-4']) !!}
                <div
                    class="col-md-8 {{ $errors->has('no_of_agents') ? 'has-error' : '' }}">
                                                                {!! Form::number('no_of_agents[${updateRowId}]',"", ['class' => 'form-control no_of_agents', 'placeholder' => '', 'id' => 'no_of_agents_${updateRowId}']) !!}
                {!! $errors->first('no_of_agents', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
        {!! Form::label('bandwidth_${updateRowId}', 'Bandwidth', ['class' => 'col-md-4']) !!}
                <div
                    class="col-md-8 {{ $errors->has('bandwidth') ? 'has-error' : '' }}">
                                                                {!! Form::number('bandwidth[${updateRowId}]',"", ['class' => 'form-control bandwidth', 'placeholder' => '', 'id' => 'bandwidth_${updateRowId}']) !!}
                {!! $errors->first('bandwidth', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        </div>

        <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
        {!! Form::label('name_of_clients_${updateRowId}', 'Name of the Clients', ['class' => 'col-md-4']) !!}
                <div
                    class="col-md-8 {{ $errors->has('name_of_clients') ? 'has-error' : '' }}">
                                                                {!! Form::text('name_of_clients[${updateRowId}]',"", ['class' => 'form-control name_of_clients', 'placeholder' => '', 'id' => 'name_of_clients_${updateRowId}']) !!}
                {!! $errors->first('name_of_clients', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

           <div class="col-md-6">
            <div class="form-group row">
        {!! Form::label('starting_date_of_service_${updateRowId}', 'Starting Date of Service', ['class' => 'col-md-4']) !!}

                <div
                    class="col-md-8 {{ $errors->has('starting_date_of_service') ? 'has-error' : '' }}">
                                                                <div class="input-group date datetimepicker4"
                                                                     id='starting_date_of_service${updateRowId}'
                                                                     data-target-input="nearest">
                                                                    {!! Form::text('starting_date_of_service[${updateRowId}]','', ['class' => 'form-control starting_date_of_service ', 'id' => 'starting_date_of_service_${updateRowId}', 'placeholder' => 'Enter Starting Date Of Service']) !!}
                <div class="input-group-append"
                     data-target="#starting_date_of_service${updateRowId}"
                     data-toggle="datetimepicker">
                    <div class="input-group-text"><i
                            class="fa fa-calendar"></i></div>
                </div>
        {!! $errors->first('starting_date_of_service', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        {{--<div class="col-md-6">--}}
        {{--    <div class="form-group row">--}}
        {{--{!! Form::label('type_of_activity_${updateRowId}', 'Type of Activity', ['class' => 'col-md-4']) !!}--}}
        {{--        <div--}}
        {{--            class="col-md-8 {{ $errors->has('type_of_activity') ? 'has-error' : '' }}">--}}
        {{--                                                        {!! Form::text('type_of_activity[${updateRowId}]',"", ['class' => 'form-control type_of_activity', 'placeholder' => '', 'id' => 'type_of_activity_${updateRowId}']) !!}--}}
        {{--        {!! $errors->first('type_of_activity', '<span class="help-block">:message</span>') !!}--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--</div>--}}
        </div>
        </div>
        </div>
        </td>
        </tr>`);

                $('.datetimepicker4').datetimepicker({
                    format: 'DD-MMM-YYYY',
                    maxDate: 'now',
                    minDate: '01/01/' + (yyyy - 110),
                    ignoreReadonly: true,
                });
                getHelpText('bpo-or-call-center-renew-app');


            });
            $('#shareholderRow').on('click', '.shareholderRow', function () {
                let prevDataCount = $("#shareholderDataCount").val();

                var child = $(this).closest('tr').nextAll();

                child.each(function () {
                    var id = $(this).attr('id');
                    var idx = $(this).children('.row-index').children('p');
                    var dig = parseInt(id.substring(1));
                    idx.html(`Row ${dig - 1}`);
                    $(this).attr('id', `R${dig - 1}`);
                });
                $(this).closest('tr').remove();
                rowId--;
                $("#shareholderDataCount").val(prevDataCount - 1);
            });
        @endif

        @if(in_array($mode, ['amendment']))
            makeReadOnlyByDivId('readOnly');
        @endif

        function makeReadOnlyByDivId(id) {
            const inputFieldsList = document.querySelectorAll(`#${id} input, #${id} select`);
            const labels = document.querySelectorAll(`#${id} label`)
            inputFieldsList.forEach(item => {
                item.classList.add('input_disabled');
            })
            labels.forEach(item => {
                item.style.pointerEvents = 'none';
            })
        }
    });
    getHelpText('bpo-or-call-center-renew-app');

    @if(in_array($mode, ['amendment', 'surrender']))
        makeReadOnlyByDivId('readOnly');
    @endif

    function makeReadOnlyByDivId(id) {
        const inputFieldsList = document.querySelectorAll(`#${id} input, #${id} select`);
        const labels = document.querySelectorAll(`#${id} label`)
        inputFieldsList.forEach(item => {
            item.classList.add('input_disabled');
        })
        labels.forEach(item => {
            item.style.pointerEvents = 'none';
        })
    }

    function deleteExistingCallCenterRow(element) {
        let lastRowId = document.getElementsByClassName('single_call_center').length;
        $("#existing_call_center_count").val(lastRowId - 1);
        element.remove();
    }

    $("#existingEditBtn").click(function() {
        if (this.checked){
            makeReadWriteByDivId('readOnly');
        }
        else {
            makeReadOnlyByDivId('readOnly');
        }
    });
</script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $(".loading_data").hide();
        }, 8000);
    });
</script>
