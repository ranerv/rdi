<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\UploadedFile;

class ProjectService
{
    public function createProject(array $data): Project
    {
        return Project::create($data);
    }

    public function updateProject(Project $project, array $data): Project
    {
        $project->update($data);
        return $project;
    }

    public function importFromExcel(UploadedFile $file): array
    {
        // Simplified import - extend as needed
        $imported = [];
        $failed = [];

        // Placeholder for Excel import logic using Maatwebsite
        // In production, implement full row-by-row import with validation

        return [
            'imported' => count($imported),
            'failed' => count($failed),
            'messages' => $failed,
        ];
    }

    public function getWithFilters(array $filters): LengthAwarePaginator
    {
        $query = Project::query();

        if ($filters['type'] ?? null) {
            $query->where('type', $filters['type']);
        }

        if ($filters['status'] ?? null) {
            $query->where('status', $filters['status']);
        }

        if ($filters['funding_type'] ?? null) {
            $query->where('funding_type', $filters['funding_type']);
        }

        if ($filters['college_id'] ?? null) {
            $query->byCollege($filters['college_id']);
        }

        if ($filters['search'] ?? null) {
            $query->search($filters['search']);
        }

        return $query
            ->with('department', 'leadProponent', 'budget')
            ->paginate($filters['per_page'] ?? 15, ['*'], 'page', $filters['page'] ?? 1);
    }
}
