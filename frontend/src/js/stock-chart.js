import React, { useState, useEffect } from 'react';
import {
  AreaChart,
  Area,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
} from 'recharts';

const FinancialChart = () => {
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [chartData, setChartData] = useState(null);
  const [chartColor, setChartColor] = useState('#ff4d4f');

  useEffect(() => {
    const container = document.getElementById('stock-chart-container');
    const postId = container?.dataset?.id;
    const type = container?.dataset?.type;

    // 根据 type 设置颜色
    setChartColor(type === 'up' ? '#52c41a' : '#ff4d4f');

    if (!postId) {
      setError('Post ID not found');
      setLoading(false);
      return;
    }

    const fetchData = async () => {
      try {
        setLoading(true);
        const response = await fetch(`/stock-data/${postId}`);
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const data = await response.json();

        // 处理历史数据
        const parsedHistoricalData = data.historicalData ? JSON.parse(data.historicalData) : [];
        const processedData = parsedHistoricalData
        .sort((a, b) => new Date(a.date) - new Date(b.date))
        .map(item => ({
          time: new Date(item.date).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric'
          }),
          price: item.close
        }));

        setChartData(processedData);
      } catch (error) {
        setError(error.message);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) {
    return <div className="w-full h-full flex items-center justify-center">Loading...</div>;
  }

  if (error) {
    return <div className="w-full h-full flex items-center justify-center text-red-500">Error: {error}</div>;
  }

  if (!chartData) {
    return <div className="w-full h-full flex items-center justify-center text-gray-500">No data available</div>;
  }

  // 计算价格范围
  const prices = chartData.map(d => d.price);
  const minPrice = Math.floor(Math.min(...prices));
  const maxPrice = Math.ceil(Math.max(...prices));
  const priceRange = maxPrice - minPrice;

  return (
      <div className="w-full h-full">
        <ResponsiveContainer width="100%" height="100%">
          <AreaChart
              data={chartData}
              margin={{ top: 0, right: 0, left: 0, bottom: 0 }}
          >
            <defs>
              <linearGradient id="colorPrice" x1="0" y1="0" x2="0" y2="1">
                <stop offset="5%" stopColor={chartColor} stopOpacity={0.2}/>
                <stop offset="95%" stopColor={chartColor} stopOpacity={0}/>
              </linearGradient>
            </defs>
            <CartesianGrid strokeDasharray="3 3" stroke="#eee" />
            <XAxis
                dataKey="time"
                tickFormatter={(time) => time}
                interval={Math.floor(chartData.length / 6)}
            />
            <YAxis
                domain={[minPrice - priceRange * 0.1, maxPrice + priceRange * 0.1]}
                tickCount={12}
                hide={true}
                width={0}
                axisLine={false}
                tickLine={false}
            />
            <Tooltip
                contentStyle={{ backgroundColor: 'white', border: 'none' }}
                labelStyle={{ color: '#666' }}
            />
            <Area
                type="monotone"
                dataKey="price"
                stroke={chartColor}
                strokeWidth={2}
                fill="url(#colorPrice)"
                dot={false}
            />
          </AreaChart>
        </ResponsiveContainer>
      </div>
  );
};

export default FinancialChart;