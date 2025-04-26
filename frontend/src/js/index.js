import ReactDOM from 'react-dom/client';
import React from 'react';
import FinancialChart from './stock-chart';

if (document.getElementById('stock-chart-container')) {
  const root = ReactDOM.createRoot(document.getElementById('stock-chart-container'));
  root.render(<FinancialChart />);
}