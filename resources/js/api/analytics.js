import api from './axios';

export const getAnalyticsOverview = () => 
  api.get('/analytics/overview');

export const getBudgetAnalytics = () => 
  api.get('/analytics/budget');

export const getFacultyAnalytics = () => 
  api.get('/analytics/faculty');

export const getQuarterlyAnalytics = () => 
  api.get('/analytics/quarterly');

export const getMapData = () => 
  api.get('/analytics/map');
