import { createContext, useState, useEffect } from 'react';
import * as authApi from '../api/auth';

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [roles, setRoles] = useState([]);
  const [permissions, setPermissions] = useState([]);
  const [loading, setLoading] = useState(true);
  const token = localStorage.getItem('auth_token');

  useEffect(() => {
    if (token) {
      authApi.me()
        .then(res => {
          setUser(res.data.data.user);
          setRoles(res.data.data.roles);
          setPermissions(res.data.data.permissions);
        })
        .catch(() => {
          localStorage.removeItem('auth_token');
        })
        .finally(() => setLoading(false));
    } else {
      setLoading(false);
    }
  }, [token]);

  const login = async (email, password) => {
    const res = await authApi.login(email, password);
    localStorage.setItem('auth_token', res.data.data.token);
    setUser(res.data.data.user);
    setRoles(res.data.data.roles);
    setPermissions(res.data.data.permissions);
    return res.data.data;
  };

  const logout = async () => {
    await authApi.logout();
    localStorage.removeItem('auth_token');
    setUser(null);
    setRoles([]);
    setPermissions([]);
  };

  const hasRole = (role) => roles.includes(role);
  const hasAnyRole = (roleList) => roleList.some(r => roles.includes(r));

  return (
    <AuthContext.Provider value={{
      user,
      roles,
      permissions,
      loading,
      login,
      logout,
      hasRole,
      hasAnyRole,
      isAuthenticated: !!token,
    }}>
      {children}
    </AuthContext.Provider>
  );
};
