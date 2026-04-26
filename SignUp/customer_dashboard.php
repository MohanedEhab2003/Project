<?php

require_once 'includes/customer_dashboard_backend.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - All Age Care</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Dashboard specific styles */
        .verification-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            text-align: center;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
            100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
        }
        
        .verification-banner h3 {
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .verification-banner p {
            margin-bottom: 5px;
            opacity: 0.95;
        }
        
        .verification-banner .email-highlight {
            background: rgba(255,255,255,0.2);
            padding: 5px 10px;
            border-radius: 20px;
            display: inline-block;
            font-weight: bold;
        }
        
        .status-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 5px solid #ffc107;
        }
        
        .status-pending {
            color: #856404;
            background-color: #fff3cd;
            padding: 10px 15px;
            border-radius: 10px;
            margin-top: 10px;
            font-size: 14px;
        }
        
        .status-pending ul {
            margin-top: 10px;
            margin-left: 20px;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid purple;
        }
        
        .profile-pic-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
        }
        
        .info-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .info-card label {
            font-weight: bold;
            color: purple;
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .info-card p {
            font-size: 16px;
            color: #333;
        }
        
        .disabled-features {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
            color: #999;
        }
        
        .disabled-features p {
            margin: 5px 0;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .feature-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid #eee;
        }
        
        .feature-card h4 {
            color: purple;
            margin-bottom: 10px;
        }
        
        .feature-card p {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">💜 All Age Care</div>
        <ul>
            <li><a href="customer_dashboard.php">Dashboard</a></li>
            <?php if ($is_verified): ?>
                <li><a href="#">Find Caregivers</a></li>
                <li><a href="#">My Bookings</a></li>
                <li><a href="#">Profile</a></li>
            <?php else: ?>
                <li><a href="#" style="opacity: 0.5; pointer-events: none;">Find Caregivers 🔒</a></li>
                <li><a href="#" style="opacity: 0.5; pointer-events: none;">My Bookings 🔒</a></li>
                <li><a href="#" style="opacity: 0.5; pointer-events: none;">Profile 🔒</a></li>
            <?php endif; ?>
            <li><a href="includes/logout.inc.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="container">
        <?php if (!$is_verified): ?>
            <!-- Verification Pending Banner -->
            <div class="verification-banner">
                <h3>⏳ Account Pending Verification</h3>
                <p>Thank you for signing up as a Care Seeker with All Age Care!</p>
                <p>Your account is currently <strong>under review</strong> by our admin team.</p>
                <p>We will send a verification confirmation to:</p>
                <p><span class="email-highlight">📧 <?php echo htmlspecialchars($email); ?></span></p>
                <p style="margin-top: 15px; font-size: 14px;">⏱️ This process usually takes 24-48 hours.</p>
            </div>
            
            <!-- Status Card -->
            <div class="status-card">
                <h3>📋 Account Status: <span style="color: #ffc107;">Pending Verification</span></h3>
                <div class="status-pending">
                    <strong>⚠️ Important:</strong> While your account is being verified, you cannot:
                    <ul>
                        <li>Search for or book caregivers</li>
                        <li>View your profile publicly</li>
                        <li>Make payments or bookings</li>
                        <li>Access all dashboard features</li>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <!-- Verified Account Banner -->
            <div class="verification-banner" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);">
                <h3>✅ Account Verified!</h3>
                <p>Your account has been verified. You can now access all features.</p>
            </div>
        <?php endif; ?>
        
        <!-- Profile Header -->
        <div class="profile-header">
            <?php if ($profile_picture && file_exists($profile_picture)): ?>
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile" class="profile-pic">
            <?php else: ?>
                <div class="profile-pic-placeholder">
                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                </div>
            <?php endif; ?>
            <div>
                <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                <p><?php echo htmlspecialchars($email); ?></p>
                <p><strong>Account Type:</strong> Care Seeker (Customer)</p>
            </div>
        </div>
        
        <!-- About Me Section -->
        <?php if (!empty($bio)): ?>
            <div class="info-card">
                <label>📝 About Me</label>
                <p><?php echo nl2br(htmlspecialchars($bio)); ?></p>
            </div>
        <?php endif; ?>
        
        <!-- Location Info -->
        <div class="info-card">
            <label>📍 Location</label>
            <p><?php echo htmlspecialchars($location); ?></p>
        </div>
        
        <?php if (!$is_verified): ?>
            <!-- Disabled Features Message -->
            <div class="disabled-features">
                <p>🔒 <strong>Account under review</strong></p>
                <p>Once verified, you will have access to:</p>
                <p>✓ Browse and search for caregivers</p>
                <p>✓ Book care services</p>
                <p>✓ Make secure payments</p>
                <p>✓ Rate and review caregivers</p>
                <p style="margin-top: 15px; font-size: 12px; color: #666;">You will receive an email once your account is verified.</p>
            </div>
        <?php else: ?>
            <!-- Full Dashboard for Verified Users -->
            <div style="margin-top: 30px;">
                <h3>📊 Your Dashboard</h3>
                <div class="feature-grid">
                    <div class="feature-card">
                        <h4>👋 Find Caregivers</h4>
                        <p>Search for professional caregivers near you</p>
                    </div>
                    <div class="feature-card">
                        <h4>📅 My Bookings</h4>
                        <p>View and manage your care bookings</p>
                    </div>
                    <div class="feature-card">
                        <h4>⭐ Reviews</h4>
                        <p>Rate and review caregivers you've hired</p>
                    </div>
                    <div class="feature-card">
                        <h4>💳 Payments</h4>
                        <p>Secure payment options for services</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>