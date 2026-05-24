# Centralized Research Monitoring System (CRMS) - DSSC
**Enterprise-level Research, Development, and Innovation Management Platform**

## 📋 Overview
CRMS is a comprehensive web-based system for managing university research and extension projects, budgets, intellectual property applications, monitoring reports, and collaborative workflows.

## 🏗️ System Architecture

### Tech Stack
- **Backend**: Laravel 13 with Sanctum authentication & Spatie RBAC
- **Frontend**: React 19 + Vite with Tailwind CSS v3
- **Database**: MySQL 8+
- **APIs**: RESTful with role-based access control

### Key Features
- ✅ Multi-role authentication (Super Admin, RDI Staff, Planning Officer, IPOPHL, Proponent)
- ✅ Project management (Research & Extension)
- ✅ Budget tracking & expenditure monitoring
- ✅ Intellectual Property application workflow
- ✅ Quarterly monitoring reports
- ✅ Interactive analytics dashboards
- ✅ Geographic project mapping
- ✅ PDF/Excel report generation
- ✅ File management system
- ✅ Activity logging & audit trails
- ✅ Real-time notifications

## 🚀 Quick Start

###Prerequisites
- PHP 8.3+
- Composer
- Node.js 20+
- MySQL 8+

### Backend Setup

```bash
# Clone and navigate to project
cd path/to/rdi

# Install dependencies
composer install

# Configure environment
cp .env.example .env
# Edit .env with your MySQL credentials

# Generate app key
php artisan key:generate

# Publish config files
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Run migrations
php artisan migrate

# Seed database with roles, permissions, and test users
php artisan db:seed

# Start Laravel server
php artisan serve  # Runs on http://localhost:8000
```

### Frontend Setup

```bash
# Install dependencies
npm install

# Create environment file
cp .env.example.frontend .env

# Start development server
npm run dev  # Runs on http://localhost:5173
```

### Database Setup Details
The system uses MySQL with the following key tables:
- `users` - User accounts with roles
- `roles` / `permissions` - Spatie RBAC tables
- `colleges` / `departments` - Organization structure
- `projects` - Research/Extension projects
- `budgets` / `expenditures` - Financial tracking
- `monitoring_reports` - Quarterly reports
- `ip_applications` / `ip_certificates` - IP tracking
- `uploaded_documents` - File management
- `notifications` - User notifications
- `activity_logs` - Audit trail

## 🔐 Default Test Credentials

### Super Admin
- **Email**: admin@crms.test
- **Password**: Password@123

### RDI Staff
- **Email**: rdi-staff@crms.test
- **Password**: Password@123

### Planning Officer
- **Email**: planning@crms.test
- **Password**: Password@123

### IPOPHL Staff
- **Email**: ipophl@crms.test
- **Password**: Password@123

### Proponent
- **Email**: proponent@crms.test
- **Password**: Password@123

## 🎯 Roles & Permissions

### Super Admin (RDI Director)
- Full system access
- User management
- Analytics dashboard
- Report generation
- IP application approval
- Budget management

### RDI Staff
- Project CRUD operations
- Excel import
- Analytics viewing
- Report generation
- Document management
- Budget setup

### Planning Officer / Monitoring Officer
- View assigned projects
- Create monitoring reports
- Track accomplishments
- Send notifications to proponents
- View analytics

### IPOPHL Staff
- Review IP applications
- Update IP status (pending → approved/rejected)
- Issue IP certificates
- View project details

### Proponent
- View own projects
- Upload project documents
- Submit IP applications
- View project status
- Receive notifications

## 📚 API Documentation

### Authentication Endpoints
```
POST   /api/login                 - User login
POST   /api/logout                - User logout
GET    /api/me                    - Get authenticated user
POST   /api/forgot-password       - Request password reset
POST   /api/reset-password        - Reset password
```

### Project Endpoints
```
GET    /api/projects              - List projects (filtered)
POST   /api/projects              - Create project
GET    /api/projects/{id}         - Get project details
PUT    /api/projects/{id}         - Update project
DELETE /api/projects/{id}         - Delete project
POST   /api/projects/import-excel - Import from Excel
```

### Budget Endpoints
```
GET    /api/projects/{project}/budgets      - Get budget
POST   /api/projects/{project}/budgets      - Create budget
PUT    /api/budgets/{budget}                 - Update budget
DELETE /api/budgets/{budget}                 - Delete budget
```

### Expenditure Endpoints
```
GET    /api/budgets/{budget}/expenditures   - List expenditures
POST   /api/budgets/{budget}/expenditures   - Record expenditure
DELETE /api/expenditures/{expenditure}      - Remove expenditure
```

### Monitoring Reports
```
GET    /api/monitoring-reports                 - List reports
POST   /api/monitoring-reports                 - Create report
PUT    /api/monitoring-reports/{id}            - Update report
POST   /api/monitoring-reports/{id}/submit     - Submit report
```

### IP Applications
```
GET    /api/ip-applications                    - List applications
POST   /api/ip-applications                    - Submit application
PATCH  /api/ip-applications/{id}/status        - Update status
```

### Analytics
```
GET    /api/analytics/overview    - KPI dashboard
GET    /api/analytics/budget      - Budget utilization
GET    /api/analytics/faculty     - Faculty involvement
GET    /api/analytics/quarterly   - Quarterly progress
GET    /api/analytics/map         - Geographic data
```

### Reports
```
GET    /api/reports/pdf           - Export PDF report
GET    /api/reports/excel         - Export Excel report
```

## 📁 Project Structure

### Backend (`app/`)
```
Http/Controllers/Api/
  ├── AuthController.php
  ├── ProjectController.php
  ├── BudgetController.php
  ├── ...AnalyticsController.php
Models/
  ├── User.php, Project.php, Budget.php, ...
Http/Requests/
  ├── LoginRequest.php, StoreProjectRequest.php, ...
Http/Resources/
  ├── ProjectResource.php, UserResource.php, ...
Services/
  ├── ProjectService.php, AnalyticsService.php, ...
Policies/
  ├── ProjectPolicy.php, IpApplicationPolicy.php
Http/Middleware/
  ├── ActivityLogger.php
```

### Frontend (`frontend/src/`)
```
api/
  ├── axios.js, auth.js, projects.js, ...
context/
  ├── AuthContext.jsx, NotificationContext.jsx
hooks/
  ├── useAuth.js, useProjects.js, usePagination.js
components/
  ├── common/ (Sidebar, Navbar, DataTable, FileUpload, ...)
  ├── charts/ (ProjectStatusChart, BudgetChart, ...)
  ├── maps/ (ProjectMap.jsx)
pages/
  ├── auth/ (Login.jsx, ForgotPassword.jsx)
  ├── admin/ (Dashboard.jsx, Projects.jsx, Users.jsx, ...)
  ├── rdi/ (Dashboard.jsx, Projects.jsx, ProjectForm.jsx, ...)
  ├── monitoring/ (Dashboard.jsx, MonitoringForm.jsx)
  ├── ipophl/ (Dashboard.jsx, ApplicationDetail.jsx, ...)
  ├── proponent/ (Dashboard.jsx, UploadDocuments.jsx, ...)
```

## 🔧 Configuration

### Environment Variables (Backend)
```env
APP_NAME="CRMS - DSSC"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crms_dssc
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DRIVER=database
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
```

### Environment Variables (Frontend)
```env
VITE_API_BASE_URL=http://localhost:8000
```

## 🧧 Migrations & Seeding

### Running Migrations
```bash
php artisan migrate  # Run all migrations
php artisan migrate:rollback  # Revert last batch
php artisan migrate:refresh  # Rollback and re-run
php artisan migrate:fresh --seed  # Fresh DB with seeds
```

### Available Seeders
```bash
php artisan db:seed  # Run all seeders
php artisan db:seed --class=CollegeDepartmentSeeder
php artisan db:seed --class=RolesPermissionsSeeder
php artisan db:seed --class=UserSeeder
```

## 📊 Dashboard Features

### Super Admin Dashboard
- Total projects count
- Project status breakdown
- Budget utilization % overtime
- Faculty involvement by college
- Funding classification breakdown
- Quarterly accomplishments
- Interactive project map
- Recent activity log

### RDI Staff Dashboard
- Project management statistics
- Pending approvals
- Budget overview
- Recent reports
- Export capabilities

### Monitoring Officer Dashboard
- Projects needing monitoring
- Overdue submissions
- Quarterly tracking
- Monitoring report status

## 🔐 Security Features

- **Authentication**: Laravel Sanctum tokens
- **Authorization**: Spatie Permission RBAC
- **CSRF Protection**: Built-in Laravel protection
- **SQL Injection Prevention**: Eloquent ORM parameterized queries
- **XSS Prevention**: React automatic escaping
- **File Upload Security**: MIME type validation, max 10MB
- **Activity Logging**: All user actions logged
- **Password Hashing**: bcrypt with configurable rounds

## 🚀 Deployment

### Production Setup
```bash
# Update environment
APP_ENV=production
APP_DEBUG=false

# Optimize composer
composer install --no-dev --optimize-autoloader

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build frontend
npm run build

# Serve with production web server (Nginx/Apache)
```

### Recommended Hosting
- **Backend**: DigitalOcean App Platform, AWS EC2, Azure App Service, Laravel Vapor
- **Database**: Amazon RDS, DigitalOcean Managed DB, Azure Database
- **Frontend**: Vercel, Netlify, Cloudflare Pages

## 📞 Support & Maintenance

### Common Commands
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check status
php artisan tinker

# View logs
tail -f storage/logs/laravel-*.log

# Run tests
php artisan test
```

### Database Backups
```bash
# MySQL backup
mysqldump -u root -p crms_dssc > backup.sql

# Restore
mysql -u root -p crms_dssc < backup.sql
```

## 📝 Development Roadmap

- [x] Backend API scaffolding
- [x] Database schema design
- [x] RBAC implementation
- [x] Authentication system
- [x] Project management module
- [x] Budget tracking
- [x] IP Application workflow
- [x] Monitoring reports
- [ ] React frontend completion
- [ ] Advanced analytics
- [ ] Real-time notifications
- [ ] Mobile app (optional)
- [ ] Integration with external systems

## 👥 Contributors

**Developed by**: Senior Full-Stack Architect & Development Team  
**Institution**: University RDI Office  
**Last Updated**: May 2026

## 📄 License

All rights reserved © 2026 University RDI Office. Proprietary software.

---

For detailed setup help or issues, refer to Laravel & React documentation or contact the development team.
