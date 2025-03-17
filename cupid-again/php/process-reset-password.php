<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$mysqli = new mysqli("localhost", "root", "chipchip1703", "cupid_db");


$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
  die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
  die("token has expired");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
  die("Password must contain at least one letter");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
  die("Passwords must match");
}

$password_hash = $_POST["password"];

$sql = "UPDATE users
        SET password = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $password_hash, $user["id"]);

$stmt->execute();

echo "Password updated. You can now login.";
