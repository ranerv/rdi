<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['ip_application_id', 'certificate_number', 'issued_at', 'expiry_date'])]
class IpCertificate extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function ipApplication(): BelongsTo
    {
        return $this->belongsTo(IpApplication::class);
    }
}
