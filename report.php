<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$sql = "SELECT role FROM users WHERE username='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="task_report.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'Start Time', 'Stop Time', 'Notes', 'Description'));

$sql = "SELECT id, start_time, stop_time, notes, description FROM tasks";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
