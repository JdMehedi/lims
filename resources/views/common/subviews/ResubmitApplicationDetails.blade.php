@if($mode === 'shortfall')
    <div class="card card-magenta border border-magenta" id="shareholder_wrapper">
        <div class="card-header">
            Update information for resubmit application
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <table class="table-responsive " style="width: 100%;     display: inline-table!important;" >
                <input type="hidden" id="shareholderDataCount" name="shareholderDataCount" value="1"/>
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                Trade Licence Information's
                            </div>

                                <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                    <div class="col-md-6">
                                        <div class="card-header mb-2">
                                            At the time of license Issuance(as per Previous application)

                                        </div>
                                        <div class="card card-magenta border border-magenta">
                                            <div class="form-group ml-1 mt-1 row">
                                                {!! Form::label('trade_license_number', 'Trade License Number', ['class' => 'col-md-4 required-star']) !!}
                                                <div class="col-md-8 mt-1 {{ $errors->has('trade_license_number') ? 'has-error' : '' }}">
                                                    {!! Form::text('trade_license_number', $appInfo->trade_license_number, ['class' => 'form-control trade_license_number', 'placeholder' => 'Enter Name', 'id' => 'trade_license_number']) !!}
                                                    {!! $errors->first('trade_license_number', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group ml-1 row" style="margin-top:10px;">
                                                {!! Form::label('trade_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('trade_validity') ? 'has-error' : '' }}">
                                                    <div class="input-group date datetimepicker4"
                                                         id="datepicker_for_trade" data-target-input="nearest">
                                                        {!! Form::text('trade_validity', $appInfo->trade_validity, ['class' => 'form-control trade_validity', 'id' => 'trade_validity', 'placeholder' => 'Enter trade validity']) !!}
                                                        <div class="input-group-append"
                                                             data-target="#datepicker_for_trade"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                        {!! $errors->first('trade_validity', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group ml-1 row">
                                                {!! Form::label('trade_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                                                <div class="col-md-8 {{ $errors->has('trade_address') ? 'has-error' : '' }}">
                                                    {!! Form::text('trade_address', $appInfo->trade_address, ['class' => 'form-control trade_address', 'placeholder' => 'Enter Address ', 'id' => 'trade_address']) !!}
                                                    {!! $errors->first('trade_address', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                                {!! Form::label('trade_license_attachment', 'Trade License Attachment', ['class' => 'col-md-4 required-star']) !!}
                                                <div class=" {{ $errors->has('trade_license_attachment') ? 'has-error' : '' }}">
                                                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   class="form-control {{ $appInfo->trade_license_attachment ? '' : ' trade_license_attachment' }} input-sm"
                                                                   name="trade_license_attachment" id="trade_license_attachment"
                                                                   accept="application/pdf"
                                                                   size="300x300" />
                                                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                        </div>

                                                    @if(isset($appInfo->trade_license_attachment) && !empty($appInfo->trade_license_attachment))
                                                        <div class="form-group">
                                                                <span><a class="btn btn-info" href="{{ asset($appInfo->trade_license_attachment) }}" target="_blank">Open file</a></span>
                                                        </div>

                                                    @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card-header mb-2">
                                            Information during Renewal (as per Current application)
                                        </div>
                                        <div class="card card-magenta border border-magenta">
                                            <div class="form-group ml-1 mt-1 row">
                                                {!! Form::label('current_trade_license_number', 'Trade License Number', ['class' => 'col-md-4 required-star']) !!}
                                                <div class="col-md-8 mt-1 {{ $errors->has('current_trade_license_number') ? 'has-error' : '' }}">
                                                    {!! Form::text('current_trade_license_number', $appInfo->current_trade_license_number, ['class' => 'form-control trade_license_number', 'placeholder' => 'Enter Name', 'id' => 'current_trade_license_number']) !!}
                                                    {!! $errors->first('current_trade_license_number', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group ml-1 row" style="margin-top:10px;">
                                                {!! Form::label('current_trade_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('current_trade_validity') ? 'has-error' : '' }}">
                                                    <div class="input-group date datetimepicker4"
                                                         id="datepicker_for_trade_current" data-target-input="nearest">
                                                        {!! Form::text('current_trade_validity', $appInfo->current_trade_validity, ['class' => 'form-control trade_validity', 'id' => 'current_trade_validity', 'placeholder' => 'Enter trade validity']) !!}
                                                        <div class="input-group-append"
                                                             data-target="#datepicker_for_trade_current"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                        {!! $errors->first('current_trade_validity', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group ml-1 row">
                                                {!! Form::label('current_trade_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                                                <div class="col-md-8 {{ $errors->has('current_trade_address') ? 'has-error' : '' }}">
                                                    {!! Form::text('current_trade_address', $appInfo->current_trade_address, ['class' => 'form-control trade_address', 'placeholder' => 'Enter Address ', 'id' => 'current_trade_address']) !!}
                                                    {!! $errors->first('current_trade_address', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                                {!! Form::label('current_trade_license_attachment', 'Trade License Attachment', ['class' => 'col-md-4 required-star']) !!}
                                                <div class=" {{ $errors->has('current_trade_license_attachment') ? 'has-error' : '' }}">
                                                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   class="form-control {{ $appInfo->trade_license_attachment ? '' : ' trade_license_attachment' }} input-sm"
                                                                   name="current_trade_license_attachment" id="current_trade_license_attachment"
                                                                   accept="application/pdf"
                                                                   size="300x300" />
                                                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                        </div>
                                                        @if(isset($appInfo->current_trade_license_attachment) && !empty($appInfo->current_trade_license_attachment))
                                                            <div class="form-group">
                                                                <span><a class="btn btn-info" href="{{ asset($appInfo->current_trade_license_attachment) }}" target="_blank">Open file</a></span>
                                                            </div>

                                                        @endif
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                        </div>
                    </td>
                </tr>
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                Tax information
                            </div>

                            <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Tax information
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 row">
                                            {!! Form::label('tax_clearance', 'Tax clearance (year)', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('tax_clearance') ? 'has-error' : '' }}">
                                                {!! Form::text('tax_clearance', $appInfo->tax_clearance, ['class' => 'form-control tax_clearance', 'placeholder' => 'Enter Validity ', 'id' => 'current_tax_clearance']) !!}
                                                {!! $errors->first('tax_clearance', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('tax_clearance_attachment', 'Tax clearance Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('tax_clearance_attachment') ? 'has-error' : '' }}">
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control {{ $appInfo->tax_clearance_attachment ? '' : ' tax_clearance_attachment' }} input-sm"
                                                               name="tax_clearance_attachment" id="tax_clearance_attachment"
                                                               accept="application/pdf"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                    </div>
                                                    @if(isset($appInfo->tax_clearance_attachment) && !empty($appInfo->tax_clearance_attachment))
                                                        <div class="form-group">
                                                            <span><a class="btn btn-info" href="{{ asset($appInfo->tax_clearance_attachment) }}" target="_blank">Open file</a></span>
                                                        </div>

                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Current tax information
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 row">
                                            {!! Form::label('current_tax_clearance', 'Tax clearance (year)', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('current_tax_clearance') ? 'has-error' : '' }}">
                                                {!! Form::text('current_tax_clearance', $appInfo->current_tax_clearance, ['class' => 'form-control tax_clearance', 'placeholder' => 'Enter Validity ', 'id' => 'current_tax_clearance']) !!}
                                                {!! $errors->first('current_tax_clearance', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('current_tax_clearance_attachment', 'Tax clearance Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('current_tax_clearance_attachment') ? 'has-error' : '' }}">
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control {{ $appInfo->tax_clearance_attachment ? '' : ' tax_clearance_attachment' }} input-sm"
                                                               name="current_tax_clearance_attachment" id="current_tax_clearance_attachment"
                                                               accept="application/pdf"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                    </div>
                                                    @if(isset($appInfo->current_tax_clearance_attachment) && !empty($appInfo->current_tax_clearance_attachment))
                                                        <div class="form-group">
                                                            <span><a class="btn btn-info" href="{{ asset($appInfo->current_tax_clearance_attachment) }}" target="_blank">Open file</a></span>
                                                        </div>

                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                House/ Rental Information
                            </div>

                                <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                    <div class="col-md-6">
                                        <div class="card-header mb-2">
                                            Previous House/ Rental Agreement(Duration and address)

                                        </div>
                                        <div class="card card-magenta border border-magenta">
                                            <div class="form-group ml-1 mt-1 row">
                                                {!! Form::label('house_rental_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('house_rental_address') ? 'has-error' : '' }}">
                                                    {!! Form::text('house_rental_address', $appInfo->house_rental_address, ['class' => 'form-control house_rental_address', 'placeholder' => 'Enter Address ', 'id' => 'house_rental_address']) !!}
                                                    {!! $errors->first('house_rental_address', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group ml-1 row" style="margin-top:10px;">
                                                {!! Form::label('house_rental_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('house_rental_validity') ? 'has-error' : '' }}">
                                                    <div class="input-group date datetimepicker4"
                                                         id="datepicker_house_rental" data-target-input="nearest">
                                                        {!! Form::text('house_rental_validity', $appInfo->house_rental_validity, ['class' => 'form-control house_rental_validity', 'id' => 'house_rental_validity', 'placeholder' => 'Enter trade validity']) !!}
                                                        <div class="input-group-append"
                                                             data-target="#datepicker_house_rental"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                        {!! $errors->first('house_rental_validity', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                                {!! Form::label('house_rental_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                                <div class=" {{ $errors->has('house_rental_attachment') ? 'has-error' : '' }}">
                                                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   class="form-control {{ $appInfo->house_rental_attachment ? '' : ' house_rental_attachment' }} input-sm"
                                                                   name="house_rental_attachment" id="house_rental_attachment"
                                                                   accept="application/pdf"
                                                                   size="300x300" />
                                                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                        </div>
                                                        @if(isset($appInfo->house_rental_attachment) && !empty($appInfo->house_rental_attachment))
                                                            <div class="form-group">
                                                                <span><a class="btn btn-info" href="{{ asset($appInfo->house_rental_attachment) }}" target="_blank">Open file</a></span>
                                                            </div>

                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card-header mb-2">
                                            Current House/ Rental Agreement(Duration and address)
                                        </div>
                                        <div class="card card-magenta border border-magenta">
                                            <div class="form-group ml-1 mt-1 row">
                                                {!! Form::label('current_house_rental_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('current_house_rental_address') ? 'has-error' : '' }}">
                                                    {!! Form::text('current_house_rental_address', $appInfo->current_house_rental_address, ['class' => 'form-control house_rental_address', 'placeholder' => 'Enter Address ', 'id' => 'current_house_rental_address']) !!}
                                                    {!! $errors->first('current_house_rental_address', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group ml-1 row" style="margin-top:10px;">
                                                {!! Form::label('current_house_rental_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('current_house_rental_validity') ? 'has-error' : '' }}">
                                                    <div class="input-group date datetimepicker4"
                                                         id="datepicker_house_rental_current" data-target-input="nearest">
                                                        {!! Form::text('current_house_rental_validity', $appInfo->current_house_rental_validity, ['class' => 'form-control house_rental_validity', 'id' => 'current_house_rental_validity', 'placeholder' => 'Enter trade validity']) !!}
                                                        <div class="input-group-append"
                                                             data-target="#datepicker_house_rental_current"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                        {!! $errors->first('current_house_rental_validity', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                                {!! Form::label('current_house_rental_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                                <div class=" {{ $errors->has('current_house_rental_attachment') ? 'has-error' : '' }}">
                                                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   class="form-control {{ $appInfo->house_rental_attachment ? '' : ' house_rental_attachment' }} input-sm"
                                                                   name="current_house_rental_attachment" id="current_house_rental_attachment"
                                                                   accept="application/pdf"
                                                                   size="300x300" />
                                                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                        </div>
                                                        @if(isset($appInfo->current_house_rental_attachment) && !empty($appInfo->current_house_rental_attachment))
                                                            <div class="form-group">
                                                                <span><a class="btn btn-info" href="{{ asset($appInfo->current_house_rental_attachment) }}" target="_blank">Open file</a></span>
                                                            </div>

                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
{{--                            </div>--}}
                        </div>
                    </td>
                </tr>
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                ISPAB Certificate Information
                            </div>

                                <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                    <div class="col-md-6">
                                        <div class="card-header mb-2">
                                            Previous ISPAB Certificate ( Validity period)
                                        </div>
                                        <div class="card card-magenta border border-magenta">
                                            <div class="form-group ml-1 mt-1 row" style="margin-top:10px;">
                                                {!! Form::label('ispab_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('ispab_validity') ? 'has-error' : '' }}">
                                                    <div class="input-group date datetimepicker4"
                                                         id="datepicker_ispab" data-target-input="nearest">
                                                        {!! Form::text('ispab_validity', $appInfo->ispab_validity, ['class' => 'form-control ispab_validity', 'id' => 'ispab_validity', 'placeholder' => 'Enter trade validity']) !!}
                                                        <div class="input-group-append"
                                                             data-target="#datepicker_ispab"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                        {!! $errors->first('ispab_validity', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                                {!! Form::label('ispab_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                                <div class=" {{ $errors->has('ispab_attachment') ? 'has-error' : '' }}">
                                                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   class="form-control {{ $appInfo->ispab_attachment ? '' : ' ispab_attachment' }} input-sm"
                                                                   name="ispab_attachment" id="ispab_attachment"
                                                                   accept="application/pdf"
                                                                   size="300x300" />
                                                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                        </div>
                                                        @if(isset($appInfo->ispab_attachment) && !empty($appInfo->ispab_attachment))
                                                            <div class="form-group">
                                                                <span><a class="btn btn-info" href="{{ asset($appInfo->ispab_attachment) }}" target="_blank">Open file</a></span>
                                                            </div>

                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card-header mb-2">
                                            Current ISPAB Certificate ( Validity period)
                                        </div>
                                        <div class="card card-magenta border border-magenta">
                                            <div class="form-group ml-1 mt-1 row" style="margin-top:10px;">
                                                {!! Form::label('current_ispab_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('current_ispab_validity') ? 'has-error' : '' }}">
                                                    <div class="input-group date datetimepicker4"
                                                         id="datepicker_ispab_current" data-target-input="nearest">
                                                        {!! Form::text('current_ispab_validity', $appInfo->current_ispab_validity, ['class' => 'form-control ispab_validity', 'id' => 'current_ispab_validity', 'placeholder' => 'Enter trade validity']) !!}
                                                        <div class="input-group-append"
                                                             data-target="#datepicker_ispab_current"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                        {!! $errors->first('current_ispab_validity', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                                {!! Form::label('current_ispab_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                                <div class=" {{ $errors->has('current_ispab_attachment') ? 'has-error' : '' }}">
                                                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   class="form-control {{ $appInfo->ispab_attachment ? '' : ' ispab_attachment' }} input-sm"
                                                                   name="current_ispab_attachment" id="current_ispab_attachment"
                                                                   accept="application/pdf"
                                                                   size="300x300" />
                                                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                        </div>
                                                        @if(isset($appInfo->current_ispab_attachment) && !empty($appInfo->current_ispab_attachment))
                                                            <div class="form-group">
                                                                <span><a class="btn btn-info" href="{{ asset($appInfo->current_ispab_attachment) }}" target="_blank">Open file</a></span>
                                                            </div>

                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
{{--                            </div>--}}
                        </div>
                    </td>
                </tr>
            </table>
            <table class="table-responsive resubmitApplicationRow" style="width: 100%;     display: inline-table!important;" >
                <tr id="r_0">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                Shareholder/partner/proprietors Information
                                <span style="float: right; cursor: pointer;" class="addResubmitApplicationRow m-l-auto">
                                                     <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                                                </span>
                            </div>

                            <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Previous List of proprietors/partners/Shareholders

                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 row">
                                            {!! Form::label('shareholders_name', "Shareholder's name:", ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 mt-1 {{ $errors->has('shareholders_name') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholders_name[0]', $appInfo->shareholders_name, ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter shareholders name', 'id' => 'shareholders_name']) !!}
                                                {!! $errors->first('shareholders_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 mt-1 row">
                                            {!! Form::label('number_of_share', 'Number of shares:', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 mt-1 {{ $errors->has('number_of_share') ? 'has-error' : '' }}">
                                                {!! Form::text('number_of_share[0]', $appInfo->number_of_share, ['class' => 'form-control number_of_share', 'placeholder' => 'Enter number of share', 'id' => 'number_of_share']) !!}
                                                {!! $errors->first('number_of_share', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group ml-1 row">
                                            {!! Form::label('shareholders_nid_passport', 'NID/Passport(where applicable)', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholders_nid_passport') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholders_nid_passport[0]', $appInfo->shareholders_nid_passport, ['class' => 'form-control', 'placeholder' => 'Enter Address ', 'id' => 'shareholders_nid_passport']) !!}
                                                {!! $errors->first('shareholders_nid_passport', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('shareholders_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('shareholders_attachment') ? 'has-error' : '' }}">
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control {{ $appInfo->shareholders_attachment ? '' : ' shareholders_attachment' }} input-sm"
                                                               name="shareholders_attachment[0]" id="shareholders_attachment"
                                                               accept="application/pdf"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                    </div>
                                                    @if(isset($appInfo->shareholders_attachment) && !empty($appInfo->shareholders_attachment))
                                                        <div class="form-group">
                                                            <span><a class="btn btn-info" href="{{ asset($appInfo->shareholders_attachment) }}" target="_blank">Open file</a></span>
                                                        </div>

                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Current List of proprietors/partners/Shareholders
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 row">
                                            {!! Form::label('current_shareholders_name', "Shareholder's name:", ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 mt-1 {{ $errors->has('shareholders_name') ? 'has-error' : '' }}">
                                                {!! Form::text('current_shareholders_name[0]', $appInfo->current_shareholders_name, ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter shareholders name', 'id' => 'current_shareholders_name']) !!}
                                                {!! $errors->first('current_shareholders_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 mt-1 row">
                                            {!! Form::label('current_number_of_share', 'Number of shares:', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 mt-1 {{ $errors->has('number_of_share') ? 'has-error' : '' }}">
                                                {!! Form::text('current_number_of_share[0]', $appInfo->current_number_of_share, ['class' => 'form-control number_of_share', 'placeholder' => 'Enter number of share', 'id' => 'current_number_of_share']) !!}
                                                {!! $errors->first('current_number_of_share', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group ml-1 row">
                                            {!! Form::label('current_nid_passport', 'NID/Passport(where applicable)', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('nid_passport') ? 'has-error' : '' }}">
                                                {!! Form::text('current_shareholders_nid_passport[0]', $appInfo->current_shareholders_nid_passport, ['class' => 'form-control', 'placeholder' => 'Enter Address ', 'id' => 'current_nid_passport']) !!}
                                                {!! $errors->first('current_shareholders_nid_passport', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('current_shareholders_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('current_shareholders_attachment') ? 'has-error' : '' }}">
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control {{ $appInfo->shareholders_attachment ? '' : ' shareholders_attachment' }} input-sm"
                                                               name="current_shareholders_attachment[0]" id="current_shareholders_attachment"
                                                               accept="application/pdf"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                    </div>
                                                    @if(isset($appInfo->current_shareholders_attachment) && !empty($appInfo->current_shareholders_attachment))
                                                        <div class="form-group">
                                                            <span><a class="btn btn-info" href="{{ asset($appInfo->current_shareholders_attachment) }}" target="_blank">Open file</a></span>
                                                        </div>

                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@elseif($mode === 'view')
    <div class="card card-magenta border border-magenta" id="shareholder_wrapper">
        <div class="card-header">
            Update information for resubmit application
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <table class="table-responsive" style="width: 100%;     display: inline-table!important;" >
                <input type="hidden" id="shareholderDataCount" name="shareholderDataCount" value="1"/>
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                Trade Licence Information's
                            </div>

                            <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        At the time of license Issuance(as per Previous application)

                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 mt-1 row">
                                            {!! Form::label('trade_license_number', 'Trade License Number', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->trade_license_number }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-top:10px;">
                                            {!! Form::label('trade_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->trade_validity }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group ml-1 row">
                                            {!! Form::label('trade_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->trade_address }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('trade_license_attachment', 'Trade License Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            @isset($appInfo->trade_license_attachment)
                                                <div class="form-group">
                                                    <span><a class="btn btn-info" href="{{ asset($appInfo->trade_license_attachment) }}" target="_blank">Open file</a></span>
                                                </div>

                                            @endisset
                                            </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Information during Renewal (as per Current application)
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 mt-1 row">
                                            {!! Form::label('current_trade_license_number', 'Trade License Number', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->current_trade_license_number }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-top:10px;">
                                            {!! Form::label('current_trade_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->current_trade_validity }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group ml-1 row">
                                            {!! Form::label('current_trade_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->current_trade_address }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('current_trade_license_attachment', 'Trade License Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('current_trade_license_attachment') ? 'has-error' : '' }}">
                                                @isset($appInfo->current_trade_license_attachment)
                                                    <div class="form-group">
                                                        <span><a class="btn btn-info" href="{{ asset($appInfo->current_trade_license_attachment) }}" target="_blank">Open file</a></span>
                                                    </div>

                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </td>
                </tr>
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                Tax information
                            </div>

                            <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Tax information
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 row">
                                            {!! Form::label('tax_clearance', 'Tax clearance (year)', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->tax_clearance }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('tax_clearance_attachment', 'Tax clearance Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" ">
                                                @isset($appInfo->tax_clearance_attachment)
                                                    <div class="form-group">
                                                        <span><a class="btn btn-info" href="{{ asset($appInfo->tax_clearance_attachment) }}" target="_blank">Open file</a></span>
                                                    </div>

                                                @endisset
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Current tax information
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 row">
                                            {!! Form::label('tax_clearance', 'Tax clearance (year)', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->current_tax_clearance }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('tax_clearance_attachment', 'Tax clearance Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" ">
                                                @isset($appInfo->current_tax_clearance_attachment)
                                                    <div class="form-group">
                                                        <span><a class="btn btn-info" href="{{ asset($appInfo->current_tax_clearance_attachment) }}" target="_blank">Open file</a></span>
                                                    </div>

                                                @endisset
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                House/ Rental Information
                            </div>

                            <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Previous House/ Rental Agreement(Duration and address)

                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 mt-1 row">
                                            {!! Form::label('house_rental_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->house_rental_address }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-top:10px;">
                                            {!! Form::label('house_rental_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->house_rental_validity }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('house_rental_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('house_rental_attachment') ? 'has-error' : '' }}">
                                                @isset($appInfo->house_rental_attachment)
                                                    <div class="form-group">
                                                        <span><a class="btn btn-info" href="{{ asset($appInfo->house_rental_attachment) }}" target="_blank">Open file</a></span>
                                                    </div>

                                                @endisset
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Current House/ Rental Agreement(Duration and address)
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 mt-1 row">
                                            {!! Form::label('current_house_rental_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->current_house_rental_address }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-top:10px;">
                                            {!! Form::label('current_house_rental_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->current_house_rental_validity }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('current_house_rental_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('current_house_rental_attachment') ? 'has-error' : '' }}">
                                                @isset($appInfo->current_house_rental_attachment)
                                                    <div class="form-group">
                                                        <span><a class="btn btn-info" href="{{ asset($appInfo->current_house_rental_attachment) }}" target="_blank">Open file</a></span>
                                                    </div>

                                                @endisset
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                ISPAB Certificate Information
                            </div>

                            <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Previous ISPAB Certificate ( Validity period)
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 mt-1 row" style="margin-top:10px;">
                                            {!! Form::label('ispab_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->ispab_validity }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('ispab_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('ispab_attachment') ? 'has-error' : '' }}">
                                                @isset($appInfo->ispab_attachment)
                                                    <div class="form-group">
                                                        <span><a class="btn btn-info" href="{{ asset($appInfo->ispab_attachment) }}" target="_blank">Open file</a></span>
                                                    </div>

                                                @endisset
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Current ISPAB Certificate ( Validity period)
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 mt-1 row" style="margin-top:10px;">
                                            {!! Form::label('current_ispab_validity', 'Validity', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->current_ispab_validity }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('current_ispab_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('current_ispab_attachment') ? 'has-error' : '' }}">
                                                @isset($appInfo->current_ispab_attachment)
                                                    <div class="form-group">
                                                        <span><a class="btn btn-info" href="{{ asset($appInfo->current_ispab_attachment) }}" target="_blank">Open file</a></span>
                                                    </div>

                                                @endisset
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                Shareholder/partner/proprietors Information
                                <span style="float: right; cursor: pointer;" class="m-l-auto">
                                                     <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                                                </span>
                            </div>

                            <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                @foreach($shareholderInfoForRenew as $singleData)

                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Previous List of proprietors/partners/Shareholders

                                    </div>

                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 mt-1 row">
                                            {!! Form::label('number_of_share', 'Number of shares:', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $singleData->number_of_share }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row">
                                            {!! Form::label('shareholders_name', "Shareholder's name:", ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $singleData->shareholders_name }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row">
                                            {!! Form::label('shareholders_nid_passport', 'NID/Passport(where applicable)', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $singleData->shareholders_nid_passport }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('shareholders_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('shareholders_attachment') ? 'has-error' : '' }}">
                                                @isset($singleData->shareholders_attachment)
                                                    <div class="form-group">
                                                        <span><a class="btn btn-info" href="{{ asset($singleData->shareholders_attachment) }}" target="_blank">Open file</a></span>
                                                    </div>

                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Current List of proprietors/partners/Shareholders
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                        <div class="form-group ml-1 mt-1 row">
                                            {!! Form::label('current_number_of_share', 'Number of shares:', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $singleData->current_number_of_share }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row">
                                            {!! Form::label('current_shareholders_name', "Shareholder's name:", ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $singleData->current_shareholders_name }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row">
                                            {!! Form::label('current_nid_passport', 'NID/Passport(where applicable)', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $singleData->current_shareholders_nid_passport }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
                                            {!! Form::label('current_shareholders_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
                                            <div class=" {{ $errors->has('current_shareholders_attachment') ? 'has-error' : '' }}">
                                                @isset($singleData->current_shareholders_attachment)
                                                    <div class="form-group">
                                                        <span><a class="btn btn-info" href="{{ asset($singleData->current_shareholders_attachment) }}" target="_blank">Open file</a></span>
                                                    </div>

                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endif

<script>
    getHelpText();
    // setIntlTelInput('.shareholder_mobile ');
    @if(in_array($mode, ['shortfall']))
    $(".addResubmitApplicationRow").on('click', function() {
        let lastRowId = parseInt($('.resubmitApplicationRow tr:last').attr('id').split('_')[1]);
        console.log('row id:',lastRowId)

        let updateRowId = parseInt(lastRowId)+1;
        console.log('updateRowId id:',updateRowId)

        $('.resubmitApplicationRow').append(
            `<tr id="R_${updateRowId}" class="client-rendered-row">
        <td>
        <div class="card card-magenta border border-magenta">
                            <div class="card-header mb-2">
                                Shareholder/partner/proprietors Information
                                <span style="float: right; cursor: pointer;">
                                                         <button type="button" class="btn btn-danger btn-sm  cross-button"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                                    </span>
                            </div>
        <div class="row justify-content-between card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Previous List of proprietors/partners/Shareholders

                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                      <div class="form-group ml-1 row">
{!! Form::label('shareholders_name', "Shareholder's name:", ['class' => 'col-md-4 ']) !!}
            <div class="col-md-8 mt-1 {{ $errors->has('shareholders_name') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholders_name[${updateRowId}]', '', ['class' => 'form-control shareholders_name', 'placeholder' => 'Enter shareholders name', 'id' => 'shareholders_name']) !!}
            {!! $errors->first('shareholders_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
                                        <div class="form-group ml-1 mt-1 row">
                                            {!! Form::label('number_of_share', 'Number of shares:', ['class' => 'col-md-4 ']) !!}
            <div class="col-md-8 mt-1 {{ $errors->has('number_of_share') ? 'has-error' : '' }}">
                                                {!! Form::text('number_of_share[${updateRowId}]', '', ['class' => 'form-control number_of_share', 'placeholder' => 'Enter number of share', 'id' => 'number_of_share']) !!}
            {!! $errors->first('number_of_share', '<span class="help-block">:message</span>') !!}
            </div>
        </div>

        <div class="form-group ml-1 row">
{!! Form::label('shareholders_nid_passport', 'NID/Passport(where applicable)', ['class' => 'col-md-4 ']) !!}
            <div class="col-md-8 {{ $errors->has('shareholders_nid_passport') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholders_nid_passport[${updateRowId}]', '', ['class' => 'form-control shareholders_nid_passport', 'placeholder' => 'Enter Address ', 'id' => 'shareholders_nid_passport']) !!}
            {!! $errors->first('shareholders_nid_passport', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
{!! Form::label('shareholders_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
            <div class=" {{ $errors->has('shareholders_attachment') ? 'has-error' : '' }}">
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control input-sm"
                                                               name="shareholders_attachment[${updateRowId}]" id="shareholders_attachment"
                                                               accept="application/pdf"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-header mb-2">
                                        Current List of proprietors/partners/Shareholders
                                    </div>
                                    <div class="card card-magenta border border-magenta">
                                      <div class="form-group ml-1 row">
{!! Form::label('current_shareholders_name', "Shareholder's name:", ['class' => 'col-md-4 ']) !!}
            <div class="col-md-8 mt-1 {{ $errors->has('shareholders_name') ? 'has-error' : '' }}">
                                                {!! Form::text('current_shareholders_name[${updateRowId}]', '', ['class' => 'form-control current_shareholders_name', 'placeholder' => 'Enter shareholders name', 'id' => 'current_shareholders_name']) !!}
            {!! $errors->first('current_shareholders_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
                                        <div class="form-group ml-1 mt-1 row">
                                            {!! Form::label('current_number_of_share', 'Number of shares:', ['class' => 'col-md-4 ']) !!}
            <div class="col-md-8 mt-1 {{ $errors->has('number_of_share') ? 'has-error' : '' }}">
                                                {!! Form::text('current_number_of_share[${updateRowId}]', '', ['class' => 'form-control current_number_of_share', 'placeholder' => 'Enter number of share', 'id' => 'current_number_of_share']) !!}
            {!! $errors->first('current_number_of_share', '<span class="help-block">:message</span>') !!}
            </div>
        </div>

        <div class="form-group ml-1 row">
{!! Form::label('current_nid_passport', 'NID/Passport(where applicable)', ['class' => 'col-md-4 ']) !!}
            <div class="col-md-8 {{ $errors->has('nid_passport') ? 'has-error' : '' }}">
                                                {!! Form::text('current_shareholders_nid_passport[${updateRowId}]', '', ['class' => 'form-control current_nid_passport', 'placeholder' => 'Enter Address ', 'id' => 'current_nid_passport']) !!}
            {!! $errors->first('current_nid_passport', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="form-group ml-1 row" style="margin-bottom:45px!important;">
{!! Form::label('current_shareholders_attachment', 'Attachment', ['class' => 'col-md-4 required-star']) !!}
            <div class=" {{ $errors->has('current_shareholders_attachment') ? 'has-error' : '' }}">
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control input-sm"
                                                               name="current_shareholders_attachment[${updateRowId}]" id="current_shareholders_attachment"
                                                               accept="application/pdf"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">


                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
    </td>
    </tr>`);

        $(document).on('click', '.cross-button', function() {
            $(this).closest('tr').remove(); // Removes the parent <tr> of the clicked button
        });

        $("#datepicker_for_trade").val(lastRowId+1);
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            // maxDate: 'now',
            // minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
        datePickerHide('datetimepicker4');
        // trade current data
        $("#datepicker_for_trade_current").val(lastRowId+1);
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            // maxDate: 'now',
            // minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
        datePickerHide('datetimepicker4');
        // house rental
        $("#datepicker_house_rental").val(lastRowId+1);
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            // maxDate: 'now',
            // minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
        datePickerHide('datetimepicker4');

        // house rental
        $("#datepicker_house_rental_current").val(lastRowId+1);
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            // maxDate: 'now',
            // minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
        datePickerHide('datetimepicker4');

        // ISPAB
        $("#datepicker_ispab").val(lastRowId+1);
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            // maxDate: 'now',
            // minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
        datePickerHide('datetimepicker4');
        // ISPAB current
        $("#datepicker_ispab_current").val(lastRowId+1);
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            // maxDate: 'now',
            // minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
        datePickerHide('datetimepicker4');
        getHelpText();
        // setIntlTelInput('.shareholder_mobile ');
        removeClassFromWrapper();
        //  setIntlTelInput('.shareholder_mobile');
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
        $("#shareholderDataCount").val(prevDataCount - 1);
        calculateShareValue();
    });

    $(document).on('change', '.shareholder_nationality', function () {
        let id = $(this).attr('id');
        let lastRowId = id.split('_')[2];

        if (this.value == 18) {
            $('#nidBlock_' + lastRowId).show();
            $('#passportBlock_' + lastRowId).hide();
            $('#shareholder_passport_'+ lastRowId).val('');

        } else {
            $('#nidBlock_' + lastRowId).hide();
            $('#shareholder_nid_'+ lastRowId).val('');
            $('#passportBlock_' + lastRowId).show();
        }
    });

    //get share value and number of share
    $("#shareholderRow").on("keyup", ".share-value", function () {
        calculateShareValue();
    });

    $("#shareholderRow").on('keyup', '.no-of-share', function () {
        calculateShareValue();
    });

    // total no. of share and total value calculation
    function calculateShareValue() {
        let sum_share = 0;
        $(".share-value").each(function () {
            sum_share = sum_share + parseInt(this.value);
        });
        $("#total_share_value").val(sum_share);
        // let sum_value = document.getElementById('total_no_of_share').value === '' ? 0 : parseInt(document.getElementById('total_no_of_share').value);
        let sum_value = 0;
        $(".no-of-share").each(function () {
            sum_value = sum_value + parseInt(this.value);
        });
        $("#total_no_of_share").val(sum_value);
    }

    // shareholder nid length validation
    $("#shareholderRow").on('keyup', '.shareholder_nid',function () {
        nidValidation();
    });

    // $("#shareholderRow").on('change','.shareholder_nid',function () {
    //     $(".shareholder_nid").each(function () {
    //         let nidValue = $(this).val();
    //
    //         if(nidValue.length != 10 || nidValue.length != 13 || nidValue.length != 17){
    //             $(this).addClass('error');
    //         }else{
    //             $(this).removeClass('error');
    //         }
    //     });
    // });

    function nidValidation(){
        $(".shareholder_nid").each(function () {
            let nidValue = $(this).val();
            if(nidValue.length == 10 || nidValue.length == 13 || nidValue.length == 17){
                $(this).removeClass('error');
                // $(this).addClass('valid');
            }else{
                $(this).addClass('error');
                // $(this).removeClass('valid');
            }
        });
    }

    @endif

    @if(in_array($mode, ['amendment','renew','surrender']))
    calculateShareValue();
    makeReadOnlyByDivId('amendmentShareholder');
    //get share value and number of share
    $("#shareholderRow").on("keyup", ".share-value", function () {
        calculateShareValue();
    });

    $("#shareholderRow").on('keyup', '.no-of-share', function () {
        calculateShareValue();
    });

    // total no. of share and total value calculation
    function calculateShareValue() {
        let sum_share = 0;
        $(".share-value").each(function () {
            sum_share = sum_share + parseInt(this.value);
        });
        $("#total_share_value").val(sum_share);
        // let sum_value = document.getElementById('total_no_of_share').value === '' ? 0 : parseInt(document.getElementById('total_no_of_share').value);
        let sum_value = 0;
        $(".no-of-share").each(function () {
            sum_value = sum_value + parseInt(this.value);
        });
        $("#total_no_of_share").val(sum_value);
    }
    @endif

    function makeReadOnlyByDivId(id) {
        const inputFieldsList = document.querySelectorAll(`#${id} input, #${id} select`);
        const labels = document.querySelectorAll(`#${id} label, #${id} .addShareholderRow,.shareholderRow`);
        inputFieldsList.forEach(item => {
            item.classList.add('input_disabled');
        })
        labels.forEach(item => {
            item.style.pointerEvents = 'none';
        })
    }

    function makeReadWriteByDivId(id) {
        const inputFieldsList = document.querySelectorAll(`#${id} input, #${id} select`);
        const labels = document.querySelectorAll(`#${id} label, #${id} .addShareholderRow,.shareholderRow `);
        inputFieldsList.forEach(item => {
            item.classList.remove('input_disabled');
            item.readOnly = false;
        })
        labels.forEach(item => {
            item.style.pointerEvents = 'auto';
        })
    }

    function readOnlyChecker(element) {
        if (element.checked) makeReadWriteByDivId('amendmentShareholder');
        else makeReadOnlyByDivId('amendmentShareholder');
    }

    $(document).ready(function () {
        setTimeout(function() {
            removeClassFromWrapper();
        }, 4000);
    })

    function removeClassFromWrapper(){
        /* when company org type was propitorship then shareholder all input field remove required attribute */
        @if(isset($companyInfo->org_type) && $companyInfo->org_type==1)
        $("div#shareholder_wrapper input, div#shareholder_wrapper label").each(function() {
            $(this).removeClass("required-star")
            $(this).removeClass("required")
        });
        @endif

        @if(isset($appInfo->org_type) && $appInfo->org_type==1)
        $("div#shareholder_wrapper input, div#shareholder_wrapper label").each(function() {
            $(this).removeClass("required-star")
            $(this).removeClass("required")
        });
        @endif
    }

    //hide calender for clicking outside of the calender.
    datePickerHide('datetimepicker4');

</script>
