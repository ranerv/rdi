<?php

namespace App\Services;

use App\Models\IpApplication;
use App\Models\IpCertificate;

class IpApplicationService
{
    public function updateStatus(IpApplication $application, string $status, ?string $remarks = null): void
    {
        $application->update([
            'status' => $status,
            'remarks' => $remarks ?? $application->remarks,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // If approved and no certificate exists, create one
        if ($status === 'approved' && !$application->ipCertificate) {
            $this->issueCertificate($application);
        }
    }

    public function issueCertificate(IpApplication $application): IpCertificate
    {
        $certificateNumber = 'IP-' . date('Y') . '-' . str_pad($application->id, 5, '0', STR_PAD_LEFT);

        return IpCertificate::create([
            'ip_application_id' => $application->id,
            'certificate_number' => $certificateNumber,
            'issued_at' => now(),
        ]);
    }
}
