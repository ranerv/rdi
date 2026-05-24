<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            'super-admin' => 'Super Admin (RDI Director)',
            'rdi-staff' => 'RDI Staff',
            'planning-officer' => 'Planning Officer / Project Monitoring Officer',
            'ipophl-staff' => 'IPOPHL Staff',
            'proponent' => 'Research Proponent',
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate(['name' => $name]);
        }

        // Create permissions
        $permissions = [
            // Projects
            'view-projects' => 'View Projects',
            'create-projects' => 'Create Projects',
            'edit-projects' => 'Edit Projects',
            'delete-projects' => 'Delete Projects',
            'import-projects' => 'Import Projects from Excel',

            // Budgets
            'view-budgets' => 'View Budgets',
            'create-budgets' => 'Create Budgets',
            'edit-budgets' => 'Edit Budgets',
            'delete-budgets' => 'Delete Budgets',

            // Expenditures
            'view-expenditures' => 'View Expenditures',
            'record-expenditures' => 'Record Expenditures',

            // Monitoring Reports
            'view-monitoring-reports' => 'View Monitoring Reports',
            'create-monitoring-reports' => 'Create Monitoring Reports',
            'edit-monitoring-reports' => 'Edit Monitoring Reports',
            'submit-monitoring-reports' => 'Submit Monitoring Reports',

            // IP Applications
            'view-ip-applications' => 'View IP Applications',
            'create-ip-applications' => 'Create IP Applications',
            'review-ip-applications' => 'Review IP Applications',
            'approve-ip-applications' => 'Approve IP Applications',

            // Documents
            'view-documents' => 'View Documents',
            'upload-documents' => 'Upload Documents',
            'delete-documents' => 'Delete Documents',

            // Analytics & Reports
            'view-analytics' => 'View Analytics Dashboard',
            'export-reports' => 'Export Reports',
            'generate-pdf-reports' => 'Generate PDF Reports',
            'generate-excel-reports' => 'Generate Excel Reports',

            // User Management
            'view-users' => 'View Users',
            'create-users' => 'Create Users',
            'edit-users' => 'Edit Users',
            'delete-users' => 'Delete Users',

            // Notifications
            'view-notifications' => 'View Notifications',
            'manage-notifications' => 'Manage Notifications',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        // Assign permissions to roles
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $rdiStaffRole = Role::where('name', 'rdi-staff')->first();
        $planningOfficerRole = Role::where('name', 'planning-officer')->first();
        $ipophlRole = Role::where('name', 'ipophl-staff')->first();
        $proponentRole = Role::where('name', 'proponent')->first();

        // Super Admin - All permissions
        $superAdminRole->syncPermissions(Permission::all());

        // RDI Staff
        $rdiStaffRole->syncPermissions([
            'view-projects', 'create-projects', 'edit-projects', 'import-projects',
            'view-budgets', 'create-budgets', 'edit-budgets',
            'view-expenditures', 'record-expenditures',
            'view-monitoring-reports', 'create-monitoring-reports', 'edit-monitoring-reports',
            'view-documents', 'upload-documents',
            'view-analytics', 'export-reports', 'generate-pdf-reports', 'generate-excel-reports',
            'view-notifications',
        ]);

        // Planning Officer
        $planningOfficerRole->syncPermissions([
            'view-projects',
            'view-monitoring-reports', 'create-monitoring-reports', 'edit-monitoring-reports', 'submit-monitoring-reports',
            'view-documents',
            'view-analytics',
            'view-notifications', 'manage-notifications',
        ]);

        // IPOPHL Staff
        $ipophlRole->syncPermissions([
            'view-projects',
            'view-ip-applications', 'review-ip-applications', 'approve-ip-applications',
            'view-documents',
            'view-notifications',
        ]);

        // Proponent
        $proponentRole->syncPermissions([
            'view-projects',
            'view-budgets',
            'view-monitoring-reports',
            'create-ip-applications', 'view-ip-applications',
            'view-documents', 'upload-documents',
            'view-notifications',
        ]);
    }
}
