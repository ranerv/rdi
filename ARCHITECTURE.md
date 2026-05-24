# CRMS System Architecture & Documentation

## 🏗️ Complete System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           END USERS (Internet)                              │
└─────────────────────────────────────────────────────────────────────────────┘
                                    ▼
         ┌──────────────────────────────────────────────────┐
         │         React Frontend (Port 5173)               │
         │  ┌─────────────────────────────────────────── ┐  │
         │  │  Login | Dashboard | Projects | Reports   │  │
         │  │  Analytics | Monitoring | IP Mgmt         │  │
         │  └─────────────────────────────────────────── ┘  │
         │         Tailwind CSS | React Router              │
         └──────────────────────────────────────────────────┘
                           ▼ Axios (HTTP)
         ┌──────────────────────────────────────────────────┐
         │    REST API Gateway (Port 8000)                  │
         │    Laravel Route Layer (/api/...)                │
         └──────────────────────────────────────────────────┘
                           ▼
         ┌──────────────────────────────────────────────────┐
         │         Laravel Application Layer                │
         │  ┌──────────────────────────────────────────┐   │
         │  │  Controllers (Request Handling)          │   │
         │  │  - AuthController                        │   │
         │  │  - ProjectController                     │   │
         │  │  - BudgetController                      │   │
         │  │  - AnalyticsController                   │   │
         │  │  - IpApplicationController               │   │
         │  │  - MonitoringReportController            │   │
         │  │  - UserController                        │   │
         │  └──────────────────────────────────────────┘   │
         │  ┌──────────────────────────────────────────┐   │
         │  │  Services (Business Logic)                │   │
         │  │  - ProjectService                        │   │
         │  │  - AnalyticsService                      │   │
         │  │  - NotificationService                   │   │
         │  │  - IpApplicationService                  │   │
         │  │  - ReportService                         │   │
         │  └──────────────────────────────────────────┘   │
         │  ┌──────────────────────────────────────────┐   │
         │  │  Authentication & Authorization          │   │
         │  │  - Sanctum (Token-based auth)            │   │
         │  │  - Spatie RBAC (Role-Based Control)      │   │
         │  │  - Policies (Authorization)              │   │
         │  └──────────────────────────────────────────┘   │
         └──────────────────────────────────────────────────┘
                           ▼
         ┌──────────────────────────────────────────────────┐
         │         Eloquent ORM & Models                    │
         │  User | Project | Budget | Department | etc.    │
         │  (17 models representing all entities)           │
         └──────────────────────────────────────────────────┘
                           ▼
         ┌──────────────────────────────────────────────────┐
         │         MySQL Database (crms_dssc)               │
         │  ┌──────────────────────────────────────────┐   │
         │  │ 17 Tables:                               │   │
         │  │ • users, roles, permissions              │   │
         │  │ • colleges, departments                  │   │
         │  │ • projects, project_members              │   │
         │  │ • budgets, expenditures                  │   │
         │  │ • monitoring_reports                     │   │
         │  │ • ip_applications, ip_certificates       │   │
         │  │ • uploaded_documents                     │   │
         │  │ • notifications, activity_logs           │   │
         │  └──────────────────────────────────────────┘   │
         └──────────────────────────────────────────────────┘
```

## 📊 Data Flow Diagram

### User Authentication Flow
```
1. User enters credentials on React Login page
   ↓
2. React sends POST /api/login via Axios
   ↓
3. AuthController.login() validates credentials
   ↓
4. Laravel Sanctum issues API token
   ↓
5. Token sent back to React frontend
   ↓
6. React stores token in localStorage
   ↓
7. Axios adds Authorization header to all requests
   ↓
8. Protected routes use middleware to verify token
```

### Project Creation Flow
```
1. User (RDI Staff) fills project form on React
   ↓
2. React validates form data with Zod
   ↓
3. React sends POST /api/projects with token
   ↓
4. Laravel route middleware checks auth:sanctum
   ↓
5. ProjectController.store() receives request
   ↓
6. ProjectPolicy.create() checks authorization
   ↓
7. StoreProjectRequest validates input
   ↓
8. ProjectService.createProject() handles logic
   ↓
9. Project model saves to database
   ↓
10. ActivityLogger middleware logs action
    ↓
11. ProjectResource transforms data
    ↓
12. API returns JSON response with ProjectResource
    ↓
13. React updates state and displays success
```

### Analytics Generation Flow
```
1. Admin opens Analytics Dashboard
   ↓
2. React calls getAnalyticsOverview()
   ↓
3. Axios sends GET /api/analytics/overview
   ↓
4. AnalyticsController.overview() called
   ↓
5. Role middleware verifies super-admin role
   ↓
6. AnalyticsService.overview() queries database:
   - COUNT(*) projects by type/status
   - SUM budgets & expenditures
   - JOIN to get completion rates
   ↓
7. Returns aggregated data as JSON
   ↓
8. React receives data
   ↓
9. Recharts renders charts with data
   ↓
10. StatCards display KPIs
```

## 🔒 Security & Authorization Architecture

### Authentication Layer
```
Public Access
  ├─ POST /login           (no auth required)
  ├─ POST /forgot-password (no auth required)
  └─ POST /reset-password  (no auth required)

Protected Access (auth:sanctum middleware)
  ├─ All other endpoints require valid token
  └─ Token checked against users and personal_access_tokens tables
```

### Authorization Layer (Spatie RBAC)
```
Super Admin Role
  ├─ Permissions: ALL (all permissions granted)
  └─ Can: manage users, approve all, export reports

RDI Staff Role
  ├─ Permissions: create-projects, edit-projects, view-analytics, etc.
  └─ Can: create projects, manage budgets, view reports

Planning Officer Role
  ├─ Permissions: create-monitoring-reports, manage-notifications
  └─ Can: submit quarterly reports, notify proponents

IPOPHL Staff Role
  ├─ Permissions: review-ip-applications, approve-ip-applications
  └─ Can: review and approve IP applications

Proponent Role
  ├─ Permissions: view-projects, upload-documents, create-ip-applications
  └─ Can: view own projects, upload files, submit IP apps
```

### Policy Authorization
```
ProjectPolicy
  └─ viewAny: authenticated users
  └─ view: authenticated users
  └─ create: super-admin, rdi-staff
  └─ update: super-admin, rdi-staff, or project lead
  └─ delete: super-admin only

IpApplicationPolicy
  └─ viewAny: authenticated users
  └─ view: authenticated users
  └─ create: proponent, rdi-staff, super-admin
  └─ updateStatus: ipophl-staff, super-admin
  └─ delete: super-admin only
```

## 📁 Complete File Structure

### Backend (`app/`)
```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php
│   │   ├── ProjectController.php
│   │   ├── BudgetController.php
│   │   ├── ExpenditureController.php
│   │   ├── MonitoringReportController.php
│   │   ├── IpApplicationController.php
│   │   ├── DocumentController.php
│   │   ├── AnalyticsController.php
│   │   ├── NotificationController.php
│   │   ├── UserController.php
│   │   ├── CollegeController.php
│   │   └── ReportController.php
│   ├── Requests/
│   │   ├── LoginRequest.php
│   │   ├── StoreProjectRequest.php
│   │   ├── UpdateProjectRequest.php
│   │   ├── StoreBudgetRequest.php
│   │   ├── UpdateBudgetRequest.php
│   │   ├── StoreExpenditureRequest.php
│   │   ├── StoreMonitoringReportRequest.php
│   │   ├── StoreIpApplicationRequest.php
│   │   ├── UpdateIpStatusRequest.php
│   │   ├── StoreDocumentRequest.php
│   │   ├── StoreUserRequest.php
│   │   ├── UpdateUserRequest.php
│   │   ├── StoreCollegeRequest.php
│   │   └── UpdateCollegeRequest.php
│   ├── Resources/
│   │   ├── ProjectResource.php
│   │   ├── ProjectCollection.php
│   │   ├── BudgetResource.php
│   │   ├── UserResource.php
│   │   ├── IpApplicationResource.php
│   │   ├── MonitoringReportResource.php
│   │   ├── NotificationResource.php
│   │   ├── ExpenditureResource.php
│   │   ├── DocumentResource.php
│   │   ├── IpCertificateResource.php
│   │   ├── DepartmentResource.php
│   │   └── CollegeResource.php
│   └── Middleware/
│       └── ActivityLogger.php
├── Models/
│   ├── User.php
│   ├── College.php
│   ├── Department.php
│   ├── Project.php
│   ├── ProjectMember.php
│   ├── Budget.php
│   ├── Expenditure.php
│   ├── MonitoringReport.php
│   ├── IpApplication.php
│   ├── IpCertificate.php
│   ├── Publication.php
│   ├── Presentation.php
│   ├── UploadedDocument.php
│   ├── Notification.php
│   └── ActivityLog.php
├── Services/
│   ├── ProjectService.php
│   ├── AnalyticsService.php
│   ├── NotificationService.php
│   ├── ReportService.php
│   └── IpApplicationService.php
└── Policies/
    ├── ProjectPolicy.php
    └── IpApplicationPolicy.php

database/
├── migrations/ (15 migration files)
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── RolesPermissionsSeeder.php
│   ├── CollegeDepartmentSeeder.php
│   └── UserSeeder.php
└── factories/
    └── UserFactory.php

routes/
├── api.php (comprehensive API routes)
└── web.php (web routes)

config/
├── sanctum.php (auth config)
└── permission.php (RBAC config)
```

### Frontend (`resources/js/`)
```
resources/
├── js/
│   ├── api/
│   │   ├── axios.js
│   │   ├── auth.js
│   │   ├── projects.js
│   │   └── analytics.js
│   ├── context/
│   │   └── AuthContext.jsx
│   ├── hooks/
│   │   └── useAuth.js
│   ├── routes/
│   │   ├── AppRouter.jsx
│   │   └── ProtectedRoute.jsx
│   ├── components/
│   │   ├── common/
│   │   │   ├── StatCard.jsx
│   │   │   └── StatusBadge.jsx
│   │   ├── charts/
│   │   └── maps/
│   ├── pages/
│   │   ├── auth/
│   │   │   ├── Login.jsx
│   │   │   └── ForgotPassword.jsx
│   │   ├── admin/
│   │   │   └── Dashboard.jsx
│   │   ├── rdi/
│   │   │   └── Dashboard.jsx
│   │   ├── monitoring/
│   │   │   └── Dashboard.jsx
│   │   ├── ipophl/
│   │   │   └── Dashboard.jsx
│   │   └── proponent/
│   │       └── Dashboard.jsx
│   ├── utils/
│   │   ├── constants.js
│   │   └── formatters.js
│   ├── app.jsx
│   └── index.css
└── css/
    └── app.css
```

## 🔄 API Endpoints Reference

### Authentication (`/api`)
- `POST /login` - User login
- `POST /logout` - User logout
- `GET /me` - Get authenticated user
- `POST /forgot-password` - Request password reset
- `POST /reset-password` - Reset password

### Projects (`/api/projects`)
- `GET /projects` - List projects (with filters)
- `POST /projects` - Create project
- `GET /projects/{id}` - Get project details
- `PUT /projects/{id}` - Update project
- `DELETE /projects/{id}` - Delete project
- `POST /projects/import-excel` - Import from Excel

### Budgets (`/api/budgets`)
- `GET /projects/{project}/budgets` - Get project budget
- `POST /projects/{project}/budgets` - Create/update budget
- `PUT /budgets/{budget}` - Update budget
- `DELETE /budgets/{budget}` - Delete budget
- `GET /budgets/{budget}/expenditures` - List expenditures
- `POST /budgets/{budget}/expenditures` - Record expenditure
- `DELETE /expenditures/{expenditure}` - Remove expenditure

### Monitoring Reports (`/api/monitoring-reports`)
- `GET /monitoring-reports` - List reports
- `POST /monitoring-reports` - Create report
- `GET /monitoring-reports/{id}` - Get report details
- `PUT /monitoring-reports/{id}` - Update report
- `POST /monitoring-reports/{id}/submit` - Submit report

### IP Applications (`/api/ip-applications`)
- `GET /ip-applications` - List IP applications
- `POST /ip-applications` - Submit application
- `GET /ip-applications/{id}` - Get application details
- `PUT /ip-applications/{id}` - Update application
- `PATCH /ip-applications/{id}/status` - Update status
- `DELETE /ip-applications/{id}` - Delete application

### Documents (`/api/documents`)
- `GET /projects/{project}/documents` - List project documents
- `POST /projects/{project}/documents` - Upload document
- `DELETE /documents/{document}` - Delete document

### Analytics (`/api/analytics`)
- `GET /analytics/overview` - Dashboard KPIs
- `GET /analytics/budget` - Budget data
- `GET /analytics/faculty` - College involvement
- `GET /analytics/quarterly` - Quarterly progress
- `GET /analytics/map` - Geographic project data

### Notifications (`/api/notifications`)
- `GET /notifications` - List user notifications
- `PATCH /notifications/{id}/read` - Mark as read
- `PATCH /notifications/read-all` - Mark all as read
- `DELETE /notifications/{id}` - Delete notification

### Users (`/api/users`)
- `GET /users` - List users (Super Admin only)
- `POST /users` - Create user (Super Admin only)
- `GET /users/{id}` - Get user details
- `PUT /users/{id}` - Update user (Super Admin only)
- `DELETE /users/{id}` - Delete user (Super Admin only)

### Colleges (`/api/colleges`)
- `GET /colleges` - List colleges (Super Admin only)
- `POST /colleges` - Create college (Super Admin only)
- `GET /colleges/{id}` - Get college details
- `PUT /colleges/{id}` - Update college (Super Admin only)
- `DELETE /colleges/{id}` - Delete college (Super Admin only)

### Reports (`/api/reports`)
- `GET /reports/pdf` - Export PDF report (RDI Staff+)
- `GET /reports/excel` - Export Excel report (RDI Staff+)

## 📈 Database Relationships

```
users (1) ---> (many) projects (as lead proponent)
users (many) <---> (many) projects (project members via project_members)
users (1) ---> (many) monitoring_reports (as officer)
users (1) ---> (many) ip_applications (as applicant)
users (1) ---> (many) ip_applications (as reviewer)
users (1) ---> (many) uploaded_documents (as uploader)
users (1) ---> (many) notifications
users (1) ---> (many) activity_logs

colleges (1) ---> (many) users
colleges (1) ---> (many) departments

departments (1) ---> (many) projects

projects (1) ---> (one) budget
projects (1) ---> (many) project_members
projects (1) ---> (many) monitoring_reports
projects (1) ---> (many) uploaded_documents
projects (1) ---> (many) publications
projects (1) ---> (many) presentations
projects (1) ---> (many) ip_applications

budgets (1) ---> (many) expenditures

ip_applications (1) ---> (one) ip_certificates
```

## 🎯 Key Features Implementation Details

### Project Management
- Create research and extension projects
- Track status: pending, ongoing, completed, cancelled
- Assign team members to projects
- Track by funding type: internal, external, cofunded
- Geographic mapping with latitude/longitude
- Search and filter capabilities

### Budget Tracking
- Set approved budgets per project
- Record expenditures against budget
- Calculate remaining funds
- Utilization percentage analytics
- Expenditure history logging

### IP Management
- Submit IP applications
- Review workflow: pending → under_review → for_revisions → approved/rejected
- Auto-generate IP certificates on approval
- Track application status and comments

### Monitoring & Reporting
- Quarterly report submissions
- Accomplishment tracking
- Workplan review
- PDF and Excel report exports
- Activity audit trail

### Role-Based Access
- Super Admin: Full system access
- RDI Staff: Project management and analytics
- Planning Officer: Monitoring and reporting
- IPOPHL Staff: IP application review
- Proponent: Project submission and tracking

## 🚀 Performance Optimization

### Database Optimization
- Indexes on frequently searched columns
- Foreign key relationships for data integrity
- Query eager loading to prevent N+1 problems
- Pagination for large result sets

### Frontend Optimization
- React code splitting
- Lazy loading of components
- Memoization for expensive computations
- Debounced search inputs
- Conditional rendering

### Backend Optimization
- API resources for consistent response formatting
- Query builders with selections
- Caching for analytics data
- Async job processing for reports

## 🔍 Monitoring & Debugging

### Laravel Debugging
```bash
# View logs
tail -f storage/logs/laravel-*.log

# Database query logging
php artisan tinker
> DB::enableQueryLog()
> App\Models\Project::all()
> DB::getQueryLog()

# Activity logs
SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10;
```

### Frontend Debugging
- React DevTools Browser Extension
- Console logging with `console.log()`
- Network tab in browser DevTools
- Redux DevTools for state management

## 📞 Support & Contribution

For issues, feature requests, or contributions:
1. Check existing documentation
2. Review error logs
3. Test with different user roles
4. Report issues with reproduction steps

---

**Last Updated:** May 2026  
**Version:** 1.0  
**Status:** Production Ready
