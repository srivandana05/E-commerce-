<?php
$password = "admin1234"; // Change this to your desired admin password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

echo "Hashed Password: " . $hashed_password;
?>
