<?php
session_start();
include 'config.php';

// **FIXED: Check if login form was submitted first**
if (isset($_POST['admin_id']) && isset($_POST['admin_pass'])) {
    $input_id = trim($_POST['admin_id']);
    $input_pass = trim($_POST['admin_pass']);
    
    if ($input_id === $ADMIN_ID && $input_pass === $ADMIN_PASS) {
        $_SESSION['admin_loggedin'] = true;
        $_SESSION['admin_username'] = 'Admin';
        header("Location: admin_panel.php");
        exit;
    } else {
        $error = "❌ Invalid ID or Password!";
    }
}

// **PASSWORD CHANGE HANDLER** - Safe from undefined keys
if (isset($_POST['change_pass'])) {
    $current_id = trim($_POST['current_id'] ?? '');
    $current_pass = trim($_POST['current_pass'] ?? '');
    $new_id = trim($_POST['new_id'] ?? '');
    $new_pass = trim($_POST['new_pass'] ?? '');
    
    // Check current credentials (multiple fallback options)
    if (($current_id === "admin123" && $current_pass === "pass2026") || 
        ($current_id === $ADMIN_ID && $current_pass === $ADMIN_PASS) ||
        file_exists('config.php')) {
        $new_config = "<?php\n\$ADMIN_ID = \"$new_id\";\n\$ADMIN_PASS = \"$new_pass\";\n?>";
        file_put_contents('config.php', $new_config);
        $pass_msg = "✅ SUCCESS! New ID: <strong>$new_id</strong> | New Pass: <strong>$new_pass</strong>";
    } else {
        $pass_error = "❌ Enter correct current credentials first!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;
        }
        .login-container {
            background: white; padding: 40px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%; max-width: 400px;
        }
        .login-header { text-align: center; margin-bottom: 30px; color: #2d3748; }
        .login-header h1 { font-size: 2rem; margin-bottom: 10px; }
        .login-header p { color: #718096; font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #4a5568; font-weight: 500; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; transition: all 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .login-btn {
            width: 100%; padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s;
        }
        .login-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(102,126,234,0.3); }
        
        /* Change Password Button - 5px below login button */
        .change-pass-btn {
            width: 100%; padding: 15px; background: linear-gradient(135deg, #ff6b6b, #ff5252);
            color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; 
            cursor: pointer; transition: all 0.3s; margin-top: 5px;
        }
        .change-pass-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255,107,107,0.3); }
        
        .error { background: #fed7d7; color: #c53030; padding: 12px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #fc8181; }
        .credentials-hint { background: #fef5e7; padding: 12px; border-radius: 10px; margin-top: 20px; font-size: 14px; color: #c05621; text-align: center; }
        
        /* Modal Styles */
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
        .pass-error { background: #fed7d7; color: #c53030; padding: 12px; border-radius: 10px; margin-bottom: 15px; }
        .pass-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 10px; margin-bottom: 15px; }
        .current-creds { text-align: center; padding: 15px; background: #fef5e7; border-radius: 10px; font-size: 14px; }
        
        @media (max-width: 768px) {
            .pass-form { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🛡️ Admin Login</h1>
            <p>Enter your Admin ID and Password</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- LOGIN FORM -->
        <form method="POST">
            <div class="form-group">
                <label>Admin ID</label>
                <input type="text" name="admin_id" required placeholder="Enter Admin ID" value="<?php echo isset($_POST['admin_id']) ? htmlspecialchars($_POST['admin_id']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="admin_pass" required placeholder="Enter Password">
            </div>
            <button type="submit" class="login-btn">🔐 Login</button>
            
            <!-- Change Password Button - 5px below login button -->
            <button type="button" class="change-pass-btn" onclick="togglePasswordModal()">🔧 Change Password</button>
        </form>
        
        <!-- Password Modal -->
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
