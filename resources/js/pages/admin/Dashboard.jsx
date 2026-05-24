import { useState, useEffect } from 'react';
import { useAuth } from '../../hooks/useAuth';
import * as analyticsApi from '../../api/analytics';
import { StatCard } from '../../components/common/StatCard';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';

export default function AdminDashboard() {
  const { logout } = useAuth();
  const [analytics, setAnalytics] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    analyticsApi.getAnalyticsOverview()
      .then(res => setAnalytics(res.data.data))
      .finally(() => setLoading(false));
  }, []);

  if (loading) return <div className="p-8">Loading...</div>;

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white shadow">
        <div className="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
          <h1 className="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
          <button
            onClick={logout}
            className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded"
          >
            Logout
          </button>
        </div>
      </div>

      {/* Main Content */}
      <div className="max-w-7xl mx-auto px-4 py-8">
        {/* Stats Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <StatCard label="Total Projects" value={analytics?.total_projects || 0} color="blue" />
          <StatCard label="Research Projects" value={analytics?.research_count || 0} color="green" />
          <StatCard label="Extension Projects" value={analytics?.extension_count || 0} color="purple" />
          <StatCard label="Completion Rate" value={`${analytics?.completion_rate || 0}%`} color="yellow" />
        </div>

        {/* Budget Section */}
        <div className="bg-white rounded-lg shadow p-6 mb-8">
          <h2 className="text-xl font-bold text-gray-900 mb-4">Budget Overview</h2>
          <div className="grid grid-cols-3 gap-6">
            <div>
              <p className="text-gray-600 text-sm">Total Budget</p>
              <p className="text-2xl font-bold text-gray-900">₱ {(analytics?.total_budget || 0).toLocaleString()}</p>
            </div>
            <div>
              <p className="text-gray-600 text-sm">Total Expenditures</p>
              <p className="text-2xl font-bold text-gray-900">₱ {(analytics?.total_expenditures || 0).toLocaleString()}</p>
            </div>
            <div>
              <p className="text-gray-600 text-sm">Utilization Rate</p>
              <p className="text-2xl font-bold text-gray-900">{analytics?.budget_utilization_percent || 0}%</p>
            </div>
          </div>
        </div>

        {/* Projects by Status */}
        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-xl font-bold text-gray-900 mb-4">Projects by Status</h2>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={[
              { name: 'Pending', value: 0 },
              { name: 'Ongoing', value: analytics?.ongoing_count || 0 },
              { name: 'Completed', value: analytics?.completed_count || 0 },
            ]}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="name" />
              <YAxis />
              <Tooltip />
              <Bar dataKey="value" fill="#3B82F6" />
            </BarChart>
          </ResponsiveContainer>
        </div>
      </div>
    </div>
  );
}
