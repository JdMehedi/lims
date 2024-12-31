<style>
    /*down-up arrow*/
    .down_up_arrow::after {
        font-family: "FontAwesome";
        content: "\f107";
        float: right;
        transition: all 1s
    }

    .down_up_arrow[aria-expanded="true"]::after {
        content: "\f106";
    }

    /*down-up arrow*/
</style>

<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table basicDataTable" style="margin-bottom: 0px;font-size:16px;">
                <tbody>
                @if($notice_tab->isNotEmpty())
                    @foreach($notice_tab as $key => $notice)
                        <?php
                        if ($key === 0) {
                            $class = 'collapse in';
                        } else {
                            $class = 'collapse';
                        }
                        ?>
                        <tr>
                            <td width="150px">
                                <span style="font-size: 13px;">{{ CommonFunction::changeDateFormat(substr($notice->update_date, 0, 10)) }}</span><br>
                                <span><a class="down_up_arrow" style="cursor:pointer; font-size: 14px; display: block;"
                                         data-toggle="collapse"
                                         data-target="#notice_<?php echo $key; ?>"><b>{{ $notice->heading }}</b></a></span>
                                <div style="font-size: 14px" id="notice_<?php echo $key; ?>" class="{{$class}}">
                                    {!!  $notice->details !!}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>There have no recent notice.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

