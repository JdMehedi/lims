<?php

namespace App\Modules\REUSELicenseIssue\Models\BPO\surrender;

use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class SurrenderProposalArea extends Model
{
    protected $table = 'call_center_surrender_proposal_area_address';
    protected $guarded = ['id'];


}
