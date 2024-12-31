@if($mode === 'add')
    <div class="card card-magenta border border-magenta">
        <div  class="card-header">
            Required Documents For Attachment
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <input type="hidden" id="doc_type_key" name="doc_type_key">
            <div id="docListDiv"></div>
        </div>
    </div>
@elseif($mode === 'edit')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Required Documents
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <input type="hidden" id="doc_type_key" name="doc_type_key">
            <div id="docListDiv"></div>
        </div>
    </div>
@elseif($mode === 'view')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Required Documents
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="table-responsive" id="">
                <table class="table-bordered table-striped" style="width: 100%;">
                    <thead>
                    <th style="padding: 10px;">Document Name</th>
                    <th style="padding: 10px;">File</th>
                    </thead>
                    <tbody>
                    @if(count($appDynamicDocInfo) > 0)
                        @foreach($appDynamicDocInfo as $docInfo)
                            <tr>
                                @if($docInfo->uploaded_path)
                                    <td style="padding: 10px;">{{$docInfo->doc_name}}</td>
                                    <td style="padding: 10px;" ><a target="_blank" href="{{url('/').'/uploads/'.$docInfo->uploaded_path}}">View</a> </td>
                                @else
                                    <td style="padding: 10px;">{{$docInfo->doc_name}}</td>
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2" style="text-align: center;">No Data found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<script>
    getHelpText();
    @if(in_array($mode, ['add', 'edit']))
        $(document).ready(function() {
            (function attachmentLoad() {
                var reg_type_id = parseInt($("#reg_type_id").val()); //order 1
                var company_type_id = parseInt($("#company_type_id").val()); //order 2
                var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
                var investment_type_id = parseInt($("#investment_type_id").val()); //order 4
                var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                    investment_type_id;
                $("#doc_type_key").val(key);
                loadApplicationDocs('docListDiv', null);
            })();
        });
    @else
        var reg_type_id = "{{ $appInfo->regist_type }}";
        var company_type_id = "{{ $appInfo->org_type }}";
        var industrial_category_id = "{{ $appInfo->ind_category_id }}";
        var investment_type_id = "{{ $appInfo->invest_type }}";
        var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' + investment_type_id;

        loadApplicationDocs('docListDiv', key);
    @endif
</script>
