<div class="responseMessageInModalForAssignApiInfo"></div>
<input type="hidden" value="{{$encodedClientId}}" class="client_id">
<div class="form-group">
    {{--                        <div class="row" style="margin-bottom: 6px;">--}}
    {{--                            <div class="col-md-12 {{$errors->has('name') ? 'has-error': ''}}">--}}
    {{--                                {!! Form::label('name','API Name',['class'=>'col-md-3 required-star','title'=>'Example: User Information','data-toggle'=>'tooltip']) !!}--}}
    {{--                                <div class="col-md-9">--}}
    {{--                                    {!! Form::text('name','', ['class' => 'form-control input-md api_name ']) !!}--}}
    {{--                                    {!! $errors->first('name','<span class="help-block">:message</span>') !!}--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}

    <div class="row" style="margin-bottom: 6px;">
        <div class="col-md-12 {{$errors->has('assign_api') ? 'has-error': ''}}">
            {!! Form::label('assign_api','Allowed API',['class'=>'col-md-2 required-star']) !!}
            <div class="col-md-10 selectTwoContent">
                {!! Form::select('assign_api[]',$api_list,$assigned_list, ['class' => 'select2_input assign_api form-control input-md required','multiple'=>'multiple']) !!}
                {!! $errors->first('assign_api','<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>

</div>