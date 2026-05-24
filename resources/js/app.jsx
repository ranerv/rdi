import React from 'react';
import ReactDOM from 'react-dom/client';
import { Router } from './routes/AppRouter';

ReactDOM.createRoot(document.getElementById('app')).render(
  <React.StrictMode>
    <Router />
  </React.StrictMode>
);
