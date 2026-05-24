import api from './axios';

export const getProjects = (filters = {}) => 
  api.get('/projects', { params: filters });

export const getProject = (id) => 
  api.get(`/projects/${id}`);

export const createProject = (data) => 
  api.post('/projects', data);

export const updateProject = (id, data) => 
  api.put(`/projects/${id}`, data);

export const deleteProject = (id) => 
  api.delete(`/projects/${id}`);

export const importExcel = (file) => {
  const formData = new FormData();
  formData.append('file', file);
  return api.post('/projects/import-excel', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  });
};

export const getMapData = () => 
  api.get('/analytics/map');
