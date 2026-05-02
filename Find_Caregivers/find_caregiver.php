<?php
// Start session to check login status
session_start();

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['user_type']);
$user_type = $is_logged_in ? $_SESSION['user_type'] : null;
$username = $is_logged_in ? $_SESSION['username'] : null;

// Include database connection
require_once 'database.php';

$caregivers = []; 
$searchPerformed = false;
$city         = isset($_GET['city']) ? trim($_GET['city']) : '';
$max_price    = (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) ? (float)$_GET['max_price'] : null;

// Check if search criteria are provided
if ($city !== '' || $max_price !== null) {
    $searchPerformed = true;
    try {
        // Only show verified caregivers (is_verified = 1)
        $sql = "SELECT c.*, l.city 
                FROM caregivers c 
                LEFT JOIN location l ON c.location_id = l.location_id 
                WHERE c.is_verified = 1";
        
        $params = [];
        $types = ""; 

        if ($city !== '') {
            $sql .= " AND l.city LIKE ?";
            $params[] = "%$city%";
            $types .= "s";
        }

        if ($max_price !== null) {
            $sql .= " AND c.price_per_hour <= ?";
            $params[] = $max_price;
            $types .= "d";
        }

        $stmt = $conn->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $caregivers = $result->fetch_all(MYSQLI_ASSOC);

    } catch (mysqli_sql_exception $e) {
        error_log("Database error: " . $e->getMessage());
        $caregivers = [];
    }
} else {
    // If no search criteria, show all verified caregivers by default
    try {
        $sql = "SELECT c.*, l.city 
                FROM caregivers c 
                LEFT JOIN location l ON c.location_id = l.location_id 
                WHERE c.is_verified = 1";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $caregivers = $result->fetch_all(MYSQLI_ASSOC);
        
        if (count($caregivers) > 0) {
            $searchPerformed = true;
        }
    } catch (mysqli_sql_exception $e) {
        error_log("Database error: " . $e->getMessage());
        $caregivers = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Caregiver - All Age Care</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Welcome message styling */
        .welcome-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            margin-left: 15px;
            display: inline-block;
        }
        .welcome-message i {
            color: white;
            font-size: 13px;
            margin-right: 5px;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        /* Verified badge styling */
        .verified-badge {
            background: #00b894;
            color: white;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            margin-left: 8px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Navigation with Dynamic Links -->
    <nav>
        <div class="logo">
            <svg viewBox="0 0 28 28" fill="none">
                <path d="M14 24s-10-6.5-10-13a6 6 0 0 1 10-4.47A6 6 0 0 1 24 11c0 6.5-10 13-10 13z" fill="#7c3aed"/>
            </svg>
            All Age Care
        </div>
        <ul class="nav-links">
            <li><a href="../Home/index.php" class="nav-link">Home</a></li>
            <li><a href="../About%20Us/index.php" class="nav-link">About Us</a></li>
            <li><a href="../Contact%20Us/contactus.php" class="nav-link">Contact Us</a></li>
            
            <?php if ($is_logged_in): ?>
                <?php if ($user_type === 'caregiver'): ?>
                    <li><a href="../SignUp/caregiver_dashboard.php" class="nav-link">Dashboard</a></li>
                    <li><a href="../Profile/caregiver_profile.php" class="nav-link">My Profile</a></li>
                    <li><a href="#" class="nav-link">Requests</a></li>
                    <li><a href="../Log%20in/includes/logout.inc.php" class="nav-link">Logout</a></li>
                <?php elseif ($user_type === 'customer'): ?>
                    <li><a href="../SignUp/customer_dashboard.php" class="nav-link">Dashboard</a></li>
                    <li><a href="../Profile/customer_profile.php" class="nav-link">My Profile</a></li>
                    <li><a href="index.php" class="nav-link active">Find Caregivers</a></li>
                    <li><a href="#" class="nav-link">My Bookings</a></li>
                    <li><a href="../Log%20in/includes/logout.inc.php" class="nav-link">Logout</a></li>
                <?php endif; ?>
                
                <span class="welcome-message">
                    👋 Welcome, <?php echo htmlspecialchars($username); ?> 
                    (<?php echo $user_type === 'caregiver' ? 'Caregiver' : 'Customer'; ?>)
                </span>
                
            <?php else: ?>
                <li><a href="../Log%20in/login.php" class="nav-link">Log in</a></li>
                <li><a href="../SignUp/index.php" class="nav-link">Sign up</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main>
        <h1>Available Caregivers</h1>
        <div class="search-container">
            <form method="GET" action="" class="search-form">
                <div class="search-field">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($_GET['city'] ?? ''); ?>">
                </div>
                <div class="search-field">
                    <label for="max_price">Max Price (EGP/hr)</label>
                    <input type="number" id="max_price" name="max_price" min="0" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
                </div>
                <!-- Availability filter removed -->
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>

        <?php if ($searchPerformed): ?>
            <?php if (count($caregivers) > 0): ?>
                <?php foreach ($caregivers as $caregiver): ?>
                    <div class="caregiver-card">
                        <?php 
                        $status = $caregiver['availability_status'] ?? 'unavailable';
                        $color = ($status === 'available') ? '#00b894' : (($status === 'busy') ? '#ff7675' : '#b2bec3');
                        ?>
                        <div style="text-align: right; color: <?php echo $color; ?>; font-weight: bold; font-size: 14px; margin-bottom: 5px;">
                            <?php echo ucfirst(htmlspecialchars($status)); ?>
                            <span class="verified-badge">✓ Verified</span>
                        </div>
                        
                        <div class="photo-placeholder">
                            <?php if (!empty($caregiver['profile_picture'])): ?>
                                <img src="../SignUp/<?php echo htmlspecialchars($caregiver['profile_picture']); ?>" 
                                     alt="Caregiver Photo" 
                                     style="width:100%; height:100%; border-radius:15px; object-fit:cover;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <span class="add-photo-text" style="display:none;">No Photo</span>
                            <?php else: ?>
                                <span class="add-photo-text">No Photo</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="caregiver-info">
                            <div class="info-row">
                                <label>Name</label>
                                <div class="display-data">
                                    <div><?php echo htmlspecialchars($caregiver['username'] ?? 'Not available'); ?></div>
                                </div>
                            </div>
                            <div class="info-row">
                                <label>City</label>
                                <div class="display-data"><?php echo htmlspecialchars($caregiver['city'] ?? 'Default'); ?></div>
                            </div>
                            <div class="info-row">
                                <label>Experience</label>
                                <div class="display-data"><?php echo (int)($caregiver['experience_years'] ?? 0); ?> Years</div>
                            </div>
                            <div class="info-row">
                                <label>Profile</label>
                                <div class="display-data">
                                    <?php echo htmlspecialchars($caregiver['bio'] ?? 'No bio available'); ?>
                                </div>
                            </div>
                            <div class="info-row">
                                <label>Price/hour</label>
                                <div class="display-data"><?php echo number_format($caregiver['price_per_hour'] ?? 0, 1); ?> EGP</div>
                            </div>
                        </div>
                        
                        <?php if ($is_logged_in && $user_type === 'customer'): ?>
                            <button class="book-btn" onclick="window.location.href='book_process.php?id=<?php echo urlencode($caregiver['caregivers_id']); ?>'">
                                Book
                            </button>
                        <?php elseif ($is_logged_in && $user_type === 'caregiver'): ?>
                            <button class="book-btn" style="background: #9ca3af; cursor: not-allowed;" disabled>
                                Caregiver View
                            </button>
                        <?php else: ?>
                            <button class="book-btn" onclick="window.location.href='../Log%20in/login.php'">
                                Login to Book
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center; font-size: 20px; color: #000000;">
                    No verified caregivers match your criteria.
                </p>
            <?php endif; ?>
        <?php else: ?>
            <p style="text-align:center; font-size: 18px; color: #666; margin-top: 40px;">
                🔍 Use the search filters above to find verified caregivers in your area.
            </p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <div class="finalsection">
        <div class="explore">
            <h4>Explore</h4>
            <?php if ($is_logged_in): ?>
                <a href="<?php echo ($user_type === 'caregiver') ? '../SignUp/caregiver_dashboard.php' : '../SignUp/customer_dashboard.php'; ?>">Dashboard</a>
                <a href="<?php echo ($user_type === 'caregiver') ? '../Profile/caregiver_profile.php' : '../Profile/customer_profile.php'; ?>">My Profile</a>
                <?php if ($user_type === 'customer'): ?>
                    <a href="index.php">Find Caregivers</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="../Log%20in/login.php">Services</a>
            <?php endif; ?>
            <a href="../About%20Us/index.php">About Us</a>
            <a href="../Contact%20Us/contactus.php">Contact Us</a>
        </div>
        <div class="legal">
            <h4>Legal</h4>
            <a href="../Home/privacy.html">Privacy</a>
            <a href="../Home/terms.html">Terms</a>
            <a href="../Home/accessibility.html">Accessibility</a>
        </div>
    </div>

    <div class="rights">
        <p>© 2026, All Age Care EG.</p>
    </div>

    <script src="script.js"></script>
</body>
</html>