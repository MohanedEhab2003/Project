<?php
require_once 'includes/config_session.inc.php';
require_once 'includes/signup_view.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Age Care | Create Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">💜 All Age Care</div>
    <ul>
        <li><a href="../Home/index.html">Home</a></li>
        <li><a href="../About/index.html">About us</a></li>
        <li><a href="../Contact/index.html">Contact us</a></li>
    </ul>
</div>

<div class="container">

    <h2>Create your All Age Care account</h2>
    <p class="sub">Choose the account type that fits you best — complete quick signup.</p>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab" data-role="customer">Care Seeker</div>
        <div class="tab active" data-role="caregiver">Care Provider</div>
    </div>

    <form id="myForm" action="includes/signup.inc.php" method="POST" enctype="multipart/form-data">
        
        <!-- Hidden input for role selection - updated by JavaScript -->
        <input type="hidden" name="user_role" id="user_role" value="caregiver">
        
        <!-- ULTIMATE FIX: Hidden input that gets set on form submit -->
        <input type="hidden" name="user_role_final" id="user_role_final" value="">

        <!-- SECTION 1: ACCOUNT CREDENTIALS -->
        <div class="row">
            <div class="box">
                <label>👤 Username</label>
                <input type="text" name="Username" placeholder="Choose a username" required>
            </div>
            <div class="box">
                <label>📧 Email</label>
                <input type="email" name="email" id="email" placeholder="your@email.com" required>
            </div>
        </div>

        <div class="row">
            <div class="box">
                <label>🔒 Password</label>
                <input type="password" name="password" id="password" required>
                <small>Must be at least 8 characters with a number, a capital letter and a symbol.</small>
            </div>
            <div class="box">
                <label>✓ Confirm password</label>
                <input type="password" name="confirmPass" id="confirmPass" required>
                <small>Make sure both passwords match.</small>
            </div>
        </div>

        <!-- SECTION 2: IDENTITY & CONTACT -->
        <div class="row">
            <div class="box national-row">
                <label>🆔 National ID</label>
                <div class="national-inner">
                    <div class="upload" id="idUploadBox">
                        ⬆ Add ID photo
                        <input type="file" id="idFileInput" name="id_photo" hidden accept="image/*">
                    </div>
                    <input type="text" name="nationalID" placeholder="Enter National ID number (14 digits)">
                </div>
                <img id="idPreview" class="preview-img" style="display:none;">
            </div>

            <div class="box">
                <label>📞 Phone number</label>
                <input type="text" name="phone" id="phone" placeholder="+20 010 123 4567" required>
            </div>
        </div>

        <!-- SECTION 3: PROFESSIONAL DETAILS (Provider Only) -->
        <div class="provider-only" id="providerFields">
            <div class="row">
                <div class="box">
                    <label>📅 Experience (years)</label>
                    <input type="number" id="experience" placeholder="e.g., 5" min="0" step="1">
                </div>
                <div class="box">
                    <label>💰 Price per hour (EGP)</label>
                    <input type="text" id="price" placeholder="Average: 44-133">
                </div>
            </div>

            <!-- Age group preferences -->
            <div class="age">
                <p>🧑‍🤝‍🧑 Age group preferences: (select all that apply)</p>
                <div class="checks">
                    <label class="check-item">
                        <input type="checkbox" name="age_children" value="children" checked>
                        <span>Children (0-12)</span>
                    </label>
                    <label class="check-item">
                        <input type="checkbox" name="age_adults" value="adults" checked>
                        <span>Adults (18-64)</span>
                    </label>
                    <label class="check-item">
                        <input type="checkbox" name="age_seniors" value="seniors" checked>
                        <span>Seniors (65+)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- SECTION 4: ABOUT YOURSELF -->
        <div class="row">
            <div class="box">
                <label>📝 About yourself</label>
                <textarea name="bio" id="bio" class="bio-textarea" rows="4" placeholder="Tell us about yourself..."></textarea>
                <small id="bioHint" class="bio-hint">Share some information about yourself. This will help others get to know you better.</small>
            </div>
        </div>

        <!-- SECTION 5: LOCATION & PROFILE PICTURE -->
        <div class="row">
            <div class="box">
                <label>📍 Location (City, Area)</label>
                <input type="text" name="location" placeholder="e.g., Cairo, Nasr City" required>
            </div>
            <div class="box">
                <label>🖼️ Profile Picture</label>
                <div class="upload" id="uploadBox">
                    ⬆ Add your photo
                    <input type="file" id="fileInput" name="profile_picture" hidden accept="image/*">
                </div>
                <img id="preview" class="preview-img" style="display:none;">
            </div>
        </div>

        <!-- Agreement & Terms -->
        <div class="agree">
            <input type="checkbox" id="agree" required>
            <span>I agree to the <strong>Privacy Policy</strong> and <strong>Terms of Service</strong></span>
        </div>

        <button type="submit" class="btn">✅ Create Account</button>

    </form>

    <?php
    if (function_exists('check_signup_errors')) {
        check_signup_errors();
    }
    ?>

</div>

<script src="script.js"></script>

</body>
</html>