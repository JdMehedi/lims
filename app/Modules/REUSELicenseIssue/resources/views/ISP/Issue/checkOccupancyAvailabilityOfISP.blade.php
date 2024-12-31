<style>
    .custom_error {
        outline: 1px solid red;
    }
</style>
@extends('layouts.admin')
@section('header-resources')
    @include('partials.datatable-css')
@endsection
@php
    $modules = DB::table('process_type')->where('status', 1)->pluck('drop_down_label', 'id');
@endphp
@section('content')
    <style>
        .content-wrapper {
            height: auto;
        }
    </style>
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session()->get('success') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            {{ session()->get('error') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    <div class="float-left">
                        <h3><strong>List of vacancy availability of ISP</strong></h3>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="card-body">

                    <table class="table table-bordered" id="occupancy_table">
                        <thead>
                        <tr>
                            <th class="text-center">Area Type</th>
                            <th class="text-center">Area Name</th>
{{--                            <th class="text-center">Scope</th>--}}
{{--                            <th class="text-center">Existing</th>--}}
                            <th class="text-center">Vacancy</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($occupancy_information as $item)
                            <tr>
                                <td class="text-center">{{ $item->Area_Type }}</td>
                                <td class="text-center">{{ $item->Area_Name }}</td>
{{--                                <td class="text-center">{{ $item->Scope }}</td>--}}
{{--                                <td class="text-center">{{ $item->Existing }}</td>--}}
                                <td class="text-center">{{ $item->Occupancy }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    @include('partials.datatable-js')
    <script>
        $(document).ready(function(){
            $('#occupancy_table').dataTable();
        });
    </script>
@endsection




