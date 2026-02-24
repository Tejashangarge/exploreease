<?php
session_start();

// SESSION CHECK
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// **DELETE HANDLER** - FIXED!
if (isset($_GET['delete'])) {
    $passenger_id = (int)$_GET['delete'];
    $conn = new mysqli("localhost", "root", "", "travel_booking");
    
    $stmt = $conn->prepare("DELETE FROM passengers WHERE id = ?");
    $stmt->bind_param("i", $passenger_id);
    
    if ($stmt->execute()) {
        header("Location: admin_panel.php?msg=deleted");
    } else {
        header("Location: admin_panel.php?msg=delete_error");
    }
    $stmt->close();
    $conn->close();
    exit;
}

// **SEAT ASSIGNMENT**
if (isset($_POST['assign_seat'])) {
    $passenger_id = (int)$_POST['passenger_id'];
    $seat_number = trim($_POST['seat_number']);
    $conn = new mysqli("localhost", "root", "", "travel_booking");
    
    $stmt = $conn->prepare("UPDATE passengers SET seat_number = ? WHERE id = ?");
    $stmt->bind_param("si", $seat_number, $passenger_id);
    
    if ($stmt->execute()) {
        header("Location: admin_panel.php?msg=seat_assigned");
    }
    $stmt->close();
    $conn->close();
    exit;
}

// **PASSWORD CHANGE**
if (isset($_POST['change_pass'])) {
    $current_id = trim($_POST['current_id']);
    $current_pass = trim($_POST['current_pass']);
    $new_id = trim($_POST['new_id']);
    $new_pass = trim($_POST['new_pass']);
    
    if (($current_id === "admin123" && $current_pass === "pass2026") || 
        file_exists('config.php')) {
        $new_config = "<?php\n\$ADMIN_ID = \"$new_id\";\n\$ADMIN_PASS = \"$new_pass\";\n?>";
        file_put_contents('config.php', $new_config);
        $pass_msg = "✅ SUCCESS! New ID: <strong>$new_id</strong> | New Pass: <strong>$new_pass</strong>";
    } else {
        $pass_error = "❌ Enter correct current credentials first!";
    }
}

// **FETCH DATA**
$conn = new mysqli("localhost", "root", "", "travel_booking");
$result = $conn->query("SELECT * FROM passengers ORDER BY created_at DESC");
$total_passengers = $result ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Travel Booking</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 20px; min-height: 100vh; }
        
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px 30px; border-radius: 15px 15px 0 0; box-shadow: 0 10px 30px rgba(102,126,234,0.3); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; }
        .header-left { display: flex; flex-direction: column; }
        .header h1 { font-size: 2.2rem; margin-bottom: 5px; }
        .total-count { font-size: 1.2rem; opacity: 0.95; }
        .header-buttons { display: flex; gap: 12px; flex-wrap: wrap; }
        .header-btn { padding: 12px 24px; border: none; border-radius: 25px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; color: white; }
        .refresh-btn { background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); }
        .add-btn { background: linear-gradient(135deg, #11998e, #38ef7d); }
        .pass-btn { background: linear-gradient(135deg, #fdcb6e, #e17055); }
        .logout-btn { background: linear-gradient(135deg, #ff6b6b, #ee5a52); }
        .header-btn:hover { transform: translateY(-2px) scale(1.05); box-shadow: 0 8px 25px rgba(0,0,0,0.3); }
        
        .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; display: none; align-items: center; justify-content: center; }
        .modal-content { background: white; border-radius: 20px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.4); animation: modalSlideIn 0.3s ease; }
        @keyframes modalSlideIn { from { transform: scale(0.7); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-header { background: linear-gradient(135deg, #ff6b6b, #ff5252); color: white; padding: 20px 25px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center; }
        .modal-header h3 { margin: 0; font-size: 1.4rem; }
        .close-btn { font-size: 28px; cursor: pointer; color: rgba(255,255,255,0.8); transition: 0.3s; }
        .close-btn:hover { color: white; transform: rotate(90deg); }
        .modal-body { padding: 30px 25px 25px; }
        .pass-form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0; }
        .pass-form input { padding: 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; transition: 0.3s; }
        .pass-form input:focus { outline: none; border-color: #ff6b6b; box-shadow: 0 0 0 3px rgba(255,107,107,0.1); }
        .pass-form button { grid-column: span 2; padding: 15px; background: linear-gradient(135deg, #ff6b6b, #ff5252); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .pass-form button:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(255,107,107,0.4); }
        .current-creds { text-align: center; padding: 15px; background: #fef5e7; border-radius: 10px; font-size: 14px; }
        .pass-error { background: #fed7d7; color: #c53030; padding: 12px; border-radius: 10px; margin-bottom: 15px; }
        .pass-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 10px; margin-bottom: 15px; }
        
        .seat-assign-section { width: 100%; max-width: 500px; background: linear-gradient(135deg, #f56a47, #ee811b); color: white; padding: 25px; margin: 0 20px 20px; border-radius: 15px; box-shadow: 0 10px 30px rgba(245,106,71,0.3); }
        .seat-assign-section h3 { margin-bottom: 15px; font-size: 1.5rem; }
        .seat-form { display: flex; gap: 15px; align-items: end; flex-wrap: wrap; }
        .seat-form select, .seat-form input { padding: 12px; border: none; border-radius: 10px; font-size: 16px; flex: 1; min-width: 150px; background: rgba(255,255,255,0.9); }
        .seat-form button { background: rgba(255,255,255,0.2); color: white; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; white-space: nowrap; }
        .seat-form button:hover { background: rgba(255,255,255,0.3); transform: translateY(-2px); }
        
        .container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); overflow: hidden; }
        .success-msg { background: #d4edda; color: #155724; padding: 15px 25px; margin: 20px; border-radius: 10px; border-left: 5px solid #28a745; text-align: center; font-weight: 500; }
        .error-msg { background: #fed7d7; color: #c53030; padding: 15px 25px; margin: 20px; border-radius: 10px; border-left: 5px solid #fc8181; text-align: center; font-weight: 500; }
        
        table { width: 100%; border-collapse: collapse; font-size: 15px; }
        th { background: #f8f9fa; color: #2d3748; font-weight: 600; padding: 18px 15px; text-align: left; border-bottom: 3px solid #e2e8f0; position: sticky; top: 0; z-index: 10; }
        td { padding: 16px 15px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        tr:hover { background: #f7fafc; }
        .mode-bus { background: linear-gradient(135deg, #ff6b6b, #ff5252) !important; box-shadow: 0 2px 8px rgba(255,107,107,0.4); }
        .mode-train { background: linear-gradient(135deg, #4ecdc4, #26a69a) !important; box-shadow: 0 2px 8px rgba(78,205,196,0.4); }
        .mode-flight { background: linear-gradient(135deg, #45b7d1, #2196f3) !important; box-shadow: 0 2px 8px rgba(69,183,209,0.4); }
        .mode-car { background: linear-gradient(135deg, #f093fb, #f5576c) !important; box-shadow: 0 2px 8px rgba(240,147,251,0.4); }
        .mode-badge, .coach-badge, .seat-badge { display: inline-block; padding: 8px 16px; border-radius: 25px; color: white; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .coach-badge, .seat-badge { padding: 6px 12px; font-size: 12px; margin-top: 4px; border-radius: 15px; }
        .seat-badge { background: linear-gradient(135deg, #fdcb6e, #e17055); box-shadow: 0 2px 8px rgba(253,203,110,0.4); }
        .date-badge { background: #fed7d7; color: #c53030; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; }
        .delete-btn { background: linear-gradient(135deg, #ff4757, #ff3742); color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer; font-weight: 600; font-size: 14px; text-decoration: none; display: inline-block; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(255,71,87,0.3); }
        .delete-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(255,71,87,0.4); }
        
        .no-data { text-align: center; padding: 60px; color: #718096; }
        .no-data h3 { margin-bottom: 20px; font-size: 1.5rem; }
        .no-data a { background: linear-gradient(135deg, #48bb78, #38a169); color: white; padding: 15px 40px; border: none; border-radius: 25px; font-size: 16px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.3s; }
        .no-data a:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(72,187,120,0.4); }
        
        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; padding: 20px; }
            .header-buttons { justify-content: center; }
            .seat-form { flex-direction: column; }
            .pass-form { grid-template-columns: 1fr; }
            table { font-size: 13px; }
            th, td { padding: 12px 8px; }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            <h1>🛡️ Admin Dashboard</h1>
            <div class="total-count">Total Bookings: <strong><?php echo $total_passengers; ?></strong></div>
        </div>
        <div class="header-buttons">
            <a href="admin_panel.php" class="header-btn refresh-btn" title="Refresh">
                <span>🔄</span> Refresh
            </a>
            <a href="passenger_form.php" class="header-btn add-btn" title="New Booking">
                <span>➕</span> New Booking
            </a>
            <a href="#" onclick="togglePasswordModal()" class="header-btn pass-btn" title="Change Password">
                <span>🔐</span> Change Pass
            </a>
            <a href="logout.php" class="header-btn logout-btn" title="Logout">
                <span>🚪</span> Logout
            </a>
        </div>
    </div>

    <!-- MESSAGES -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'seat_assigned'): ?>
        <div class="success-msg">✅ Seat assigned successfully!</div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="success-msg">✅ Booking deleted successfully!</div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'delete_error'): ?>
        <div class="error-msg">❌ Delete failed! Please try again.</div>
    <?php endif; ?>

    <!-- PASSWORD MODAL -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>🔐 Change Admin Password</h3>
                <span class="close-btn" onclick="togglePasswordModal()">&times;</span>
            </div>
            <div class="modal-body">
                <?php if (isset($pass_error)): ?>
                    <div class="pass-error"><?php echo $pass_error; ?></div>
                <?php endif; ?>
                <?php if (isset($pass_msg)): ?>
                    <div class="pass-success"><?php echo $pass_msg; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="pass-form">
                    <input type="text" name="current_id" placeholder="Current ID" required>
                    <input type="password" name="current_pass" placeholder="Current Password" required>
                    <input type="text" name="new_id" placeholder="New ID" required>
                    <input type="password" name="new_pass" placeholder="New Password" required>
                    <button type="submit" name="change_pass">Change Password 🔄</button>
                </form>
                
            </div>
        </div>
    </div>

    <!-- SEAT ASSIGNMENT -->
    <?php if ($total_passengers > 0): ?>
    <div class="seat-assign-section">
        <h3>🎫 Assign Seat/Coach Number</h3>
        <form method="POST" class="seat-form">
            <select name="passenger_id" required>
                <option value="">Select Passenger</option>
                <?php 
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()): 
                ?>
                <option value="<?php echo $row['id']; ?>">
                    #<?php echo $row['id']; ?> - <?php echo htmlspecialchars($row['name']); ?> 
                    (<?php echo $row['travel_mode']; ?> - <?php echo $row['coach_type'] ?: 'N/A'; ?>)
                </option>
                <?php endwhile; 
                $result->data_seek(0);
                ?>
            </select>
            <input type="text" name="seat_number" placeholder="A1, 12A, Seat 15" required>
            <button type="submit" name="assign_seat">Assign Seat 🎫</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- MAIN TABLE -->
    <div class="container">
        <?php if ($total_passengers > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Passenger</th><th>Contact</th><th>Date</th><th>Route</th><th>Mode</th><th>Coach</th><th>Seat</th><th>Added</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td style="font-weight: bold; color: #4a5568;">#<?php echo $row['id']; ?></td>
                    <td>
                        <div style="font-weight: 600; color: #2d3748;"><?php echo htmlspecialchars($row['name']); ?></div>
                        <div style="font-size: 13px; color: #718096;"><?php echo htmlspecialchars(substr($row['address'], 0, 40)); ?>...</div>
                    </td>
                    <td>
                        <div><?php echo htmlspecialchars($row['mobile']); ?></div>
                        <div style="font-size: 13px; color: #718096;"><?php echo htmlspecialchars($row['email']); ?></div>
                    </td>
                    <td><span class="date-badge"><?php echo date('d M Y', strtotime($row['journey_date'])); ?></span></td>
                    <td style="font-weight: 600;">
                        <span style="color: #38a169;"><?php echo htmlspecialchars($row['from_location']); ?></span>
                        <span style="color: #e53e3e; font-weight: bold; font-size: 18px;"> → </span>
                        <span style="color: #38a169;"><?php echo htmlspecialchars($row['to_location']); ?></span>
                    </td>
                    <td><span class="mode-badge mode-<?php echo $row['travel_mode']; ?>"><?php echo ucfirst($row['travel_mode']); ?></span></td>
                    <td><span class="coach-badge"><?php echo htmlspecialchars($row['coach_type'] ?: 'N/A'); ?></span></td>
                    <td>
                        <?php if (!empty($row['seat_number'])): ?>
                            <span class="seat-badge">🎫 <?php echo htmlspecialchars($row['seat_number']); ?></span>
                        <?php else: ?>
                            <span style="color: #a0aec0; font-style: italic;">Not Assigned</span>
                        <?php endif; ?>
                    </td>
                    <td style="color: #718096; font-size: 14px;"><?php echo date('d M Y H:i', strtotime($row['created_at'])); ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete booking for <?php echo addslashes($row['name']); ?>?')">
                            🗑️ Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-data">
            <h3>📭 No Bookings Found</h3>
            <a href="passenger_form.php">➕ Add First Booking</a>
        </div>
        <?php endif; ?>
    </div>

    <script>
    function togglePasswordModal() {
        const modal = document.getElementById('passwordModal');
        modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
    }
    window.onclick = function(event) {
        const modal = document.getElementById('passwordModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
    </script>
</body>
</html>
<?php if (isset($conn)) { $conn->close(); } ?>
