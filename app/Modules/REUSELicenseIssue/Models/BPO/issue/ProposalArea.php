<?php

namespace App\Modules\REUSELicenseIssue\Models\BPO\issue;

use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ProposalArea extends Model
{
    protected $table = 'call_center_issue_proposal_area_address';
    protected $guarded = ['id'];


}
