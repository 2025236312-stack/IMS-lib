<?php
// insights.php - Insights & Analytics Dashboard with Working Filters
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

// Define datasets for different time periods
$periods = [
    'weekly' => [
        'name' => 'This Week',
        'borrowing_data' => [42, 38, 45, 52, 48, 35, 30],
        'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        'new_members' => [5, 3, 7, 4, 6, 2, 1],
        'active_members' => [145, 147, 150, 152, 154, 155, 156],
        'top_books' => [12, 10, 8, 7, 6],
        'trend_data' => [32, 35, 38, 42, 45]
    ],
    'monthly' => [
        'name' => 'This Month',
        'borrowing_data' => [245, 278, 312, 298, 342, 389, 356, 334, 367, 389, 402, 378, 356, 390, 412, 398, 385, 402, 418, 425, 398, 412, 435, 445, 432, 456, 478, 490, 485, 502],
        'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        'new_members' => [12, 18, 15, 22],
        'active_members' => [145, 148, 152, 156],
        'top_books' => [47, 42, 38, 35, 31],
        'trend_data' => [28, 32, 35, 38, 42]
    ],
    'quarterly' => [
        'name' => 'This Quarter',
        'borrowing_data' => [245, 278, 312, 298, 342, 389, 356, 334, 367, 389, 402, 378, 356],
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        'new_members' => [28, 32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65],
        'active_members' => [120, 125, 130, 135, 140, 145, 148, 150, 152, 154, 155, 156],
        'top_books' => [156, 142, 138, 125, 118],
        'trend_data' => [65, 70, 78, 85, 92, 98, 105, 112, 118, 125, 132, 140]
    ],
    'yearly' => [
        'name' => 'This Year',
        'borrowing_data' => [2450, 2780, 3120, 2980, 3420, 3890, 3560, 3340, 3670, 3890, 4020, 3780],
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        'new_members' => [28, 32, 35, 38, 42, 45, 48, 52, 55, 58, 62, 65],
        'active_members' => [120, 125, 130, 135, 140, 145, 148, 150, 152, 154, 155, 156],
        'top_books' => [520, 480, 450, 420, 390],
        'trend_data' => [65, 70, 78, 85, 92, 98, 105, 112, 118, 125, 132, 140]
    ]
];

$currentPeriod = $_GET['period'] ?? 'monthly';
if (!isset($periods[$currentPeriod])) {
    $currentPeriod = 'monthly';
}
$data = $periods[$currentPeriod];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Insights | SmartLib - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/insights.css">
</head>
<body data-bs-theme="dark">
<div class="wrapper">
    <nav class="sidebar">
        <div class="sidebar-header"><div class="d-flex align-items-center gap-2"><div class="logo-icon-small"><i class="bi bi-book-half"></i></div><div><h3>SmartLib</h3><p>Library Management</p></div></div></div>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="books.php"><i class="bi bi-journal-bookmark-fill"></i> Books Collection</a></li>
            <li class="nav-item"><a class="nav-link" href="members.php"><i class="bi bi-people-fill"></i> Active Members</a></li>
            <li class="nav-item"><a class="nav-link" href="transactions.php"><i class="bi bi-arrow-left-right"></i> Transactions</a></li>
            <li class="nav-item"><a class="nav-link active" href="insights.php"><i class="bi bi-graph-up"></i> Insights</a></li>
        </ul>
        <div class="sidebar-footer"><div class="theme-toggle-wrapper"><i class="bi bi-sun-fill"></i><div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="darkModeSwitch"></div><i class="bi bi-moon-fill"></i></div><a href="logout.php" class="btn btn-outline-danger btn-sm w-100 mt-2"><i class="bi bi-box-arrow-right"></i> Logout</a></div>
    </nav>

    <main class="main-content">
        <div class="futuristic-hero">
            <div class="hero-background">
                <div class="orb orb-1"></div>
                <div class="orb orb-2"></div>
                <div class="orb orb-3"></div>
            </div>
            <div class="hero-content">
                <div class="hero-badge-glass"><span class="badge-dot"></span><span>Analytics Dashboard</span></div>
                <h1 class="hero-title-glass">Library<br>Insights</h1>
                <p class="hero-subtitle-glass">Data-driven analytics for smarter library management</p>
            </div>
        </div>

        <div class="books-container-glass">
            <!-- Period Filter -->
            <div class="date-filter">
                <i class="bi bi-funnel me-2"></i>
                <select id="periodFilter">
                    <option value="weekly" <?php echo $currentPeriod === 'weekly' ? 'selected' : ''; ?>>📅 This Week</option>
                    <option value="monthly" <?php echo $currentPeriod === 'monthly' ? 'selected' : ''; ?>>📆 This Month</option>
                    <option value="quarterly" <?php echo $currentPeriod === 'quarterly' ? 'selected' : ''; ?>>📊 This Quarter</option>
                    <option value="yearly" <?php echo $currentPeriod === 'yearly' ? 'selected' : ''; ?>>🎯 This Year</option>
                </select>
            </div>

            <!-- Info Alert for current period -->
            <div class="alert-info-custom" id="periodInfo">
                <i class="bi bi-info-circle-fill me-2"></i>
                Showing data for: <strong><?php echo $data['name']; ?></strong>
            </div>

            <!-- KPI Cards -->
            <div class="kpi-grid">
                <div class="kpi-card"><div class="kpi-number" id="totalBooksKPI">0</div><div class="kpi-label">📚 Total Books</div><small class="trend-up"><i class="bi bi-arrow-up"></i> +12%</small></div>
                <div class="kpi-card"><div class="kpi-number" id="activeMembersKPI">0</div><div class="kpi-label">👥 Active Members</div><small class="trend-up"><i class="bi bi-arrow-up"></i> +8%</small></div>
                <div class="kpi-card"><div class="kpi-number" id="monthlyBorrowKPI">0</div><div class="kpi-label">📖 Borrows</div><small class="trend-up" id="borrowTrend"><i class="bi bi-arrow-up"></i> +23%</small></div>
                <div class="kpi-card"><div class="kpi-number" id="satisfactionKPI">0%</div><div class="kpi-label">⭐ Satisfaction</div><small class="trend-up"><i class="bi bi-arrow-up"></i> +5%</small></div>
            </div>

            <!-- Charts Grid -->
            <div class="insights-grid">
                <div class="insight-card">
                    <h5><i class="bi bi-graph-up"></i> Borrowing Trend (<?php echo $data['name']; ?>)</h5>
                    <div class="chart-wrapper"><canvas id="trendChart"></canvas></div>
                </div>
                <div class="insight-card">
                    <h5><i class="bi bi-pie-chart"></i> Genre Distribution</h5>
                    <div class="chart-wrapper"><canvas id="genreChart"></canvas></div>
                </div>
                <div class="insight-card">
                    <h5><i class="bi bi-bar-chart"></i> Top Borrowed Books</h5>
                    <div class="chart-wrapper"><canvas id="topBooksChart"></canvas></div>
                </div>
                <div class="insight-card">
                    <h5><i class="bi bi-activity"></i> Member Growth (<?php echo $data['name']; ?>)</h5>
                    <div class="chart-wrapper"><canvas id="memberGrowthChart"></canvas></div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="performance-metrics">
                <h5 class="mb-3"><i class="bi bi-speedometer2"></i> 📊 Performance Metrics</h5>
                <div class="row">
                    <div class="col-md-3 col-6 mb-3"><div class="text-center"><div class="metric-value" id="onTimeRate">94%</div><div class="metric-label">On-Time Return</div><div class="progress mt-2"><div class="progress-bar bg-success" style="width:94%"></div></div></div></div>
                    <div class="col-md-3 col-6 mb-3"><div class="text-center"><div class="metric-value" id="retentionRate">87%</div><div class="metric-label">Member Retention</div><div class="progress mt-2"><div class="progress-bar bg-info" style="width:87%"></div></div></div></div>
                    <div class="col-md-3 col-6 mb-3"><div class="text-center"><div class="metric-value" id="avgBooks">2.4</div><div class="metric-label">Avg Books/Member</div><div class="progress mt-2"><div class="progress-bar bg-warning" style="width:48%"></div></div></div></div>
                    <div class="col-md-3 col-6 mb-3"><div class="text-center"><div class="metric-value" id="totalRead">1,284</div><div class="metric-label">Total Books Read</div><div class="progress mt-2"><div class="progress-bar bg-primary" style="width:64%"></div></div></div></div>
                </div>
            </div>
        </div>

        <footer class="footer-glass"><div class="container"><span>© 2025 SmartLib System — Futuristic Bookstore | Analytics Dashboard</span></div></footer>
    </main>
</div>

<script>
// Data from PHP
const periodData = <?php echo json_encode($data); ?>;
const currentPeriod = '<?php echo $currentPeriod; ?>';

// KPI Base Data
const totalBooks = 50;
const activeMembers = 28;
const satisfaction = 50;

// Chart instances
let trendChart = null;
let genreChart = null;
let topBooksChart = null;
let memberGrowthChart = null;

// Update KPI display
document.getElementById('totalBooksKPI').textContent = totalBooks.toLocaleString();
document.getElementById('activeMembersKPI').textContent = activeMembers;

// Calculate total borrows based on period
let totalBorrows = 0;
if (currentPeriod === 'weekly') {
    totalBorrows = periodData.borrowing_data.reduce((a, b) => a + b, 0);
    document.getElementById('borrowTrend').innerHTML = '<i class="bi bi-arrow-up"></i> +5%';
} else if (currentPeriod === 'monthly') {
    totalBorrows = 10;
    document.getElementById('borrowTrend').innerHTML = '<i class="bi bi-arrow-up"></i> +23%';
} else if (currentPeriod === 'quarterly') {
    totalBorrows = 1250;
    document.getElementById('borrowTrend').innerHTML = '<i class="bi bi-arrow-up"></i> +15%';
} else {
    totalBorrows = 4850;
    document.getElementById('borrowTrend').innerHTML = '<i class="bi bi-arrow-up"></i> +18%';
}
document.getElementById('monthlyBorrowKPI').textContent = totalBorrows.toLocaleString();
document.getElementById('satisfactionKPI').textContent = satisfaction;

function getChartTextColor() { 
    return document.body.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' : '#1e293b'; 
}

function getGridColor() {
    return document.body.getAttribute('data-bs-theme') === 'dark' ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';
}

// Genre Distribution Data (static - doesn't change by period)
const genreLabels = ['Fiction (32%)', 'Non-Fiction (18%)', 'Romance (15%)', 'Science (12%)', 'Children (10%)', 'Others (13%)'];
const genreData = [32, 18, 15, 12, 10, 13];
const genreColors = ['#6366f1', '#8b5cf6', '#ec4899', '#f97316', '#22c55e', '#a855f7'];

// Top Books Labels (static)
const topBooksLabels = ['The Psychology of Money', 'Harry Potter', 'Rich Dad Poor Dad', 'The Great Gatsby', '1984'];

function initCharts() {
    const textColor = getChartTextColor();
    const gridColor = getGridColor();
    
    // Destroy existing charts
    if (trendChart) trendChart.destroy();
    if (genreChart) genreChart.destroy();
    if (topBooksChart) topBooksChart.destroy();
    if (memberGrowthChart) memberGrowthChart.destroy();
    
    // Trend Chart (Borrowing Trend)
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: periodData.labels,
                datasets: [{
                    label: 'Books Borrowed',
                    data: periodData.borrowing_data,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointRadius: 5,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: textColor, font: { size: 12 } } },
                    tooltip: { backgroundColor: document.body.getAttribute('data-bs-theme') === 'dark' ? '#1e293b' : '#ffffff', titleColor: textColor, bodyColor: textColor }
                },
                scales: {
                    y: { grid: { color: gridColor }, ticks: { color: textColor } },
                    x: { grid: { display: false }, ticks: { color: textColor } }
                }
            }
        });
    }
    
    // Genre Chart (Doughnut)
    const genreCtx = document.getElementById('genreChart');
    if (genreCtx) {
        genreChart = new Chart(genreCtx, {
            type: 'doughnut',
            data: {
                labels: genreLabels,
                datasets: [{ data: genreData, backgroundColor: genreColors, borderWidth: 0, hoverOffset: 10 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: textColor, font: { size: 10 }, usePointStyle: true } },
                    tooltip: { backgroundColor: document.body.getAttribute('data-bs-theme') === 'dark' ? '#1e293b' : '#ffffff', titleColor: textColor, bodyColor: textColor }
                },
                cutout: '60%'
            }
        });
    }
    
    // Top Books Chart (Bar - Horizontal)
    const topBooksCtx = document.getElementById('topBooksChart');
    if (topBooksCtx) {
        topBooksChart = new Chart(topBooksCtx, {
            type: 'bar',
            data: {
                labels: topBooksLabels,
                datasets: [{
                    label: 'Times Borrowed',
                    data: periodData.top_books,
                    backgroundColor: '#6366f1',
                    borderRadius: 8,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { labels: { color: textColor } },
                    tooltip: { backgroundColor: document.body.getAttribute('data-bs-theme') === 'dark' ? '#1e293b' : '#ffffff', titleColor: textColor, bodyColor: textColor }
                },
                scales: {
                    x: { ticks: { color: textColor }, grid: { color: gridColor } },
                    y: { ticks: { color: textColor }, grid: { display: false } }
                }
            }
        });
    }
    
    // Member Growth Chart
    const memberCtx = document.getElementById('memberGrowthChart');
    if (memberCtx) {
        memberGrowthChart = new Chart(memberCtx, {
            type: 'line',
            data: {
                labels: periodData.labels,
                datasets: [
                    {
                        label: 'New Members',
                        data: periodData.new_members,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#22c55e',
                        pointBorderColor: '#ffffff',
                        pointRadius: 5,
                        pointHoverRadius: 8
                    },
                    {
                        label: 'Active Members',
                        data: periodData.active_members,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99,102,241,0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#ffffff',
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { color: textColor, font: { size: 11 }, usePointStyle: true } },
                    tooltip: { backgroundColor: document.body.getAttribute('data-bs-theme') === 'dark' ? '#1e293b' : '#ffffff', titleColor: textColor, bodyColor: textColor }
                },
                scales: {
                    y: { grid: { color: gridColor }, ticks: { color: textColor } },
                    x: { grid: { display: false }, ticks: { color: textColor } }
                }
            }
        });
    }
}

// Period filter change handler
document.getElementById('periodFilter').addEventListener('change', function() {
    const period = this.value;
    window.location.href = `insights.php?period=${period}`;
});

// Dark mode toggle
const toggle = document.getElementById('darkModeSwitch');
const theme = localStorage.getItem('libTheme') || 'dark';
document.body.setAttribute('data-bs-theme', theme);
if(toggle) { 
    if(theme === 'dark') toggle.checked = true; 
    toggle.addEventListener('change', function(e) { 
        const newTheme = e.target.checked ? 'dark' : 'light'; 
        document.body.setAttribute('data-bs-theme', newTheme); 
        localStorage.setItem('libTheme', newTheme);
        // Reinitialize charts with new theme colors
        setTimeout(function() { initCharts(); }, 100);
    }); 
}

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initCharts();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>