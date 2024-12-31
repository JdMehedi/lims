<div class="card">
    {{--    <div class="card-header"><strong>{{trans("Documents::messages.required_docs_for_attachment")}}</strong></div>--}}
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered custom-responsive-table"
               style="border-top: 1px solid #DEE2E6;">
            <thead>
            <tr>
                <th style="text-align: center;">Serial</th>
                <th style="text-align: center;">Items</th>
                <th style="text-align: center;">Sample File</th>
                <th style="text-align: center;">Attached<span>
                        <span></span>
{{--                        <i title="{{trans("Documents::messages.max_file_size")}} 3MB!"--}}
                        {{--                                                                 data-toggle="tooltip" data-placement="right"--}}
                        {{--                                                                 class="fa fa-question-circle"--}}
                        {{--                                                                 aria-hidden="true"></i>--}}
                    </span></th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1; ?>

            @if(count($document) > 0)

                @foreach($document as $row)
                    <tr>
                        <td data-label="{{trans("Documents::messages.number")}}">
                            <div
                                align="center">{!! $i !!}<?php echo $row->is_required == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                        </td>

                        <td data-label="{{trans("Documents::messages.required_attachment")}}">{!!  $row->doc_name !!}
                            @if($viewMode == 'on')
                            @else
                                <br>
                                <span
                                    style="font-size: 12px;">Maximum file size {{ ($row->doc_max_size > 1023) ? round($row->doc_max_size/1024, 2) .' MB' : $row->doc_max_size.' KB' }}</span>
                            @endif
                        </td>

                        <td>
                            @if($row->simple_file)
                                <a class='btn btn-outline-primary' href='{{ asset($row->simple_file) }}'
                                   target='_blank' download >Download</a>
                            @endif
                        </td>

                        <td data-label="{{trans("Documents::messages.attached_pdf_file")}}">
                            <input name="document_id_<?php echo $row->doc_list_for_service_id; ?>" type="hidden"
                                   value="{{(!empty($row->document_id) ? $row->document_id : '')}}">
                            <input type="hidden" value="{!!  $row->doc_name !!}"
                                   id="doc_name_<?php echo $row->doc_list_for_service_id; ?>"
                                   name="doc_name_<?php echo $row->doc_list_for_service_id; ?>"/>


                            @if($viewMode != 'on')
                                <input name="file<?php echo $row->doc_list_for_service_id; ?>"
                                       <?php if ( empty( $row->uploaded_path ) && $row->is_required == "1" ) {
                                           echo "class='required'";
                                       } ?>
                                       id="file<?php echo $row->doc_list_for_service_id; ?>" type="file" size="20"
                                       accept="application/pdf"
                                       onchange="uploadAppDocument('preview_<?php echo $row->doc_list_for_service_id; ?>', this.id, 'validate_field_<?php echo $row->doc_list_for_service_id; ?>', '<?php echo $row->is_required; ?>', '<?php echo $row->doc_max_size; ?>', '<?php echo $row->doc_min_size; ?>')"/>
{{--                                <label id="label_file<?php echo $row->doc_list_for_service_id; ?>">--}}
{{--                                    <br>--}}
{{--                                    <b style="display: block; max-width: 350px !important; overflow:hidden; text-overflow: ellipsis;">{!! $row->doc_name !!}</b>--}}
{{--                                </label>--}}
                                <a style="display: block;" href="https://www.adobe.com/acrobat/online/jpg-to-pdf.html"
                                   target="_blank"
                                   style="font-size: 12px">{{trans("Documents::messages.click_to_convert")}}</a>
                            @endif
                            <input type="hidden" name="output_pdf_page_count" id="output_pdf_page_count" value="0">
                            {{-- if this document have attachment then show it --}}

                            @if(!empty($row->uploaded_path))
                                <div class="save_file saved_file_{{$row->doc_list_for_service_id}}">
                                    <a target="_blank" class="documentUrl btn btn-xs btn-primary"
                                       href="{{CommonComponent()->fileUrlEncode('/uploads/'.(!empty($row->uploaded_path) ? $row->uploaded_path : ''))}}"
                                       title="{{$row->doc_name}}">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        Open File
                                    </a>
                                    <div id="pdf_show_preview_{{$row->doc_list_for_service_id}}"></div>
                                    <?php if($viewMode != 'on') {?>
                                    {{--                                    <a href="javascript:void(0)"--}}
                                    {{--                                       onclick="removeAttachedFile('{{ $row->doc_list_for_service_id }}', '{{ $row->is_required }}')">--}}
                                    {{--                                                                    <span class="btn btn-xs btn-danger"><i--}}
                                    {{--                                                                                class="fa fa-times"></i></span>--}}
                                    {{--                                    </a>--}}
                                    {{--                                    todo next anable this code--}}
                                    <?php } ?>
                                </div>
                            @endif

                            <div id="preview_<?php echo $row->doc_list_for_service_id; ?>">
                                <input type="hidden"
                                       value="<?php echo !empty($row->uploaded_path) ?
                                           $row->uploaded_path : ''?>"
                                       id="validate_field_<?php echo $row->doc_list_for_service_id; ?>"
                                       name="validate_field_<?php echo $row->doc_list_for_service_id; ?>"
                                       class="<?php echo $row->is_required == "1" ? "required" : '';  ?>"/>
                            </div>
                        </td>
                    </tr>
                    <?php $i ++; ?>
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align: center"><span
                            class="label label-info">{{trans("Documents::messages.no_req_doc")}}</span></td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>


<?php $useragent = $_SERVER['HTTP_USER_AGENT']; ?>
@if($viewMode != 'off' && !\Jenssegers\Agent\Facades\Agent::isMobile())
    {{--    @include('VisaAssistance::doc-tab')--}}
@endif

@if($viewMode == 'on')
    @if(count($document) > 0)
        <div class="panel panel-default hidden-xs">
            <div class="panel-body table-responsive">
                <div id="docTabs" style="margin:10px;">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php $i = 1; ?>
                        @foreach($document as $row)
                            @if(!empty($row->uploaded_path))
                                <li role="presentation" class="<?php if ($i == 1) {
                                    echo 'active';
                                } ?>">
                                    <a href="#tabs{{$i}}" data-toggle="tab">Doc {{$i}}</a>
                                </li>
                            @endif
                            <?php $i ++; ?>
                        @endforeach
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" style="width: 100%;">
                        <?php $i = 1; ?>
                        @foreach($document as $row)
                            @if(!empty($row->uploaded_path))
                                <div role="tabpanel" class="tab-pane <?php if ($i == 1) {
                                    echo 'active';
                                }?>" id="tabs{{$i}}">
                                    @if(!empty($row->uploaded_path))
                                        <h4 style="text-align: left;">{{$row->doc_name}}</h4>
                                        <?php
                                        $fileUrl = public_path() . '/uploads/' . $row->uploaded_path;

                                        if(file_exists( $fileUrl )) {
                                        ?>
                                        <object style="display: block; margin: 0 auto;" width="100%" height="1260"
                                                type="application/pdf"
                                                data="/uploads/<?php echo $row->uploaded_path; ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH"></object>
                                        <?php } else { ?>
                                        <div class="">No such file is existed!</div>
                                        <?php } ?>

                                    @else
                                        <div class="">No file found!</div>
                                    @endif
                                </div>
                            @endif
                            <?php $i ++; ?>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
