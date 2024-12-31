<div class="panel panel-default">
    <div class="panel-heading">
        @if(ACL::getAccsessRight('settings','E'))
            <button type="button" class="pull-right btn btn-success"
                    onclick="openModal(this)"
                    data-action="{{ url('/settings/document-v2/service/create') }}">
                <i class="fa fa-plus"></i> <b>Add New Validation</b>
            </button>
        @endif
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="serviceDocList"
                   class="table table-striped table-bordered dt-responsive"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Document name</th>
                    <th>Process type</th>
                    <th>Document type</th>
                    <th>Required status</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>