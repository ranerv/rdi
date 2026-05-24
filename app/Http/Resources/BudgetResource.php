<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'total_amount' => $this->total_amount,
            'allocated' => $this->allocated,
            'released' => $this->released,
            'spent' => $this->expenditures?->sum('amount') ?? 0,
            'remaining_budget' => $this->remaining_budget,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
