@extends('public_home.front')

@section('header-resources')
@endsection

@section ('body')
    <div class="container">
        <div class="singlePageDesign">
            <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success">
                        <h5 class="box-title"><i class="fa fa-book"></i> Document and Downloads</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered" id="userManualList" style="width: 100%;">
                            <thead>
                            <tr>
                                <th width="80%">Document name</th>
                                <th width="20%">Link</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection

@section('footer-script')
    @include('partials.datatable-css')
    @include('partials.datatable-js')
    <script>
        $(function () {
            $('#userManualList').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                order: [0, 'desc'],
                ajax: {
                    url: '{{url("web/get-user-manual")}}',
                    method: 'POST',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'typeName', name: 'typeName'},
                    {data: 'action', name: 'action', orderable: true, searchable: true}
                ],
                "aaSorting": []
            });
        });
    </script>
@endsection
