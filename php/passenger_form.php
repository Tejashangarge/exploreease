<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Details - Travel Booking</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Arial', sans-serif; 
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            min-height: 100vh; 
            padding: 20px;
        }
        
        .main-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .form-header h1 { font-size: 2.2rem; margin-bottom: 10px; }
        
        .form-body { padding: 40px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px; }
        .form-group { margin-bottom: 25px; }
        .form-group.full-width { grid-column: 1 / -1; }
        
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; font-size: 15px; }
        input, textarea, select {
            width: 100%; padding: 15px 18px; border: 2px solid #e1e8ed; border-radius: 10px;
            font-size: 16px; transition: all 0.3s ease; background: #f8fafc;
        }
        input:focus, textarea:focus, select:focus {
            outline: none; border-color: #667eea; background: white;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1); transform: translateY(-2px);
        }
        textarea { resize: vertical; min-height: 100px; }
        
        /* TRAVEL MODE SELECTION */
        .travel-mode-group {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 25px; border-radius: 15px; margin-bottom: 25px;
        }
        .travel-mode-group label { color: white; margin-bottom: 15px; font-size: 16px; }
        .travel-options { display: flex; gap: 15px; flex-wrap: wrap; }
        .travel-option {
            flex: 1; min-width: 140px; padding: 15px 20px; background: rgba(255,255,255,0.95);
            border-radius: 12px; cursor: pointer; text-align: center; transition: all 0.3s ease;
            border: 3px solid transparent; font-weight: bold;
        }
        .travel-option:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .travel-option.selected { 
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white; border-color: #4facfe; box-shadow: 0 10px 25px rgba(79,172,254,0.4);
        }
        .travel-icon { font-size: 28px; margin-bottom: 8px; display: block; }
        
        /* NEW COACH SELECTION */
        .coach-group {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            padding: 25px; border-radius: 15px; margin-bottom: 25px; display: none;
        }
        .coach-group.show { display: block; animation: slideDown 0.4s ease; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .coach-group label { color: #2d3436; margin-bottom: 15px; font-size: 16px; font-weight: bold; }
        .coach-options { display: flex; gap: 12px; flex-wrap: wrap; }
        .coach-option {
            flex: 1; min-width: 120px; padding: 12px 16px; background: white;
            border: 2px solid #e1e8ed; border-radius: 10px; cursor: pointer;
            text-align: center; transition: all 0.3s ease; font-weight: 500;
        }
        .coach-option:hover { border-color: #667eea; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .coach-option.selected { 
            background: #667eea; color: white; border-color: #5a67d8;
            box-shadow: 0 8px 20px rgba(102,126,234,0.3);
        }
        
        .location-row {
            display: grid; grid-template-columns: 1fr 70px 1fr; gap: 20px; align-items: end; margin-bottom: 30px;
        }
        .arrow-icon { font-size: 30px; color: #667eea; text-align: center; font-weight: bold; padding-top: 15px; }
        
        /* GLASSMORPHISM SUBMIT BUTTON */
        .submit-button {
            width: 100%;
            background: rgba(245, 48, 48, 0.89);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            color: white;
            padding: 22px 30px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            overflow: hidden;
        }
        .submit-button::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        .submit-button:hover::before { left: 100%; }
        .submit-button:hover {
            background: rgb(236, 100, 9);
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }
        
        @media (max-width: 768px) {
            .form-row, .location-row { grid-template-columns: 1fr; gap: 20px; }
            .travel-options, .coach-options { flex-direction: column; }
            .form-body { padding: 25px; }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="form-header">
            <h1>👤 Passenger Details</h1>
            <p>Complete your travel booking</p>
        </div>
        
        <div class="form-body">
            <form action="submit_booking.php" method="POST">
                <!-- Basic Details -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number *</label>
                        <input type="tel" name="mobile" placeholder="Enter Your Mobile Number" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" placeholder="Enter Your Email" required>
                    </div>
                    <div class="form-group">
                        <label>Journey Date *</label>
                        <input type="date" name="journey_date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Complete Address *</label>
                    <textarea name="address" placeholder="Enter your full address including city, state, pincode" required></textarea>
                </div>

                <!-- Travel Mode -->
                <div class="travel-mode-group">
                    <label>Choose Travel Mode *</label>
                    <div class="travel-options">
                        <div class="travel-option" onclick="selectTravelMode('bus', this)">
                            <span class="travel-icon">🚌</span>
                            <div>Bus</div>
                        </div>
                        <div class="travel-option" onclick="selectTravelMode('train', this)">
                            <span class="travel-icon">🚂</span>
                            <div>Train</div>
                        </div>
                        <div class="travel-option" onclick="selectTravelMode('flight', this)">
                            <span class="travel-icon">✈️</span>
                            <div>Flight</div>
                        </div>
                    </div>
                    <input type="hidden" name="travel_mode" id="travel_mode_input" value="">
                </div>

                <!-- Coach Selection -->
                <div class="coach-group" id="coach_selection">
                    <label>Select Coach/Class *</label>
                    <div class="coach-options" id="coach_options"></div>
                    <input type="hidden" name="coach_type" id="coach_type_input" value="">
                </div>

                <!-- Locations -->
                <div class="location-row">
                    <div class="form-group">
                        <label>From Location *</label>
                        <input type="text" name="from_location" placeholder="Mumbai" required>
                    </div>
                    <div class="arrow-icon">→</div>
                    <div class="form-group">
                        <label>To Location *</label>
                        <input type="text" name="to_location" placeholder="Delhi" required>
                    </div>
                </div>

                <button type="submit" class="submit-button">✅ Confirm & Book</button>
            </form>
        </div>
    </div>

    <script>
        let currentMode = '';
        
        function selectTravelMode(mode, element) {
            // Remove previous selection
            document.querySelectorAll('.travel-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('travel_mode_input').value = mode;
            currentMode = mode;
            
            // Show coach selection
            const coachGroup = document.getElementById('coach_selection');
            const coachOptions = document.getElementById('coach_options');
            coachGroup.classList.add('show');
            
            // Load coach options based on mode
            loadCoachOptions(mode, coachOptions);
        }
        
        function loadCoachOptions(mode, container) {
            const coaches = {
                bus: ['AC Sleeper', 'Non-AC Sleeper', 'Seater AC', 'Seater Non-AC'],
                train: ['Sleeper (SL)', '3rd AC (3A)', '2nd AC (2A)', '1st AC (1A)', 'AC Chair Car'],
                flight: ['Economy', 'Premium Economy', 'Business', 'First Class']
            };
            
            container.innerHTML = '';
            coaches[mode].forEach(coach => {
                const option = document.createElement('div');
                option.className = 'coach-option';
                option.onclick = () => selectCoach(coach, option);
                option.innerHTML = `<div style="font-size: 14px;">${coach}</div>`;
                container.appendChild(option);
            });
        }
        
        function selectCoach(coach, element) {
            document.querySelectorAll('.coach-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('coach_type_input').value = coach;
        }
    </script>
</body>
</html>
