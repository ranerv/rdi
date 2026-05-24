<?php

namespace App\Services;

use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Http\Response;

class ReportService
{
    public function generateProjectsPdf(Collection $projects): Response
    {
        $pdf = Pdf::loadView('reports.projects', [
            'projects' => $projects,
            'generatedAt' => now(),
        ]);

        return $pdf->download('projects-report-' . date('Y-m-d') . '.pdf');
    }

    public function generateProjectsExcel(Collection $projects): Response
    {
        return Excel::download(
            new ProjectsExport($projects),
            'projects-report-' . date('Y-m-d') . '.xlsx'
        );
    }
}

class ProjectsExport implements \Maatwebsite\Excel\Concerns\FromCollection
{
    private Collection $projects;

    public function __construct(Collection $projects)
    {
        $this->projects = $projects;
    }

    public function collection(): Collection
    {
        return $this->projects->map(fn($p) => [
            'Title' => $p->title,
            'Code' => $p->project_code,
            'Type' => $p->type,
            'Status' => $p->status,
            'Budget' => $p->approved_budget,
            'Lead' => $p->leadProponent->name ?? '',
            'Department' => $p->department->name ?? '',
        ]);
    }
}
