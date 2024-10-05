<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Login</title>
</head>

<body>
    <?php
    session_start();
    include('common/config/database.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT * FROM patient WHERE email = '$email'";
        $result = mysqli_query($connection, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                switch ($row['role']) {
                    case 'patient':
                        $_SESSION['role'] = $row['role'];
                        header("Location: patient/dashboard.php");
                        break;

                    case 'therapist':
                        $_SESSION['role'] = $row['role'];
                        header("Location: therapist/dashboard.php");
                        break;

                    case 'auditor':
                        $_SESSION['role'] = $row['role'];
                        header("Location: auditor/dashboard.php");
                        break;

                    case 'staff':
                        $_SESSION['role'] = $row['role'];
                        header("Location: staff/dashboard.php");
                        break;

                    default:
                        header("Location: sign_up.php");
                        break;
                }
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
    ?>
    <form class="center" method="POST" action="">
        <div>
            <?php if (isset($error)) echo "<h3 class='alert'>$error</h3>"; ?>
            <img class="logo" src="img/logo.png" alt="CARE">
            <h3> Login </h3>
            <hr>
            <div>
                <label>Email</label>
                <div>
                    <input type="email" name="email" id="email" placeholder="Enter Email" required>
                </div>
            </div>

            <div>
                <label>Password</label>
                <div>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                </div>
                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
            </div>

            <div>
                <button type="submit" class="btn">Login</button>
            </div>

            <div>
                <p>
                    Not having account!
                    <a href="sign_up.php"> Sign Up! </a>
                </p>
            </div>
        </div>
    </form>
    <script src="js/navbar.js"></script>
</body>

</html>