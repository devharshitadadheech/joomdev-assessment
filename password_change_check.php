<?php
$username = $_SESSION['username'];
$sql = "SELECT last_login, last_password_reset, password_change_required FROM users WHERE username='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$last_login = strtotime($row['last_login']);

$last_password_change = strtotime($row['last_password_reset']);
$current_time = time();
$password_change_due = false;

if ($row['password_change_required'] == 1) {
    $password_change_due = true;
} elseif (($current_time - $last_password_change) >= (30 * 24 * 60 * 60)) {
    $password_change_due = true;
}
if ($password_change_due) {
    header("Location: change_password.php");
    exit;
}
