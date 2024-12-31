<?php

namespace App\Modules\REUSELicenseIssue\Models\BPO\renew;

use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class RenewProposalArea extends Model
{
    protected $table = 'call_center_renew_proposal_area_address';
    protected $guarded = ['id'];


}
