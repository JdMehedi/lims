<style>
    .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
    }
</style>

@php
    $addressDefaultLabel = 'Address';
    if(isset($extra)) {
        if(in_array('address2', $extra)) {
            $addressDefaultLabel = 'Address Line 1';
        }
    }
@endphp
@if($mode === 'add')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Company/ Organization Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_name', 'Company/ Organization Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('company_name') ? 'has-error' : '' }}">
                            {!! Form::text('company_name', isset($companyInfo->org_nm) ? $companyInfo->org_nm : '',
                                ['class' => 'form-control',
                                'readonly' => isset($companyInfo->org_nm) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'company_name']) !!}
                            {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('company_type') ? 'has-error' : '' }}">
                            <?php
                                $companyType = \App\Modules\CompanyProfile\Models\CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
                            ?>
                            {!! Form::select('company_type',$companyType , isset($companyInfo->org_type) ? $companyInfo->org_type : '', ['class' => 'form-control input_disabled',
                            'readonly' => isset($companyInfo->org_type) ?? 'readonly',
                            'id' => 'company_type']) !!}
                            {!! $errors->first('company_type', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('incorporation_num', 'Incorporation Number', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('incorporation_num') ? 'has-error' : '' }}">
                            {!! Form::text('incorporation_num', isset($companyInfo->incorporation_num) ? $companyInfo->incorporation_num : '',
                                ['class' => 'form-control',
                                'readonly' => isset($companyInfo->incorporation_num) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'company_name']) !!}
                            {!! $errors->first('incorporation_num', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('incorporation_date', 'Incorporation Date', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('incorporation_date') ? 'has-error' : '' }}">
                            {!! Form::text('incorporation_date', isset($companyInfo->incorporation_date) ? $companyInfo->incorporation_date : '',
                                ['class' => 'form-control',
                                'readonly' => isset($companyInfo->incorporation_date) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'company_name']) !!}
                            {!! $errors->first('incorporation_date', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>

            </div>

            <br>

            {{-- Registered Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Registered Office Address
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_district', $districts, isset($companyInfo->office_district) ? $companyInfo->office_district : '', ['class' => 'form-control','required' => 'required', 'id' => 'reg_office_district', 'onchange' => "getThanaByDistrictId('reg_office_district', this.value, 'reg_office_thana',0)"]) !!}
                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_thana', isset($thana) ? $thana : [], $companyInfo->office_thana, ['class' => 'form-control','required' => 'required', 'placeholder' => 'Select district at first', 'id' => 'reg_office_thana']) !!}
                                    {!! $errors->first('reg_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_address', $addressDefaultLabel, ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_address') ? 'has-error' : '' }}">
                                    {!! Form::text('reg_office_address', isset($companyInfo->office_location) ? $companyInfo->office_location : '', ['class' => 'form-control','required' => 'required', 'placeholder' => 'Enter  '.$addressDefaultLabel, 'id' => 'reg_office_address']) !!}
                                    {!! $errors->first('reg_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        @isset($extra)
                            @if(in_array('address2', $extra))
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('reg_office_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                        <div
                                                class="col-md-8 {{ $errors->has('reg_office_address2') ? 'has-error' : '' }}">
                                            {!! Form::text('reg_office_address2', '', ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 2', 'id' => 'reg_office_address2']) !!}
                                            {!! $errors->first('reg_office_address2', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>

            {{-- Operational Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Operational Office Address
                    <span style="float: right; cursor: pointer;" class="m-l-auto"
                          id="permanentSameAsRegisterdAddressSec">
                        {!! Form::checkbox('permanentSameAsRegisterdAddress', 'YES', false, ['id'=> 'permanentSameAsRegisterdAddress', 'style' => 'vertical-align:middle' ]) !!}
                        {!! Form::label('permanentSameAsRegisterdAddress', 'As Same As Registered Address') !!}
                    </span>
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_district') ? 'has-error' : '' }}">
                                    {!! Form::select('op_office_district', $districts, '', ['class' => 'form-control','required' => 'required', 'id' => 'op_office_district', 'onchange' => "getThanaByDistrictId('op_office_district', this.value, 'op_office_thana',0)"]) !!}
                                    {!! $errors->first('op_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('op_office_thana', [], '', ['class' => 'form-control','required' => 'required', 'placeholder' => 'Select district at first', 'id' => 'op_office_thana']) !!}
                                    {!! $errors->first('op_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_address', $addressDefaultLabel, ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('op_office_address') ? 'has-error' : '' }}">
                                    {!! Form::text('op_office_address', '', ['class' => 'form-control','required' => 'required', 'placeholder' => 'Enter  '.$addressDefaultLabel, 'id' => 'op_office_address']) !!}
                                    {!! $errors->first('op_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        @isset($extra)
                            @if(in_array('address2', $extra))
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('op_office_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                        <div
                                                class="col-md-8 {{ $errors->has('op_office_address2') ? 'has-error' : '' }}">
                                            {!! Form::text('op_office_address2', '', ['class' => 'form-control', 'placeholder' => 'Enter  Address', 'id' => 'op_office_address2']) !!}
                                            {!! $errors->first('op_office_address2', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>

        </div>
    </div>
@elseif($mode === 'edit')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Company/ Organization Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_name', 'Company/ Organization Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('company_name') ? 'has-error' : '' }}">
                            {!! Form::text('company_name', isset($appInfo->org_nm) ? $appInfo->org_nm : '',
                                ['class' => 'form-control',
                                'readonly' => isset($appInfo->org_nm) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'company_name']) !!}
                            {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('company_type') ? 'has-error' : '' }}">
                            <?php
                            $companyType = \App\Modules\CompanyProfile\Models\CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
                            ?>
                            {!! Form::select('company_type',$companyType, isset($appInfo->org_type) ? $appInfo->org_type : '', ['class' => 'form-control input_disabled',
                            'readonly' => isset($appInfo->org_type) ?? 'readonly',
                            'id' => 'company_type']) !!}
                            {!! $errors->first('company_type', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>


            </div>

            <br>

            {{-- Registered Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Registered Office Address
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_district', $districts,$appInfo->reg_office_district, ['class' => 'form-control','required' => 'required', 'id' => 'reg_office_district', 'onchange' => "getThanaByDistrictId('reg_office_district', this.value, 'reg_office_thana',0)"]) !!}
                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_thana', [], $appInfo->reg_office_thana, ['class' => 'form-control','required' => 'required', 'placeholder' => 'Select district at first', 'id' => 'reg_office_thana']) !!}
                                    {!! $errors->first('reg_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_address', $addressDefaultLabel, ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_address') ? 'has-error' : '' }}">
                                    {!! Form::text('reg_office_address', $appInfo->reg_office_address, ['class' => 'form-control','required' => 'required', 'placeholder' => 'Enter '.$addressDefaultLabel, 'id' => 'reg_office_address']) !!}
                                    {!! $errors->first('reg_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        @isset($extra)
                            @if(in_array('address2', $extra))
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('reg_office_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                        <div
                                                class="col-md-8 {{ $errors->has('reg_office_address2') ? 'has-error' : '' }}">
                                            {!! Form::text('reg_office_address2', $appInfo->reg_office_address2, ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 2', 'id' => 'reg_office_address2']) !!}
                                            {!! $errors->first('reg_office_address2', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>

            {{-- Operational Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Operational Office Address
                    <span style="float: right; cursor: pointer;" class="m-l-auto"
                          id="permanentSameAsRegisterdAddressSec">
                                        {!! Form::checkbox('permanentSameAsRegisterdAddress', 'YES', false, ['id'=> 'permanentSameAsRegisterdAddress', 'style' => 'vertical-align:middle' ]) !!}
                        {!! Form::label('permanentSameAsRegisterdAddress', 'Same as Registered office address') !!}
                                    </span>
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_district') ? 'has-error' : '' }}">
                                    {!! Form::select('op_office_district', $districts, $appInfo->op_office_district, ['class' => 'form-control','required' => 'required', 'id' => 'op_office_district', 'onchange' => "getThanaByDistrictId('op_office_district', this.value, 'op_office_thana',0)"]) !!}
                                    {!! $errors->first('op_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('op_office_thana', $thana, $appInfo->op_office_thana, ['class' => 'form-control','required' => 'required', 'placeholder' => 'Select district at first', 'id' => 'op_office_thana']) !!}
                                    {!! $errors->first('op_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_address', $addressDefaultLabel, ['class' => 'col-md-4 required-star']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_address') ? 'has-error' : '' }}">
                                    {!! Form::text('op_office_address', $appInfo->op_office_address, ['class' => 'form-control','required' => 'required', 'placeholder' => 'Enter '.$addressDefaultLabel, 'id' => 'op_office_address']) !!}
                                    {!! $errors->first('op_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        @isset($extra)
                            @if(in_array('address2', $extra))
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('op_office_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                        <div
                                                class="col-md-8 {{ $errors->has('noc_address') ? 'has-error' : '' }}">
                                            {!! Form::text('op_office_address2', $appInfo->op_office_address2, ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 2', 'id' => 'op_office_address2']) !!}
                                            {!! $errors->first('op_office_address2', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>

        </div>
    </div>
@elseif($mode === 'view')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Company/ Organization Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_name', 'Company/ Organization Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->org_nm }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <?php
                            $companyType = \App\Modules\CompanyProfile\Models\CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
                            ?>
                            <span>: {{ $companyType[$appInfo->org_type] ?? null }}</span>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('incorporation_num', 'Incorporation Number', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->incorporation_num }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('incorporation_date', 'Incorporation Date', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->incorporation_date }}</span>
                        </div>
                    </div>
                </div>


            </div>
            <br>
            {{-- Registered Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Registered Office Address
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div class="col-md-8 ">
                                    <span>: {{ $appInfo->reg_office_district_en }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 ">
                                    <span>: {{ $appInfo->reg_office_thana_en }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_address', $addressDefaultLabel, ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8">
                                    <span>: {{ $appInfo->reg_office_address }}</span>
                                </div>
                            </div>
                        </div>
                        @isset($extra)
                            @if(in_array('address2', $extra))
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('reg_office_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                        <div
                                                class="col-md-8">
                                            <span>: {{ $appInfo->reg_office_address2 }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>

            {{-- Operational Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Operational Office Address
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8">
                                    <span>: {{ $appInfo->op_office_district_en }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8">
                                    <span>: {{ $appInfo->op_office_thana_en }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_address', $addressDefaultLabel, ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8">
                                    <span>: {{ $appInfo->op_office_address }}</span>
                                </div>
                            </div>
                        </div>
                        @isset($extra)
                            @if(in_array('address2', $extra))
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('op_office_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                        <div
                                                class="col-md-8">
                                            <span>: {{ $appInfo->op_office_address2 }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($mode === 'renew')
<div class="card card-magenta border border-magenta">
        <div class="card-header d-flex justify-content-between areaAddress">
            <div class="col-md-10">Company/ Organization Information</div>
            <div>
                <label class="amendmentEditBtn" style="vertical-align: middle;">
                    <input type="checkbox" id="companyInfo" style="vertical-align: middle;">
                    EDIT
                </label>
            </div>
        </div>
        <div class="card-body" id="companyFields" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_name', 'Company/ Organization Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('company_name') ? 'has-error' : '' }}">
                            {!! Form::text('company_name', isset($appInfo->org_nm) ? $appInfo->org_nm : '',
                                ['class' => 'form-control input_disabled',
                                'readonly' => isset($appInfo->org_nm) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'company_name']) !!}
                            {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('company_type') ? 'has-error' : '' }}">
                            <?php
                            $companyType = \App\Modules\CompanyProfile\Models\CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
                            ?>

                            {!! Form::select('company_type',$companyType, isset($appInfo->org_type) ? $appInfo->org_type : '', ['class' => 'form-control input_disabled',
                            'readonly' => isset($appInfo->org_type) ?? 'readonly',
                            'id' => 'company_type']) !!}
                            {!! $errors->first('company_type', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('incorporation_num', 'Incorporation Num', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('incorporation_num') ? 'has-error' : '' }}">
                            {!! Form::text('incorporation_num', isset($appInfo->incorporation_num) ? $appInfo->incorporation_num : '',
                                ['class' => 'form-control input_disabled',
                                'readonly' => isset($appInfo->incorporation_num) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'incorporation_num']) !!}
                            {!! $errors->first('incorporation_num', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('incorporation_date', 'Incorporation Date', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('incorporation_date') ? 'has-error' : '' }}">
                            {!! Form::text('incorporation_date', isset($appInfo->incorporation_date) ? $appInfo->incorporation_date : '',
                                ['class' => 'form-control input_disabled',
                                'readonly' => isset($appInfo->incorporation_date) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'incorporation_date']) !!}
                            {!! $errors->first('incorporation_date', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>


            </div>

            <br>

            {{-- Registered Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Registered Office Address
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_district', $districts,$appInfo->reg_office_district, ['class' => 'form-control input_disabled', 'id' => 'reg_office_district', 'onchange' => "getThanaByDistrictId('reg_office_district', this.value, 'reg_office_thana',0)"]) !!}
                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_thana', [], $appInfo->reg_office_thana, ['class' => 'form-control input_disabled', 'placeholder' => 'Select district at first', 'id' => 'reg_office_thana']) !!}
                                    {!! $errors->first('reg_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_address') ? 'has-error' : '' }}">
                                    {!! Form::text('reg_office_address', $appInfo->reg_office_address, ['class' => 'form-control input_disabled', 'placeholder' => 'Enter  Address', 'id' => 'reg_office_address']) !!}
                                    {!! $errors->first('reg_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        @isset($extra)
                        @if(in_array('address2', $extra))
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('reg_office_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                    <div
                                            class="col-md-8 {{ $errors->has('reg_office_address2') ? 'has-error' : '' }}">
                                        {!! Form::text('reg_office_address2', $appInfo->reg_office_address2, ['class' => 'form-control  input_disabled', 'placeholder' => 'Enter  Address Line 2', 'id' => 'reg_office_address2']) !!}
                                        {!! $errors->first('reg_office_address2', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endisset
                    </div>
                </div>
            </div>

            {{-- Operational Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Operational Office Address
                    <span style="float: right; cursor: pointer;" class="m-l-auto"
                          id="permanentSameAsRegisterdAddressSec">
                                        {!! Form::checkbox('permanentSameAsRegisterdAddress', 'YES', false, ['id'=> 'permanentSameAsRegisterdAddress','style' => 'vertical-align:middle']) !!}
                        {!! Form::label('permanentSameAsRegisterdAddress', 'As Same As Registered Address') !!}
                                    </span>
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_district') ? 'has-error' : '' }}">
                                    {!! Form::select('op_office_district', $districts, $appInfo->op_office_district, ['class' => 'form-control input_disabled', 'id' => 'op_office_district', 'onchange' => "getThanaByDistrictId('op_office_district', this.value, 'op_office_thana',0)"]) !!}
                                    {!! $errors->first('op_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('op_office_thana', [], $appInfo->op_office_thana, ['class' => 'form-control input_disabled', 'placeholder' => 'Select district at first', 'id' => 'op_office_thana']) !!}
                                    {!! $errors->first('op_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_address') ? 'has-error' : '' }}">
                                    {!! Form::text('op_office_address', $appInfo->op_office_address, ['class' => 'form-control input_disabled', 'placeholder' => 'Enter  Address', 'id' => 'op_office_address']) !!}
                                    {!! $errors->first('op_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        @isset($extra)
                            @if(in_array('address2', $extra))
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('op_office_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                        <div
                                                class="col-md-8 {{ $errors->has('noc_address') ? 'has-error' : '' }}">
                                            {!! Form::text('op_office_address2', $appInfo->op_office_address2, ['class' => 'form-control  input_disabled', 'placeholder' => 'Enter  Address Line 2', 'id' => 'op_office_address2']) !!}
                                            {!! $errors->first('op_office_address2', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>

        </div>
    </div>
@elseif($mode === "amendment")
    <div class="card card-magenta border border-magenta">
        <div class="card-header d-flex justify-content-between areaAddress">
            <div>Company/ Organization Information</div>
            <div>
                <label class="amendmentEditBtn">
                    <input type="checkbox" id="companyInfo"/>
                    EDIT
                </label>
            </div>
        </div>
        <div class="card-body" id="companyFields" style="padding: 15px 25px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_name', 'Company/ Organization Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('company_name') ? 'has-error' : '' }}">
                            {!! Form::text('company_name', isset($appInfo->company_name) ? $appInfo->company_name : '',
                                ['class' => 'form-control input_disabled',
                                'placeholder' => 'Enter Name', 'id' => 'company_name']) !!}
                            {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('company_type') ? 'has-error' : '' }}">
                            <?php
                            $companyType = \App\Modules\CompanyProfile\Models\CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
                            ?>
                            {!! Form::select('company_type',
                            $companyType, isset($appInfo->company_type) ? $appInfo->company_type : '',
                            ['class' => 'form-control input_disabled',
                            'id' => 'company_type']) !!}
                            {!! $errors->first('company_type', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('incorporation_num', 'Incorporation Num', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('incorporation_num') ? 'has-error' : '' }}">
                            {!! Form::text('incorporation_num', isset($appInfo->incorporation_num) ? $appInfo->incorporation_num : '',
                                ['class' => 'form-control input_disabled',
                                'readonly' => isset($appInfo->incorporation_num) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'incorporation_num']) !!}
                            {!! $errors->first('incorporation_num', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('incorporation_date', 'Incorporation Date', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('incorporation_date') ? 'has-error' : '' }}">
                            {!! Form::text('incorporation_date', isset($appInfo->incorporation_date) ? $appInfo->incorporation_date : '',
                                ['class' => 'form-control input_disabled',
                                'readonly' => isset($appInfo->incorporation_date) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'incorporation_date']) !!}
                            {!! $errors->first('incorporation_date', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <br>
            {{-- Registered Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Registered Office Address
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_district', $districts, $appInfo->reg_office_district, ['class' => 'form-control input_disabled', 'id' => 'reg_office_district', 'onchange' => "getThanaByDistrictId('reg_office_district', this.value, 'reg_office_thana',0)"]) !!}
                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_thana', [], $appInfo->reg_office_thana, ['class' => 'form-control input_disabled', 'placeholder' => 'Select district at first', 'id' => 'reg_office_thana']) !!}
                                    {!! $errors->first('reg_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_address') ? 'has-error' : '' }}">
                                    {!! Form::text('reg_office_address', $appInfo->reg_office_address, ['class' => 'form-control input_disabled', 'placeholder' => 'Enter  Address', 'id' => 'reg_office_address']) !!}
                                    {!! $errors->first('reg_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Operational Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Operational Office Address
                    <span style="float: right; cursor: pointer;" class="m-l-auto"
                          id="permanentSameAsRegisterdAddressSec">
                                        {!! Form::checkbox('permanentSameAsRegisterdAddress', 'YES', false, ['id'=> 'permanentSameAsRegisterdAddress']) !!}
                        {!! Form::label('permanentSameAsRegisterdAddress', 'As Same As Registered Address') !!}
                                    </span>
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('noc_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_district') ? 'has-error' : '' }}">
                                    {!! Form::select('noc_district', $districts, $appInfo->op_office_district, ['class' => 'form-control input_disabled', 'id' => 'op_office_district', 'onchange' => "getThanaByDistrictId('noc_district', this.value, 'noc_thana',0)"]) !!}
                                    {!! $errors->first('noc_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('noc_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('noc_thana', [], $appInfo->op_office_thana, ['class' => 'form-control input_disabled', 'placeholder' => 'Select district at first', 'id' => 'op_office_thana']) !!}
                                    {!! $errors->first('noc_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('noc_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_address') ? 'has-error' : '' }}">
                                    {!! Form::text('noc_address', $appInfo->op_office_address, ['class' => 'form-control input_disabled', 'placeholder' => 'Enter  Address', 'id' => 'op_office_address','rows'=>'1']) !!}
                                    {!! $errors->first('noc_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    getHelpText();
    $(document).ready(function () {

        {{--        @if(in_array($mode,['add']) && isset($companyInfo->office_thana))--}}
        {{--            getThanaByDistrictId('reg_office_district', {{ $companyInfo->office_district ?? '' }},--}}
        {{--            'reg_office_thana', {{ $companyInfo->office_thana ?? '' }});--}}
        {{--        @endif--}}

        @if(!in_array($mode, ['add']) && (!isset($selected) || ($selected != 1)))
        @isset($appInfo->reg_office_district)
        getThanaByDistrictId('reg_office_district', {{ $appInfo->reg_office_district ?? '' }},
            'reg_office_thana', {{ $appInfo->reg_office_thana ?? '' }});
        @endisset

        // Operational Office Address
        @isset($appInfo->op_office_district)
        getThanaByDistrictId('op_office_district', {{ $appInfo->op_office_district ?? '' }},
            'op_office_thana', {{ $appInfo->op_office_thana ?? '' }});
        @endisset
        @endif

        $("#companyInfo").click(function () {
            if (this.checked) makeReadWriteByDivId('companyFields');
            else makeReadOnlyByDivId('companyFields');
        });

        var check = $('#same_address').prop('checked');
        if ("{{ isset($companyInfo) && $companyInfo->is_same_address === 0 }}") {
            $('#company_factory_div').removeClass('hidden');
        }
        if (check == false) {
            $('#company_factory_div').removeClass('hidden');
        }

        $("#permanentSameAsRegisterdAddress").on('change', function (e) {
            if (this.checked === true) {
                let office_district = $("#reg_office_district").val();
                let office_upazilla_thana = $("#reg_office_thana").val();
                $("#op_office_district").val(office_district);
                getThanaByDistrictId('op_office_district', office_district, 'op_office_thana', office_upazilla_thana.trim());
                $("#op_office_address").val($("#reg_office_address").val());
                $("#op_office_address2").val($("#reg_office_address2").val());

                    $("#op_office_district").addClass("input_disabled");
                    $("#op_office_thana").addClass("input_disabled");
                    $("#op_office_address").prop('readonly', true);
                    $("#op_office_address2").prop('readonly', true);

            } else {
                $("#op_office_thana").val('');
                $("#op_office_address").val('');
                $("#op_office_address2").val('');
                $("#op_office_district").val('');

                $("#op_office_thana").removeClass("input_disabled");
                $("#op_office_district").removeClass("input_disabled");
                $("#op_office_address").prop('readonly', false);
                $("#op_office_address2").prop('readonly', false);
            }
        })

        @if($mode === 'amendment' || $mode === 'renew')
        $("#permanentSameAsRegisterdAddress").on('change', function (e) {
            if (this.checked === true) {
                let office_district = $("#reg_office_district").val();
                let office_upazilla_thana = $("#reg_office_thana").val();
                $("#noc_district").val(office_district);
                getThanaByDistrictId('noc_district', office_district, 'noc_thana', office_upazilla_thana.trim());
                $("#noc_address").val($("#reg_office_address").val());

            } else {
                $("#noc_thana").val('');
                $("#noc_address").val('');
                $("#noc_district").val('');
            }
        })
        @endif

    });
</script>

