{!! Form::open(['url' => '#process/search', 'method' => 'post', 'id' => '']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2 ">
                <label for="ProcessType">{!! trans('ProcessPath::messages.service') !!}: </label>
                {{-- {!! Form::select('ProcessType', ['' => 'Select One'] + $ProcessType, session('active_process_list'), ['class' => 'form-control search_type']) !!} --}}
                {!! Form::select('ProcessType', ['' => 'Select'] + $ProcessType, '', [
                    'class' => 'form-control search_type',
                ]) !!} {{-- process_type get by javascript --}}
            </div>
            <div class="col-md-2 ">
                <label for="status">{!! trans('ProcessPath::messages.status_') !!}: </label>
                {!! Form::select('status', $status, isset($status_id) ? $status_id : '', [
                    'class' => 'form-control search_status',
                    'id' => 'search_status',
                ]) !!}
            </div>


            @if (!empty($search_by_keyword))
                <?php
                $search_by_keyword_str = explode('@@@', $search_by_keyword);
                ?>

                @if (count($search_by_keyword_str) > 1)
                    {{-- for dashboard search --}}
                    <div class="col-md-3">
                        <label for="search_text">{!! trans('ProcessPath::messages.search_text') !!}:<i class="fa fa-info-circle" aria-hidden="true"
                                data-placement="right" data-toggle="tooltip" title=""
                                data-original-title="Only the application's tracking number and reference data will be searched by the keyword you provided."></i>
                        </label>
                        {!! Form::text('search_text', '', [
                            'class' => 'form-control search_text',
                            'placeholder' => 'Type at least 3 characters',
                        ]) !!}
                    </div>
                @else
                    {{-- for global search --}}
                    <div class="col-md-3">
                        <label for="search_text">{!! trans('ProcessPath::messages.search_text') !!}:<i class="fa fa-info-circle" aria-hidden="true"
                                data-placement="right" data-toggle="tooltip" title=""
                                data-original-title="Only the application's tracking number and reference data will be searched by the keyword you provided."></i>
                        </label>
                        {!! Form::text('search_text', !empty($search_by_keyword) ? $search_by_keyword : '', [
                            'class' => 'form-control search_text',
                            'placeholder' => 'Type at least 3 characters',
                        ]) !!}
                    </div>
                @endif
            @else
                {{-- for global search --}}
                <div class="col-md-2">
                    <label for="search_text">{!! trans('ProcessPath::messages.search_text') !!}:<i class="fa fa-info-circle" aria-hidden="true"
                            data-placement="right" data-toggle="tooltip" title=""
                            data-original-title="Only the application's tracking number and reference data will be searched by the keyword you provided."></i>
                    </label>
                    {!! Form::text('search_text', !empty($search_by_keyword) ? $search_by_keyword : '', [
                        'class' => 'form-control search_text',
                        'placeholder' => 'Type at least 3 characters',
                    ]) !!}
                </div>
            @endif

            <div class="col-md-2">
                <label for="searchTimeLine">{!! trans('ProcessPath::messages.date_within') !!}: </label>
                {!! Form::select('searchTimeLine', $searchTimeLine, '', ['class' => 'form-control search_time']) !!}
            </div>
            <div class="col-md-2">
                <label for="date_within">{!! trans('ProcessPath::messages.of') !!}</label>
                <div class="input-group date" id="dateWithinDP" data-target-input="nearest">
                    {!! Form::text('date_within', '', ['class' => 'form-control search_date date_within']) !!}
                    <div class="input-group-append" data-target="#dateWithinDP" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <label for="">&nbsp;</label><br>
                <input type="button" id="search_process" class="btn btn-primary" value="{!! trans('ProcessPath::messages.search') !!}">
            </div>

        </div>
    </div>
</div>
{!! Form::close() !!}

<div id="list_search" class="" style="margin-top: 20px;">
    <table id="table_search" class="table table-striped table-bordered display" style="width: 100%">
        <thead>
            <tr>
                <th style="width: 15%;">{!! trans('ProcessPath::messages.tracking_no') !!}</th>
                <th>{!! trans('ProcessPath::messages.current_desk') !!}</th>
                <th>{!! trans('ProcessPath::messages.process_type') !!}</th>
                <th style="width: 15%">License Category</th>
                <th style="width: 35%">{!! trans('ProcessPath::messages.reference_data') !!}</th>
                <th>{!! trans('ProcessPath::messages.status_') !!}</th>
                <th>{!! trans('ProcessPath::messages.modified') !!}</th>
                <th>{!! trans('ProcessPath::messages.action') !!}</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@section('footer-script2')
    <script language="javascript">
        $(function() {

            $('#dateWithinDP').datetimepicker({
                viewMode: 'days',
                format: 'DD-MMM-YYYY',
                maxDate: 'now'
            });


            /**
             * Reload 'My Desk' table on change process type
             * @type {jQuery}
             */
            $('.ProcessType').change(function() {
                $.get('{{ route('process.setProcessType') }}', {
                    _token: $('input[name="_token"]').val(),
                    data: $(this).val()
                }, function(data) {
                    if (data == 'success') {
                        table_desk.ajax.reload();
                        // var len = table.length;
                        // for (var i = 0; i < len; i++) {
                        //     table[i].ajax.reload();
                        // }
                    }
                });
            });
            $('#table_search').hide();

            var search_list = '';
            $('#search_process').click(function(e, process_type_id, status_id) {
                // console.log(status_id)
                if (typeof(process_type_id) != "undefined") { //process type selected by trigger
                    $('.search_type').val(process_type_id); // process type selected by js
                }
                if (typeof(status_id) != "undefined") { //process status selected by trigger
                    $('.search_status').val(status_id); // process status selected by js
                }

                $('#table_search').show();

                var searchStatus = '';
                var searchType = '';

                if (typeof(status_id) != "undefined") {
                    searchType = process_type_id;
                    searchStatus = status_id;
                } else {
                    searchType = $('.search_type').val();
                    searchStatus = $('.search_status').val();
                }
                var table_search = $('#table_search').DataTable({
                    destroy: true,
                    iDisplayLength: 25,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    responsive: true,
                    ajax: {
                        url: '{{ route('process.getList') }}',
                        method: 'get',
                        data: function(d) {
                            d.process_search = true;
                            d.search_type = searchType;
                            d.search_time = $('.search_time').val();
                            d.search_text = $('.search_text').val();
                            d.search_date = $('.search_date').val();
                            d.search_status = searchStatus;
                        },
                        dataSrc: function(response) {

                            if (response.responseType == 'single') {
                                window.location.href = response.url;
                            }

                            return response.data;
                        }
                    },
                    columns: [{
                            data: 'tracking_no',
                            name: 'tracking_no',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'user_desk.desk_name',
                            name: 'user_desk.desk_name',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'process_name',
                            name: 'process_name',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'license_json',
                            name: 'license_json',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'json_object',
                            name: 'json_object',
                            orderable: false,
                        },
                        {
                            data: 'process_status.status_name',
                            name: 'process_status.status_name',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    "aaSorting": []
                });
                
                if(searchType == 1){
                    table_search.column(3).visible(true);
                }else{
                    table_search.column(3).visible(false);
                }
            });

            // Global search or dashboard search option

            @if (!empty($search_by_keyword))
                <?php
                $search_by_keyword_str = explode('@@@', $search_by_keyword);
                ?>
                @if (count($search_by_keyword_str) > 1)
                    {{-- for dashboard search --}}
                    var dashboard_search_status_id = '{{ $search_by_keyword_str[1] }}';
                    $("#search_process").trigger('click', [-1000, dashboard_search_status_id]);
                @else
                    {{-- for global search --}}
                    $("#search_process").trigger('click');
                @endif
            @endif

            /**
             * Get process type wise status on change process status from search list
             * @type {jQuery}
             */
            $('.search_type').change(function() {
                $.get('{{ route('process.searchProcessType') }}', {
                    _token: $('input[name="_token"]').val(),
                    data: $(this).val()
                }, function(response) {
                    console.log(response.data)
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function(id, value) {
                            option += '<option value="' + id.trim() + '">' + value +
                                '</option>';
                        });
                    }
                    $('#search_status').html(option);
                });
            });


            function openCity(evt, cityName) {
                var i, tabcontent;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                document.getElementById(cityName).style.display = "block";
                evt.currentTarget.className += " active";

            }

            $('body').on('click', '.statusWiseList', function() {

                $('#list_desk').removeClass('active');
                $('#tab1').removeClass('active');
                $('#tab2').removeClass('active');
                $('#list_delg_desk').removeClass('active');
                $('#tab3').addClass('active');
                $('#list_search').addClass('active');

                var data = $(this).attr("data-id");
                var typeAndStatus = data.split(",");
                var process_type_id = typeAndStatus[0];
                var statusId = typeAndStatus[1];
                var statustext = jQuery.trim( $(this).text());
                if(statustext=='Others'){
                    $("#search_process").trigger('click', [process_type_id,0]);
                    // 104 for cancel button
                }else if(statusId == 104){
                    $("#search_process").trigger('click', [process_type_id, 'bulk_status']);
                }else{
                    $("#search_process").trigger('click', [process_type_id, statusId]);
                }


                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#table_search").offset().top
                }, 1500);

            });


        });

        //current used the code for batch update
        @if (\App\Libraries\CommonFunction::getUserType() == '4x404')
            $('body').on('click', '.common_batch_update_search', function() {
                var current_process_id = $(this).parent().parent().find('.batchInputSearch').val();
                process_id_array = [];
                var id = $('.batchInputSearch').val();
                $('.batchInputSearch').each(function(i, obj) {
                    process_id_array.push(this.value);
                });
                console.log(process_id_array);
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    type: "get",
                    url: "<?php echo url('/'); ?>/process/batch-process-set",
                    async: false,
                    data: {
                        _token: _token,
                        process_id_array: process_id_array,
                        current_process_id: current_process_id,
                    },
                    success: function(response) {
                        if (response.responseType == 'single') {
                            window.location.href = response.url;

                        }
                        if (response.responseType == false) {
                            toastr.error('did not found any data for search list!');
                            return false;
                        }
                    }
                });
            });

            $('body').on('click', '.status_wise_batch_update', function() {
                var current_process_id = $(this).parent().parent().find('.batchInputStatus').val();
                process_id_array = [];

                $('.batchInputStatus').each(function(i, obj) {
                    process_id_array.push(this.value);
                });
                console.log(process_id_array);
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    type: "get",
                    url: "<?php echo url('/'); ?>/process/batch-process-set",
                    async: false,
                    data: {
                        _token: _token,
                        process_id_array: process_id_array,
                        current_process_id: current_process_id,
                    },
                    success: function(response) {
                        if (response.responseType == 'single') {
                            window.location.href = response.url;

                        }
                        if (response.responseType == false) {
                            toastr.error('did not found any data for search list!');
                            return false;
                        }
                    }
                });
            });
        @endif
    </script>
@endsection
