@if($type == 'equipment')
    <tr row_id="equipment_{{$row_id}}">
        <td class="d-flex justify-content-center w-60">{{$row_id}}</td>
        <td><input type="text" style="text-align: center;" class="form-control equipment_name" name="equipment_name[{{$row_id}}]"
                   placeholder="Enter equipment name"></td>
        <td><input type="text" style="text-align: center;" class="form-control equipment_brand_model" name="equipment_brand_model[{{$row_id}}]"
                   placeholder="Enter brand & model"></td>
        <td><input type="number" style="text-align: center;" class="form-control equipment_quantity" name="equipment_quantity[{{$row_id}}]"
                   placeholder="Enter quantity"></td>
        <td><input type="text" style="text-align: center;" class="form-control equipment_remarks" name="equipment_remarks[{{$row_id}}]"
                   placeholder="Enter remarks"></td>
        <td class="d-flex justify-content-center">
            <button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i
                    class="fa fa-minus"></i></button>
        </td>
    </tr>
@elseif($type == 'tariffChart')
    <tr row_id="tariffChart_{{$row_id}}">
        <td class="d-flex justify-content-center w-60">{{$row_id}}</td>
        <td><input type="text" style="text-align: center;" class="form-control tariffChart_package_name_plan"
                   name="tariffChart_package_name_plan[{{$row_id}}]" placeholder="Enter packages name/ plan"></td>
        <td><input type="number" style="text-align: center;" class="form-control tariffChart_bandwidth_package"
                   name="tariffChart_bandwidth_package[{{$row_id}}]" placeholder="Enter Speed (Kbps/Mbps)"></td>
        <td><input type="number" style="text-align: center;" class="form-control tariffChart_price" name="tariffChart_price[{{$row_id}}]"
                   placeholder="Enter price(BDT)"></td>
        <td><input type="text" style="text-align: center;" class="form-control tariffChart_duration" name="tariffChart_duration[{{$row_id}}]"
                   placeholder="Enter duration"></td>
        <td><input type="text" style="text-align: center;" class="form-control tariffChart_remarks" name="tariffChart_remarks[{{$row_id}}]"
                   placeholder="Enter remarks"></td>
        <td class="d-flex justify-content-center">
            <button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i
                    class="fa fa-minus"></i></button>
        </td>
    </tr>

@elseif($type == 'bandwidth')
    <tr row_id="bandwidth_{{$row_id}}">
        <td class="d-flex justify-content-center">{{$row_id}}</td>
        <td><input type="text" class="form-control" name="bandwidth_primary_iig[]"
                   placeholder="Enter Name of the primary IIG"></td>
        <td><input type="number" class="form-control" name="bandwidth_allocation[]" placeholder="Enter Allocation"></td>
        <td><input type="number" class="form-control" name="bandwidth_up_stream[]" placeholder="Enter Up Stream"></td>
        <td><input type="number" class="form-control" name="bandwidth_down_stream[]" placeholder="Enter Down Stream">
        </td>
        <td class="d-flex justify-content-center ">
            <button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i
                    class="fa fa-minus"></i></button>
        </td>
    </tr>
@elseif($type == 'connectivity')
    <tr row_id="connectivity_{{$row_id}}">
        <td class="d-flex justify-content-center">{{$row_id}}</td>
        <td><input type="text" class="form-control" name="connectivity_provider[]"
                   placeholder="Enter Name of the primary IIG"></td>
        <td><input type="number" class="form-control" name="connectivity_up_stream[]" placeholder="Enter Up Stream">
        </td>
        <td><input type="number" class="form-control" name="connectivity_down_stream[]" placeholder="Enter Down Stream">
        </td>
        <td><input type="number" class="form-control" name="connectivity_up_frequency[]" placeholder="Enter Up"></td>
        <td><input type="number" class="form-control" name="connectivity_down_frequency[]" placeholder="Enter Down">
        </td>
        <td class="d-flex justify-content-center">
            <button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i
                    class="fa fa-minus"></i></button>
        </td>
    </tr>
@elseif($type == 'IPTSPlistOfISPinformation')
    <tr row_id="IPTSPlistOfISPinformation_{{$row_id}}">
        <td>1</td>
        <td>
            {!! Form::select("institution_type[$row_id]",[
                "1"=>"School",
                "2"=>"College",
                "3"=>"University",
                ""=>"Select"
                ], null, ["class"=>"form-control institution_type", "id"=>"institution_type_$row_id"]) !!}
        </td>
        <td><input type="text" class="form-control institution_name" id="institution_name_{{$row_id}}" placeholder="Institution Name" name="institution_name[{{$row_id}}]"></td>
        <td class="d-flex justify-content-center">
            <button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i
                        class="fa fa-minus"></i></button>
        </td>
    </tr>
@elseif($type == 'technical')
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

