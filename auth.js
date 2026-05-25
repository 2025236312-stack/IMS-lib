// ============================================
// SMARTLIB AUTH SYSTEM
// ============================================

// Current page
const currentPage = window.location.pathname.split("/").pop();

// ============================================
// CHECK LOGIN STATUS
// ============================================
const isLoggedIn = localStorage.getItem("smartlib_logged_in");

// ============================================
// LOGIN PAGE LOGIC
// ============================================
if (currentPage === "index.html" || currentPage === "") {

    // If already logged in -> go dashboard
    if (isLoggedIn === "true") {
        window.location.href = "dashboard.html";
    }

    // Login form
    const loginForm = document.getElementById("loginForm");

    if (loginForm) {

        loginForm.addEventListener("submit", function (e) {

            e.preventDefault();

            // Example demo login
            const username =
                document.getElementById("username")?.value;

            const password =
                document.getElementById("password")?.value;

            // Demo credentials
            if (username === "admin" && password === "admin123") {

                localStorage.setItem(
                    "smartlib_logged_in",
                    "true"
                );

                window.location.href = "dashboard.html";

            } else {

                alert("Invalid username or password");

            }

        });

    }

}

// ============================================
// PROTECTED PAGES
// ============================================
else {

    // If NOT logged in -> back to login
    if (isLoggedIn !== "true") {

        window.location.href = "index.html";

    }

}

// ============================================
// LOGOUT BUTTON
// ============================================
document.addEventListener("DOMContentLoaded", () => {

    const logoutBtns =
        document.querySelectorAll('[href="index.html"]');

    logoutBtns.forEach(btn => {

        btn.addEventListener("click", function () {

            localStorage.removeItem(
                "smartlib_logged_in"
            );

        });

    });

});