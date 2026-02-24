<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📄 Download Your Ticket</title>
    <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .ticket-wrapper {
        background: white;
        max-width: 450px;
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        overflow: hidden;
    }
    
    .ticket-header {
        background: linear-gradient(135deg, #74b9ff, #0984e3);
        color: white;
        padding: 30px;
        text-align: center;
    }
    
    .ticket-header h1 {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .ticket-body {
        padding: 40px 30px;
    }
    
    .ticket-id {
        background: linear-gradient(135deg, #00b894, #00cec9);
        color: white;
        padding: 15px;
        text-align: center;
        border-radius: 12px;
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 30px;
    }
    
    .ticket-details {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 25px;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 16px;
    }
    
    .detail-label { 
        font-weight: 600; 
        color: #2d3436;
        min-width: 100px;
    }
    
    .detail-value { 
        color: #636e72; 
        font-weight: 500;
    }
    
    .seat-section {
        background: linear-gradient(135deg, #fdcb6e, #e17055);
        color: white;
        padding: 25px;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 30px;
    }
    
    .seat-number {
        font-size: 3.5rem;
        font-weight: 800;
        text-shadow: 0 3px 10px rgba(0,0,0,0.3);
        margin: 10px 0;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        padding: 25px 30px 30px;
        background: #f8f9fa;
    }
    
    .btn {
        padding: 15px 25px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        text-align: center;
        min-width: 140px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #00b894, #00cec9);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,184,148,0.4);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102,126,234,0.4);
    }
    
    .no-seat {
        background: rgba(255,193,7,0.2);
        border: 2px dashed #856404;
        color: #856404;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }
    
    /* ========================================
       PRINT STYLES - PERFECT FOR PRINTING
       ======================================== */
    @media print {
        /* Remove all backgrounds to save ink */
        body, .ticket-wrapper, .ticket-header, .ticket-id, 
        .ticket-details, .seat-section, .action-buttons {
            background: white !important;
            box-shadow: none !important;
        }
        
        /* Black text only */
        * {
            color: black !important;
            text-shadow: none !important;
        }
        
        /* Full page width, no margins */
        body {
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
        }
        
        .ticket-wrapper {
            max-width: none !important;
            width: 100% !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            margin: 0 !important;
        }
        
        /* Hide buttons completely */
        .action-buttons {
            display: none !important;
        }
        
        /* Print-optimized ticket header */
        .ticket-header {
            background: #74b9ff !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            padding: 25px !important;
        }
        
        .ticket-header h1 {
            font-size: 28px !important;
            margin-bottom: 8px !important;
        }
        
        /* Solid ticket ID background */
        .ticket-id {
            background: #00b894 !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            padding: 18px !important;
            font-size: 20px !important;
        }
        
        /* Clean ticket details */
        .ticket-details {
            background: #f5f5f5 !important;
            border: 2px solid #ddd !important;
            padding: 25px !important;
            margin: 0 0 20px 0 !important;
        }
        
        .detail-row {
            font-size: 16px !important;
            margin-bottom: 12px !important;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }
        
        .detail-label {
            font-weight: bold !important;
            color: black !important;
        }
        
        .detail-value {
            font-weight: 500 !important;
            color: black !important;
        }
        
        /* Perfect seat section for print */
        .seat-section {
            background: #fdcb6e !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            border: 3px solid #e17055 !important;
            padding: 25px !important;
        }
        
        .seat-number {
            font-size: 48px !important;
            color: black !important;
            font-weight: bold !important;
        }
        
        /* No seat message */
        .no-seat {
            border: 2px dashed black !important;
            background: white !important;
            color: black !important;
        }
        
        /* Page margins */
        @page {
            margin: 1cm;
        }
    }
    
    @media (max-width: 500px) {
        .detail-row { flex-direction: column; gap: 5px; }
        .detail-label { min-width: auto; }
        .action-buttons { flex-direction: column; }
    }
    </style>
</head>
<body>
    <?php
    $mobile = isset($_GET['mobile']) ? trim($_GET['mobile']) : '';
    $booking = null;
    
    if (!empty($mobile)) {
        $conn = new mysqli("localhost", "root", "", "travel_booking");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $result = $conn->query("SELECT * FROM passengers WHERE mobile='$mobile' ORDER BY created_at DESC LIMIT 1");
        $booking = $result ? $result->fetch_assoc() : null;
        $conn->close();
    }
    ?>
    
    <div class="ticket-wrapper">
        <?php if ($booking): ?>
            <div class="ticket-header">
                <h1>✈️ TravelBooking</h1>
                <p>Your Journey Ticket</p>
            </div>
            
            <div class="ticket-body">
                <div class="ticket-id">Ticket #<?php echo $booking['id']; ?></div>
                
                <div class="ticket-details">
                    <div class="detail-row">
                        <span class="detail-label">Passenger:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Mobile:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['mobile']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Route:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['from_location']); ?> → <?php echo htmlspecialchars($booking['to_location']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value"><?php echo date('d M Y', strtotime($booking['journey_date'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Mode:</span>
                        <span class="detail-value"><?php echo ucfirst(htmlspecialchars($booking['travel_mode'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Coach:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['coach_type']); ?></span>
                    </div>
                </div>
                
                <?php if (!empty($booking['seat_number'])): ?>
                    <div class="seat-section">
                        <h3>🎫 Your Seat</h3>
                        <div class="seat-number"><?php echo htmlspecialchars($booking['seat_number']); ?></div>
                    </div>
                <?php else: ?>
                    <div class="no-seat">
                        <strong>🪑 Seat Not Assigned</strong><br>
                        <small>Admin will assign soon</small>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="window.print()">🖨️ Print Ticket</button>
                <a href="check_booking.php" class="btn btn-secondary">🔍 Back</a>
            </div>
            
        <?php else: ?>
            <div style="padding: 60px 40px; text-align: center;">
                <h2 style="color: #ff6b6b; margin-bottom: 20px;">❌ No Booking Found</h2>
                <p style="color: #636e72; font-size: 16px;">No booking found for this mobile number.</p>
                <a href="check_booking.php" style="display: inline-block; margin-top: 25px; padding: 12px 30px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-decoration: none; border-radius: 12px;">← Back to Check Booking</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
       
    </script>
</body>
</html>
