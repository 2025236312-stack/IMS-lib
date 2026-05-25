// ============================================
// SMARTLIB AUTH SYSTEM
// ============================================

// Current page
const currentPage = window.location.pathname.split("/").pop();

// Pages that require login
const protectedPages = [
    "dashboard.html",
    "books.html",
    "members.html",
    "transactions.html",
    "insights.html"
];

// ============================================
// CHECK LOGIN STATUS
// ============================================
function isLoggedIn() {
    return localStorage.getItem("smartlib_logged_in") === "true";
}

// ============================================
// LOGIN FUNCTION
// ============================================
function login(email, password) {

    // Demo login
    if (email && password) {

        // SAVE LOGIN STATUS
        localStorage.setItem("smartlib_logged_in", "true");
        localStorage.setItem("smartlib_user", email);

        // PREVENT LOOP
        setTimeout(() => {
            window.location.href = "dashboard.html";
        }, 300);

        return true;
    }

    return false;
}

// ============================================
// LOGOUT FUNCTION
// ============================================
function logout() {
    localStorage.removeItem("smartlib_logged_in");
    localStorage.removeItem("smartlib_user");

    window.location.href = "index.html";
}

// ============================================
// PAGE PROTECTION
// ============================================

// If user is NOT logged in and tries protected page
if (protectedPages.includes(currentPage)) {

    if (!isLoggedIn()) {
        window.location.href = "index.html";
    }
}

// If already logged in and opens login page
if (
    (currentPage === "index.html" || currentPage === "") &&
    isLoggedIn()
) {
    window.location.href = "dashboard.html";
}

// ============================================
// LOGIN FORM HANDLER
// ============================================

document.addEventListener("DOMContentLoaded", () => {

    const loginForm = document.getElementById("loginForm");

    if (loginForm) {

        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const email =
                document.getElementById("email")?.value || "";

            const password =
                document.getElementById("password")?.value || "";

            const success = login(email, password);

            if (!success) {
                alert("Invalid login!");
            }
        });
    }

    // Logout buttons
    const logoutBtns = document.querySelectorAll(".logout-btn");

    logoutBtns.forEach(btn => {
        btn.addEventListener("click", logout);
    });
});