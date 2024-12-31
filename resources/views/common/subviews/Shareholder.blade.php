@if($mode === 'add')
    <div class="card card-magenta border border-magenta" id="shareholder_wrapper">
        <div class="card-header">
            Shareholder/ Partner/ Proprietor Information
            {{--                            <span style="float: right; cursor: pointer;" class="addShareholderRow">--}}
            {{--                                <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>--}}
            {{--                            </span>--}}
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('total_no_of_share', 'Total No. of Share', ['class' => 'col-md-5 required-star']) !!}
                        <div class="col-md-7 {{ $errors->has('total_no_of_share') ? 'has-error' : '' }}">
                            {!! Form::number('total_no_of_share', '', ['class' => 'form-control', 'placeholder' => 'Total No. of Share', 'id' => 'total_no_of_share', 'readonly']) !!}
                            {!! $errors->first('total_no_of_share', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('total_share_value', 'Total Share Value', ['class' => 'col-md-5 required-star']) !!}
                        <div class="col-md-7 {{ $errors->has('total_share_value') ? 'has-error' : '' }}">
                            {!! Form::number('total_share_value', '', ['class' => 'form-control', 'placeholder' => 'Total Share Value', 'id' => 'total_share_value', 'readonly']) !!}
                            {!! $errors->first('total_share_value', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <table class="table-responsive" style="width: 100%;     display: inline-table!important;" id="shareholderRow">
                <input type="hidden" id="shareholderDataCount" name="shareholderDataCount" value="1"/>
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Shareholder/ Partner/ Proprietor Details
                                <span style="float: right; cursor: pointer;" class="addShareholderRow m-l-auto">
                                                     <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                                                </span>
                            </div>
                            <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_name[1]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name']) !!}
                                                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_designation[1]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation']) !!}
                                                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_email[1]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email']) !!}
                                                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8  {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="iti-flag bd"></span>
                                                        <span>+88</span>
                                                    </span>
                                                </div>
                                                {!! Form::text('shareholder_mobile[1]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile']) !!}
                                                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_share_of', '% Of Share', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                {!! Form::number('shareholder_share_of[1]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of']) !!}
                                                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('no_of_share_1', 'No. of Share', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('no_of_share') ? 'has-error' : '' }}">
                                                {!! Form::number('no_of_share[1]', '', ['class' => 'form-control no-of-share', 'placeholder' => 'Enter % Of Share', 'id' => 'no_of_share_1', 'required' => 'required']) !!}
                                                {!! $errors->first('no_of_share', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row" id="nidBlock_1" style="display: none;">
                                            {!! Form::label('shareholder_nid', 'NID No', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_nid[1]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_1']) !!}
                                                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row" id="passportBlock_1" style="display: none;">
                                            {!! Form::label('shareholder_passport', 'Passport No.', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_passport[1]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_1']) !!}
                                                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row" style="margin-bottom:45px!important;">
                                            {!! Form::label('correspondent_photo0_1', 'Image', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                {{--start--}}
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control correspondent_photo input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                               name="correspondent_photo[1]" id="correspondent_photo_1"
                                                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_1', 'correspondent_photo_base64_1')"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                                    <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                                </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="center-block image-upload"
                                                               for="correspondent_photo0_1">
                                                            <figure>
                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                     class="img-responsive img-thumbnail"
                                                                     id="correspondent_photo_preview_1" />
                                                            </figure>
                                                            <input type="hidden" id="correspondent_photo_base64_1"
                                                                   name="correspondent_photo_base64[1]" />
                                                        </label>
                                                    </div>
                                                </div>
                                                {{--end--}}
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin-top:10px;">
                                            {!! Form::label('shareholder_dob', 'Date of Birth', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                <div class="input-group date datetimepicker4"
                                                     id="datepicker0" data-target-input="nearest">
                                                    {!! Form::text('shareholder_dob[1]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob', 'placeholder' => 'Enter Date Of Birth']) !!}
                                                    <div class="input-group-append"
                                                         data-target="#datepicker0"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i
                                                                class="fa fa-calendar"></i></div>
                                                    </div>
                                                    {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_nationality', 'Nationality', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                {!! Form::select('shareholder_nationality[1]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_1']) !!}
                                                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('share_value_1', 'Share Value', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('share_value') ? 'has-error' : '' }}">
                                                {!! Form::number('share_value[1]', '', ['class' => 'form-control share-value', 'placeholder' => 'Enter Share Value', 'id' => 'share_value_1']) !!}
                                                {!! $errors->first('share_value', '<span class="help-block">:message</span>') !!}
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
@elseif($mode === 'edit')
    <div class="card card-magenta border border-magenta" id="shareholder_wrapper">
        <div class="card-header">
            Shareholder/ Partner/ Proprietor Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('total_no_of_share', 'Total No. of Share', ['class' => 'col-md-4']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('total_no_of_share') ? 'has-error' : '' }}">
                            {!! Form::number('total_no_of_share', $appInfo->total_no_of_share, ['class' => 'form-control', 'placeholder' => 'Total No. of Share', 'id' => 'total_no_of_share', 'readonly']) !!}
                            {!! $errors->first('total_no_of_share', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('total_share_value', 'Total Share Value', ['class' => 'col-md-4 required-star']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('total_share_value') ? 'has-error' : '' }}">
                            {!! Form::number('total_share_value', $appInfo->total_share_value, ['class' => 'form-control', 'placeholder' => 'Total Share Value', 'id' => 'total_share_value', 'readonly']) !!}
                            {!! $errors->first('total_share_value', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <table class="table-responsive" style="width: 100%;     display: inline-table!important;"
                   id="shareholderRow">
                <input type="hidden" id="shareholderDataCount" name="shareholderDataCount"
                       value="{{ count($shareholders_data) }}"/>
                @foreach($shareholders_data as $index=>$shareholder)
                    <tr id="r_{{$index+1}}">
                        <td>
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Shareholder/ Partner/ Proprietor Details
                                    @if($index == 0)
                                        <span style="float: right; cursor: pointer;"
                                              class="addShareholderRow m-l-auto">
                                                            <i style="font-size: 20px;" class="fa fa-plus-square"
                                                               aria-hidden="true"></i>
                                                        </span>
                                    @else
                                        <span style="float: right; cursor: pointer;">
                                                            <button type="button"
                                                                    class="btn btn-danger btn-sm shareholderRow cross-button m-l-auto"><strong><i
                                                                        style="font-size: 16px;" class="fa fa-times"
                                                                        aria-hidden="true"></i></strong></button>
                                                        </span>
                                    @endif
                                </div>
                                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_name_'.$index, 'Name', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                                                    {!! Form::text("shareholder_name[$index]", $shareholder->shareholders_name, ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name_'.$index]) !!}
                                                    {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_designation_'.$index, 'Designation', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                    {!! Form::text("shareholder_designation[$index]", $shareholder->shareholders_designation, ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation_'.$index]) !!}
                                                    {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_email_'.$index, 'Email', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                    {!! Form::text("shareholder_email[$index]", $shareholder->shareholders_email, ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email_'.$index]) !!}
                                                    {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_mobile_'.$index, 'Mobile Number', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8  {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <span class="iti-flag bd"></span>
                                                            <span>+88</span>
                                                        </span>
                                                    </div>
                                                    {!! Form::text("shareholder_mobile[$index]", $shareholder->shareholders_mobile, ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_'.$index]) !!}
                                                    {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_share_of_'.$index, '% Of Share', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                    {!! Form::number("shareholder_share_of[$index]", $shareholder->shareholders_share_percent, ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_'.$index]) !!}
                                                    {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('no_of_share_'.$index, 'No. of Share', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('no_of_share') ? 'has-error' : '' }}">
                                                    {!! Form::number("no_of_share[$index]", $shareholder->no_of_share , ['class' => 'form-control no-of-share', 'placeholder' => 'Enter % Of Share', 'id' => 'no_of_share_'.$index]) !!}
                                                    {!! $errors->first('no_of_share', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <span id="nidBlock_{{$index+1}}"
                                                  style="{{$shareholder->shareholders_nationality == 18?'display:inline':'display:none'}}">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_nid_'.($index+1), 'NID No', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                                {!! Form::text("shareholder_nid[$index]", $shareholder->shareholders_nid, ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_'.($index+1)]) !!}
                                                                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </span>
                                            <span id="passportBlock_{{$index+1}}"
                                                  style="{{(isset($shareholder->shareholders_nationality) && $shareholder->shareholders_nationality != 18)?'display:inline':'display:none'}}">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_passport_'.($index+1), 'Passport No.', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                                                                {!! Form::text("shareholder_passport[$index]", $shareholder->shareholders_passport, ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_'.($index+1)]) !!}
                                                                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </span>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row"
                                                 style="margin-bottom:45px!important">
                                                {!! Form::label("correspondent_photo0_{{$index}}", 'Image', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                    {{--start--}}
                                                    <div class="row"
                                                         style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   value="{{ $shareholder->shareholders_image }}"
                                                                   class="form-control input-sm correspondent_photo shareholderImg"
                                                                   name="correspondent_photo[{{$index}}]"
                                                                   id="correspondent_photo_{{$index}}"
                                                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_{{$index}}', 'correspondent_photo_base64_{{$index}}')"
                                                                   size="300x300"/>

                                                            <span class="text-success"
                                                                  style="font-size: 9px; font-weight: bold; display: block;">
                                                            [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 MB]
                                                            <p style="font-size: 12px;"><a target="_blank"
                                                                                           href="https://picresize.com/">You may update your image.</a></p>
                                                        </span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="center-block image-upload"
                                                                   for="correspondent_photo0_{{$index}}">
                                                                <figure>
                                                                    <img
                                                                        style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                        src="{{ !empty($shareholder->shareholders_image) ? $shareholder->shareholders_image : url('assets/images/demo-user.jpg') }}"
                                                                        class="img-responsive img-thumbnail"
                                                                        id="correspondent_photo_preview_{{$index}}"/>
                                                                </figure>
                                                                <input type="hidden"
                                                                       id="correspondent_photo_base64_{{$index}}"
                                                                       value="{{ $shareholder->shareholders_image }}"
                                                                       name="correspondent_photo_base64[{{$index}}]"/>
                                                            </label>
                                                            <input type="hidden"
                                                                   value="{{$shareholder->image_real_path}}"
                                                                   name="image_real_path[]"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_dob_'.$index, 'Date of Birth', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                    <div class="input-group date datetimepicker4"
                                                         id="datepicker{{$index}}"
                                                         data-target-input="nearest">
                                                        {!! Form::text("shareholder_dob[$index]", !empty($shareholder->shareholders_dob)? \App\Libraries\CommonFunction::changeDateFormat($shareholder->shareholders_dob):'', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_'.$index, 'placeholder' => 'Enter Date Of Birth']) !!}
                                                        <div class="input-group-append"
                                                             data-target="#datepicker{{$index}}"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_nationality'.($index+1), 'Nationality', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                    {!! Form::select("shareholder_nationality[$index]", $nationality, $shareholder->shareholders_nationality, ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_'.($index+1)]) !!}
                                                    {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('share_value_'.($index+1), 'Share Value', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('share_value') ? 'has-error' : '' }}">
                                                    {!! Form::number("share_value[$index]", $shareholder->share_value, ['class' => 'form-control share-value', 'placeholder' => 'Enter Share Value', 'id' => 'share_value_'.($index+1)]) !!}
                                                    {!! $errors->first('share_value', '<span class="help-block">:message</span>') !!}
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
@elseif($mode === 'view')
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 90000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; /* Adjusted margin for better vertical alignment */
            padding: 20px;
            border: 2px solid red; /* Debugging */
            color: black;
            width: 70%; /* Expanded width to 70% of the viewport */
            max-width: 1000px; /* Optional: Restrict the maximum width */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add a subtle shadow for better visual effect */
            border-radius: 8px; /* Optional: Add rounded corners */
        }

        .close {
            background-color: #f44336; /* Red color */
            color: white;
            border: none;
            padding: 7px 15px;
            font-size: 12px;
            cursor: pointer;
            position: absolute;
            right: 10px;
            margin-top: 38px; /* Space from the content */
            text-align: center;
        }

        .close:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }



    </style>
    <div class="card card-magenta border border-magenta" id="shareholder_wrapper">
        <div class="card-header justify-content-between">
           <span> Shareholder/ Partner/ Proprietor Information</span>
                <?php
                $companyType = \App\Modules\CompanyProfile\Models\CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
                ?>

            @if(\Illuminate\Support\Facades\Auth::user()->user_type == '4x404')
                @if(str_contains($companyType[$appInfo->org_type], 'Limited'))

                        <span style="float: right" id="verify"
                                class="btn btn-primary verify"  onclick="viewRJSCInfo()"
                        >{{trans('CompanyProfile::messages.verify')}}</span>

                @endif
            @endif
            <div id="apiModal" class="modal">
                <div class="modal-content">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img id="logo" src="{{ asset('assets/rjsc/rjsc.png') }}" alt="RJSC Logo" style="max-width: 150px; height: auto;"/>
                    </div>
                    <p id="modalText"></p>
                </div>
            </div>
        </div>



        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('total_no_of_share', 'Total No. of Share', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->total_no_of_share }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('total_share_value', 'Total Share Value', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->total_share_value }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @foreach($appShareholderInfo as $key => $shareholderInfo)
                <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Shareholder/ Partner/ Proprietor Details
                    </div>
                    <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $shareholderInfo->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    @if(!empty($shareholderInfo->nationality == 18))
                                        {!! Form::label('nid', 'National ID No', ['class' => 'col-md-4 ']) !!}
                                        <div class="col-md-8">
                                            <span>: {{ $shareholderInfo->nid }}</span>
                                        </div>
                                    @else
                                        {!! Form::label('passport', 'Passport No', ['class' => 'col-md-4 ']) !!}
                                        <div class="col-md-8">
                                            <span>: {{ $shareholderInfo->passport }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $shareholderInfo->designation }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $shareholderInfo->mobile }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $shareholderInfo->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('shareholder_dob', 'Date of Birth', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $shareholderInfo->dob }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('shareholder_image', 'Images', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                                        {{--start--}}

                                        <label class="center-block image-upload"
                                               for="correspondent_photo0_1">
                                            <figure>
                                                <img style="height: 99px; width: 95px; border: 1px solid #EBEBEB;"
                                                     src="{{$shareholderInfo->image !=""? asset($shareholderInfo->image):asset('assets/images/demo-user.jpg') }}"
                                                     class="img-responsive img-thumbnail"/>
                                            </figure>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('shareholder_share_of', '% Of Share', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $shareholderInfo->share_percent }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('no_of_share', 'No. of Share', ['class' => 'col-md-4']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $shareholderInfo->no_of_share }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('share_value', 'Share Value', ['class' => 'col-md-4']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $shareholderInfo->share_value }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@elseif($mode === "renew")
<style>
        .shareholderHeader::after {
            display: none;
        }
    </style>
    <div class="card card-magenta border border-magenta" id="shareholder_wrapper">
        <div class="card-header d-flex justify-content-between shareholderHeader">
            <div>Shareholder/ Partner/ Proprietor Information</div>

            <div>
                <label class="amendmentEditBtn" style="vertical-align: middle;">
                    <input type="checkbox" onclick="readOnlyChecker(this)"style="vertical-align: middle;"/>
                    EDIT
                </label>
            </div>
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?> id="amendmentShareholder">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('total_no_of_share', 'Total No. of Share', ['class' => 'col-md-4 required-star']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('total_no_of_share') ? 'has-error' : '' }}">
                            {!! Form::number('total_no_of_share', $appInfo->total_no_of_share, ['class' => 'form-control', 'placeholder' => 'Total No. of Share', 'id' => 'total_no_of_share', 'readonly']) !!}
                            {!! $errors->first('total_no_of_share', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('total_share_value', 'Total Share Value', ['class' => 'col-md-4 required-star']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('total_share_value') ? 'has-error' : '' }}">
                            {!! Form::number('total_share_value', $appInfo->total_share_value, ['class' => 'form-control', 'placeholder' => 'Total Share Value', 'id' => 'total_share_value', 'readonly']) !!}
                            {!! $errors->first('total_share_value', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <table class="table-responsive" style="width: 100%;     display: inline-table!important;"
                   id="shareholderRow">
                <input type="hidden" id="shareholderDataCount" name="shareholderDataCount"
                       value="{{ count($shareholders_data) }}"/>
                       @if(count($shareholders_data)==0)
                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Shareholder/ Partner/ Proprietor Details
                                <span style="float: right; cursor: pointer;" class="addShareholderRow m-l-auto">
                                                     <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                                                </span>
                            </div>
                            <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_name[1]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name']) !!}
                                                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_designation[1]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation']) !!}
                                                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_email[1]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email']) !!}
                                                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8  {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="iti-flag bd"></span>
                                                        <span>+88</span>
                                                    </span>
                                                </div>
                                                {!! Form::text('shareholder_mobile[1]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile']) !!}
                                                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_share_of', '% Of Share', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                {!! Form::number('shareholder_share_of[1]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of']) !!}
                                                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('no_of_share_1', 'No. of Share', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('no_of_share') ? 'has-error' : '' }}">
                                                {!! Form::number('no_of_share[1]', '', ['class' => 'form-control no-of-share', 'placeholder' => 'Enter % Of Share', 'id' => 'no_of_share_1']) !!}
                                                {!! $errors->first('no_of_share', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row" id="nidBlock_1" style="display: none;">
                                            {!! Form::label('shareholder_nid', 'NID No', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_nid[1]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_1']) !!}
                                                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row" id="passportBlock_1" style="display: none;">
                                            {!! Form::label('shareholder_passport', 'Passport No.', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_passport[1]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_1']) !!}
                                                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row" style="margin-bottom:45px!important;">
                                            {!! Form::label('correspondent_photo0_1', 'Image', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                {{--start--}}
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control correspondent_photo input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                               name="correspondent_photo[1]" id="correspondent_photo_1"
                                                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_1', 'correspondent_photo_base64_1')"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                                    <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                                </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="center-block image-upload"
                                                               for="correspondent_photo0_1">
                                                            <figure>
                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                     class="img-responsive img-thumbnail"
                                                                     id="correspondent_photo_preview_1" />
                                                            </figure>
                                                            <input type="hidden" id="correspondent_photo_base64_1"
                                                                   name="correspondent_photo_base64[1]" />
                                                        </label>
                                                    </div>
                                                </div>
                                                {{--end--}}
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin-top:10px;">
                                            {!! Form::label('shareholder_dob', 'Date of Birth', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                <div class="input-group date datetimepicker4"
                                                     id="datepicker0" data-target-input="nearest">
                                                    {!! Form::text('shareholder_dob[1]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob', 'placeholder' => 'Enter Date Of Birth']) !!}
                                                    <div class="input-group-append"
                                                         data-target="#datepicker0"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i
                                                                class="fa fa-calendar"></i></div>
                                                    </div>
                                                    {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_nationality', 'Nationality', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                {!! Form::select('shareholder_nationality[1]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_1']) !!}
                                                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('share_value_1', 'Share Value', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('share_value') ? 'has-error' : '' }}">
                                                {!! Form::number('share_value[1]', '', ['class' => 'form-control share-value', 'placeholder' => 'Enter Share Value', 'id' => 'share_value_1']) !!}
                                                {!! $errors->first('share_value', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                @else
                @foreach($shareholders_data as $index=>$shareholder)
                    <tr id="r_{{$index+1}}">
                        <td>
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Shareholder/ Partner/ Proprietor Details
                                    @if($index==0)
                                    <span style="float: right; cursor: pointer;" class="addShareholderRow m-l-auto">
                                                     <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                                                </span>
                                    @else
                                    <span style="float: right; cursor: pointer;" >
                                                         <button type="button" class="btn btn-danger btn-sm shareholderRow cross-button"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                                    </span>
                                    @endif

                                </div>
                                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_name_'.$index, 'Name', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                                                    {!! Form::text("shareholder_name[$index]", $shareholder->shareholders_name, ['class' => 'form-control shareholder_name input_disabled','readonly', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name_'.$index]) !!}
                                                    {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_designation_'.$index, 'Designation', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                    {!! Form::text("shareholder_designation[$index]", $shareholder->shareholders_designation, ['class' => 'form-control shareholder_designation input_disabled','readonly', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation_'.$index]) !!}
                                                    {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_email_'.$index, 'Email', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                    {!! Form::text("shareholder_email[$index]", $shareholder->shareholders_email, ['class' => 'form-control shareholder_email input_disabled','readonly', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email_'.$index]) !!}
                                                    {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_mobile_'.$index, 'Mobile Number', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8  {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                    <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <span class="iti-flag bd"></span>
                                                            <span>+88</span>
                                                        </span>
                                                    </div>
                                                    {!! Form::text("shareholder_mobile[$index]", $shareholder->shareholders_mobile, ['class' => 'form-control shareholder_mobile input_disabled','readonly', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_'.$index]) !!}
                                                    {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_share_of_'.$index, '% Of Share', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                    {!! Form::number("shareholder_share_of[$index]", $shareholder->shareholders_share_percent, ['class' => 'form-control shareholder_share_of input_disabled','readonly', 'placeholder' => '', 'id' => 'shareholder_share_of_'.$index]) !!}
                                                    {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('no_of_share_'.$index, 'No. of Share', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('no_of_share') ? 'has-error' : '' }}">
                                                    {!! Form::number("no_of_share[$index]", $shareholder->no_of_share , ['class' => 'form-control no-of-share input_disabled','readonly', 'placeholder' => 'Enter % Of Share', 'id' => 'no_of_share_'.$index]) !!}
                                                    {!! $errors->first('no_of_share', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <span id="nidBlock_{{$index+1}}"
                                                  style="{{$shareholder->shareholders_nationality == 18?'display:inline':'display:none'}}">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_nid_'.($index+1), 'NID No', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                                {!! Form::text("shareholder_nid[$index]", $shareholder->shareholders_nid, ['class' => 'form-control shareholder_nid input_disabled','readonly', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_'.($index+1)]) !!}
                                                                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </span>
                                            <span id="passportBlock_{{$index+1}}"
                                                  style="{{(isset($shareholder->shareholders_nationality) && $shareholder->shareholders_nationality != 18)?'display:inline':'display:none'}}">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_passport_'.($index+1), 'Passport No.', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                                                                {!! Form::text("shareholder_passport[$index]", $shareholder->shareholders_passport, ['class' => 'form-control shareholder_passport input_disabled','readonly', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_'.($index+1)]) !!}
                                                                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </span>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row" style="margin-bottom:45px !important">
                                                {!! Form::label("correspondent_photo0_{{$index}}", 'Image', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                    {{--start--}}
                                                    <div class="row"
                                                         style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   value="{{ $shareholder->shareholders_image }}"
                                                                   class="form-control input-sm correspondent_photo shareholderImg input_disabled"
                                                                   name="correspondent_photo[{{$index}}]"
                                                                   id="correspondent_photo_{{$index}}"
                                                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_{{$index}}', 'correspondent_photo_base64_{{$index}}')"
                                                                   size="300x300"/>

                                                            <span class="text-success"
                                                                  style="font-size: 9px; font-weight: bold; display: block;">
                                                            [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 MB]
                                                            <p style="font-size: 12px;"><a target="_blank"
                                                                                           href="https://picresize.com/">You may update your image.</a></p>
                                                        </span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="center-block image-upload"
                                                                   for="correspondent_photo0_{{$index}}">
                                                                <figure>
                                                                    <img
                                                                        style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                        src="{{ !empty($shareholder->shareholders_image) ? $shareholder->shareholders_image : url('assets/images/demo-user.jpg') }}"
                                                                        class="img-responsive img-thumbnail"
                                                                        id="correspondent_photo_preview_{{$index}}"/>
                                                                </figure>
                                                                <input type="hidden"
                                                                       id="correspondent_photo_base64_{{$index}}"
                                                                       value="{{ $shareholder->shareholders_image }}"
                                                                       name="correspondent_photo_base64[{{$index}}]"/>
                                                            </label>
                                                            <input type="hidden"
                                                                   value="{{$shareholder->image_real_path}}"
                                                                   name="image_real_path[]"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_dob_'.$index, 'Date of Birth', ['class' => 'col-md-4']) !!}
                                                <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                    <div class="input-group date datetimepicker4"
                                                         id="datepicker{{$index}}"
                                                         data-target-input="nearest">
                                                        {!! Form::text("shareholder_dob[$index]", !empty($shareholder->shareholders_dob)? \App\Libraries\CommonFunction::changeDateFormat($shareholder->shareholders_dob):'', ['class' => 'form-control shareholder_dob input_disabled','readonly', 'id' => 'shareholder_dob_'.$index, 'placeholder' => 'Enter Date Of Birth']) !!}
                                                        <div class="input-group-append"
                                                             data-target="#datepicker{{$index}}"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_nationality'.($index+1), 'Nationality', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                    {!! Form::select("shareholder_nationality[$index]", $nationality, $shareholder->shareholders_nationality, ['class' => 'form-control shareholder_nationality input_disabled','readonly', 'id' => 'shareholder_nationality_'.($index+1)]) !!}
                                                    {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('share_value_'.($index+1), 'Share Value', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('share_value') ? 'has-error' : '' }}">
                                                    {!! Form::number("share_value[$index]", $shareholder->share_value, ['class' => 'form-control share-value input_disabled','readonly','placeholder' => 'Enter Share Value', 'id' => 'share_value_'.($index+1)]) !!}
                                                    {!! $errors->first('share_value', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @endif
            </table>
        </div>
    </div>
@elseif($mode === "amendment")
    <style>
        .shareholderHeader::after {
            display: none;
        }
    </style>
    <div class="card card-magenta border border-magenta" id="shareholder_wrapper">
        <div class="card-header d-flex justify-content-between shareholderHeader">
            <div>Shareholder/ Partner/ Proprietor Information</div>

            <div>
                <label class="amendmentEditBtn">
                    <input type="checkbox" onclick="readOnlyChecker(this)"/>
                    EDIT
                </label>
            </div>
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?> id="amendmentShareholder">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('total_no_of_share', 'Total No. of Share', ['class' => 'col-md-5 required-star']) !!}
                        <div
                            class="col-md-7 {{ $errors->has('total_no_of_share') ? 'has-error' : '' }}">
                            {!! Form::number('total_no_of_share', $appInfo->total_no_of_share, ['class' => 'form-control', 'placeholder' => 'Total No. of Share', 'id' => 'total_no_of_share', 'readonly']) !!}
                            {!! $errors->first('total_no_of_share', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('total_share_value', 'Total Share Value', ['class' => 'col-md-5 required-star']) !!}
                        <div
                            class="col-md-7 {{ $errors->has('total_share_value') ? 'has-error' : '' }}">
                            {!! Form::number('total_share_value', $appInfo->total_share_value, ['class' => 'form-control', 'placeholder' => 'Total Share Value', 'id' => 'total_share_value', 'readonly']) !!}
                            {!! $errors->first('total_share_value', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <table class="table-responsive" style="width: 100%;     display: inline-table!important;"
                   id="shareholderRow">
                <input type="hidden" id="shareholderDataCount" name="shareholderDataCount"
                       value="{{ count($shareholders_data) }}"/>

                @if(count($shareholders_data)==0)


                <tr id="r_1">
                    <td>
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Shareholder/ Partner/ Proprietor Details
                                <span style="float: right; cursor: pointer;" class="addShareholderRow m-l-auto">
                                                     <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                                                </span>
                            </div>
                            <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_name[1]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name']) !!}
                                                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_designation[1]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation']) !!}
                                                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_email[1]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email']) !!}
                                                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8  {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="iti-flag bd"></span>
                                                        <span>+88</span>
                                                    </span>
                                                </div>
                                                {!! Form::text('shareholder_mobile[1]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile']) !!}
                                                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_share_of', '% Of Share', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                {!! Form::number('shareholder_share_of[1]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of']) !!}
                                                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('no_of_share_1', 'No. of Share', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('no_of_share') ? 'has-error' : '' }}">
                                                {!! Form::number('no_of_share[1]', '', ['class' => 'form-control no-of-share', 'placeholder' => 'Enter % Of Share', 'id' => 'no_of_share_1']) !!}
                                                {!! $errors->first('no_of_share', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row" id="nidBlock_1" style="display: none;">
                                            {!! Form::label('shareholder_nid', 'NID No', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_nid[1]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_1']) !!}
                                                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row" id="passportBlock_1" style="display: none;">
                                            {!! Form::label('shareholder_passport', 'Passport No.', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                                                {!! Form::text('shareholder_passport[1]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_1']) !!}
                                                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row" style="margin-bottom:45px!important;">
                                            {!! Form::label('correspondent_photo0_1', 'Image', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                {{--start--}}
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               style="border: none; margin-bottom: 5px;"
                                                               class="form-control correspondent_photo input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                               name="correspondent_photo[1]" id="correspondent_photo_1"
                                                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_1', 'correspondent_photo_base64_1')"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                                    <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                                </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="center-block image-upload"
                                                               for="correspondent_photo0_1">
                                                            <figure>
                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                     class="img-responsive img-thumbnail"
                                                                     id="correspondent_photo_preview_1" />
                                                            </figure>
                                                            <input type="hidden" id="correspondent_photo_base64_1"
                                                                   name="correspondent_photo_base64[1]" />
                                                        </label>
                                                    </div>
                                                </div>
                                                {{--end--}}
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin-top:10px;">
                                            {!! Form::label('shareholder_dob', 'Date of Birth', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                <div class="input-group date datetimepicker4"
                                                     id="datepicker0" data-target-input="nearest">
                                                    {!! Form::text('shareholder_dob[1]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob', 'placeholder' => 'Enter Date Of Birth']) !!}
                                                    <div class="input-group-append"
                                                         data-target="#datepicker0"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i
                                                                class="fa fa-calendar"></i></div>
                                                    </div>
                                                    {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('shareholder_nationality', 'Nationality', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                {!! Form::select('shareholder_nationality[1]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_1']) !!}
                                                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('share_value_1', 'Share Value', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('share_value') ? 'has-error' : '' }}">
                                                {!! Form::number('share_value[1]', '', ['class' => 'form-control share-value', 'placeholder' => 'Enter Share Value', 'id' => 'share_value_1']) !!}
                                                {!! $errors->first('share_value', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                @else
                @foreach($shareholders_data as $index=>$shareholder)
                    <tr id="r_{{$index+1}}">
                        <td>
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Shareholder/ Partner/ Proprietor Details
                                    @if($index == 0)
                                        <span style="float: right; cursor: pointer;" class="addShareholderRow">
                                            <i style="font-size: 20px;" class="fa fa-plus-square"
                                                               aria-hidden="true"></i>
                                        </span>
                                    @else
                                        <span style="float: right; cursor: pointer;">
                                                            <button type="button"
                                                                    class="btn btn-danger btn-sm shareholderRow cross-button"><strong><i
                                                                        style="font-size: 16px;" class="fa fa-times"
                                                                        aria-hidden="true"></i></strong></button>
                                                        </span>
                                    @endif
                                </div>
                                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_name_'.$index, 'Name', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                                                    {!! Form::text("shareholder_name[$index]", $shareholder->shareholders_name, ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name_'.$index]) !!}
                                                    {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_designation_'.$index, 'Designation', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                    {!! Form::text("shareholder_designation[$index]", $shareholder->shareholders_designation, ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation_'.$index]) !!}
                                                    {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_email_'.$index, 'Email', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                    {!! Form::text("shareholder_email[$index]", $shareholder->shareholders_email, ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email_'.$index]) !!}
                                                    {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_mobile_'.$index, 'Mobile Number', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 input-group {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="iti-flag bd"></span>
                                                        <span>+88</span>
                                                    </span>
                                                    </div>
                                                    {!! Form::text("shareholder_mobile[$index]", $shareholder->shareholders_mobile, ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_'.$index]) !!}
                                                    {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_share_of_'.$index, '% Of Share', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                    {!! Form::number("shareholder_share_of[$index]", $shareholder->shareholders_share_percent, ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_'.$index]) !!}
                                                    {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('no_of_share_'.$index, 'No. of Share', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('no_of_share') ? 'has-error' : '' }}">
                                                    {!! Form::number("no_of_share[$index]", $shareholder->no_of_share , ['class' => 'form-control no-of-share', 'placeholder' => 'Enter % Of Share', 'id' => 'no_of_share_'.$index]) !!}
                                                    {!! $errors->first('no_of_share', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <span id="nidBlock_{{$index+1}}"
                                                  style="{{$shareholder->shareholders_nationality == 18?'display:inline':'display:none'}}">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_nid_'.($index+1), 'NID No', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                                {!! Form::text("shareholder_nid[$index]", $shareholder->shareholders_nid, ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_'.($index+1)]) !!}
                                                                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </span>
                                            <span id="passportBlock_{{$index+1}}"
                                                  style="{{(isset($shareholder->shareholders_nationality) && $shareholder->shareholders_nationality != 18)?'display:inline':'display:none'}}">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_passport_'.($index+1), 'Passport No.', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                                                                {!! Form::text("shareholder_passport[$index]", $shareholder->shareholders_passport, ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_'.($index+1)]) !!}
                                                                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </span>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row"
                                                 style="margin-bottom:45px!important">
                                                {!! Form::label("correspondent_photo0_{{$index}}", 'Image', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                    {{--start--}}
                                                    <div class="row"
                                                         style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   value="{{ $shareholder->shareholders_image }}"
                                                                   class="form-control input-sm correspondent_photo shareholderImg"
                                                                   name="correspondent_photo[{{$index}}]"
                                                                   id="correspondent_photo_{{$index}}"
                                                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_{{$index}}', 'correspondent_photo_base64_{{$index}}')"
                                                                   size="300x300"/>

                                                            <span class="text-success"
                                                                  style="font-size: 9px; font-weight: bold; display: block;">
                                                            [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 MB]
                                                            <p style="font-size: 12px;"><a target="_blank"
                                                                                           href="https://picresize.com/">You may update your image.</a></p>
                                                        </span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="center-block image-upload"
                                                                   for="correspondent_photo0_{{$index}}">
                                                                <figure>
                                                                    <img
                                                                        style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                        src="{{ !empty($shareholder->shareholders_image) ? $shareholder->shareholders_image : url('assets/images/demo-user.jpg') }}"
                                                                        class="img-responsive img-thumbnail"
                                                                        id="correspondent_photo_preview_{{$index}}"/>
                                                                </figure>
                                                                <input type="hidden"
                                                                       id="correspondent_photo_base64_{{$index}}"
                                                                       value="{{ $shareholder->shareholders_image }}"
                                                                       name="correspondent_photo_base64[{{$index}}]"/>
                                                            </label>
                                                            <input type="hidden"
                                                                   value="{{$shareholder->image_real_path}}"
                                                                   name="image_real_path[]"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_dob_'.$index, 'Date of Birth', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                    <div class="input-group date datetimepicker4"
                                                         id="datepicker{{$index}}"
                                                         data-target-input="nearest">
                                                        {!! Form::text("shareholder_dob[$index]", !empty($shareholder->shareholders_dob)? \App\Libraries\CommonFunction::changeDateFormat($shareholder->shareholders_dob):'', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_'.$index, 'placeholder' => 'Enter Date Of Birth']) !!}
                                                        <div class="input-group-append"
                                                             data-target="#datepicker{{$index}}"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_nationality'.($index+1), 'Nationality', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                    {!! Form::select("shareholder_nationality[$index]", $nationality, $shareholder->shareholders_nationality, ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_'.($index+1)]) !!}
                                                    {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('share_value_'.($index+1), 'Share Value', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('share_value') ? 'has-error' : '' }}">
                                                    {!! Form::number("share_value[$index]", $shareholder->share_value, ['class' => 'form-control share-value', 'placeholder' => 'Enter Share Value', 'id' => 'share_value_'.($index+1)]) !!}
                                                    {!! $errors->first('share_value', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach

                @endif

            </table>
        </div>
    </div>
@endif

<script>
    getHelpText();
    // setIntlTelInput('.shareholder_mobile ');
    @if(in_array($mode, ['add', 'edit', 'amendment','renew']))
        $(".addShareholderRow").on('click', function() {
            let lastRowId = parseInt($('#shareholderRow tr:last').attr('id').split('_')[1]);
            let updateRowId = parseInt(lastRowId)+1;
            $('#shareholderRow').append(
                `<tr id="R_${updateRowId}" class="client-rendered-row">
        <td>
        <div class="card card-magenta border border-magenta">
                                                <div class="card-header">
                                                    Shareholder/ Partner/ Proprietor Details
                                                    <span style="float: right; cursor: pointer;" class="addShareholderRow">
                                                         <button type="button" class="btn btn-danger btn-sm shareholderRow cross-button"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                                    </span>
                                                </div>
                                                <div class="card-body" style="padding: 15px 25px;">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    {!! Form::label('shareholder_name_${updateRowId}', 'Name', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                        {!! Form::text('shareholder_name[${updateRowId}]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name',  'id' => 'shareholder_name_${updateRowId}']) !!}
                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
        {!! Form::label('shareholder_designation_${updateRowId}', 'Designation', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                        {!! Form::text('shareholder_designation[${updateRowId}]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation_${updateRowId}']) !!}
                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
        {!! Form::label('shareholder_email_${updateRowId}', 'Email', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                        {!! Form::text('shareholder_email[${updateRowId}]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email_${updateRowId}']) !!}
                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
        {!! Form::label('shareholder_mobile_${updateRowId}', 'Mobile Number', ['class' => 'col-md-4']) !!}
                <div class="col-md-8  {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                  <div class="input-group">
                  <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="iti-flag bd"></span>
                                                        <span>+88</span>
                                                    </span>
                                          </div>
                        {!! Form::text('shareholder_mobile[${updateRowId}]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_${updateRowId}']) !!}
                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                </div>
                </div>
            </div>
            <div class="form-group row">
        {!! Form::label('shareholder_share_of_${updateRowId}', '% Of Share', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                        {!! Form::number('shareholder_share_of[${updateRowId}]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_${updateRowId}']) !!}
                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
        {!! Form::label('no_of_share_${updateRowId}', 'No. of Share', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('no_of_share') ? 'has-error' : '' }}">
                        {!! Form::number('no_of_share[${updateRowId}]', '', ['class' => 'form-control no-of-share', 'placeholder' => 'Enter % Of Share', 'id' => 'no_of_share_${updateRowId}']) !!}
                {!! $errors->first('no_of_share', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        <div class="form-group row" id="nidBlock_${updateRowId}" style="display: none;">
                    {!! Form::label('shareholder_nid_${updateRowId}', 'NID No', ['class' => 'col-md-4 ']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                        {!! Form::text('shareholder_nid[${updateRowId}]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_'.'${updateRowId}']) !!}
                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row" id="passportBlock_${updateRowId}" style="display: none;">
                    {!! Form::label('shareholder_passport_${updateRowId}', 'Passport No.', ['class' => 'col-md-4 ']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                        {!! Form::text('shareholder_passport[${updateRowId}]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_'.'${updateRowId}']) !!}
                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row" style="margin-bottom:45px!important;">
        {!! Form::label('correspondent_photo0_${updateRowId}', 'Image', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                        <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                            <div class="col-md-8">
                                <input type="file" class="form-control input-sm correspondent_photo"
                                style="border: none; margin-bottom: 5px;"
                                       name="correspondent_photo[${updateRowId}]" id="correspondent_photo_${updateRowId}" size="300x300"
                                       onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_${updateRowId}', 'correspondent_photo_base64_${updateRowId}')" />

                                <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                  [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX  | Max Size: 4 KB]
                                                <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                            </span>
                            </div>
                            <div class="col-md-4">
                                <label class="center-block image-upload" for="correspondent_photo0_${updateRowId}">
                                    <figure>
                                        <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                             src="{{asset('assets/images/demo-user.jpg') }}"
                                             class="img-responsive img-thumbnail" id="correspondent_photo_preview_${updateRowId}" />
                                    </figure>
                                    <input type="hidden" id="correspondent_photo_base64_${updateRowId}" name="correspondent_photo_base64[${updateRowId}]" />
                                </label>
                            </div>
                    </div>
                </div>
                </div>
                <div class="form-group row" style="margin-top:10px;">
                    {!! Form::label('shareholder_dob_${updateRowId}', 'Date of Birth', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                    <div class="input-group date datetimepicker4" id="dob${lastRowId}" data-target-input="nearest">
                            {!! Form::text('shareholder_dob[${updateRowId}]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_${updateRowId}', 'placeholder' => 'Enter Date Of Birth']) !!}
                <div class="input-group-append" data-target="#dob${lastRowId}" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
        {!! Form::label('shareholder_nationality_${updateRowId}', 'Nationality', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                        {!! Form::select('shareholder_nationality[${updateRowId}]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_'.'${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
        {!! Form::label('share_value_${updateRowId}', 'Share Value', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('share_value') ? 'has-error' : '' }}">
                        {!! Form::number('share_value[${updateRowId}]', '', ['class' => 'form-control share-value', 'placeholder' => 'Enter Share Value', 'id' => 'share_value_${updateRowId}']) !!}
                {!! $errors->first('share_value', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        </div>
        </div>
        </div>
        </td>
        </tr>`);

            $("#shareholderDataCount").val(lastRowId+1);
            var today = new Date();
            var yyyy = today.getFullYear();

            $('.datetimepicker4').datetimepicker({
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
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

    function viewRJSCInfo() {
        const modal = document.getElementById('apiModal');
        const modalContent = document.querySelector('.modal-content'); // Reference to modal-content
        const modalText = document.getElementById('modalText');

        // Display the modal with a loading message
        modal.style.display = 'block';
        modalText.innerHTML = `
        <div style="text-align: center; font-family: Arial, sans-serif; color: #2c3e50; font-size: 18px; padding: 20px;">
            <p style="font-size: 20px; font-weight: bold; color: #34495e;">
                Connecting to RJSC server
            </p>
            <p style="font-size: 16px; font-weight: normal; color: #34495e;">
                Please wait.....
            </p>

        </div>
    `;
        const appInfo = @json($appInfo ?? null);
        let companyId = null;
        if (appInfo && appInfo.org_type) {
                 companyId = appInfo.company_id
        } else {
            console.log("No appInfo available.");
        }
        const url = companyId
            ? `/show-information-from-rjsc?company_id=${encodeURIComponent(companyId)}`
            : '/show-information-from-rjsc';
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Unable to connect to RJSC’s server. Please try again.");
                }
               const logo = document.getElementById('logo')
                logo.className = 'hidden';
                return response.json(); // Parse JSON directly
            })
            .then(data => {
                if (data.message) {

                    modalText.innerHTML = data.message;

                    const closeButton = document.createElement('span');
                    closeButton.id = 'verify';
                    closeButton.className = 'btn btn-primary close';
                    closeButton.innerText = 'Close';
                    closeButton.onclick = closeModal;

                    modalContent.appendChild(closeButton);
                } else {
                    console.warn('No HTML content in the response');
                }
            })
            .catch(error => {
                clearTimeout(timeout); // Clear the timeout in case of error

                modalText.textContent = `Error: ${error.message}`;
                console.error('Fetch Error:', error);
            });
    }

    function closeModal() {
        const logo = document.getElementById('logo')
        logo.classList.remove('hidden');
        document.getElementById('apiModal').style.display = 'none';
    }




    // function closeModal() {
    //     document.getElementById('apiModal').style.display = 'none';
    // }



</script>
