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
      <Route path="/" element={
        isAuthenticated ? <Navigate to="/admin" /> : <LoginPage />
      } />
      <Route path="/login" element={<Navigate to="/" replace />} />
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

      {/* Catch-all redirect */}
      <Route path="*" element={<Navigate to="/" replace />} />
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
