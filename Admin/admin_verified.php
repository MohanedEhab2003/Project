<?php
session_start();
require_once 'includes/db.inc.php';

// Check if admin is logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    die();
}

// Get verified customers
$queryCustomers = "SELECT customers_id, username, email, phone, created_at, is_verified 
                   FROM Customers 
                   WHERE is_verified = 1 
                   ORDER BY created_at DESC";
$stmtCustomers = $pdo->prepare($queryCustomers);
$stmtCustomers->execute();
$verifiedCustomers = $stmtCustomers->fetchAll(PDO::FETCH_ASSOC);

// Get verified caregivers
$queryCaregivers = "SELECT caregivers_id, username, email, phone, created_at, is_verified, experience_years, price_per_hour 
                    FROM Caregivers 
                    WHERE is_verified = 1 
                    ORDER BY created_at DESC";
$stmtCaregivers = $pdo->prepare($queryCaregivers);
$stmtCaregivers->execute();
$verifiedCaregivers = $stmtCaregivers->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verified Users - All Age Care Admin</title>
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
        }
        .btn-remove:hover {
            background: #c0392b;
            transform: scale(1.05);
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">💜 All Age Care Admin</div>
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="admin_verified.php" style="color: purple;">Verified Users</a></li>
        <li><form action="admin_logout.php" method="POST" style="display: inline;">
            <button type="submit" class="btn-logout">Logout</button>
        </form></li>
    </ul>
</div>

<div class="container">
    <!-- Verified Customers Section -->
    <div class="section">
        <h2>✅ Verified Customers <span class="count"><?php echo count($verifiedCustomers); ?></span></h2>
        
        <?php if (empty($verifiedCustomers)): ?>
            <div class="empty-state">
                <p>No verified customers yet.</p>
            </div>
        <?php else: ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Verified Since</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($verifiedCustomers as $customer): ?>
                    <tr>
                        <td><?php echo $customer['customers_id']; ?></td>
                        <td><?php echo htmlspecialchars($customer['username']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
                        <td><span class="badge badge-verified">Verified</span></td>
                        <td>
                            <div class="action-buttons">
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
    
    <!-- Verified Caregivers Section -->
    <div class="section">
        <h2>✅ Verified Caregivers <span class="count"><?php echo count($verifiedCaregivers); ?></span></h2>
        
        <?php if (empty($verifiedCaregivers)): ?>
            <div class="empty-state">
                <p>No verified caregivers yet.</p>
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
                        <th>Verified Since</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($verifiedCaregivers as $caregiver): ?>
                    <tr>
                        <td><?php echo $caregiver['caregivers_id']; ?></td>
                        <td><?php echo htmlspecialchars($caregiver['username']); ?></td>
                        <td><?php echo htmlspecialchars($caregiver['email']); ?></td>
                        <td><?php echo htmlspecialchars($caregiver['phone']); ?></td>
                        <td><?php echo $caregiver['experience_years']; ?> yrs</td>
                        <td><?php echo $caregiver['price_per_hour']; ?> EGP</td>
                        <td><?php echo date('M d, Y', strtotime($caregiver['created_at'])); ?></td>
                        <td><span class="badge badge-verified">Verified</span></td>
                        <td>
                            <div class="action-buttons">
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