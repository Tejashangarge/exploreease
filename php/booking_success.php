<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed! 🎉</title>
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
        
        .success-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 40px 30px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.2);
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        
        .checkmark {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            border-radius: 50%;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: white;
            box-shadow: 0 15px 30px rgba(76,175,80,0.4);
            animation: bounceIn 1s ease-out;
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.15); }
            100% { transform: scale(1); }
        }
        
        .success-title {
            font-size: 2.2rem;
            color: #2d3436;
            margin-bottom: 12px;
            font-weight: 700;
        }
        
        .success-subtitle {
            font-size: 1.1rem;
            color: #636e72;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        
        .highlight {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
        }
        
        /* PERFECT SEAT TICKET */
        .seat-ticket {
            background: linear-gradient(135deg, #fdcb6e, #e17055);
            color: white;
            padding: 25px 20px;
            border-radius: 18px;
            margin: 25px 0;
            box-shadow: 0 12px 30px rgba(253,203,110,0.4);
            animation: slideUp 0.8s ease-out 0.2s both;
        }
        
        .seat-ticket h3 {
            font-size: 1.4rem;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .seat-number {
            font-size: 2.8rem;
            font-weight: 800;
            margin: 8px 0 15px;
            text-shadow: 0 3px 10px rgba(0,0,0,0.3);
            letter-spacing: 2px;
        }
        
        .ticket-details {
            display: flex;
            justify-content: space-around;
            font-size: 0.95rem;
            opacity: 0.95;
            font-weight: 500;
        }
        
        .pending-seat {
            background: linear-gradient(135deg, #e9ecef, #dee2e6);
            padding: 22px 20px;
            border-radius: 15px;
            margin: 20px 0;
            border-left: 5px solid #6c757d;
        }
        
        .pending-seat h4 {
            color: #495057;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .pending-seat p {
            color: #6c757d;
            margin-bottom: 8px;
        }
        
        .buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .btn {
            flex: 1;
            min-width: 160px;
            padding: 15px 25px;
            border: none;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #fdcb6e, #e17055);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before { left: 100%; }
        
        @media (max-width: 600px) {
            body { padding: 15px; }
            .success-container { padding: 30px 25px; margin: 10px; }
            .success-title { font-size: 1.9rem; }
            .seat-number { font-size: 2.3rem; }
            .ticket-details { flex-direction: column; gap: 5px; text-align: center; }
            .buttons { flex-direction: column; }
            .btn { min-width: 100%; padding: 16px; }
        }
        
        @media (max-width: 400px) {
            .checkmark { width: 85px; height: 85px; font-size: 42px; }
            .success-title { font-size: 1.7rem; }
        }
    </style>
</head>
<body>
    <?php
    // Get LAST booking safely
    $conn = new mysqli("localhost", "root", "", "travel_booking");
    $last_booking = null;
    if (!$conn->connect_error) {
        $result = $conn->query("SELECT * FROM passengers ORDER BY id DESC LIMIT 1");
        $last_booking = $result ? $result->fetch_assoc() : null;
        $conn->close();
    }
    ?>
    
    <div class="success-container">
        <div class="checkmark">✅</div>
        <h1 class="success-title">Booking Confirmed!</h1>
        
        <?php if ($last_booking): ?>
            <p class="success-subtitle">
                Welcome <strong><?php echo htmlspecialchars($last_booking['name']); ?></strong>! <br>
                <span class="highlight">Ticket #<?php echo $last_booking['id']; ?> confirmed</span>
            </p>
        <?php else: ?>
            <p class="success-subtitle">Your booking is confirmed successfully!</p>
        <?php endif; ?>
        
        <!-- SEAT STATUS -->
        <?php if ($last_booking && !empty($last_booking['seat_number'])): ?>
            <div class="seat-ticket">
                <h3>🎫 Seat Allocated!</h3>
                <div class="seat-number"><?php echo htmlspecialchars($last_booking['seat_number']); ?></div>
                <div class="ticket-details">
                    <span><?php echo htmlspecialchars($last_booking['coach_type']); ?></span>
                    <span><?php echo strtoupper($last_booking['travel_mode']); ?></span>
                    <span><?php echo date('d M Y', strtotime($last_booking['journey_date'])); ?></span>
                </div>
            </div>
        <?php else: ?>
            <div class="pending-seat">
                <h4>📋 Booking Confirmed</h4>
                <p>✅ Ticket saved successfully!</p>
                <?php if ($last_booking): ?>
                    <p><strong>Ref:</strong> #<?php echo $last_booking['id']; ?> | <strong>Mobile:</strong> <?php echo htmlspecialchars($last_booking['mobile']); ?></p>
                <?php endif; ?>
                <p style="font-size: 0.9rem; margin-top: 10px;">Admin will assign seat soon</p>
            </div>
        <?php endif; ?>
        
        <div class="buttons">
            <a href="passenger_form.php" class="btn btn-primary">
                ➕ Book Another
            </a>
            <a href="view_booking.php" class="btn btn-secondary" target="_blank">
                🔍 Check Booking
            </a>
        </div>
    </div>

    <script>
        function createConfetti() {
            for(let i = 0; i < 25; i++) {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                    position: fixed; left: ${Math.random() * 100}vw; top: -10px; 
                    width: 8px; height: 8px; 
                    background: hsl(${Math.random() * 360}, 70%, 60%); 
                    border-radius: 50%; pointer-events: none; z-index: 1000; 
                    animation: fall 2s linear forwards;
                `;
                document.body.appendChild(confetti);
                setTimeout(() => confetti.remove(), 2000);
            }
        }
        const style = document.createElement('style');
        style.textContent = '@keyframes fall { to { transform: translateY(100vh) rotate(720deg); } }';
        document.head.appendChild(style);
        setTimeout(createConfetti, 800);
    </script>
</body>
</html>














<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed! 🎉</title>
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
            overflow-x: hidden;
        }
        
        .success-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 60px 40px;
            max-width: 550px;
            width: 100%;
            text-align: center;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.2);
            animation: slideUp 0.8s ease-out;
            position: relative;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .checkmark {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
            box-shadow: 0 20px 40px rgba(76,175,80,0.4);
            animation: bounceIn 1s ease-out;
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .success-title {
            font-size: 2.8rem;
            color: #2d3436;
            margin-bottom: 15px;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .success-subtitle {
            font-size: 1.3rem;
            color: #636e72;
            margin-bottom: 30px;
            font-weight: 400;
        }
        
        .highlight {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }
        
        .btn {
            flex: 1;
            min-width: 180px;
            padding: 18px 30px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            box-shadow: 0 10px 30px rgba(0,184,148,0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #fdcb6e, #e17055);
            color: white;
            box-shadow: 0 10px 30px rgba(253,203,110,0.4);
        }
        
        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        @media (max-width: 768px) {
            .success-container {
                padding: 40px 25px;
                margin: 20px;
            }
            
            .success-title {
                font-size: 2.2rem;
            }
            
            .buttons {
                flex-direction: column;
            }
            
            .btn {
                min-width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="checkmark">✅</div>
        <h1 class="success-title">Booking Confirmed!</h1>
        <p class="success-subtitle">
            Thank you for choosing our service! <br>
            <span class="highlight">Your ticket is confirmed</span>
        </p>
        
        <div class="buttons">
            <a href="passenger_form.php" class="btn btn-primary">
                ➕ Book Another
            </a>
            <a href="admin_panel.php" class="btn btn-secondary">
                🛡️ View Admin Panel
            </a>
        </div>
    </div>

    <script>
        // Simple confetti effect
        function createConfetti() {
            for(let i = 0; i < 30; i++) {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                    position: fixed;
                    left: ${Math.random() * 100}vw;
                    top: -10px;
                    width: 8px;
                    height: 8px;
                    background: hsl(${Math.random() * 360}, 70%, 60%);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 1000;
                    animation: fall 2s linear forwards;
                `;
                document.body.appendChild(confetti);
                setTimeout(() => confetti.remove(), 2000);
            }
        }
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fall {
                to { transform: translateY(100vh) rotate(720deg); }
            }
        `;
        document.head.appendChild(style);
        
        setTimeout(createConfetti, 800);
    </script>
</body>
</html>
-->