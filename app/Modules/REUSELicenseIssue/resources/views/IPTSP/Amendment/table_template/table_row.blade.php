@if($type == 'technical')
    <tr row_id="technical_{{$row_id}}">
        <td>{{$row_id}}</td>
        <td><input type="text" class="form-control technical_name" name="technical_name[{{$row_id}}]" placeholder="Enter  name"></td>
        <td><input type="text" class="form-control technical_type" name="technical_type[{{$row_id}}]" placeholder="Enter type"></td>
        <td><input type="text" class="form-control technical_manufacturer" name="technical_manufacturer[{{$row_id}}]" placeholder="Enter manufacturer"></td>
        <td><input type="text" class="form-control technical_country_of_Origin" name="technical_country_of_Origin[{{$row_id}}]" placeholder="Enter country of Origin"></td>
        <td><input type="text" class="form-control technical_power_output" name="technical_power_output[{{$row_id}}]" placeholder="Enter power output"></td>
        <td><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@elseif($type == 'satelite')

    <tr row_id="satelite_{{$row_id}}">

        <td>{{$row_id}}</td>
        <td><input type="text" class="form-control service_provider" name="service_provider[{{$row_id}}]" placeholder="Enter Service Provider Name"></td>
        <td><input type="text" class="form-control service_details" name="service_details[{{$row_id}}]" placeholder="Enter Service Detials"></td>
        <td><input type="text" class="form-control service_location" name="service_location[{{$row_id}}]" placeholder="Enter Location"></td>

        <td><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@elseif($type == 'vsat')
    <tr row_id="vsat_{{$row_id}}">
        <td>{{$row_id}}</td>
        <td><input type="text" class="form-control vsat_place_name" name="vsat_place_name[{{$row_id}}]"  placeholder="Place name(installed existing)"></td>
        <td><input type="text"  class="form-control vsat_location" name="vsat_location[{{$row_id}}]" placeholder="Geographical location ( class=form=control measured by set)"></td>
        <td><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@elseif($type == 'listOfEquipment')
    <tr row_id="listOfEquipment_{{$row_id}}">
        <td>{{$row_id}}</td>
        <td><input type="text" class="form-control list_equipment" placeholder="Equipment Name" name="list_equipment[{{$row_id}}]"></td>
        <td><input type="text" class="form-control list_storage" placeholder="Storage Capacity" name="list_storage[{{$row_id}}]"></td>
        <td><input type="text" class="form-control list_data" placeholder="Data" name="list_data[{{$row_id}}]"></td>
        <td><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@elseif($type == 'IPTSPlistOfISPinformation')
    <tr row_id="IPTSPlistOfISPinformation_{{$row_id}}">
        <td>{{$row_id}}</td>
        <td>
            {!! Form::select("institution_type[$row_id]",[
                                            "1"=>"School",
                                            "2"=>"College",
                                            "3"=>"University",
                                            ""=>"Select"
                                            ], null, ["class"=>"form-control institution_type", "id"=>"institution_type_$row_id"]) !!}
        </td>
        <td><input type="text" class="form-control institution_name" id="institution_name_{{$row_id}}" placeholder="Institution Name" name="institution_name[{{$row_id}}]"></td>
        <td><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@endif

