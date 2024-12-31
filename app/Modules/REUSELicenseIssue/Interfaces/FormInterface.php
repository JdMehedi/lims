<?php

namespace App\Modules\REUSELicenseIssue\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

interface FormInterface  {

    public function createForm($currentInstance) : string;

    public function storeForm( $request, $currentInstance): RedirectResponse;

    public function viewForm( $processTypeId, $applicationId ): JsonResponse;

    public function editForm( $processTypeId, $applicationId): JsonResponse;
}
