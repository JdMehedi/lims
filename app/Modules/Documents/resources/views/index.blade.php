<?php
$user_type = CommonFunction::getUserType();
$accessMode = ACL::getAccsessRight('Documents');
if (!ACL::isAllowed($accessMode, '-V-'))
    die('no access right!');
?>



    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal"></div>
        </div>
    </div>

    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">পুরাতন তথ্য</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%">
                            <thead>
                                <th>ডকুমেন্ট</th>
                                <th>সর্বশেষ সংশোধন</th>
                            </thead>
                            <tbody id="historyTable">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
{{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </div>
    </div>


        <div class="col-md-12">

            <div class="table-responsive">
                <table id="myDocumentList"
                       class="table table-striped table-bordered"
                       width="100%" cellspacing="0" style="font-size: 14px;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{trans("Documents::messages.doc_name")}}</th>
                        <th>{{trans("Documents::messages.action")}}</th>
                        {{--                                <th>{{trans("Documents::messages.last_updated")}}</th>--}}
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>




    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
<script type="text/javascript" src="{{ asset("assets/plugins/jquery/jquery.min.js") }}"></script>
    @include('partials.datatable-js')
    <script src="{{ asset("assets/scripts/moment.min.js") }}"></script>
    <script language="javascript">
        $(document).ready(function (){
            getDocList();
        })

        function getDocList() {

            $('#myDocumentList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("documents/get-user-documents")}}',
                    method: 'get',
                },
                columns: [
                    {data: 'sl', name: 'sl', searchable: false},
                    {data: 'mergeColumn', name: 'name'},
                    // {data: 'name', name: 'name'},
                    {data: 'action', name: 'action'},
                    // {data: 'updated_at', name: 'updated_at'},

                ],
                "aaSorting": []
            });
        };

        function openModal(btn) {
            //e.preventDefault();
            const this_action = btn.getAttribute('data-action');
            if (this_action != '') {
                $.get(this_action, function (data, success) {
                    if (success === 'success') {
                        $('#myModal .load_modal').html(data);
                    } else {
                        $('#myModal .load_modal').html('Unknown Error!');
                    }
                    $('#myModal').modal('show', {backdrop: 'static'});
                });
            }
        }

        function getHistory(e){
            var user_doc_id = e.value;
            $.ajax({
                url: '{{ url("documents/get-user-document-history") }}',
                type: 'GET',
                dataType: 'json',
                data: {user_doc_id: user_doc_id},
                success: function (response) {
                    // console.log(response);
                    var html = '';

                    $.each(response, function (index, value) {

                        html = '<tr><td><a class="btn btn-primary btn-xs" href="/uploads/'+ value.uploaded_path +'" target="_blank">Open</a></td><td class="">' + moment(value.updated_at).format("DD-MM-YYYY") + '</td></tr>';

                        $('#historyTable').html(html);
                    });
                    $('#historyModal').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
            });
        }

        $('#historyModal').on('hidden.bs.modal', function () {
            $('#historyTable').empty();
        })
    </script>
