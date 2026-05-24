---
name: testing-crms-frontend
description: Test the CRMS React frontend routing and UI without a Laravel backend. Use when verifying client-side routing, page rendering, or React component changes.
---

# Testing CRMS Frontend

## Stack Overview
- **Backend**: Laravel + Inertia.js (PHP, requires Composer + database)
- **Frontend**: React 18 + React Router v6 + Tailwind CSS
- **Build**: Vite with `laravel-vite-plugin`
- **Entry point**: `resources/js/app.jsx` → `Router` from `resources/js/routes/AppRouter.jsx`

## Testing Frontend Without Laravel

The `laravel-vite-plugin` expects a running Laravel server. For frontend-only changes (routing, components, UI), you can bypass it:

1. Create a temporary `index.html` in the repo root:
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRMS Test</title>
</head>
<body>
    <div id="app"></div>
    <script type="module" src="/resources/js/app.jsx"></script>
</body>
</html>
```

2. Create a temporary `vite.config.test.js`:
```js
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [react()],
    root: '.',
    server: { port: 5173, host: '0.0.0.0' },
    css: { postcss: { plugins: [] } },
});
```

3. Run: `npm install && npx vite --config vite.config.test.js`
4. Open `http://localhost:5173/` in the browser
5. **Clean up**: Remove `index.html` and `vite.config.test.js` after testing

**Note**: Tailwind CSS styling will not be applied in this standalone mode (no PostCSS/Tailwind config), but functional behavior (routing, redirects, component rendering) works correctly.

## Key Files
- `resources/js/routes/AppRouter.jsx` — All client-side routes
- `resources/js/routes/ProtectedRoute.jsx` — Auth guard for protected routes
- `resources/js/context/AuthContext.jsx` — Auth state (`isAuthenticated` = `!!localStorage.getItem('auth_token')`)
- `resources/js/api/axios.js` — API client with 401 redirect logic
- `resources/js/pages/auth/Login.jsx` — Login page component

## Auth State for Testing
- **Unauthenticated**: Clear `auth_token` from localStorage (default state)
- **Simulated authenticated**: Set `localStorage.setItem('auth_token', 'test')` in browser console (will redirect to protected routes, but API calls will fail without backend)

## Limitations
- Login form submission requires a running Laravel API server at `localhost:8000`
- Protected route content (dashboards) requires backend API responses
- Full-stack testing requires PHP, Composer, and a database

## Devin Secrets Needed
None required for frontend-only testing.
