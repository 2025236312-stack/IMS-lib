<?php
// members.php - Active Members with Futuristic Glassmorphism Design
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Active Members | SmartLib - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Members Page Specific Styles */
        .stats-grid-futuristic {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 992px) {
            .stats-grid-futuristic { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 576px) {
            .stats-grid-futuristic { grid-template-columns: 1fr; }
        }
        .stat-card-futuristic {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            text-align: center;
        }
        .stat-card-futuristic:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(99, 102, 241, 0.5);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.25rem;
        }
        .stat-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.875rem;
        }
        .members-container {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .search-bar-futuristic {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 60px;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }
        .search-input-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 40px;
            padding: 0.5rem 1rem;
        }
        .search-input-wrapper i {
            color: rgba(255, 255, 255, 0.5);
        }
        .search-input-wrapper input {
            background: transparent;
            border: none;
            color: white;
            width: 100%;
            outline: none;
        }
        .search-input-wrapper input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        .filter-select {
            background: rgba(0, 0, 0, 0.3);
            border: none;
            border-radius: 40px;
            padding: 0.5rem 1rem;
            color: white;
            cursor: pointer;
        }
        .members-table-wrapper {
            overflow-x: auto;
        }
        .members-table {
            width: 100%;
            border-collapse: collapse;
        }
        .members-table th {
            text-align: left;
            padding: 1rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .members-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: white;
        }
        .members-table tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }
        .member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
        }
        .badge-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-active {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        .badge-inactive {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .badge-premium {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #000;
        }
        .chart-container-futuristic {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 1.5rem;
        }
        .chart-wrapper {
            position: relative;
            height: 300px;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .section-title i {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .action-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            padding: 0.25rem 0.5rem;
            color: white;
            margin: 0 0.2rem;
            transition: all 0.3s ease;
        }
        .action-btn:hover {
            background: rgba(99, 102, 241, 0.6);
        }
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            gap: 0.5rem;
        }
        .page-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 0.5rem 1rem;
            color: white;
            transition: all 0.3s ease;
        }
        .page-btn.active, .page-btn:hover {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-color: transparent;
        }
    </style>
</head>
<body data-bs-theme="dark">
<div class="wrapper">
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-2">
                <div class="logo-icon-small"><i class="bi bi-book-half"></i></div>
                <div><h3>SmartLib</h3><p>Library Management</p></div>
            </div>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="books.php"><i class="bi bi-journal-bookmark-fill"></i> Books Collection</a></li>
            <li class="nav-item"><a class="nav-link active" href="members.php"><i class="bi bi-people-fill"></i> Active Members</a></li>
            <li class="nav-item"><a class="nav-link" href="transactions.php"><i class="bi bi-arrow-left-right"></i> Transactions</a></li>
            <li class="nav-item"><a class="nav-link" href="insights.php"><i class="bi bi-graph-up"></i> Insights</a></li>
        </ul>
        <div class="sidebar-footer">
            <div class="theme-toggle-wrapper"><i class="bi bi-sun-fill"></i><div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="darkModeSwitch"></div><i class="bi bi-moon-fill"></i></div>
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 mt-2"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <!-- Hero Section -->
        <div class="futuristic-hero">
            <div class="hero-background">
                <div class="orb orb-1"></div>
                <div class="orb orb-2"></div>
                <div class="orb orb-3"></div>
            </div>
            <div class="hero-content">
                <div class="hero-badge-glass">
                    <span class="badge-dot"></span>
                    <span>Member Management</span>
                </div>
                <h1 class="hero-title-glass">Active<br>Members</h1>
                <p class="hero-subtitle-glass">Manage and monitor your library's registered members</p>
                <div class="hero-stats-glass">
                    <div class="stat-glass"><span class="stat-number" id="totalMembersCount">0</span><span class="stat-label">Total Members</span></div>
                    <div class="stat-glass"><span class="stat-number" id="activeTodayCount">0</span><span class="stat-label">Active Today</span></div>
                    <div class="stat-glass"><span class="stat-number" id="newThisMonthCount">0</span><span class="stat-label">New This Month</span></div>
                </div>
            </div>
        </div>

        <div class="books-container-glass">
            <!-- Stats Grid -->
            <div class="stats-grid-futuristic">
                <div class="stat-card-futuristic">
                    <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                    <div class="stat-number" id="statTotal">0</div>
                    <div class="stat-label">Total Members</div>
                </div>
                <div class="stat-card-futuristic">
                    <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="stat-number" id="statActive">0</div>
                    <div class="stat-label">Active Members</div>
                </div>
                <div class="stat-card-futuristic">
                    <div class="stat-icon"><i class="bi bi-star-fill"></i></div>
                    <div class="stat-number" id="statPremium">0</div>
                    <div class="stat-label">Premium Members</div>
                </div>
                <div class="stat-card-futuristic">
                    <div class="stat-icon"><i class="bi bi-book-fill"></i></div>
                    <div class="stat-number" id="statBorrowed">0</div>
                    <div class="stat-label">Books Borrowed</div>
                </div>
            </div>

            <!-- Members Container -->
            <div class="members-container">
                <div class="search-bar-futuristic">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Search by name, email, or member ID...">
                    </div>
                    <select id="statusFilter" class="filter-select">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <select id="membershipFilter" class="filter-select">
                        <option value="all">All Membership</option>
                        <option value="premium">Premium</option>
                        <option value="standard">Standard</option>
                    </select>
                    <button id="resetFilters" class="filter-select" style="background: rgba(99,102,241,0.3);">Reset</button>
                </div>

                <div class="members-table-wrapper">
                    <table class="members-table" id="membersTable">
                        <thead>
                            <tr><th>Member ID</th><th>Member</th><th>Email</th><th>Phone</th><th>Membership</th><th>Books Borrowed</th><th>Status</th><th>Join Date</th><th>Actions</th></tr>
                        </thead>
                        <tbody id="membersTableBody"></tbody>
                    </table>
                </div>

                <div class="pagination-wrapper" id="paginationWrapper"></div>
            </div>

            <!-- Chart Section -->
            <div class="chart-container-futuristic">
                <div class="section-title"><i class="bi bi-graph-up"></i> Member Activity Analytics</div>
                <div class="chart-wrapper">
                    <canvas id="memberActivityChart"></canvas>
                </div>
            </div>
        </div>

        <footer class="footer-glass"><div class="container"><span>© 2025 SmartLib System — Futuristic Bookstore | Member Management System</span></div></footer>
    </main>
</div>

<!-- Member Detail Modal -->
<div class="modal fade" id="memberDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-glass">
            <div class="modal-header modal-glass-header">
                <h5 class="modal-title"><i class="bi bi-person-badge"></i> Member Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body modal-glass-body" id="memberDetailContent"></div>
        </div>
    </div>
</div>

<script>
// Member Data
const membersData = [
    { id: 'LIB001', name: 'Ahmad Faiz Bin Abdullah', email: 'ahmad.faiz@email.com', phone: '012-3456789', membership: 'premium', books: 3, status: 'active', joinDate: '2024-01-15' },
    { id: 'LIB002', name: 'Nurul Izzati Binti Hassan', email: 'nurul.izzati@email.com', phone: '013-4567890', membership: 'standard', books: 2, status: 'active', joinDate: '2024-02-20' },
    { id: 'LIB003', name: 'Tan Wei Ming', email: 'weiming.tan@email.com', phone: '014-5678901', membership: 'premium', books: 5, status: 'active', joinDate: '2023-11-10' },
    { id: 'LIB004', name: 'Siti Aisyah Binti Omar', email: 'aisyah.omar@email.com', phone: '015-6789012', membership: 'standard', books: 1, status: 'active', joinDate: '2024-03-05' },
    { id: 'LIB005', name: 'Raj Kumar A/L Maniam', email: 'raj.kumar@email.com', phone: '016-7890123', membership: 'premium', books: 4, status: 'active', joinDate: '2023-09-18' },
    { id: 'LIB006', name: 'Lim Su Yin', email: 'suyin.lim@email.com', phone: '017-8901234', membership: 'standard', books: 2, status: 'active', joinDate: '2024-01-22' },
    { id: 'LIB007', name: 'Muhammad Ikhwan Bin Rosli', email: 'ikhwan.rosli@email.com', phone: '018-9012345', membership: 'premium', books: 3, status: 'active', joinDate: '2023-12-01' },
    { id: 'LIB008', name: 'Pavitra A/P Selvam', email: 'pavitra.selvam@email.com', phone: '019-0123456', membership: 'standard', books: 0, status: 'inactive', joinDate: '2024-02-14' },
    { id: 'LIB009', name: 'Wong Kar Wai', email: 'karwai.wong@email.com', phone: '011-1234567', membership: 'premium', books: 7, status: 'active', joinDate: '2023-08-01' },
    { id: 'LIB010', name: 'Farah Syazwani', email: 'farah.syazwani@email.com', phone: '012-2345678', membership: 'standard', books: 2, status: 'active', joinDate: '2024-03-10' },
    { id: 'LIB011', name: 'Goh Soon Huat', email: 'soonhuat.goh@email.com', phone: '013-3456789', membership: 'premium', books: 6, status: 'active', joinDate: '2023-10-15' },
    { id: 'LIB012', name: 'Noraini Binti Sulaiman', email: 'noraini.sulaiman@email.com', phone: '014-4567890', membership: 'standard', books: 1, status: 'inactive', joinDate: '2024-01-05' }
];

let currentPage = 1;
const itemsPerPage = 8;
let filteredMembers = [...membersData];

// Calculate stats
function updateStats() {
    const total = membersData.length;
    const active = membersData.filter(m => m.status === 'active').length;
    const premium = membersData.filter(m => m.membership === 'premium').length;
    const totalBorrowed = membersData.reduce((sum, m) => sum + m.books, 0);
    
    document.getElementById('statTotal').textContent = total;
    document.getElementById('statActive').textContent = active;
    document.getElementById('statPremium').textContent = premium;
    document.getElementById('statBorrowed').textContent = totalBorrowed;
    document.getElementById('totalMembersCount').textContent = total;
    document.getElementById('activeTodayCount').textContent = active;
    document.getElementById('newThisMonthCount').textContent = membersData.filter(m => m.joinDate >= '2025-05-01').length;
}

function applyFilters() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const status = document.getElementById('statusFilter')?.value || 'all';
    const membership = document.getElementById('membershipFilter')?.value || 'all';
    
    filteredMembers = membersData.filter(member => {
        if (searchTerm && !member.name.toLowerCase().includes(searchTerm) && !member.email.toLowerCase().includes(searchTerm) && !member.id.toLowerCase().includes(searchTerm)) return false;
        if (status !== 'all' && member.status !== status) return false;
        if (membership !== 'all' && member.membership !== membership) return false;
        return true;
    });
    currentPage = 1;
    renderMembers();
    renderPagination();
}

function renderMembers() {
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageMembers = filteredMembers.slice(start, end);
    const tbody = document.getElementById('membersTableBody');
    
    if (pageMembers.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align:center; padding:3rem;">No members found</td></tr>';
        return;
    }
    
    tbody.innerHTML = pageMembers.map(member => {
        const initial = member.name.substring(0, 2);
        const joinDate = new Date(member.joinDate).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
        return `
            <tr>
                <td><strong>${member.id}</strong></td>
                <td><div class="d-flex align-items-center gap-2"><div class="member-avatar">${initial}</div><div>${escapeHtml(member.name)}</div></div></td>
                <td>${escapeHtml(member.email)}</td>
                <td>${member.phone}</td>
                <td><span class="badge-status ${member.membership === 'premium' ? 'badge-premium' : ''}" style="${member.membership === 'standard' ? 'background:rgba(100,100,100,0.2); color:#aaa;' : ''}">${member.membership === 'premium' ? '🏆 Premium' : 'Standard'}</span></td>
                <td><span style="background:rgba(99,102,241,0.2); padding:0.25rem 0.75rem; border-radius:20px;">${member.books} books</span></td>
                <td><span class="badge-status ${member.status === 'active' ? 'badge-active' : 'badge-inactive'}">${member.status === 'active' ? '● Active' : '○ Inactive'}</span></td>
                <td>${joinDate}</td>
                <td><button class="action-btn" onclick="viewMember('${member.id}')"><i class="bi bi-eye"></i></button></td>
            </tr>
        `;
    }).join('');
}

function renderPagination() {
    const totalPages = Math.ceil(filteredMembers.length / itemsPerPage);
    const wrapper = document.getElementById('paginationWrapper');
    if (totalPages <= 1) { wrapper.innerHTML = ''; return; }
    
    let html = '';
    for (let i = 1; i <= Math.min(totalPages, 5); i++) {
        html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }
    wrapper.innerHTML = html;
}

function goToPage(page) { currentPage = page; renderMembers(); renderPagination(); }
function resetAllFilters() { document.getElementById('searchInput').value = ''; document.getElementById('statusFilter').value = 'all'; document.getElementById('membershipFilter').value = 'all'; applyFilters(); }
function viewMember(id) { const member = membersData.find(m => m.id === id); if(member) { document.getElementById('memberDetailContent').innerHTML = `<div class="text-center"><div class="member-avatar mx-auto mb-3" style="width:80px; height:80px; font-size:2rem;">${member.name.substring(0,2)}</div><h4>${escapeHtml(member.name)}</h4><p class="text-muted">${member.id}</p><hr><div class="row"><div class="col-6"><small>Email</small><p>${escapeHtml(member.email)}</p></div><div class="col-6"><small>Phone</small><p>${member.phone}</p></div><div class="col-6"><small>Membership</small><p>${member.membership}</p></div><div class="col-6"><small>Status</small><p>${member.status}</p></div><div class="col-6"><small>Books Borrowed</small><p>${member.books}</p></div><div class="col-6"><small>Join Date</small><p>${member.joinDate}</p></div></div></div>`; new bootstrap.Modal(document.getElementById('memberDetailModal')).show(); } }
function escapeHtml(text) { if(!text) return ''; const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }

// Chart: Member Activity
const ctx = document.getElementById('memberActivityChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Active Members', 'Inactive Members'],
        datasets: [{
            data: [membersData.filter(m => m.status === 'active').length, membersData.filter(m => m.status === 'inactive').length],
            backgroundColor: ['#4ade80', '#f87171'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { color: 'white' } } },
        cutout: '60%'
    }
});

// Event Listeners
document.getElementById('searchInput')?.addEventListener('input', applyFilters);
document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
document.getElementById('membershipFilter')?.addEventListener('change', applyFilters);
document.getElementById('resetFilters')?.addEventListener('click', resetAllFilters);

// Initialize
updateStats();
applyFilters();

// Dark mode toggle
const toggle = document.getElementById('darkModeSwitch');
const theme = localStorage.getItem('libTheme') || 'dark';
document.body.setAttribute('data-bs-theme', theme);
if(toggle) { if(theme === 'dark') toggle.checked = true; toggle.addEventListener('change', (e) => { const nt = e.target.checked ? 'dark' : 'light'; document.body.setAttribute('data-bs-theme', nt); localStorage.setItem('libTheme', nt); }); }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>