<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['project_id', 'total_amount', 'allocated', 'released'])]
class Budget extends Model
{
    use HasFactory;

    protected $appends = ['remaining_budget'];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'allocated' => 'decimal:2',
            'released' => 'decimal:2',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function expenditures(): HasMany
    {
        return $this->hasMany(Expenditure::class);
    }

    public function getRemainingBudgetAttribute(): float
    {
        $totalExpended = $this->expenditures()->sum('amount');
        return floatval($this->total_amount) - floatval($totalExpended);
    }
}
