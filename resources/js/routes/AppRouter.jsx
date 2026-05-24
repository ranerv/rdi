import { Navigate, BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from '../context/AuthContext';
import { ProtectedRoute } from './ProtectedRoute';
import { useAuth } from '../hooks/useAuth';

// Pages - Auth
import LoginPage from '../pages/auth/Login';
import ForgotPasswordPage from '../pages/auth/ForgotPassword';

// Pages - Dashboard (will be created)
import AdminDashboard from '../pages/admin/Dashboard';
import RdiDashboard from '../pages/rdi/Dashboard';
import ProponentDashboard from '../pages/proponent/Dashboard';

export const AppRouter = () => {
  const { isAuthenticated } = useAuth();

  return (
    <Routes>
      {/* Public routes */}
      <Route path="/login" element={<LoginPage />} />
      <Route path="/forgot-password" element={<ForgotPasswordPage />} />

      {/* Protected routes - Super Admin */}
      <Route
        path="/admin/*"
        element={
          <ProtectedRoute requiredRoles={['super-admin']}>
            <AdminDashboard />
          </ProtectedRoute>
        }
      />

      {/* Protected routes - RDI Staff */}
      <Route
        path="/rdi/*"
        element={
          <ProtectedRoute requiredRoles={['rdi-staff', 'super-admin']}>
            <RdiDashboard />
          </ProtectedRoute>
        }
      />

      {/* Protected routes - Proponent */}
      <Route
        path="/proponent/*"
        element={
          <ProtectedRoute requiredRoles={['proponent']}>
            <ProponentDashboard />
          </ProtectedRoute>
        }
      />

      {/* Default redirect */}
      <Route path="/" element={
        isAuthenticated ? <Navigate to="/dashboard" /> : <Navigate to="/login" />
      } />
    </Routes>
  );
};

export const Router = () => (
  <BrowserRouter>
    <AuthProvider>
      <AppRouter />
    </AuthProvider>
  </BrowserRouter>
);
