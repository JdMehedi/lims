@if($type == 'technical')
    <tr row_id="technical_{{$row_id}}">
        <td class="text-center">{{$row_id}}</td>
        <td><input type="text" class="form-control technical_name" id="technical_name_{{$row_id}}" name="technical_name[{{$row_id}}]" placeholder="Enter  name"></td>
        <td><input type="text" class="form-control technical_type" id="technical_type_{{$row_id}}" name="technical_type[{{$row_id}}]" placeholder="Enter type"></td>
        <td><input type="text" class="form-control technical_manufacturer" id="technical_manufacturer_{{$row_id}}" name="technical_manufacturer[{{$row_id}}]" placeholder="Enter manufacturer"></td>
        <td><input type="text" class="form-control technical_country_of_Origin" id="technical_country_of_Origin_{{$row_id}}"  name="technical_country_of_Origin[{{$row_id}}]" placeholder="Enter country of Origin"></td>
        <td><input type="text" class="form-control technical_power_output" id="technical_power_output_{{$row_id}}"  name="technical_power_output[{{$row_id}}]" placeholder="Enter power output"></td>
        <td class="d-flex justify-content-center"><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@elseif($type == 'satelite')
    <tr row_id="satelite_{{$row_id}}">
        <td class="text-center">{{$row_id}}</td>
        <td><input type="text" class="form-control service_provider" name="service_provider[{{$row_id}}]" id="service_provider_{{$row_id}}" placeholder="Enter Service Provider Name"></td>
        <td><input type="text" class="form-control service_details" name="service_details[{{$row_id}}]" id="service_details_{{$row_id}}" placeholder="Enter Service Details"></td>
        <td><input type="text" class="form-control service_location" name="service_location[{{$row_id}}]" id="service_location_{{$row_id}}" placeholder="Enter Location"></td>
        <td class="d-flex justify-content-center"><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@elseif($type == 'vsat')
    <tr row_id="vsat_{{$row_id}}">
        <td class="text-center">{{$row_id}}</td>
        <td><input type="text" class="form-control vsat_place_name" name="vsat_place_name[{{$row_id}}]" id="vsat_place_name_{{$row_id}}"  placeholder="Place name(installed existing)"></td>
        <td><input type="number"  class="form-control vsat_location" name="vsat_location[{{$row_id}}]" id="vsat_location_{{$row_id}}" placeholder="Geographical location (Measured by set)"></td>
        <td class="d-flex justify-content-center"><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@elseif($type == 'listOfEquipment')
    <tr row_id="listOfEquipment_{{$row_id}}">
        <td class="text-center">{{$row_id}}</td>
        <td><input type="text" class="form-control list_equipment" id="list_equipment_{{$row_id}}" placeholder="Equipment Name" name="list_equipment[{{$row_id}}]"></td>
        <td><input type="text" class="form-control list_storage" id="list_storage_{{$row_id}}" placeholder="Storage Capacity" name="list_storage[{{$row_id}}]"></td>
        <td><input type="text" class="form-control list_data" id="list_data_{{$row_id}}" placeholder="Data" name="list_data[{{$row_id}}]"></td>
        <td class="d-flex justify-content-center"><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@endif

