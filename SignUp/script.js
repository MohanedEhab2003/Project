// ========== TABS FUNCTIONALITY ==========
document.addEventListener('DOMContentLoaded', function() {
    
    let tabs = document.querySelectorAll(".tab");
    let roleInput = document.getElementById("user_role");
    let roleFinal = document.getElementById("user_role_final");
    let bodyElement = document.body;
    let bioTextarea = document.getElementById("bio");
    let bioHint = document.getElementById("bioHint");
    let experienceInput = document.getElementById("experience");
    let priceInput = document.getElementById("price");
    
    // Function to enable/disable provider fields
    function setProviderFields(enable) {
        if (enable) {
            // Care Provider mode - ADD name attributes
            if (experienceInput) {
                experienceInput.setAttribute("name", "experience");
                experienceInput.disabled = false;
                experienceInput.required = true;
            }
            if (priceInput) {
                priceInput.setAttribute("name", "price");
                priceInput.disabled = false;
                priceInput.required = true;
            }
        } else {
            // Care Seeker mode - REMOVE name attributes
            if (experienceInput) {
                experienceInput.removeAttribute("name");
                experienceInput.disabled = true;
                experienceInput.required = false;
                experienceInput.value = "";
            }
            if (priceInput) {
                priceInput.removeAttribute("name");
                priceInput.disabled = true;
                priceInput.required = false;
                priceInput.value = "";
            }
        }
    }
    
    // Function to switch roles
    function switchRole(role) {
        console.log("Switching to role:", role);
        
        if (role === "customer") {
            // Care Seeker mode
            if (roleInput) {
                roleInput.value = "customer";
                console.log("user_role set to:", roleInput.value);
            }
            bodyElement.classList.add("care-seeker-mode");
            setProviderFields(false);
            
            let submitBtn = document.querySelector(".btn");
            if (submitBtn) submitBtn.innerHTML = "✅ Create Customer Account";
            
            if (bioTextarea) {
                bioTextarea.placeholder = "Tell us about your family... (e.g., family size, special needs, care requirements, daily schedule, any medical conditions, etc.)";
            }
            if (bioHint) {
                bioHint.innerHTML = "💡 Help caregivers understand your family's needs better.";
            }
        } else {
            // Care Provider mode
            if (roleInput) {
                roleInput.value = "caregiver";
                console.log("user_role set to:", roleInput.value);
            }
            bodyElement.classList.remove("care-seeker-mode");
            setProviderFields(true);
            
            let submitBtn = document.querySelector(".btn");
            if (submitBtn) submitBtn.innerHTML = "✅ Create Care Provider Account";
            
            if (bioTextarea) {
                bioTextarea.placeholder = "Tell us about yourself... (e.g., your experience, certifications, skills, languages, why you love caregiving, availability, etc.)";
            }
            if (bioHint) {
                bioHint.innerHTML = "💡 Share your caregiving experience, special skills, and what makes you a great caregiver.";
            }
        }
    }
    
    // Add click listeners to tabs
    if (tabs) {
        tabs.forEach(tab => {
            tab.addEventListener("click", function() {
                tabs.forEach(t => t.classList.remove("active"));
                this.classList.add("active");
                let selectedRole = this.getAttribute("data-role");
                switchRole(selectedRole);
            });
        });
    }
    
    // Initialize based on active tab
    let activeTab = document.querySelector(".tab.active");
    if (activeTab) {
        let defaultRole = activeTab.getAttribute("data-role");
        switchRole(defaultRole);
    } else {
        switchRole("caregiver");
    }
    
    // ========== ULTIMATE FIX: Set final role on form submit ==========
    let form = document.getElementById("myForm");
    if (form) {
        form.addEventListener("submit", function(e) {
            // Get the current role from the main hidden input
            let currentRole = roleInput ? roleInput.value : "caregiver";
            
            // Set the final role hidden input
            if (roleFinal) {
                roleFinal.value = currentRole;
                console.log("=== FORM SUBMITTING ===");
                console.log("Current role:", currentRole);
                console.log("Final role set to:", roleFinal.value);
            }
        });
    }
});

// ========== PROFILE IMAGE PREVIEW ==========
let uploadBox = document.getElementById("uploadBox");
let fileInput = document.getElementById("fileInput");
let preview = document.getElementById("preview");

if (uploadBox && fileInput && preview) {
    uploadBox.addEventListener("click", () => fileInput.click());
    fileInput.addEventListener("change", () => {
        let file = fileInput.files[0];
        if (file) {
            if (preview.src && preview.src.startsWith("blob:")) URL.revokeObjectURL(preview.src);
            preview.src = URL.createObjectURL(file);
            preview.style.display = "block";
        }
    });
}

// ========== NATIONAL ID IMAGE PREVIEW ==========
let idUploadBox = document.getElementById("idUploadBox");
let idFileInput = document.getElementById("idFileInput");
let idPreview = document.getElementById("idPreview");

if (idUploadBox && idFileInput && idPreview) {
    idUploadBox.addEventListener("click", () => idFileInput.click());
    idFileInput.addEventListener("change", () => {
        let file = idFileInput.files[0];
        if (file) {
            if (idPreview.src && idPreview.src.startsWith("blob:")) URL.revokeObjectURL(idPreview.src);
            idPreview.src = URL.createObjectURL(file);
            idPreview.style.display = "block";
        }
    });
}

// ========== FORM VALIDATION ==========
let form = document.getElementById("myForm");

if (form) {
    form.addEventListener("submit", function(e) {
        let username = document.querySelector("input[name='Username']")?.value.trim() || "";
        let email = document.getElementById("email")?.value.trim() || "";
        let password = document.getElementById("password")?.value || "";
        let confirmPass = document.getElementById("confirmPass")?.value || "";
        let phone = document.getElementById("phone")?.value.trim() || "";
        let nationalID = document.querySelector("input[name='nationalID']")?.value.trim() || "";
        let location = document.querySelector("input[name='location']")?.value.trim() || "";
        let bio = document.getElementById("bio")?.value.trim() || "";
        let role = document.getElementById("user_role_final")?.value || document.getElementById("user_role")?.value || "";
        let agree = document.getElementById("agree")?.checked || false;
        
        let experienceInput = document.getElementById("experience");
        let priceInput = document.getElementById("price");
        let experience = experienceInput ? experienceInput.value : "";
        let price = priceInput ? priceInput.value.trim() : "";

        console.log("Validation - Role being used:", role);

        // Username validation
        if (username === "") {
            e.preventDefault();
            alert("❌ Please enter a username.");
            return;
        }
        if (username.length < 3) {
            e.preventDefault();
            alert("❌ Username must be at least 3 characters.");
            return;
        }

        // Email validation
        if (email === "") {
            e.preventDefault();
            alert("❌ Please enter an email address.");
            return;
        }
        if (!email.includes("@") || !email.includes(".")) {
            e.preventDefault();
            alert("❌ Please enter a valid email address.");
            return;
        }

        // Phone validation
        if (phone === "") {
            e.preventDefault();
            alert("❌ Please enter a phone number.");
            return;
        }
        if (phone.length < 8) {
            e.preventDefault();
            alert("❌ Please enter a valid phone number (at least 8 digits).");
            return;
        }

        // National ID validation
        if (nationalID === "") {
            e.preventDefault();
            alert("❌ Please enter your National ID number.");
            return;
        }
        if (!/^\d{14}$/.test(nationalID)) {
            e.preventDefault();
            alert("❌ National ID must be exactly 14 digits.");
            return;
        }

        // ID Photo validation
        let idPhotoInput = document.getElementById("idFileInput");
        let idPhotoUploaded = false;
        if (idPhotoInput && idPhotoInput.files.length > 0) {
            let file = idPhotoInput.files[0];
            let allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!allowed.includes(file.type)) {
                e.preventDefault();
                alert("❌ ID photo must be JPG, PNG, GIF, or WEBP.");
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                e.preventDefault();
                alert("❌ ID photo must be less than 5MB.");
                return;
            }
            idPhotoUploaded = true;
        }
        if (!idPhotoUploaded) {
            e.preventDefault();
            alert("❌ Please upload a clear photo of your National ID.");
            return;
        }

        // Location validation
        if (location === "") {
            e.preventDefault();
            alert("❌ Please enter your location.");
            return;
        }

        // Password validation
        let passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        if (password === "") {
            e.preventDefault();
            alert("❌ Please enter a password.");
            return;
        }
        if (!passwordRegex.test(password)) {
            e.preventDefault();
            alert("❌ Password must have 8+ chars, 1 capital, 1 number, 1 symbol.");
            return;
        }
        if (password !== confirmPass) {
            e.preventDefault();
            alert("❌ Passwords do not match.");
            return;
        }

        // ONLY validate experience/price for caregiver
        if (role === "caregiver") {
            if (experience === "") {
                e.preventDefault();
                alert("❌ Please enter your years of experience.");
                return;
            }
            if (parseFloat(experience) < 0) {
                e.preventDefault();
                alert("❌ Experience cannot be negative.");
                return;
            }
            if (price === "") {
                e.preventDefault();
                alert("❌ Please enter your price per hour.");
                return;
            }
            let priceNum = parseFloat(price);
            if (isNaN(priceNum) || priceNum < 0) {
                e.preventDefault();
                alert("❌ Please enter a valid price per hour.");
                return;
            }
        }

        if (bio !== "" && bio.length < 10) {
            e.preventDefault();
            alert("❌ 'About yourself' should be at least 10 characters if filled.");
            return;
        }
        if (!agree) {
            e.preventDefault();
            alert("❌ You must agree to the Terms.");
            return;
        }

        alert("✅ All validations passed! Submitting...");
    });
}

// ========== PASSWORD STRENGTH ==========
let passwordField = document.getElementById("password");
if (passwordField) {
    passwordField.addEventListener("input", function() {
        let password = this.value;
        let strengthText = document.querySelector(".password-strength");
        if (!strengthText && this.parentNode) {
            strengthText = document.createElement("small");
            strengthText.className = "password-strength";
            strengthText.style.display = "block";
            strengthText.style.marginTop = "5px";
            this.parentNode.appendChild(strengthText);
        }
        if (strengthText) {
            let regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (password.length === 0) {
                strengthText.innerHTML = "";
            } else if (regex.test(password)) {
                strengthText.innerHTML = "✓ Strong password!";
                strengthText.style.color = "green";
            } else if (password.length >= 8) {
                strengthText.innerHTML = "⚠️ Add 1 capital letter, 1 number, and 1 symbol";
                strengthText.style.color = "orange";
            } else {
                strengthText.innerHTML = "⚠️ Password must be at least 8 characters";
                strengthText.style.color = "red";
            }
        }
    });
}