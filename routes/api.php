<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\ExpenditureController;
use App\Http\Controllers\Api\MonitoringReportController;
use App\Http\Controllers\Api\IpApplicationController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CollegeController;

Route::prefix('api')->group(function () {
    // Public Auth Routes
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['api']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->withoutMiddleware(['api']);
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->withoutMiddleware(['api']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);

        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('', [NotificationController::class, 'index']);
            Route::patch('{notification}/read', [NotificationController::class, 'markRead']);
            Route::patch('read-all', [NotificationController::class, 'markAllRead']);
            Route::delete('{notification}', [NotificationController::class, 'destroy']);
        });

        // Projects - Main Resource
        Route::apiResource('projects', ProjectController::class);
        Route::post('projects/import-excel', [ProjectController::class, 'importExcel']);

        // Nested routes for budgets and expenditures
        Route::prefix('projects/{project}')->group(function () {
            Route::apiResource('budgets', BudgetController::class, ['except' => ['index', 'destroy']]);
            Route::get('budgets', [BudgetController::class, 'index']);
            Route::delete('budgets/{budget}', [BudgetController::class, 'destroy']);

            // Documents
            Route::get('documents', [DocumentController::class, 'index']);
            Route::post('documents', [DocumentController::class, 'store']);
        });

        // Budgets budgets expenditures
        Route::prefix('budgets/{budget}/expenditures')->group(function () {
            Route::get('', [ExpenditureController::class, 'index']);
            Route::post('', [ExpenditureController::class, 'store']);
        });
        Route::delete('expenditures/{expenditure}', [ExpenditureController::class, 'destroy']);

        // Documents
        Route::delete('documents/{document}', [DocumentController::class, 'destroy']);

        // Monitoring Reports
        Route::apiResource('monitoring-reports', MonitoringReportController::class);
        Route::post('monitoring-reports/{monitoringReport}/submit', [MonitoringReportController::class, 'submit']);

        // IP Applications
        Route::apiResource('ip-applications', IpApplicationController::class);
        Route::patch('ip-applications/{ipApplication}/status', [IpApplicationController::class, 'updateStatus']);

        // Super Admin Only Routes
        Route::middleware('role:super-admin')->group(function () {
            // Users Management
            Route::apiResource('users', UserController::class);

            // Colleges Management
            Route::apiResource('colleges', CollegeController::class);

            // Analytics
            Route::prefix('analytics')->group(function () {
                Route::get('overview', [AnalyticsController::class, 'overview']);
                Route::get('budget', [AnalyticsController::class, 'budget']);
                Route::get('faculty', [AnalyticsController::class, 'faculty']);
                Route::get('quarterly', [AnalyticsController::class, 'quarterly']);
                Route::get('map', [AnalyticsController::class, 'map']);
            });

            // Reports
            Route::prefix('reports')->group(function () {
                Route::get('pdf', [ReportController::class, 'exportPdf']);
                Route::get('excel', [ReportController::class, 'exportExcel']);
            });
        });

        // RDI Staff Routes
        Route::middleware('role:rdi-staff|super-admin')->group(function () {
            Route::get('analytics/overview', [AnalyticsController::class, 'overview']);
            Route::get('analytics/budget', [AnalyticsController::class, 'budget']);
            Route::get('analytics/faculty', [AnalyticsController::class, 'faculty']);
            Route::get('reports/pdf', [ReportController::class, 'exportPdf']);
            Route::get('reports/excel', [ReportController::class, 'exportExcel']);
        });

        // Full Analytics & Reports for authorized roles
        Route::prefix('analytics')->group(function () {
            Route::get('overview', [AnalyticsController::class, 'overview']);
            Route::get('budget', [AnalyticsController::class, 'budget']);
            Route::get('faculty', [AnalyticsController::class, 'faculty']);
            Route::get('quarterly', [AnalyticsController::class, 'quarterly']);
            Route::get('map', [AnalyticsController::class, 'map']);
        });
    });
});
