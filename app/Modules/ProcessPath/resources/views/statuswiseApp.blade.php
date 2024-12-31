<style>
    .statusBox {
        float: left;
        width: 110px;
        margin: 5px 3px;
        height: 100px;
    }

    .statusBox-inner {
        padding: 3px !important;
        font-weight: bold !important;
        height: 100%;
    }
    .font-size {
        font-weight: bold;
        font-size: 13px;
        margin-top: 3px;
        text-align: center;
    }
</style>

@if(!empty($ispData))
<div class="row justify-content-between"  style="border: #28a745 solid 5px;">

<div class="col-sm-2" style="border: #28a745 solid 5px;margin:5px;color:orange">
<h3 class="font-size">Issue ({{$ispData[1]}})</h3>

</div>
<div class="col-sm-2" style="border: #28a745 solid 5px;margin:5px;color:orange">
<h3 class="font-size">Renew ({{$ispData[2]}})</h3>
</div>
<div class="col-sm-2" style="border: #28a745 solid 5px;margin:5px;color:orange">
<h3 class="font-size">Amendment ({{$ispData[3] }}) </h3>
</div>
<div class="col-sm-2" style="border: #28a745 solid 5px;margin:5px;color:orange">
<h3 class="font-size">Cancelled ({{$ispData[4] }}) </h3>
</div>
<div class="col-sm-2" style="border: #28a745 solid 5px;margin:5px;color:orange">
<h3 class="font-size">Total ({{$ispData[5]}})</h3>
</div>

</div>
@endif

@if ($status_wise_apps) {{-- Desk Officers --}}
    @foreach ($status_wise_apps as $key => $row)
        @break($loop->index === 6)
        @php($totalApplicationCount = !empty($row['totalApplication']) ? $row['totalApplication'] : '0')
        <div class="statusBox {{ $totalApplicationCount!=0?'statusWiseList':''}}">
            <div class="card statusBox-inner "
                style="display:block; {{ $row['color'] }}; border: 1px solid {{ $row['color'] }} !important;">
                <a href="#" class="{{ $totalApplicationCount!=0?'statusWiseList':''}}" data-id="{{ $row['process_type_id'] . ',' . $row['id'] }}"
                    style="background: {{ $row['color'] }}">
                    <div class="card-header"
                        style="background: {{ $row['color'] }};color: white; padding: 10px 5px !important; alignment-adjust: central;height: 100%"
                        title="{{ !empty($row['status_name']) ? $row['status_name'] : 'N/A' }}">

                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;font-weight: bold"
                                    id="{{ !empty($row['status_name']) ? $row['status_name'] : 'N/A' }}">
                                    {{ $totalApplicationCount }}

                                </div>
                            </div>
                        </div>

                        <div class="row" style=" text-decoration: none !important">
                            <div class="col-12 text-center">
                                <div class="h3"
                                    style="margin-top:0;margin-bottom:0;font-size:13px; font-weight: bold">
                                    {{ !empty($row['status_name']) ? $row['status_name'] : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
    <div class="statusBox">
        <div class="card statusBox-inner"
             style="display:block; border: 1px solid #1c9d50 !important;">
            <a href="javascript:void(0)" class=""
               style="background: #1c9d50">
                <div class="card-header" style="background: #1c9d50;color: white; padding: 10px 5px !important; alignment-adjust: central;height: 100%">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;font-weight: bold">
                                0
                            </div>
                        </div>
                    </div>
                    <div class="row" style=" text-decoration: none !important">
                        <div class="col-12 text-center">
                            <div class="h3"
                                 style="margin-top:0;margin-bottom:0;font-size:13px; font-weight: bold">
                                Annual Fee
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
<div class="statusBox statusWiseList">
    <div class="card statusBox-inner"
         style="display:block; border: 1px solid #1c9d50 !important;">
        <a href="javascript:void(0)" class="statusWiseList" data-id="{{ $row['process_type_id'] . ',0'  }}"
           style="background: #1c9d50">
            <div class="card-header" style="background: #1c9d50;color: white; padding: 10px 5px !important; alignment-adjust: central;height: 100%; ">
                <div class="row">
                    <div class="col-12 text-center">

                        <div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;font-weight: bold">
                            {{ $othersData}}
                        </div>
                    </div>
                </div>
                <div class="row" style=" text-decoration: none !important">
                    <div class="col-12 text-center">
                        <div class="h3"
                             style="margin-top:0;margin-bottom:0;font-size:13px; font-weight: bold">
                            Others
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>

<div class="statusBox">
    <div class="card statusBox-inner"
         style="display:block; border: 1px solid #1c9d50 !important;">
{{--                data-id="{{ $row['process_type_id'] . ',' . '100' }}" Here 100 = bulk_status--}}
        <a href="#" class="{{ $bulkData!=0?'statusWiseList':''}}" data-id="{{ $row['process_type_id'] . ',' . '100' }}" style="background: #1c9d50">
            <div class="card-header" style="background: #28a745;color: white; padding: 10px 5px !important; alignment-adjust: central;height: 100%">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;font-weight: bold">
                            {{ $bulkData }}
                        </div>
                    </div>
                </div>
                <div class="row" style=" text-decoration: none !important">
                    <div class="col-12 text-center">
                        <div class="h3"
                             style="margin-top:0;margin-bottom:0;font-size:13px; font-weight: bold">
                            Approved Bulk License
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>


@if(in_array($row['process_type_id'], [4, 8]))
    <div class="statusBox">
        <div class="card statusBox-inner"
                style="display:block; border: 1px solid #1c9d50 !important;">
            <a href="#" class="{{ $cancelData!=0?'statusWiseList':''}}" data-id="{{ $row['process_type_id'] . ',' . '104' }}" style="background: #1c9d50">
                <div class="card-header" style="background: #c10f0f;color: white; padding: 10px 5px !important; alignment-adjust: central;height: 100%">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;font-weight: bold">
                                    {{ $cancelData }}
                            </div>
                        </div>
                    </div>
                    <div class="row" style=" text-decoration: none !important">
                        <div class="col-12 text-center">
                            <div class="h3"
                                    style="margin-top:0;margin-bottom:0;font-size:13px; font-weight: bold">
                                Cancelled
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endif



@endif {{-- checking not empty $appsInDesk --}}
