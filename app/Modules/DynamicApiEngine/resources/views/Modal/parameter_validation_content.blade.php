<div class="responseMessageInModal"></div>



    <input type="hidden" name="parameter_id" value="{{ \App\Libraries\Encryption::encodeId($api_parameter_data[0]->id) }}"
           id="parameter_id"/>
    <input type="hidden" name="api_id" value="{{ \App\Libraries\Encryption::encodeId($api_parameter_data[0]->api_id) }}"
           id="api_id"/>
    <div class="row">
      <div class="col-md-12">
          <div class="form-group row">
              <div class="col-md-9">
                 {!! Form::text('edited_request_parameter_name', $api_parameter_data[0]->parameter_name, ['class' => 'edited_parameter_name form-control input-md ','readonly']) !!}
                 {!! $errors->first('request_parameter_name','<span class="help-block">:message</span>') !!}

              </div>
             <div class="col-md-3">
                <button type="button" data-action="update" class="add_more_parameter_validation btn btn-success btn-sm">Add Param Validation</button>
                </div>
        </div>
    </div>
    </div>
<div class="addParameterForEdit">
@foreach($api_parameter_data as $key => $paramSingleData)
    @if(isset($paramSingleData->validation_method))
<div class="row">
    <div class="col-md-12">
        <div class="form-group row readonlyPointerEditSection removeParameterEditSection" style="margin-top:20px;padding: 5px;">

            @php
                $Validation_rules_val = isset(explode(":",$paramSingleData->validation_method_full)[1]) ? explode(':',$paramSingleData->validation_method_full)[1] : "";
            @endphp
            <div class="col-md-3">
                {!! Form::select('edited_validation_method[]',$validationRules,$paramSingleData->validation_method, ['class' => 'form-control input-md required edited_validation_method']) !!}
            </div>
            <div class="col-md-1 validationValueSection">
                {!! Form::text('edited_validation_method_val[]', in_array($paramSingleData->validation_method,['LENGTH','MIN','MAX','LENGTH_BETWEEN','DATE'])?$Validation_rules_val:'N/A' , ['class' => 'form-control input-md required edited_validation_method_val',in_array($paramSingleData->validation_method,['LENGTH','MIN','MAX','LENGTH_BETWEEN','DATE'])?'':'style'=>'visibility:hidden']) !!}
            </div>
            <div class="col-md-6">
                {!! Form::text('edited_validation_text[]', $paramSingleData->message , ['class' => 'form-control input-md required','placeholder'=>'Validation message']) !!}
            </div>
            <div class="col-md-1">
                {{--                    @if($key == 0)--}}
                {{--                        <button type="button" data-action="update" class="add_more_parameter_validation btn btn-success btn-sm">+</button>--}}
                {{--                    @else--}}
                {{--                    @endif--}}
                <button type="button" data-action="update" class="remove_parameter_validation btn btn-danger btn-sm">-</button>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 readonlyPointerEditSection removeParameterEditSection">
        <div class="form-group row">
            @if($paramSingleData->validation_method == 'SQL')
            <div class="row rmv_sql">
                <div class="col-md-12 {{$errors->has('validation_exe_sql') ? 'has-error': ''}}" style="margin-top: 6px;">
                    <div class="col-md-10">
                        {!! Form::textarea('edited_validation_exe_sql[]', $paramSingleData->exe_sql, ['class' => 'form-control input-sm edited_validation_exe_sql',
                                'size'=>'5x8', 'data-charcount-enable' => 'true', "data-charcount-maxlength" => "240"]) !!}
                        {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                        {{--                                <button class="sql_code_beautify_query" type="button">Beautify Query</button>--}}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
    @endif
@endforeach
</div>

