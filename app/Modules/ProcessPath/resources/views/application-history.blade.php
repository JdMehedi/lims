@if (\Illuminate\Support\Facades\Auth::user()->user_type == '1x101')
    <a href="{{ url('/process-path/verify_history/' . $process_list_id) }}" class="btn btn-info float-right"
        target="_blank"><b>Block Chain Verification</b></a>
    <div class="clearfix"></div>
@endif
<div class="table-responsive">
    <table id="app_history_table" class="table table-striped table-bordered display no-margin" style="width: 100%;">
        <thead>
            <tr>
                <th width="10%" class="text-center">On Desk</th>
                <th width="15%">Updated By</th>
                <th width="15%">Status</th>
                <th width="15%">Process Time</th>
                <th width="25%">Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php $sl = 0; ?>
            @forelse($process_history as $key=>$history)
                <?php $sl++; ?>
                <tr>
                    <td class="text-center">{{ $history->deskname }}</td>
                    <td>{{ $history->user_first_name . ' ' . $history->user_middle_name . ' ' . $history->user_last_name }}
                        <?php
                        if (isset($process_history[$key + 1])) {
                            if ($process_history[$key + 1]->deskname != 'Applicant') {
                                echo '[Desk: ' . $process_history[$key + 1]->deskname . ']';
                            } else {
                                echo '[' . $process_history[$key + 1]->deskname . ']';
                            }
                        } else {
                            echo '[Applicant]';
                        }
                        ?>
                    </td>
                    <td>{{ $history->status_name }}</td>
                    <td>{{ date('d-m-Y h:i A', strtotime($history->updated_at)) }}</td>
                    <td>{{ $history->process_desc }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center">No result found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
