<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["username"]) || (isset($_SESSION['role']) && $_SESSION['role'] != 'user')) {
    header("Location: login.php");
    exit;
}

require_once "password_change_check.php";

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
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
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Welcome, <?php echo $_SESSION['username']; ?>!</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <form action="" method="post">
                            <button type="submit" class="btn btn-primary">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (isset($_SESSION['_task'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['_task'];
                        unset($_SESSION['_task']);
                        ?>
                    </div>
                <?php } ?>
                <h3>Submit Tasks</h3>
                <form action="submit_task.php" method="post">
                    <div class="form-group">
                        <label for="start_time">Start Time:</label>
                        <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label for="stop_time">Stop Time:</label>
                        <input type="datetime-local" class="form-control" id="stop_time" name="stop_time" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes:</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Task</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>