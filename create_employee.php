<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $password = sanitizeInput(password_hash($_POST['password'], PASSWORD_DEFAULT));

    if (!validateEmail($email)) {
        $errors['email'] = "Invalid email format";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: dashboard.php");
    }

    if (empty($errors)) {
        $username = explode('@', $email)[0];
        $sql = "INSERT INTO users (username, first_name, last_name, email, phone, password) VALUES ('$username','$first_name', '$last_name', '$email', '$phone', '$password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['_flash'] = "User created successfully";
            header("Location: dashboard.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
