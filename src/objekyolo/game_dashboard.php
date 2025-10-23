<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Expert Dashboard - YOLO Analytics</title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --purple: #8b5cf6;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] {
            --bg-primary: #1e293b;
            --bg-secondary: #0f172a;
            --bg-tertiary: #020617;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border: #334155;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: var(--bg-secondary);
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Header */
        .header {
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1800px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--purple));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-icon {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
            padding: 0.5rem;
            font-size: 1.2rem;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        /* Container */
        .container {
            max-width: 1800px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-primary);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-color);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 12px -2px rgba(0, 0, 0, 0.15);
        }

        .stat-card.primary::before { background: var(--primary); }
        .stat-card.success::before { background: var(--success); }
        .stat-card.warning::before { background: var(--warning); }
        .stat-card.info::before { background: var(--info); }
        .stat-card.purple::before { background: var(--purple); }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: var(--bg-secondary);
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }

        .stat-trend.up {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-trend.down {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Grid Layouts */
        .grid-2 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 1400px) {
            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
        }

        /* Card */
        .card {
            background: var(--bg-primary);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-title-icon {
            font-size: 1.25rem;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Video Feed */
        .video-wrapper {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            background: #000;
        }

        .video-wrapper img {
            width: 100%;
            height: auto;
            display: block;
        }

        .video-overlay {
            position: absolute;
            top: 1rem;
            left: 1rem;
            right: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .video-badge {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--success);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(0.9); }
        }

        /* Charts */
        .chart-container {
            position: relative;
            height: 300px;
        }

        .chart-container.tall {
            height: 400px;
        }

        /* Model Performance Metrics */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .metric-item {
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .metric-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .metric-bar {
            width: 100%;
            height: 6px;
            background: var(--bg-tertiary);
            border-radius: 3px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .metric-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--info));
            border-radius: 3px;
            transition: width 0.5s ease;
        }

        /* Confusion Matrix Style */
        .confusion-matrix {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }

        .matrix-cell {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--bg-secondary);
            border-radius: 8px;
            border: 1px solid var(--border);
            padding: 1rem;
            transition: all 0.2s ease;
        }

        .matrix-cell:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow);
        }

        .matrix-value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .matrix-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
            max-height: 400px;
            overflow-y: auto;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            position: sticky;
            top: 0;
            background: var(--bg-secondary);
            z-index: 10;
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--border);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: var(--bg-secondary);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            gap: 0.25rem;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .badge-info {
            background: rgba(6, 182, 212, 0.1);
            color: var(--info);
        }

        /* Filters */
        .filters {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            min-width: 150px;
        }

        .filter-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .filter-input, .filter-select {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        /* Status */
        .status-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-style: italic;
            margin-top: 1rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: var(--warning);
        }

        .alert-icon {
            font-size: 1.25rem;
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
        }

        .spinner {
            border: 3px solid var(--border);
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Heatmap */
        .heatmap-grid {
            display: grid;
            grid-template-columns: repeat(24, 1fr);
            gap: 4px;
        }

        .heatmap-cell {
            aspect-ratio: 1;
            border-radius: 4px;
            background: var(--bg-secondary);
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
        }

        .heatmap-cell:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 10;
        }

        .heatmap-legend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            font-size: 0.875rem;
        }

        .heatmap-gradient {
            width: 200px;
            height: 20px;
            border-radius: 4px;
            background: linear-gradient(90deg, 
                rgba(59, 130, 246, 0.1), 
                rgba(59, 130, 246, 0.5), 
                rgba(59, 130, 246, 1));
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <div class="logo-icon">🤖</div>
                <div>
                    <div>ML Expert Dashboard</div>
                    <div style="font-size: 0.75rem; font-weight: 400; color: var(--text-secondary);">
                        YOLO Object Detection Analytics
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-icon" onclick="exportAllData()" title="Export All Data">
                    📥
                </button>
                <button class="btn btn-icon" onclick="toggleTheme()" title="Toggle Theme">
                    <span id="theme-icon">🌙</span>
                </button>
                <button class="btn btn-primary" onclick="refreshAll()">
                    🔄 Refresh All
                </button>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-header">
                    <div class="stat-icon">📊</div>
                    <div class="stat-trend up">
                        <span>↑</span>
                        <span id="total-trend">0%</span>
                    </div>
                </div>
                <div class="stat-value" id="total-detections">0</div>
                <div class="stat-label">Total Detections</div>
            </div>

            <div class="stat-card success">
                <div class="stat-header">
                    <div class="stat-icon">🎯</div>
                    <div class="stat-trend up">
                        <span>↑</span>
                        <span id="conf-trend">0%</span>
                    </div>
                </div>
                <div class="stat-value" id="avg-confidence">0%</div>
                <div class="stat-label">Average Confidence</div>
            </div>

            <div class="stat-card warning">
                <div class="stat-header">
                    <div class="stat-icon">⚡</div>
                </div>
                <div class="stat-value" id="fps-value">0</div>
                <div class="stat-label">Detections/Min</div>
            </div>

            <div class="stat-card info">
                <div class="stat-header">
                    <div class="stat-icon">🏆</div>
                </div>
                <div class="stat-value" id="top-class" style="font-size: 1.25rem;">-</div>
                <div class="stat-label">Top Detected Class</div>
            </div>

            <div class="stat-card purple">
                <div class="stat-header">
                    <div class="stat-icon">📈</div>
                </div>
                <div class="stat-value" id="precision-value">0%</div>
                <div class="stat-label">Model Precision (>0.6)</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid-2">
            
            <!-- Live Video Feed -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="card-title-icon">🎥</span>
                        Live Detection Feed
                    </div>
                </div>
                <div class="video-wrapper">
                    <img src="http://localhost:5002/video_feed" 
                         alt="Camera feed" 
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjYwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMTExIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIyNCIgZmlsbD0iI2ZmZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkNhbWVyYSBOb3QgQ29ubmVjdGVkPC90ZXh0Pjwvc3ZnPg=='" />
                    <div class="video-overlay">
                        <div class="video-badge">
                            <div class="pulse"></div>
                            <span>LIVE</span>
                        </div>
                        <div class="video-badge">
                            <span>🎯</span>
                            <span id="live-count">0 objects</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Model Performance Metrics -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="card-title-icon">📊</span>
                        Model Performance
                    </div>
                </div>
                <div class="metrics-grid">
                    <div class="metric-item">
                        <div class="metric-label">Precision</div>
                        <div class="metric-value" id="precision-metric">0%</div>
                        <div class="metric-bar">
                            <div class="metric-bar-fill" id="precision-bar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Recall</div>
                        <div class="metric-value" id="recall-metric">0%</div>
                        <div class="metric-bar">
                            <div class="metric-bar-fill" id="recall-bar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">F1 Score</div>
                        <div class="metric-value" id="f1-metric">0%</div>
                        <div class="metric-bar">
                            <div class="metric-bar-fill" id="f1-bar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">mAP@0.5</div>
                        <div class="metric-value" id="map-metric">0%</div>
                        <div class="metric-bar">
                            <div class="metric-bar-fill" id="map-bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <div class="metric-label" style="margin-bottom: 0.75rem;">Detection Distribution by Hour</div>
                    <div class="heatmap-grid" id="heatmap"></div>
                    <div class="heatmap-legend">
                        <span style="color: var(--text-secondary); font-size: 0.75rem;">Low</span>
                        <div class="heatmap-gradient"></div>
                        <span style="color: var(--text-secondary); font-size: 0.75rem;">High</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Charts Grid -->
        <div class="grid-3">
            
            <!-- Class Distribution -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="card-title-icon">🥧</span>
                        Class Distribution
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="distributionChart"></canvas>
                </div>
                <div class="status-text" id="dist-status">Loading...</div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="card-title-icon">📈</span>
                        Detection Timeline
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="timelineChart"></canvas>
                </div>
                <div class="status-text" id="timeline-status">Loading...</div>
            </div>

            <!-- Confidence Distribution -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="card-title-icon">📊</span>
                        Confidence Distribution
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="confidenceChart"></canvas>
                </div>
                <div class="status-text" id="conf-status">Loading...</div>
            </div>

        </div>

        <!-- Detection Log -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-title-icon">📋</span>
                    Detection Log
                </div>
                <div class="card-actions">
                    <button class="btn btn-icon" onclick="exportTableData()">📥</button>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters">
                <div class="filter-group">
                    <label class="filter-label">Class</label>
                    <select class="filter-select" id="class-filter">
                        <option value="">All Classes</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Min Confidence</label>
                    <input type="number" class="filter-input" id="conf-filter" 
                           min="0" max="100" value="0" step="5">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                    <input type="text" class="filter-input" id="search-filter" placeholder="Search...">
                </div>
                <div class="filter-group" style="justify-content: flex-end;">
                    <label class="filter-label">&nbsp;</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <button class="btn btn-primary" onclick="applyFilters()">Apply</button>
                        <button class="btn btn-icon" onclick="resetFilters()">Reset</button>
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Class</th>
                            <th>Confidence</th>
                            <th>Timestamp</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <tr>
                            <td colspan="5" class="loading">
                                <div class="spinner"></div>
                                Loading data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="status-text" id="table-status">Initializing...</div>
        </div>

    </div>

    <script>
        // Global Variables
        let distributionChart = null;
        let timelineChart = null;
        let confidenceChart = null;
        let allData = [];
        let filteredData = [];
        let classSet = new Set();
        let previousTotal = 0;

        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const newTheme = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            document.getElementById('theme-icon').textContent = newTheme === 'dark' ? '☀️' : '🌙';
            localStorage.setItem('theme', newTheme);
            
            // Update charts colors
            updateChartsTheme();
        }

        // Initialize theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        document.getElementById('theme-icon').textContent = savedTheme === 'dark' ? '☀️' : '🌙';

        // Update Charts Theme
        function updateChartsTheme() {
            const textColor = getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim();
            const gridColor = getComputedStyle(document.documentElement).getPropertyValue('--border').trim();
            
            [distributionChart, timelineChart, confidenceChart].forEach(chart => {
                if (chart) {
                    if (chart.options.plugins?.legend?.labels) {
                        chart.options.plugins.legend.labels.color = textColor;
                    }
                    if (chart.options.scales?.x?.ticks) {
                        chart.options.scales.x.ticks.color = textColor;
                        chart.options.scales.x.grid.color = gridColor;
                    }
                    if (chart.options.scales?.y?.ticks) {
                        chart.options.scales.y.ticks.color = textColor;
                        chart.options.scales.y.grid.color = gridColor;
                    }
                    chart.update();
                }
            });
        }

        // Load Stats
        async function loadStats() {
            try {
                const [statsRes, dataRes] = await Promise.all([
                    fetch('get_game_stats.php'),
                    fetch('get_game_data.php')
                ]);
                
                if (!statsRes.ok || !dataRes.ok) throw new Error('Failed to fetch');
                
                const stats = await statsRes.json();
                const data = await dataRes.json();
                
                // Total Detections
                const totalDetections = stats.values.reduce((a, b) => a + b, 0);
                document.getElementById('total-detections').textContent = totalDetections.toLocaleString();
                
                // Calculate trend
                if (previousTotal > 0) {
                    const trend = ((totalDetections - previousTotal) / previousTotal * 100).toFixed(1);
                    document.getElementById('total-trend').textContent = Math.abs(trend) + '%';
                }
                previousTotal = totalDetections;
                
                // Average Confidence
                if (data.length > 0) {
                    const avgConf = data.reduce((sum, d) => sum + parseFloat(d.confidence), 0) / data.length;
                    document.getElementById('avg-confidence').textContent = (avgConf * 100).toFixed(1) + '%';
                    
                    // Precision (detections with >0.6 confidence)
                    const highConf = data.filter(d => parseFloat(d.confidence) > 0.6).length;
                    const precision = (highConf / data.length * 100).toFixed(1);
                    document.getElementById('precision-value').textContent = precision + '%';
                    
                    // Update performance metrics
                    updatePerformanceMetrics(data);
                }
                
                // Top Class
                if (stats.labels.length > 0) {
                    document.getElementById('top-class').textContent = stats.labels[0];
                }
                
                // Detections per minute
                if (data.length > 1) {
                    const first = new Date(data[data.length - 1].timestamp);
                    const last = new Date(data[0].timestamp);
                    const minutes = Math.max((last - first) / 60000, 1);
                    const perMin = Math.round(data.length / minutes);
                    document.getElementById('fps-value').textContent = perMin;
                }
                
                // Live count
                document.getElementById('live-count').textContent = 
                    (data.length > 0 ? data.slice(0, 5).length : 0) + ' objects';
                
            } catch (err) {
                console.error('Failed to load stats:', err);
            }
        }

        // Update Performance Metrics
        function updatePerformanceMetrics(data) {
            const total = data.length;
            const highConf = data.filter(d => parseFloat(d.confidence) > 0.7).length;
            const medConf = data.filter(d => parseFloat(d.confidence) > 0.5).length;
            
            // Simulated metrics (in real scenario, these would come from validation data)
            const precision = (highConf / total * 100).toFixed(1);
            const recall = (medConf / total * 100).toFixed(1);
            const f1 = (2 * precision * recall / (parseFloat(precision) + parseFloat(recall))).toFixed(1);
            const map = ((parseFloat(precision) + parseFloat(recall)) / 2).toFixed(1);
            
            document.getElementById('precision-metric').textContent = precision + '%';
            document.getElementById('recall-metric').textContent = recall + '%';
            document.getElementById('f1-metric').textContent = f1 + '%';
            document.getElementById('map-metric').textContent = map + '%';
            
            document.getElementById('precision-bar').style.width = precision + '%';
            document.getElementById('recall-bar').style.width = recall + '%';
            document.getElementById('f1-bar').style.width = f1 + '%';
            document.getElementById('map-bar').style.width = map + '%';
        }

        // Load Distribution Chart
        async function loadDistributionChart() {
            try {
                const res = await fetch('get_game_stats.php');
                if (!res.ok) throw new Error('Failed to fetch');
                
                const data = await res.json();
                const ctx = document.getElementById('distributionChart').getContext('2d');
                
                const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'];
                
                if (distributionChart) {
                    distributionChart.data.labels = data.labels;
                    distributionChart.data.datasets[0].data = data.values;
                    distributionChart.update();
                } else {
                    distributionChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.values,
                                backgroundColor: colors,
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary'),
                                        padding: 15,
                                        font: { size: 11 }
                                    }
                                }
                            }
                        }
                    });
                }
                
                document.getElementById('dist-status').textContent = 
                    `Updated: ${new Date().toLocaleTimeString()}`;
            } catch (err) {
                console.error('Failed to load distribution:', err);
                document.getElementById('dist-status').textContent = 'Failed to load';
            }
        }

        // Load Timeline Chart
        async function loadTimelineChart() {
            try {
                const res = await fetch('get_timeline_stats.php');
                if (!res.ok) throw new Error('Failed to fetch');
                
                const data = await res.json();
                const ctx = document.getElementById('timelineChart').getContext('2d');
                
                if (timelineChart) {
                    timelineChart.data.labels = data.labels;
                    timelineChart.data.datasets[0].data = data.values;
                    timelineChart.update();
                } else {
                    timelineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Detections',
                                data: data.values,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary')
                                    },
                                    grid: {
                                        color: getComputedStyle(document.documentElement).getPropertyValue('--border')
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary')
                                    },
                                    grid: {
                                        color: getComputedStyle(document.documentElement).getPropertyValue('--border')
                                    }
                                }
                            }
                        }
                    });
                }
                
                document.getElementById('timeline-status').textContent = 
                    `Updated: ${new Date().toLocaleTimeString()}`;
            } catch (err) {
                console.error('Failed to load timeline:', err);
                document.getElementById('timeline-status').textContent = 'Failed to load';
            }
        }

        // Load Confidence Distribution Chart
        async function loadConfidenceChart() {
            try {
                const res = await fetch('get_game_data.php');
                if (!res.ok) throw new Error('Failed to fetch');
                
                const data = await res.json();
                
                // Create confidence bins
                const bins = {
                    '0-50%': 0,
                    '50-60%': 0,
                    '60-70%': 0,
                    '70-80%': 0,
                    '80-90%': 0,
                    '90-100%': 0
                };
                
                data.forEach(d => {
                    const conf = parseFloat(d.confidence) * 100;
                    if (conf < 50) bins['0-50%']++;
                    else if (conf < 60) bins['50-60%']++;
                    else if (conf < 70) bins['60-70%']++;
                    else if (conf < 80) bins['70-80%']++;
                    else if (conf < 90) bins['80-90%']++;
                    else bins['90-100%']++;
                });
                
                const ctx = document.getElementById('confidenceChart').getContext('2d');
                
                if (confidenceChart) {
                    confidenceChart.data.datasets[0].data = Object.values(bins);
                    confidenceChart.update();
                } else {
                    confidenceChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(bins),
                            datasets: [{
                                label: 'Count',
                                data: Object.values(bins),
                                backgroundColor: [
                                    'rgba(239, 68, 68, 0.7)',
                                    'rgba(245, 158, 11, 0.7)',
                                    'rgba(245, 158, 11, 0.9)',
                                    'rgba(16, 185, 129, 0.7)',
                                    'rgba(16, 185, 129, 0.9)',
                                    'rgba(16, 185, 129, 1)'
                                ],
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary')
                                    },
                                    grid: {
                                        color: getComputedStyle(document.documentElement).getPropertyValue('--border')
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary')
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
                
                document.getElementById('conf-status').textContent = 
                    `Updated: ${new Date().toLocaleTimeString()}`;
            } catch (err) {
                console.error('Failed to load confidence chart:', err);
                document.getElementById('conf-status').textContent = 'Failed to load';
            }
        }

        // Load Heatmap
        async function loadHeatmap() {
            try {
                const res = await fetch('get_game_data.php');
                if (!res.ok) throw new Error('Failed to fetch');
                
                const data = await res.json();
                
                // Create hourly distribution
                const hourCounts = Array(24).fill(0);
                data.forEach(d => {
                    const hour = new Date(d.timestamp).getHours();
                    hourCounts[hour]++;
                });
                
                const maxCount = Math.max(...hourCounts);
                const heatmapContainer = document.getElementById('heatmap');
                heatmapContainer.innerHTML = '';
                
                hourCounts.forEach((count, hour) => {
                    const cell = document.createElement('div');
                    cell.className = 'heatmap-cell';
                    const intensity = maxCount > 0 ? count / maxCount : 0;
                    cell.style.background = `rgba(59, 130, 246, ${intensity * 0.8 + 0.1})`;
                    cell.setAttribute('data-tooltip', `${hour}:00 - ${count} detections`);
                    heatmapContainer.appendChild(cell);
                });
            } catch (err) {
                console.error('Failed to load heatmap:', err);
            }
        }

        // Load Table Data
        async function loadTableData() {
            try {
                const res = await fetch('get_game_data.php');
                if (!res.ok) throw new Error('Failed to fetch');
                
                allData = await res.json();
                
                // Update class filter
                allData.forEach(d => classSet.add(d.class_name));
                updateClassFilter();
                
                applyFilters();
                
                document.getElementById('table-status').textContent = 
                    `Updated: ${new Date().toLocaleTimeString()} | Total: ${allData.length} records`;
            } catch (err) {
                console.error('Failed to load table:', err);
                document.getElementById('table-status').textContent = 'Failed to load data';
            }
        }

        // Update Class Filter
        function updateClassFilter() {
            const select = document.getElementById('class-filter');
            const current = select.value;
            
            select.innerHTML = '<option value="">All Classes</option>';
            Array.from(classSet).sort().forEach(className => {
                const option = document.createElement('option');
                option.value = className;
                option.textContent = className;
                select.appendChild(option);
            });
            
            select.value = current;
        }

        // Apply Filters
        function applyFilters() {
            const classFilter = document.getElementById('class-filter').value;
            const confFilter = parseFloat(document.getElementById('conf-filter').value) / 100;
            const searchFilter = document.getElementById('search-filter').value.toLowerCase();
            
            filteredData = allData.filter(d => {
                const matchClass = !classFilter || d.class_name === classFilter;
                const matchConf = parseFloat(d.confidence) >= confFilter;
                const matchSearch = !searchFilter || 
                    d.class_name.toLowerCase().includes(searchFilter) ||
                    d.id.toString().includes(searchFilter);
                
                return matchClass && matchConf && matchSearch;
            });
            
            renderTable();
        }

        // Reset Filters
        function resetFilters() {
            document.getElementById('class-filter').value = '';
            document.getElementById('conf-filter').value = '0';
            document.getElementById('search-filter').value = '';
            applyFilters();
        }

        // Render Table
        function renderTable() {
            const tbody = document.getElementById('table-body');
            
            if (filteredData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-secondary);">No data found</td></tr>';
                return;
            }
            
            let rows = '';
            filteredData.forEach(d => {
                const confidence = parseFloat(d.confidence) * 100;
                let badgeClass = 'badge-success';
                let badgeText = 'High';
                if (confidence < 70) {
                    badgeClass = 'badge-warning';
                    badgeText = 'Medium';
                }
                if (confidence < 50) {
                    badgeClass = 'badge-danger';
                    badgeText = 'Low';
                }
                
                rows += `<tr>
                    <td><strong>#${d.id}</strong></td>
                    <td><span class="badge badge-info">${d.class_name}</span></td>
                    <td>${confidence.toFixed(1)}%</td>
                    <td>${d.timestamp}</td>
                    <td><span class="badge ${badgeClass}">${badgeText}</span></td>
                </tr>`;
            });
            
            tbody.innerHTML = rows;
        }

        // Export Functions
        function exportTableData() {
            let csv = 'ID,Class,Confidence,Timestamp\n';
            filteredData.forEach(d => {
                csv += `${d.id},"${d.class_name}",${d.confidence},"${d.timestamp}"\n`;
            });
            
            downloadCSV(csv, `detections_${new Date().toISOString().slice(0,10)}.csv`);
        }

        function exportAllData() {
            let csv = 'ID,Class,Confidence,Timestamp\n';
            allData.forEach(d => {
                csv += `${d.id},"${d.class_name}",${d.confidence},"${d.timestamp}"\n`;
            });
            
            downloadCSV(csv, `all_detections_${new Date().toISOString().slice(0,10)}.csv`);
        }

        function downloadCSV(csv, filename) {
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Refresh All
        function refreshAll() {
            loadStats();
            loadDistributionChart();
            loadTimelineChart();
            loadConfidenceChart();
            loadHeatmap();
            loadTableData();
        }

        // Initialize
        window.onload = () => {
            refreshAll();
            
            // Auto-refresh every 3 seconds
            setInterval(refreshAll, 3000);
        };
    </script>
</body>
</html>