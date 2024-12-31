@extends('layouts.admin')

@section('header-resources')
    @include('partials.datatable-css')
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-info" id="inputForm">
                <div class="card-header">
                    <h5><strong> {!!trans('ProcessPath::messages.block_chain_verification')!!}</strong></h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped no-margin">
                        <thead>
                        <tr>
                            <th>Process ID</th>
                            <th>On Desk</th>
                            <th>Updated By</th>
                            <th>Status</th>
                            <th>Process Time</th>
                            <th>Verification Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($process_history) > 0)
                            @foreach ($process_history as $data)
                                <?php
                                $resultData = $data->process_id . '-' . $data->tracking_no .
                                    $data->desk_id . '-' . $data->status_id . '-' . $data->user_id . '-' .
                                    $data->updated_by;
                                $plaintext = \App\Libraries\Encryption::decode($data->hash_value);

                                ?>
                                <tr>
                                    <td>{{ $data->process_id }}</td>
                                    <td>{{ $data->deskname }}</td>
                                    <td>{{ $data->user_full_name }}</td>
                                    <td>{{ $data->status_name }}</td>
                                    <td>{{ date('d-m-Y h:i A', strtotime($data->updated_at)) }}</td>
                                    <td>
                                        @if ($resultData == $plaintext)
                                            <i class="fa fa-check-square text-success"></i>
                                        @else
                                            <i class="fa fa-ban text-danger"></i>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-danger">No result found!</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    @include('partials.datatable-js')

    <script language="javascript">
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
@endsection
