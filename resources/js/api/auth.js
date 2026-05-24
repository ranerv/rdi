import api from './axios';

export const login = (email, password) => 
  api.post('/login', { email, password });

export const logout = () => 
  api.post('/logout');

export const me = () => 
  api.get('/me');

export const forgotPassword = (email) => 
  api.post('/forgot-password', { email });

export const resetPassword = (data) => 
  api.post('/reset-password', data);
