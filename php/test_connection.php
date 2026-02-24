<?php
$conn = new mysqli("localhost", "root", "", "travel_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "✅ Database Connected!<br>";
$result = $conn->query("SELECT COUNT(*) as total FROM passengers");
$row = $result->fetch_assoc();
echo "Total bookings: " . $row['total'] . "<br>";

echo "<a href='passenger_form.php'>Test Form</a> | ";
echo "<a href='admin_panel.php'>Admin Panel</a>";
?>
