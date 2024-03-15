<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_time = $_POST['start_time'];
    $stop_time = $_POST['stop_time'];
    $notes = $_POST['notes'];
    $description = $_POST['description'];
    $username = $_SESSION['username'];

    $sql = "INSERT INTO tasks (username, start_time, stop_time, notes, description) VALUES ('$username', '$start_time', '$stop_time', '$notes', '$description')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['_task'] = "Task submitted successfully";
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
