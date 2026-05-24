# CRMS Complete Setup Guide

This guide covers the complete setup for the Centralized Research Monitoring System.

## 📋 Prerequisites

### System Requirements
- **PHP**: 8.3 or higher
- **Composer**: Latest version
- **Node.js**: 20+ (comes with npm)
- **MySQL**: 8.0+ or MariaDB 10.4+
- **Git**: For version control

### Development Environment
- **Windows/Mac/Linux** system with terminal access
- Text editor or IDE (VS Code recommended)
- Postman or API testing tool (optional, for API testing)

## 🗄️ Database Setup (MySQL)

### For Windows with MySQL installed locally:

```bash
# Open MySQL command line
mysql -u root -p

# Create database
CREATE DATABASE crms_dssc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create db user (optional, recommended for production)
CREATE USER 'crms_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON crms_dssc.* TO 'crms_user'@'localhost';
FLUSH PRIVILEGES;

# Verify connection
mysql -u root -p crms_dssc
```

### Update `.env` with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crms_dssc
DB_USERNAME=root
DB_PASSWORD=(leave blank if no password)
```

## 🚀 Backend Setup

### Step 1: Install Composer Dependencies
```bash
cd path/to/rdi
composer install
```

### Step 2: Environment Configuration
```bash
# Copy environment template
cp .env.example .env

# Generate application key
php artisan key:generate

# Verify key is set
cat .env | grep APP_KEY
```

### Step 3: Publish Package Configuration
```bash
# Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Spatie Permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### Step 4: Run Database Migrations
```bash
# Run all migrations
php artisan migrate

# You should see output like:
# Migration table created successfully.
# Running migrations...
#  [OK] Database\Migrations\2024_01_01_000001_create_colleges_table
# [OK] Database\Migrations\2024_01_01_000002_create_departments_table
# ... and so on
```

### Step 5: Seed Database with Initial Data
```bash
# Run all seeders
php artisan db:seed

# Or run specific seeders
php artisan db:seed --class=RolesPermissionsSeeder
php artisan db:seed --class=CollegeDepartmentSeeder
php artisan db:seed --class=UserSeeder
```

### Step 6: Create Storage Symlink
```bash
# For file uploads
php artisan storage:link
```

### Step 7: Start Laravel Development Server
```bash
# Start on port 8000 (default)
php artisan serve

# Or specify different port
php artisan serve --port=8001

# You'll see:
# Laravel development server started on [http://127.0.0.1:8000]
```

**Backend is now running on:** `http://localhost:8000`

## 🎨 Frontend Setup

### Step 1: Install JavaScript Dependencies
```bash
# In the root directory
npm install

# This installs React, Vite, Tailwind, Axios, etc.
# Wait for installation to complete
```

### Step 2: Environment Configuration
```bash
# Copy frontend environment template
cp .env.frontend.example .env

# Verify VITE_API_BASE_URL is set
# VITE_API_BASE_URL=http://localhost:8000
```

### Step 3: Start Development Server
```bash
# Start Vite dev server
npm run dev

# You'll see output like:
# VITE v..
# ➜  Local:   http://localhost:5173/
```

**Frontend is now running on:** `http://localhost:5173`

## ✅ Verify Everything is Working

### 1. Test Login Endpoint
```bash
# In another terminal, test the API
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@crms.test","password":"Password@123"}'

# Should return:
# {"data":{"user":{...},"token":"..."},"message":"Login successful","status":true}
```

### 2. Open Frontend in Browser
```
Navigate to: http://localhost:5173
```

### 3. Test Login
- Email: `admin@crms.test`
- Password: `Password@123`
- Should redirect to admin dashboard

## 📝 Database Migrations Explained

The system creates 17 tables:

### Core Tables
1. **users** - User accounts
2. **roles** - User roles (Spatie)
3. **permissions** - User permissions (Spatie)
4. **model_has_roles** - User-to-role mappings (Spatie)
5. **model_has_permissions** - User permissions (Spatie)

### Organization
6. **colleges** - University colleges/faculties
7. **departments** - Departments within colleges

### Projects
8. **projects** - Research/Extension projects
9. **project_members** - Project team members

### Financial
10. **budgets** - Project budgets
11. **expenditures** - Budget spending

### Monitoring
12. **monitoring_reports** - Quarterly project reports

### IP Management
13. **ip_applications** - IP application submissions
14. **ip_certificates** - Issued IP certificates

### Documents
15. **uploaded_documents** - Project files/documents

### Notifications & Logging
16. **notifications** - User notifications
17. **activity_logs** - Audit trail

## 🔐 Available Test Users

After seeding, you can login with these credentials:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@crms.test | Password@123 |
| RDI Staff | rdi-staff@crms.test | Password@123 |
| Planning Officer | planning@crms.test | Password@123 |
| IPOPHL Staff | ipophl@crms.test | Password@123 |
| Proponent | proponent@crms.test | Password@123 |

## 🚨 Common Issues & Solutions

### Issue: Port 8000/5173 Already in Use
```bash
# Find what's using the port
lsof -i :8000  # macOS/Linux
netstat -ano | findstr :8000  # Windows

# Use different port
php artisan serve --port=8001
npm run dev -- --port 5174
```

### Issue: Database Connection Failed
```bash
# Verify MySQL is running
mysql -u root -p

# Check .env credentials
cat .env | grep DB_

# If password has special chars, quote them
DB_PASSWORD="p@ss!word"
```

### Issue: npm node_modules Error
```bash
# Clear cache and reinstall
rm -rf node_modules package-lock.json
npm install
```

### Issue: Laravel Key Not Generated
```bash
# Generate key
php artisan key:generate

# Verify
grep APP_KEY .env
```

### Issue: Migrations Fail
```bash
# Check for existing tables
php artisan tinker
> DB::select('SHOW TABLES')

# Reset database (WARNING: deletes all data)
php artisan migrate:fresh --seed
```

### Issue: Can't Login to Frontend
```bash
# Check API is responding
curl http://localhost:8000/api/login -X POST

# Check browser console for errors (F12)
# Check network tab for API calls
# Verify token is being stored in localStorage
```

## 🧪 Running Tests

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test tests/Feature/ProjectTest.php

# With code coverage
php artisan test --coverage
```

## 📦 Production Build

### Build Frontend Assets
```bash
npm run build
# Creates: public/build/ directory with optimized files
```

### Optimize Backend
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Optimize autoloader
composer install --no-dev --optimize-autoloader
```

## 🔄 Daily Development Workflow

Morning startup:
```bash
# Terminal 1: Database
mysql -u root -p crms_dssc

# Terminal 2: Backend API
cd path/to/rdi
php artisan serve

# Terminal 3: Frontend
npm run dev
```

Evening cleanup:
```bash
# Stop all servers (Ctrl+C in terminals)

# Optional: commit changes
git add .
git commit -m "Work in progress"
```

## 📚 Useful Commands

### Laravel Commands
```bash
# View all routes
php artisan route:list

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Create new model
php artisan make:model ModelName -m

# Run specific migration
php artisan migrate --path=database/migrations/2024_01_01_000001_create_colleges_table.php

# Rollback migrations
php artisan migrate:rollback

# View logs
tail -f storage/logs/laravel-*.log
```

### Database Commands
```bash
# Access database CLI
mysql -u root -p crms_dssc

# Common MySQL queries
SHOW TABLES;
SELECT * FROM users;
DESC users;
```

### Frontend Commands
```bash
# Build for production
npm run build

# Preview production build locally
npm run preview

# Format code
npm run lint
```

## 🎓 Key Concepts

### Authentication Flow
1. User submits login (email + password)
2. Backend validates, creates Sanctum token
3. Frontend stores token in localStorage
4. Axios adds "Authorization: Bearer {token}" to requests
5. Protected routes check token via middleware

### Authorization (RBAC)
1. User has role(s) (super-admin, rdi-staff, etc.)
2. Roles have permission(s)
3. Policies check permissions before allowing actions
4. Frontend ProtectedRoute component enforces role access

### API Response Format
```json
{
  "data": {},           // Actual response data
  "message": "Success", // Human-readable message
  "status": true,       // Success indicator
  "pagination": {}      // Optional for list endpoints
}
```

## 📞 Support Resources

- Laravel Docs: https://laravel.com/docs
- React Docs: https://react.dev
- Sanctum Docs: https://laravel.com/docs/sanctum
- Spatie Permission: https://spatie.be/docs/laravel-permission
- Tailwind CSS: https://tailwindcss.com/docs
- Recharts: https://recharts.org/

## ✨ Next Steps

After successful setup:

1. **Explore the API** using Postman
2. **Customize dashboard** cards and charts
3. **Add more projects** via frontend
4. **Test different roles** with different user accounts
5. **Review database** structure and relationships
6. **Implement additional features** as needed

---

**Congratulations!** Your CRMS instance is now fully set up and ready for development. 🎉
