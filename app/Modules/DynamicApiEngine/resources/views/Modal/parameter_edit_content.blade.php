<div class="responseMessageInParamModal"></div>

<div class="form-group">

    <input type="hidden" name="parameter_id" value="{{ \App\Libraries\Encryption::encodeId($api_parameter_data->id) }}"
           id="parameter_id"/>
    <input type="hidden" name="api_id" value="{{ \App\Libraries\Encryption::encodeId($api_parameter_data->api_id) }}"
           id="api_id"/>

    <div class="row" style="margin-bottom: 6px;">
        <div class="col-md-12 {{$errors->has('request_parameter_name') ? 'has-error': ''}}">
            {!! Form::label('request_parameter_name','Parameter Name',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'']) !!}
            <div class="col-md-9">
                {!! Form::text('edited_request_parameter_name', $api_parameter_data->parameter_name, ['class' => 'edited_parameter_name form-control input-md required']) !!}
                {!! $errors->first('request_parameter_name','<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>


</div>