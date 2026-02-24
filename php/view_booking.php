<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Check Your Booking</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .booking-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 45px 35px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.2);
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h1 {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .logo p {
            color: #636e72;
            font-size: 1rem;
        }
        
        .form-section {
            background: rgba(255,255,255,0.5);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .form-section h2 {
            color: #2d3436;
            margin-bottom: 20px;
            font-size: 1.6rem;
            text-align: center;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .input-group input {
            width: 100%;
            padding: 18px 20px 18px 50px;
            border: 2px solid rgba(102,126,234,0.2);
            border-radius: 15px;
            font-size: 16px;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .input-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 10px 25px rgba(102,126,234,0.2);
            transform: translateY(-2px);
        }
        
        .input-group::before {
            content: '📱';
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #667eea;
        }
        
        .check-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0,184,148,0.3);
            position: relative;
            overflow: hidden;
        }
        
        .check-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,184,148,0.4);
        }
        
        .check-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .check-btn:hover::before { left: 100%; }
        
        /* TICKET RESULT */
        .ticket-result {
            animation: slideUp 0.6s ease-out 0.2s both;
        }
        
        .ticket-success {
            background: linear-gradient(135deg, #c47807, #c2a880);
            color: white;
            padding: 30px 25px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(76,175,80,0.3);
        }
        
        .ticket-success h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        
        .ticket-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
            font-size: 15px;
        }
        
        .seat-highlight {
            background: linear-gradient(135deg, #fdcb6e, #e17055);
            color: white;
            padding: 25px;
            border-radius: 18px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 12px 30px rgba(253,203,110,0.4);
        }
        
        .seat-highlight h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
        }
        
        .seat-number {
            font-size: 2.8rem;
            font-weight: 800;
            text-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }
        
        .ticket-error {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 25px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(255,107,107,0.3);
        }
        
        .nav-links {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 25px;
        }
        
        .nav-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: rgba(102,126,234,0.1);
        }
        
        .nav-links a:hover {
            background: rgba(102,126,234,0.2);
            transform: translateY(-2px);
        }
        
        @media (max-width: 600px) {
            .booking-container { padding: 35px 25px; margin: 15px; }
            .logo h1 { font-size: 1.9rem; }
            .ticket-details-grid { grid-template-columns: 1fr; gap: 10px; }
            .seat-number { font-size: 2.3rem; }
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <div class="logo">
            <h1>✈️ TravelBooking</h1>
            <p>Check your booking status</p>
        </div>
        
        <div class="form-section">
            <h2>🔍 Enter Mobile Number</h2>
            <form method="POST">
                <div class="input-group">
                    <input type="tel" name="mobile" placeholder="Enter Your Mobile Number" maxlength="10" required 
                           value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>">
                </div>
                <button type="submit" name="check_booking" class="check-btn">
                    Check Booking
                </button>
            </form>
        </div>
        
        <?php
        if (isset($_POST['check_booking'])) {
            $conn = new mysqli("localhost", "root", "", "travel_booking");
            $mobile = trim($_POST['mobile']);
            
            $result = $conn->query("SELECT * FROM passengers WHERE mobile='$mobile' ORDER BY created_at DESC LIMIT 1");
            $booking = $result ? $result->fetch_assoc() : null;
            $conn->close();
            
            if ($booking): ?>
                <div class="ticket-result">
                    <div class="ticket-success">
                        <h2>✅ Booking Found!</h2>
                        <div class="ticket-details-grid">
                            <div><strong>Ticket:</strong> #<?php echo $booking['id']; ?></div>
                            <div><strong>Name:</strong> <?php echo htmlspecialchars($booking['name']); ?></div>
                            <div><strong>Route:</strong> <?php echo htmlspecialchars($booking['from_location']); ?> → <?php echo htmlspecialchars($booking['to_location']); ?></div>
                            <div><strong>Date:</strong> <?php echo date('d M Y', strtotime($booking['journey_date'])); ?></div>
                            <div><strong>Mode:</strong> <?php echo ucfirst($booking['travel_mode']); ?></div>
                            <div><strong>Coach:</strong> <?php echo htmlspecialchars($booking['coach_type']); ?></div>
                        </div>
                        
                        <?php if (!empty($booking['seat_number'])): ?>
                            <div class="seat-highlight">
                                <h3>🎫 Your Seat</h3>
                                <div class="seat-number"><?php echo htmlspecialchars($booking['seat_number']); ?></div>
                            </div>
                        <?php else: ?>
                            <div style="background: rgba(255,193,7,0.2); color: #856404; padding: 20px; border-radius: 15px; margin-top: 20px;">
                                <strong>🪑 Seat:</strong> Not assigned yet<br>
                                <small>Admin will assign soon. Please check back.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="ticket-result">
                    <div class="ticket-error">
                        <h2>❌ No Booking Found</h2>
                        <p>No booking found for this mobile number.</p>
                        <p style="font-size: 0.95rem; opacity: 0.9;">Please check the number and try again.</p>
                    </div>
                </div>
            <?php endif;
        }
        ?>
        
       <div class="nav-links">
    <a href="passenger_form.php">➕ New Booking</a>
    <a href="ticket_download.php?mobile=<?php echo isset($booking) ? urlencode($mobile) : ''; ?>" 
       <?php echo isset($booking) ? '' : 'style="display:none;"'; ?> id="download-link">
       📥 Download Ticket
    </a>
    <a href="admin_panel.php">👨‍💼 Admin</a>
</div>

    </div>
    <script>
document.querySelector('form').addEventListener('submit', function() {
    const downloadLink = document.getElementById('download-link');
    if (downloadLink) downloadLink.style.display = 'none';
});
</script>

</body>
</html>
