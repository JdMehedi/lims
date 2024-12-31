<?php

namespace App\Modules\REUSELicenseIssue\Models\BPO\Amendment;

use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ProposalArea extends Model
{
    protected $table = 'call_center_amendment_proposal_area_address';
    protected $guarded = ['id'];
}
