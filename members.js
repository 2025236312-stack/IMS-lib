// MOBILE SIDEBAR

const menuBtn = document.getElementById('menuBtn');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('mobileOverlay');

menuBtn.addEventListener('click', () => {

sidebar.classList.toggle('active');
overlay.classList.toggle('active');

});

overlay.addEventListener('click', () => {

sidebar.classList.remove('active');
overlay.classList.remove('active');

});

// SEARCH FILTER

const searchInput = document.getElementById('searchInput');

searchInput.addEventListener('keyup', function(){

const value = this.value.toLowerCase();

const rows = document.querySelectorAll('#memberTable tr');

rows.forEach(row => {

row.style.display =
row.innerText.toLowerCase().includes(value)
? ''
: 'none';

});

});