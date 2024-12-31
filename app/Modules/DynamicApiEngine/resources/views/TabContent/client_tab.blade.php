<div class="panel panel-default">
    <div class="panel-heading">
        @if(ACL::getAccsessRight('settings','E'))
            <div class="outputResponseMessageDiv"></div>
            <button type="button" class="float-right btn btn-success"
                    onclick="openAddClientModal(this)"
                    data-action="{{ url('/') }}">
                <i class="fa fa-plus"></i> <b>Add New Client</b>
            </button>
        @endif
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="clientList"
                   class="table table-striped table-bordered dt-responsive"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Client name</th>
                    <th>Token Expiration</th>
                    <th>Grant Type</th>
                    <th>Client ID</th>
                    <th>Secret</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<script>

    function getClientList(){

        $('#clientList').DataTable({
            iDisplayLength: 10,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: '{{url("dynamic-api-engine/authentications/get-client-list")}}',
                method: 'POST',
                data: function (d) {
                    d.api_id = $('#api_id').val();
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {data: 'client_name', name: 'client_name', searchable: true},
                {data: 'token_expire_time', name: 'token_expire_time', searchable: true},
                {data: 'grant_type', name: 'grant_type', searchable: true},
                {data: 'client_id', name: 'client_id', searchable: true},
                {data: 'client_secret', name: 'client_secret', searchable: true},
                {data: 'is_active', name: 'is_active', searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });

    }

</script>
