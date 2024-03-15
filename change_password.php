<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $hashed_password = generateHash($new_password);
    $username = $_SESSION['username'];
    $sql = "UPDATE users SET password='$hashed_password', last_password_reset=NOW(), password_change_required = 0 WHERE username='$username'";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating password: " . $conn->error;
    }
}

function generateHash($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function validatePassword() {
            var passwordInput = document.getElementById("new_password");
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

            var specialCharacters = /[$@$!%*?&]/g;
            if (!password.match(specialCharacters)) {
                errorElement.innerText = "Password must contain at least one special character ($@$!%*?&).";
                errorMessage.style.display = "block";
                passwordInput.focus();
                return false;
            }

            errorElement.innerText = "";
            errorMessage.style.display = "none";
            return true;
        }
    </script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger" id="danger" role="alert" style="display: none;">
                    <ul id="password-error"></ul>
                </div>
                <h2>Change Password</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validatePassword()">
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>