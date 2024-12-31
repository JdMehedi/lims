<?php

namespace App\Modules\SonaliPayment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\SonaliPayment\Models\IpnRequest;
use App\Modules\SonaliPayment\Models\IpnRequestHistory;
use yajra\Datatables\Datatables;

class IpnController extends Controller
{
    public function ipnList()
    {
        return view('SonaliPayment::ipn.ipn-list');
    }

    public function getIpnList()
    {
        $ipn = IpnRequest::where('is_archive', 0)->orderBy('id', 'desc')->get();
        return Datatables::of($ipn)
            ->addColumn('action', function ($ipn) {
                return '<a href="' . url('ipn/ipn-history/' . Encryption::encodeId($ipn->id)) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Open</a>';
            })
            ->editColumn('is_authorized_request', function ($ipn) {
                if ($ipn->is_authorized_request == 1) {
                    return "<label class='btn btn-xs btn-success'>Valid</label>";
                } else {
                    return "<label class='btn btn-xs btn-danger'>Wrong</label>";
                }
            })
            ->rawColumns(['is_authorized_request', 'action'])
            ->make(true);
    }

    public function ipnHistory($id)
    {
        $ipn_history = IpnRequestHistory::where('sp_ipn_request_id', Encryption::decodeId($id))->get();

        return view('SonaliPayment::ipn.ipn-history-list', compact('ipn_history'));
    }
}
