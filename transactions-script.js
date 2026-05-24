// ========== TRANSACTIONS PAGE JAVASCRIPT ==========
let currentFilter = 'all';
let currentSearch = '';
let chartInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    // Search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            currentSearch = e.target.value.toLowerCase();
            filterTransactions();
        });
    }
    
    // Filter buttons
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            filterTransactions();
        });
    });
    
    // Dark Mode Toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        const savedMode = localStorage.getItem('darkMode');
        if (savedMode === 'enabled') {
            document.body.classList.add('dark-mode');
            darkModeToggle.checked = true;
        }
        
        darkModeToggle.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'disabled');
            }
        });
    }
    
    // Initialize chart
    initChart();
});

function filterTransactions() {
    // Filter table rows
    const rows = document.querySelectorAll('#tableBody tr');
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        const member = row.cells[1]?.innerText.toLowerCase() || '';
        const book = row.cells[2]?.innerText.toLowerCase() || '';
        const id = row.cells[0]?.innerText.toLowerCase() || '';
        
        const matchFilter = currentFilter === 'all' || status === currentFilter;
        const matchSearch = currentSearch === '' || member.includes(currentSearch) || book.includes(currentSearch) || id.includes(currentSearch);
        
        row.style.display = matchFilter && matchSearch ? '' : 'none';
    });
    
    // Filter mobile cards
    const cards = document.querySelectorAll('.transaction-card');
    cards.forEach(card => {
        const status = card.getAttribute('data-status');
        const text = card.innerText.toLowerCase();
        
        const matchFilter = currentFilter === 'all' || status === currentFilter;
        const matchSearch = currentSearch === '' || text.includes(currentSearch);
        
        card.style.display = matchFilter && matchSearch ? 'block' : 'none';
    });
    
    // Update record count
    const visibleRows = Array.from(document.querySelectorAll('#tableBody tr')).filter(row => row.style.display !== 'none').length;
    const recordSpan = document.getElementById('recordCount');
    if (recordSpan) recordSpan.innerHTML = visibleRows + ' records';
}

function returnBook(button) {
    if (!confirm('Mark this book as returned?')) return;
    
    const row = button.closest('tr');
    const card = button.closest('.transaction-card');
    const element = row || card;
    
    if (!element) return;
    
    const today = new Date();
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const dateStr = `${today.getDate()} ${months[today.getMonth()]} ${today.getFullYear()}`;
    
    // Update status badge
    const statusSpan = element.querySelector('.status-badge');
    if (statusSpan) {
        statusSpan.className = 'status-badge status-returned';
        statusSpan.innerText = 'Returned';
    }
    
    // Update returned date for table
    if (row) {
        const returnedCell = row.cells[5];
        if (returnedCell && returnedCell.innerText === '—') {
            returnedCell.innerText = dateStr;
        }
        const fineCell = row.cells[7];
        if (fineCell) fineCell.innerHTML = '₹0';
        button.innerText = 'Done';
        button.disabled = true;
    }
    
    // Update for mobile card
    if (card) {
        const detailItems = card.querySelectorAll('.detail-item');
        detailItems.forEach(item => {
            if (item.innerText.includes('Fine:')) {
                item.innerHTML = '<span class="detail-label">Fine:</span> ₹0';
            }
        });
        button.innerText = 'Returned';
        button.disabled = true;
        button.classList.add('disabled');
    }
    
    element.setAttribute('data-status', 'returned');
    
    // Update stats
    updateStats();
    showToast('Book returned successfully!');
}

function addTransaction() {
    const member = document.getElementById('newMember').value.trim();
    const book = document.getElementById('newBook').value.trim();
    const type = document.getElementById('newType').value;
    
    if (!member || !book) {
        showToast('Please fill all fields', 'error');
        return;
    }
    
    const today = new Date();
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const borrowedDate = `${today.getDate()} ${months[today.getMonth()]} ${today.getFullYear()}`;
    const dueDate = new Date(today);
    dueDate.setDate(dueDate.getDate() + 14);
    const dueDateStr = `${dueDate.getDate()} ${months[dueDate.getMonth()]} ${dueDate.getFullYear()}`;
    const newId = Math.floor(Math.random() * 9000) + 1000;
    
    // Add to table
    const tableBody = document.getElementById('tableBody');
    const emptyRow = tableBody.querySelector('.empty-row');
    if (emptyRow) emptyRow.remove();
    
    const newRow = document.createElement('tr');
    newRow.setAttribute('data-status', type);
    newRow.innerHTML = `
        <td>#${newId}</td>
        <td>${escapeHtml(member)}</td>
        <td>${escapeHtml(book)}</td>
        <td>${borrowedDate}</td>
        <td>${dueDateStr}</td>
        <td>—</td>
        <td><span class="status-badge status-${type}">${type}</span></td>
        <td>₹0</td>
        <td><button class="action-btn" onclick="returnBook(this)">Return</button></td>
    `;
    tableBody.insertBefore(newRow, tableBody.firstChild);
    
    // Add to mobile cards
    const mobileContainer = document.getElementById('mobileCards');
    const emptyCard = mobileContainer.querySelector('.empty-card');
    if (emptyCard) emptyCard.remove();
    
    const newCard = document.createElement('div');
    newCard.className = 'transaction-card';
    newCard.setAttribute('data-status', type);
    newCard.innerHTML = `
        <div class="card-header">
            <div class="card-member-info">
                <strong>${escapeHtml(member)}</strong>
                <br><small>${escapeHtml(book)}</small>
            </div>
            <span class="status-badge status-${type}">${type}</span>
        </div>
        <div class="card-details">
            <div class="detail-item"><span class="detail-label">ID:</span> #${newId}</div>
            <div class="detail-item"><span class="detail-label">Fine:</span> ₹0</div>
            <div class="detail-item"><span class="detail-label">Borrowed:</span> ${borrowedDate}</div>
            <div class="detail-item"><span class="detail-label">Due:</span> ${dueDateStr}</div>
        </div>
        <button class="action-btn full-width" onclick="returnBook(this)">Mark Returned</button>
    `;
    mobileContainer.insertBefore(newCard, mobileContainer.firstChild);
    
    // Close modal and reset
    const modal = bootstrap.Modal.getInstance(document.getElementById('newModal'));
    modal.hide();
    document.getElementById('newMember').value = '';
    document.getElementById('newBook').value = '';
    document.getElementById('newType').value = 'borrowed';
    
    updateStats();
    filterTransactions();
    showToast('Transaction added successfully!');
}

function updateStats() {
    const rows = document.querySelectorAll('#tableBody tr');
    let total = rows.length;
    let active = 0, overdue = 0, returned = 0;
    
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        if (status === 'borrowed') active++;
        if (status === 'overdue') overdue++;
        if (status === 'returned') returned++;
    });
    
    document.getElementById('totalCount').innerText = total;
    document.getElementById('activeCount').innerText = active;
    document.getElementById('overdueCount').innerText = overdue;
    document.getElementById('returnedCount').innerText = returned;
}

function initChart() {
    const ctx = document.getElementById('trendChart');
    if (!ctx) return;
    
    const labels = Object.keys(monthlyChartData);
    const borrows = labels.map(l => monthlyChartData[l]?.borrows || 0);
    const returns = labels.map(l => monthlyChartData[l]?.returns || 0);
    
    if (chartInstance) chartInstance.destroy();
    
    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Borrows',
                    data: borrows,
                    borderColor: '#ff9800',
                    backgroundColor: 'rgba(255,152,0,0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Returns',
                    data: returns,
                    borderColor: '#4caf50',
                    backgroundColor: 'rgba(76,175,80,0.05)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: getComputedStyle(document.body).getPropertyValue('--text-color') || '#333'
                    }
                }
            }
        }
    });
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        bottom: 100px;
        right: 20px;
        background: ${type === 'success' ? '#4caf50' : '#f44336'};
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 1000;
        animation: fadeInOut 3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Add animation style
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInOut {
        0% { opacity: 0; transform: translateX(100px); }
        15% { opacity: 1; transform: translateX(0); }
        85% { opacity: 1; transform: translateX(0); }
        100% { opacity: 0; transform: translateX(100px); }
    }
`;
document.head.appendChild(style);