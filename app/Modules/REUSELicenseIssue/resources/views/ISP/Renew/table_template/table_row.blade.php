@if($type == 'bandwidth')
    <tr row_id="bandwidth_{{$row_id}}">
        <td class="d-flex justify-content-center">{{$row_id}}</td>
        <td><input type="text" class="form-control" name="bandwidth_primary_iig[]" placeholder="Enter Name of the primary IIG"></td>
        <td><input type="number" class="form-control" name="bandwidth_allocation[]" placeholder="Enter Allocation"></td>
        <td><input type="number" class="form-control" name="bandwidth_up_stream[]" placeholder="Enter Up Stream"></td>
        <td><input type="number" class="form-control" name="bandwidth_down_stream[]" placeholder="Enter Down Stream"></td>
        <td class="d-flex justify-content-center "><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@elseif($type == 'connectivity')
    <tr row_id="connectivity_{{$row_id}}">
        <td class="d-flex justify-content-center">{{$row_id}}</td>
        <td><input type="text" class="form-control" name="connectivity_provider[]" placeholder="Enter Name of the primary IIG"></td>
        <td><input type="number" class="form-control" name="connectivity_up_stream[]" placeholder="Enter Up Stream"></td>
        <td><input type="number" class="form-control" name="connectivity_down_stream[]" placeholder="Enter Down Stream"></td>
        <td><input type="number" class="form-control" name="connectivity_up_frequency[]" placeholder="Enter Up"></td>
        <td><input type="number" class="form-control" name="connectivity_down_frequency[]" placeholder="Enter Down"></td>
        <td class="d-flex justify-content-center"><button class="btn btn-danger rounded-circle btn-sm text-white remove_row" type="button"><i class="fa fa-minus"></i></button></td>
    </tr>
@endif

