export const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
  }).format(amount);
};

export const formatDate = (date, format = 'short') => {
  if (!date) return '';
  const d = new Date(date);
  if (format === 'short') return d.toLocaleDateString('en-PH');
  if (format === 'long') return d.toLocaleDateString('en-PH', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  });
  return d.toISOString().split('T')[0];
};

export const formatPercent = (value, decimals = 2) => {
  return `${parseFloat(value).toFixed(decimals)}%`;
};

export const formatStatus = (status) => {
  return status?.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};
