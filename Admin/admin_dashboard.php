<?php
session_start();
require_once 'includes/db.inc.php';

// Check if admin is logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    die();
}

// Get unverified customers
$queryCustomers = "SELECT customers_id, username, email, phone, created_at, is_verified 
                   FROM Customers 
                   WHERE is_verified = 0 
                   ORDER BY created_at DESC";
$stmtCustomers = $pdo->prepare($queryCustomers);
$stmtCustomers->execute();
$unverifiedCustomers = $stmtCustomers->fetchAll(PDO::FETCH_ASSOC);

// Get unverified caregivers
$queryCaregivers = "SELECT caregivers_id, username, email, phone, created_at, is_verified, experience_years, price_per_hour 
                    FROM Caregivers 
                    WHERE is_verified = 0 
                    ORDER BY created_at DESC";
$stmtCaregivers = $pdo->prepare($queryCaregivers);
$stmtCaregivers->execute();
$unverifiedCaregivers = $stmtCaregivers->fetchAll(PDO::FETCH_ASSOC);

// Get counts
$totalCustomers = count($unverifiedCustomers);
$totalCaregivers = count($unverifiedCaregivers);
$totalPending = $totalCustomers + $totalCaregivers;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - All Age Care</title>
    <link rel="stylesheet" href="admin_style.css">
    <style>
        .btn-remove {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            transition: all 0.3s;
            margin-left: 5px;
        }
        .btn-remove:hover {
            background: #c0392b;
            transform: scale(1.05);
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">💜 All Age Care Admin</div>
    <ul>
        <li><a href="admin_dashboard.php" style="color: purple;">Dashboard</a></li>
        <li><a href="admin_verified.php">Verified Users</a></li>
        <li><form action="admin_logout.php" method="POST" style="display: inline;">
            <button type="submit" class="btn-logout">Logout</button>
        </form></li>
    </ul>
</div>

<div class="container">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Pending Customers</h3>
            <div class="stat-number"><?php echo $totalCustomers; ?></div>
        </div>
        <div class="stat-card">
            <h3>Pending Caregivers</h3>
            <div class="stat-number"><?php echo $totalCaregivers; ?></div>
        </div>
        <div class="stat-card">
            <h3>Total Pending</h3>
            <div class="stat-number"><?php echo $totalPending; ?></div>
        </div>
    </div>
    
    <!-- Unverified Customers Section -->
    <div class="section">
        <h2>👥 Unverified Customers <span class="count"><?php echo $totalCustomers; ?></span></h2>
        
        <?php if (empty($unverifiedCustomers)): ?>
            <div class="empty-state">
                <p>✅ No pending customer verifications. All customers are verified!</p>
            </div>
        <?php else: ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unverifiedCustomers as $customer): ?>
                    <tr>
                        <td><?php echo $customer['customers_id']; ?></td>
                        <td><?php echo htmlspecialchars($customer['username']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
                        <td><span class="badge badge-pending">Pending</span></td>
                        <td>
                            <div class="action-buttons">
                                <form action="verify_user.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="user_type" value="customer">
                                    <input type="hidden" name="user_id" value="<?php echo $customer['customers_id']; ?>">
                                    <button type="submit" class="btn-verify" onclick="return confirm('Verify <?php echo htmlspecialchars($customer['username']); ?>?')">✓ Verify</button>
                                </form>
                                <form action="remove_user.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="user_type" value="customer">
                                    <input type="hidden" name="user_id" value="<?php echo $customer['customers_id']; ?>">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($customer['username']); ?>">
                                    <button type="submit" class="btn-remove" onclick="return confirm('⚠️ WARNING: This will permanently delete <?php echo htmlspecialchars($customer['username']); ?> and all their data. Are you sure?')">🗑 Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Unverified Caregivers Section -->
    <div class="section">
        <h2>👩‍⚕️ Unverified Caregivers <span class="count"><?php echo $totalCaregivers; ?></span></h2>
        
        <?php if (empty($unverifiedCaregivers)): ?>
            <div class="empty-state">
                <p>✅ No pending caregiver verifications. All caregivers are verified!</p>
            </div>
        <?php else: ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Experience</th>
                        <th>Price/hr</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unverifiedCaregivers as $caregiver): ?>
                    <tr>
                        <td><?php echo $caregiver['caregivers_id']; ?></td>
                        <td><?php echo htmlspecialchars($caregiver['username']); ?></td>
                        <td><?php echo htmlspecialchars($caregiver['email']); ?></td>
                        <td><?php echo htmlspecialchars($caregiver['phone']); ?></td>
                        <td><?php echo $caregiver['experience_years']; ?> yrs</td>
                        <td><?php echo $caregiver['price_per_hour']; ?> EGP</td>
                        <td><?php echo date('M d, Y', strtotime($caregiver['created_at'])); ?></td>
                        <td><span class="badge badge-pending">Pending</span></td>
                        <td>
                            <div class="action-buttons">
                                <form action="verify_user.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="user_type" value="caregiver">
                                    <input type="hidden" name="user_id" value="<?php echo $caregiver['caregivers_id']; ?>">
                                    <button type="submit" class="btn-verify" onclick="return confirm('Verify <?php echo htmlspecialchars($caregiver['username']); ?>?')">✓ Verify</button>
                                </form>
                                <form action="remove_user.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="user_type" value="caregiver">
                                    <input type="hidden" name="user_id" value="<?php echo $caregiver['caregivers_id']; ?>">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($caregiver['username']); ?>">
                                    <button type="submit" class="btn-remove" onclick="return confirm('⚠️ WARNING: This will permanently delete <?php echo htmlspecialchars($caregiver['username']); ?> and all their data. Are you sure?')">🗑 Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>