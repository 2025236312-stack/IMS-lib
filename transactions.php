<?php
// transactions.php - Transactions Management
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

// Initialize transactions in session
if (!isset($_SESSION['transactions_data'])) {
    $_SESSION['transactions_data'] = [
        ['id' => 'TRX001', 'book' => 'The Psychology of Money', 'member' => 'Ahmad Faiz', 'borrow' => '2025-05-01', 'due' => '2025-05-15', 'return' => null, 'status' => 'borrowed', 'fine' => 0],
        ['id' => 'TRX002', 'book' => 'Rich Dad Poor Dad', 'member' => 'Nurul Izzati', 'borrow' => '2025-05-02', 'due' => '2025-05-16', 'return' => null, 'status' => 'borrowed', 'fine' => 0],
        ['id' => 'TRX003', 'book' => 'Watchmen', 'member' => 'Tan Wei Ming', 'borrow' => '2025-04-20', 'due' => '2025-05-04', 'return' => '2025-05-03', 'status' => 'returned', 'fine' => 0],
        ['id' => 'TRX004', 'book' => 'Pride and Prejudice', 'member' => 'Siti Aisyah', 'borrow' => '2025-04-25', 'due' => '2025-05-09', 'return' => null, 'status' => 'overdue', 'fine' => 15],
        ['id' => 'TRX005', 'book' => 'The Sandman', 'member' => 'Raj Kumar', 'borrow' => '2025-05-03', 'due' => '2025-05-17', 'return' => null, 'status' => 'borrowed', 'fine' => 0],
        ['id' => 'TRX006', 'book' => 'To Kill a Mockingbird', 'member' => 'Lim Su Yin', 'borrow' => '2025-04-28', 'due' => '2025-05-12', 'return' => '2025-05-10', 'status' => 'returned', 'fine' => 0],
        ['id' => 'TRX007', 'book' => 'The Great Gatsby', 'member' => 'Muhammad Ikhwan', 'borrow' => '2025-04-15', 'due' => '2025-04-29', 'return' => null, 'status' => 'overdue', 'fine' => 30],
        ['id' => 'TRX008', 'book' => 'The Intelligent Investor', 'member' => 'Pavitra', 'borrow' => '2025-05-05', 'due' => '2025-05-19', 'return' => null, 'status' => 'borrowed', 'fine' => 0],
        ['id' => 'TRX009', 'book' => '1984', 'member' => 'Wong Kar Wai', 'borrow' => '2025-05-06', 'due' => '2025-05-20', 'return' => null, 'status' => 'borrowed', 'fine' => 0],
        ['id' => 'TRX010', 'book' => 'Harry Potter', 'member' => 'Farah Syazwani', 'borrow' => '2025-04-30', 'due' => '2025-05-14', 'return' => '2025-05-12', 'status' => 'returned', 'fine' => 0]
    ];
}

// Initialize books for dropdown
if (!isset($_SESSION['books_data'])) {
    $_SESSION['books_data'] = [
        ['id' => 1, 'title' => 'The Psychology of Money', 'author' => 'Morgan Housel', 'available' => true],
        ['id' => 2, 'title' => 'Rich Dad Poor Dad', 'author' => 'Robert Kiyosaki', 'available' => true],
        ['id' => 3, 'title' => 'Watchmen', 'author' => 'Alan Moore', 'available' => true],
        ['id' => 4, 'title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'available' => false],
        ['id' => 5, 'title' => 'The Sandman', 'author' => 'Neil Gaiman', 'available' => true],
        ['id' => 6, 'title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'available' => true],
        ['id' => 7, 'title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'available' => false],
        ['id' => 8, 'title' => 'The Intelligent Investor', 'author' => 'Benjamin Graham', 'available' => true],
        ['id' => 9, 'title' => '1984', 'author' => 'George Orwell', 'available' => true],
        ['id' => 10, 'title' => 'Harry Potter', 'author' => 'J.K. Rowling', 'available' => true]
    ];
}

// Initialize members for dropdown
if (!isset($_SESSION['members_data'])) {
    $_SESSION['members_data'] = [
        ['id' => 1, 'name' => 'Ahmad Faiz', 'email' => 'ahmad@email.com', 'membership' => 'Premium'],
        ['id' => 2, 'name' => 'Nurul Izzati', 'email' => 'nurul@email.com', 'membership' => 'Regular'],
        ['id' => 3, 'name' => 'Tan Wei Ming', 'email' => 'wei@email.com', 'membership' => 'Premium'],
        ['id' => 4, 'name' => 'Siti Aisyah', 'email' => 'siti@email.com', 'membership' => 'Regular'],
        ['id' => 5, 'name' => 'Raj Kumar', 'email' => 'raj@email.com', 'membership' => 'Student'],
        ['id' => 6, 'name' => 'Lim Su Yin', 'email' => 'suyin@email.com', 'membership' => 'Premium'],
        ['id' => 7, 'name' => 'Muhammad Ikhwan', 'email' => 'ikhwan@email.com', 'membership' => 'Regular'],
        ['id' => 8, 'name' => 'Pavitra', 'email' => 'pavitra@email.com', 'membership' => 'Student'],
        ['id' => 9, 'name' => 'Wong Kar Wai', 'email' => 'karwai@email.com', 'membership' => 'Premium'],
        ['id' => 10, 'name' => 'Farah Syazwani', 'email' => 'farah@email.com', 'membership' => 'Regular']
    ];
}

// Generate new transaction ID
function generateTransactionId() {
    $lastId = 0;
    foreach ($_SESSION['transactions_data'] as $transaction) {
        $num = (int)substr($transaction['id'], 3);
        if ($num > $lastId) $lastId = $num;
    }
    $newNum = $lastId + 1;
    return 'TRX' . str_pad($newNum, 3, '0', STR_PAD_LEFT);
}

// Handle Add New Transaction
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_transaction') {
    $bookTitle = $_POST['book_title'];
    $memberName = $_POST['member_name'];
    $borrowDate = $_POST['borrow_date'];
    $dueDate = $_POST['due_date'];
    
    if (empty($dueDate)) {
        $dueDate = date('Y-m-d', strtotime($borrowDate . ' + 14 days'));
    }
    
    $newTransaction = [
        'id' => generateTransactionId(),
        'book' => $bookTitle,
        'member' => $memberName,
        'borrow' => $borrowDate,
        'due' => $dueDate,
        'return' => null,
        'status' => 'borrowed',
        'fine' => 0
    ];
    
    array_unshift($_SESSION['transactions_data'], $newTransaction);
    $_SESSION['message'] = ['type' => 'success', 'text' => '📚 New transaction added successfully!'];
    
    header('Location: transactions.php');
    exit();
}

// Handle Return Book
if (isset($_GET['return'])) {
    $id = $_GET['return'];
    foreach ($_SESSION['transactions_data'] as $key => $t) {
        if ($t['id'] === $id && $t['status'] === 'borrowed') {
            $_SESSION['transactions_data'][$key]['return'] = date('Y-m-d');
            $_SESSION['transactions_data'][$key]['status'] = 'returned';
            $_SESSION['message'] = ['type' => 'success', 'text' => '📚 Book returned successfully!'];
            break;
        }
    }
    header('Location: transactions.php');
    exit();
}

// Handle Delete Transaction
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    foreach ($_SESSION['transactions_data'] as $key => $t) {
        if ($t['id'] === $id) {
            unset($_SESSION['transactions_data'][$key]);
            $_SESSION['transactions_data'] = array_values($_SESSION['transactions_data']);
            $_SESSION['message'] = ['type' => 'danger', 'text' => '🗑️ Transaction deleted successfully!'];
            break;
        }
    }
    header('Location: transactions.php');
    exit();
}

$transactionsData = $_SESSION['transactions_data'];
$booksData = $_SESSION['books_data'];
$membersData = $_SESSION['members_data'];
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Transactions | SmartLib - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/transactions.css">
    <style>
        /* FORCE TABLE BORDERS - GUARANTEED WORKING */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }
        
        .transactions-table {
            width: 100%;
            min-width: 1000px;
            border-collapse: collapse;
        }
        
        .transactions-table th,
        .transactions-table td {
            border: 1px solid #3e3e42 !important;
            padding: 12px 10px !important;
            text-align: left !important;
            vertical-align: middle !important;
        }
        
        .transactions-table th {
            background: #2a2a2e;
            color: #a1a1aa;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .transactions-table td {
            background: var(--bs-card-bg);
            color: var(--bs-body-color);
            font-size: 13px;
        }
        
        /* Light mode border color */
        [data-bs-theme="light"] .transactions-table th,
        [data-bs-theme="light"] .transactions-table td {
            border: 1px solid #d4d4d8 !important;
        }
        
        [data-bs-theme="light"] .transactions-table th {
            background: #f4f4f5;
            color: #71717a;
        }
        
        /* Status badges */
        .badge-transaction {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .badge-borrowed {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }
        
        .badge-returned {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
        }
        
        .badge-overdue {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }
        
        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .return-btn, .delete-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }
        
        .return-btn {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }
        
        .return-btn:hover {
            background: #22c55e;
            color: white;
        }
        
        .delete-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        
        .delete-btn:hover {
            background: #ef4444;
            color: white;
        }
        
        .fine-positive {
            color: #ef4444;
            font-weight: 600;
        }
        
        .fine-zero {
            color: #71717a;
        }
        
        /* Stats cards */
        .transactions-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card-glass {
            background: var(--bs-card-bg);
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            border: 1px solid var(--bs-border-color);
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: var(--bs-secondary-color);
            margin-top: 5px;
        }
        
        /* Filter buttons */
        .filter-bar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            background: var(--bs-secondary-bg);
            border: 1px solid var(--bs-border-color);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 13px;
            cursor: pointer;
        }
        
        .filter-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-color: transparent;
        }
        
        /* Search bar */
        .search-input-wrapper {
            position: relative;
            margin-bottom: 20px;
        }
        
        .search-input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #71717a;
        }
        
        .search-input-wrapper input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 10px;
            font-size: 13px;
        }
        
        /* Pagination */
        .pagination-controls {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 20px;
        }
        
        .page-btn {
            background: var(--bs-secondary-bg);
            border: 1px solid var(--bs-border-color);
            padding: 5px 12px;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .page-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .transactions-table th,
            .transactions-table td {
                padding: 8px 6px !important;
                font-size: 11px !important;
            }
            
            .transactions-table {
                min-width: 800px;
            }
            
            .transactions-stats {
                grid-template-columns: repeat(2, 1fr);
            }
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
            <li class="nav-item"><a class="nav-link" href="members.php"><i class="bi bi-people-fill"></i> Active Members</a></li>
            <li class="nav-item"><a class="nav-link active" href="transactions.php"><i class="bi bi-arrow-left-right"></i> Transactions</a></li>
            <li class="nav-item"><a class="nav-link" href="insights.php"><i class="bi bi-graph-up"></i> Insights</a></li>
        </ul>
        <div class="sidebar-footer">
            <div class="theme-toggle-wrapper">
                <i class="bi bi-sun-fill"></i>
                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="darkModeSwitch"></div>
                <i class="bi bi-moon-fill"></i>
            </div>
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 mt-2"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <div class="futuristic-hero">
            <div class="hero-background">
                <div class="orb orb-1"></div>
                <div class="orb orb-2"></div>
                <div class="orb orb-3"></div>
            </div>
            <div class="hero-content">
                <div class="hero-badge-glass"><span class="badge-dot"></span><span>Transaction Management</span></div>
                <h1 class="hero-title-glass">Borrowing<br>Transactions</h1>
                <p class="hero-subtitle-glass">Track all book borrowing and return activities</p>
            </div>
        </div>

        <div class="books-container-glass">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message['type']; ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo $message['text']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="transactions-stats" id="statsContainer"></div>
            
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div class="filter-bar" id="filterBar"></div>
                <div class="d-flex gap-2">
                    <button class="btn add-transaction-btn" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                        <i class="bi bi-plus-lg"></i> New Transaction
                    </button>
                    <div class="date-range-picker d-flex align-items-center gap-2 bg-secondary bg-opacity-25 px-3 rounded-pill">
                        <i class="bi bi-calendar3"></i>
                        <input type="date" id="startDate" class="border rounded px-2 py-1" style="font-size:12px">
                        <span>→</span>
                        <input type="date" id="endDate" class="border rounded px-2 py-1" style="font-size:12px">
                        <button id="refreshBtn" class="btn btn-sm"><i class="bi bi-arrow-repeat"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="search-input-wrapper mb-3">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Search by book title or member name...">
            </div>
            
            <div class="table-responsive">
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Book</th>
                            <th>Member</th>
                            <th>Borrow Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Fine</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsBody"></tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper" id="paginationWrapper"></div>
        </div>

        <footer class="footer-glass">
            <div class="container"><span>© 2025 SmartLib System — Library Management | Transaction Management</span></div>
        </footer>
    </main>
</div>

<!-- Add Transaction Modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-glass">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> New Borrowing Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add_transaction">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Book</label>
                        <select name="book_title" class="form-select" required>
                            <option value="">-- Choose a book --</option>
                            <?php foreach ($booksData as $book): ?>
                                <?php if ($book['available']): ?>
                                <option value="<?php echo htmlspecialchars($book['title']); ?>">
                                    <?php echo htmlspecialchars($book['title']); ?> by <?php echo htmlspecialchars($book['author']); ?>
                                </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Member</label>
                        <select name="member_name" class="form-select" required>
                            <option value="">-- Choose a member --</option>
                            <?php foreach ($membersData as $member): ?>
                            <option value="<?php echo htmlspecialchars($member['name']); ?>">
                                <?php echo htmlspecialchars($member['name']); ?> (<?php echo $member['membership']; ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Borrow Date</label>
                            <input type="date" name="borrow_date" class="form-control" id="borrowDate" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control" id="dueDate">
                        </div>
                    </div>
                    <div class="alert alert-info py-2">
                        <i class="bi bi-info-circle"></i> Borrowing period: 14 days. Late fine: RM1.00/day
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Borrowing</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const transactionsData = <?php echo json_encode($transactionsData); ?>;
let currentPage = 1;
const itemsPerPage = 8;
let currentFilter = 'all';
let filteredTransactions = [...transactionsData];

document.getElementById('borrowDate')?.addEventListener('change', function() {
    if (!document.getElementById('dueDate').value) {
        const date = new Date(this.value);
        date.setDate(date.getDate() + 14);
        document.getElementById('dueDate').value = date.toISOString().split('T')[0];
    }
});

function updateStats() {
    const borrowed = transactionsData.filter(t => t.status === 'borrowed').length;
    const returned = transactionsData.filter(t => t.status === 'returned').length;
    const overdue = transactionsData.filter(t => t.status === 'overdue').length;
    const totalFine = transactionsData.reduce((sum, t) => sum + t.fine, 0);
    document.getElementById('statsContainer').innerHTML = `
        <div class="stat-card-glass"><div class="stat-number">${borrowed}</div><div class="stat-label">📖 Active Borrowings</div></div>
        <div class="stat-card-glass"><div class="stat-number">${returned}</div><div class="stat-label">✅ Returned</div></div>
        <div class="stat-card-glass"><div class="stat-number">${overdue}</div><div class="stat-label">⚠️ Overdue</div></div>
        <div class="stat-card-glass"><div class="stat-number">RM${totalFine}</div><div class="stat-label">💰 Total Fines</div></div>
    `;
}

function renderFilterBar() {
    const filters = ['all', 'borrowed', 'returned', 'overdue'];
    const labels = { all: 'All', borrowed: 'Borrowed', returned: 'Returned', overdue: 'Overdue' };
    document.getElementById('filterBar').innerHTML = filters.map(f => `<button class="filter-btn ${f === currentFilter ? 'active' : ''}" onclick="setFilter('${f}')">${labels[f]}</button>`).join('');
}

function setFilter(filter) { currentFilter = filter; applyFilters(); }

function applyFilters() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const startDate = document.getElementById('startDate')?.value;
    const endDate = document.getElementById('endDate')?.value;
    filteredTransactions = transactionsData.filter(t => {
        if (currentFilter !== 'all' && t.status !== currentFilter) return false;
        if (searchTerm && !t.book.toLowerCase().includes(searchTerm) && !t.member.toLowerCase().includes(searchTerm)) return false;
        if (startDate && t.borrow < startDate) return false;
        if (endDate && t.borrow > endDate) return false;
        return true;
    });
    currentPage = 1;
    renderTransactions();
    renderPagination();
}

function renderTransactions() {
    const start = (currentPage - 1) * itemsPerPage;
    const pageTransactions = filteredTransactions.slice(start, start + itemsPerPage);
    const tbody = document.getElementById('transactionsBody');
    if (pageTransactions.length === 0) { tbody.innerHTML = '<td><td colspan="9" style="text-align:center; padding:40px;">No transactions found</td></tr>'; return; }
    tbody.innerHTML = pageTransactions.map(t => {
        const statusClass = `badge-transaction badge-${t.status}`;
        return `<tr>
            <td><strong>${t.id}</strong></td>
            <td>${t.book}</td>
            <td>${t.member}</td>
            <td>${t.borrow}</td>
            <td>${t.due}</td>
            <td>${t.return || '-'}</td>
            <td><span class="${statusClass}">${t.status}</span></td>
            <td class="${t.fine > 0 ? 'fine-positive' : 'fine-zero'}">${t.fine > 0 ? `RM${t.fine}` : '-'}</td>
            <td class="action-buttons">
                ${t.status === 'borrowed' ? `<a href="?return=${t.id}" class="return-btn" onclick="return confirm('Return this book?')"><i class="bi bi-arrow-return-left"></i></a>` : ''}
                <button class="delete-btn" onclick="deleteTransaction('${t.id}')"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;
    }).join('');
}

function deleteTransaction(id) {
    if (confirm('Delete this transaction?')) window.location.href = `?delete=${id}`;
}

function renderPagination() {
    const totalPages = Math.ceil(filteredTransactions.length / itemsPerPage);
    const wrapper = document.getElementById('paginationWrapper');
    if (totalPages <= 1) { wrapper.innerHTML = ''; return; }
    let html = '<div class="pagination-controls">';
    if (currentPage > 1) html += `<button class="page-btn" onclick="goToPage(${currentPage - 1})">Prev</button>`;
    for (let i = 1; i <= Math.min(totalPages, 5); i++) html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    if (currentPage < totalPages) html += `<button class="page-btn" onclick="goToPage(${currentPage + 1})">Next</button>`;
    html += '</div>';
    wrapper.innerHTML = html;
}

function goToPage(page) { currentPage = page; renderTransactions(); renderPagination(); }

document.getElementById('searchInput')?.addEventListener('input', applyFilters);
document.getElementById('startDate')?.addEventListener('change', applyFilters);
document.getElementById('endDate')?.addEventListener('change', applyFilters);
document.getElementById('refreshBtn')?.addEventListener('click', () => {
    document.getElementById('searchInput').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    setFilter('all');
});

updateStats();
renderFilterBar();
applyFilters();

const toggle = document.getElementById('darkModeSwitch');
const theme = localStorage.getItem('libTheme') || 'dark';
document.body.setAttribute('data-bs-theme', theme);
if(toggle) { if(theme === 'dark') toggle.checked = true; toggle.addEventListener('change', (e) => { const nt = e.target.checked ? 'dark' : 'light'; document.body.setAttribute('data-bs-theme', nt); localStorage.setItem('libTheme', nt); }); }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>