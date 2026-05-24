<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'department_id',
    'lead_proponent_id',
    'title',
    'project_code',
    'type',
    'status',
    'funding_type',
    'approved_budget',
    'objectives',
    'start_date',
    'end_date',
    'latitude',
    'longitude',
    'location_description',
])]
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'approved_budget' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function leadProponent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lead_proponent_id');
    }

    public function projectMembers(): HasMany
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members');
    }

    public function budget(): HasOne
    {
        return $this->hasOne(Budget::class);
    }

    public function monitoringReports(): HasMany
    {
        return $this->hasMany(MonitoringReport::class);
    }

    public function uploadedDocuments(): HasMany
    {
        return $this->hasMany(UploadedDocument::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function presentations(): HasMany
    {
        return $this->hasMany(Presentation::class);
    }

    public function ipApplications(): HasMany
    {
        return $this->hasMany(IpApplication::class);
    }

    // Scopes
    public function scopeByType(Builder $query, $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus(Builder $query, $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByFundingType(Builder $query, $fundingType): Builder
    {
        return $query->where('funding_type', $fundingType);
    }

    public function scopeByCollege(Builder $query, $collegeId): Builder
    {
        return $query->whereHas('department', fn($q) => $q->where('college_id', $collegeId));
    }

    public function scopeSearch(Builder $query, $search): Builder
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('project_code', 'like', "%{$search}%");
    }
}
