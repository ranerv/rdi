<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['college_id', 'name'])]
class Department extends Model
{
    use HasFactory;

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
