// ========== DOM ELEMENTS ==========
let emailInput = document.getElementById("email");
let passwordBox = document.querySelector(".password-box input");
let showBtn = document.querySelector(".show");
let loginBtn = document.getElementById("btn");
let loginForm = document.getElementById("loginForm");

let emailError = document.querySelectorAll(".error")[0];
let passwordError = document.querySelectorAll(".error")[1];

// ========== SHOW/HIDE PASSWORD ==========
if (showBtn && passwordBox) {
    showBtn.addEventListener("click", function () {
        if (passwordBox.type === "password") {
            passwordBox.type = "text";
            showBtn.textContent = "Hide";
        } else {
            passwordBox.type = "password";
            showBtn.textContent = "Show";
        }
    });
}

// ========== EMAIL VALIDATION FUNCTION ==========
function validateEmail(value) {
    let pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(value);
}

// ========== REAL-TIME VALIDATION ==========
if (emailInput) {
    emailInput.addEventListener("input", function() {
        let email = this.value.trim();
        if (email !== "" && !validateEmail(email)) {
            emailError.style.display = "block";
            emailError.textContent = "Please enter a valid email address";
        } else {
            emailError.style.display = "none";
        }
    });
}

if (passwordBox) {
    passwordBox.addEventListener("input", function() {
        let password = this.value;
        if (password !== "" && password.length < 8) {
            passwordError.style.display = "block";
            passwordError.textContent = "Password must be at least 8 characters";
        } else {
            passwordError.style.display = "none";
        }
    });
}

// ========== FORM SUBMISSION VALIDATION ==========
if (loginForm) {
    loginForm.addEventListener("submit", function(event) {
        let email = emailInput.value.trim();
        let password = passwordBox.value.trim();
        let isValid = true;

        // Email validation
        if (email === "") {
            emailError.style.display = "block";
            emailError.textContent = "Please enter your email address";
            isValid = false;
        } else if (!validateEmail(email)) {
            emailError.style.display = "block";
            emailError.textContent = "Please enter a valid email address";
            isValid = false;
        } else {
            emailError.style.display = "none";
        }

        // Password validation
        if (password === "") {
            passwordError.style.display = "block";
            passwordError.textContent = "Please enter your password";
            isValid = false;
        } else if (password.length < 8) {
            passwordError.style.display = "block";
            passwordError.textContent = "Password must be at least 8 characters";
            isValid = false;
        } else {
            passwordError.style.display = "none";
        }

        // If validation fails, prevent form submission
        if (!isValid) {
            event.preventDefault();
        }
    });
}

// ========== REMEMBER ME FUNCTIONALITY ==========
document.addEventListener("DOMContentLoaded", function() {
    let rememberCheckbox = document.getElementById("remember");
    let savedEmail = localStorage.getItem("rememberedEmail");
    
    if (savedEmail && emailInput) {
        emailInput.value = savedEmail;
        if (rememberCheckbox) {
            rememberCheckbox.checked = true;
        }
    }
    
    // Save email when form submits successfully
    if (loginForm) {
        loginForm.addEventListener("submit", function() {
            if (rememberCheckbox && rememberCheckbox.checked && emailInput) {
                localStorage.setItem("rememberedEmail", emailInput.value.trim());
            } else if (rememberCheckbox && !rememberCheckbox.checked) {
                localStorage.removeItem("rememberedEmail");
            }
        });
    }
});