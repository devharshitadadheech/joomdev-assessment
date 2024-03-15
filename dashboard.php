<?php
session_start();
require_once "config.php";


if (!isset($_SESSION["username"]) || (isset($_SESSION['role']) && $_SESSION['role'] != 'admin')) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Welcome, <?php echo $row['username']; ?>!</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="report.php" style="color: black;">Download Report CSV <i class="fa fa-download" style="color: green;"></i></a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                            <button type="submit" class="btn btn-primary">
                                <a class="nav-link" href="logout.php" style="color: white;">Logout</a>
                            </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (isset($_SESSION['errors'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach ($_SESSION['errors'] as $error) { ?>
                                <li><?php echo $error; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php unset($_SESSION['errors']);
                } ?>
                <?php if (isset($_SESSION['_flash'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['_flash'];
                        unset($_SESSION['_flash']);
                        ?>
                    </div>
                <?php } ?>
                <div class="alert alert-danger" id="danger" role="alert" style="display: none;"><ul id="password-error"></ul></div>
                <h3>Create Employee</h3>
                <form action="create_employee.php" method="post" onsubmit="return validatePassword()">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="text" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="auto_generate" name="auto_generate" onchange="fillPassword(this)">
                        <label class="form-check-label" for="auto_generate">Auto-generate password</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Employee</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function generatePassword() {
            var length = 12;
            var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
            var password = "";
            for (var i = 0; i < length; i++) {
                var charIndex = Math.floor(Math.random() * charset.length);
                password += charset.charAt(charIndex);
            }
            return password;
        }

        function fillPassword(key) {
            if ($(key).prop('checked')) {
                var passwordInput = document.getElementById("password");
                var generatedPassword = generatePassword();
                passwordInput.value = generatedPassword;
            }
        }
    </script>
    <script>
        function validatePhone() {
            var phoneInput = document.getElementById("phone");
            var phonePattern = /^\d{10}$/;
            if (!phonePattern.test(phoneInput.value)) {
                alert("Please enter a valid 10-digit phone number.");
                phoneInput.focus();
                return false;
            }
            return true;
        }
    </script>
    <script>
        function validatePassword() {
            var passwordInput = document.getElementById("password");
            var password = passwordInput.value;
            var errorElement = document.getElementById("password-error");
            var errorMessage = document.getElementById("danger");

            if (password.length < 8) {
                errorElement.innerText = "Password must be at least 8 characters long.";
                errorMessage.style.display = "block";
                passwordInput.focus();
                return false;
            }

            var upperCaseLetters = /[A-Z]/g;
            if (!password.match(upperCaseLetters)) {
                errorElement.innerText = "Password must contain at least one uppercase letter.";
                errorMessage.style.display = "block";
                passwordInput.focus();
                return false;
            }

            var lowerCaseLetters = /[a-z]/g;
            if (!password.match(lowerCaseLetters)) {
                errorElement.innerText = "Password must contain at least one lowercase letter.";
                errorMessage.style.display = "block";
                passwordInput.focus();
                return false;
            }

            var numbers = /[0-9]/g;
            if (!password.match(numbers)) {
                errorElement.innerText = "Password must contain at least one number.";
                errorMessage.style.display = "block";
                passwordInput.focus();
                return false;
            }

            errorElement.innerText = ""; 
            errorMessage.style.display = "none";
            return true;
        }
    </script>

</body>

</html>
