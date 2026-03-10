<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize inputs
    $username = trim(htmlspecialchars($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "Both username and password are required.";
    } else {
        $users_file = 'profiles/users.txt';

        if (!file_exists($users_file)) {
            $error = "No accounts found. Please register first.";
        } else {
            $found = false;
            $lines = file($users_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                $user = json_decode($line, true);

                if ($user && strtolower($user['username']) === strtolower($username)) {
                    $found = true;

                    // Verify password
                    if (password_verify($password, $user['password'])) {
                        // Set session variables
                        $_SESSION['username']    = $user['username'];
                        $_SESSION['full_name']   = $user['full_name'];
                        $_SESSION['student_id']  = $user['student_id'];
                        $_SESSION['email']       = $user['email'];
                        $_SESSION['phone']       = $user['phone']       ?? '';
                        $_SESSION['program']     = $user['program'];
                        $_SESSION['year_level']  = $user['year_level'];
                        $_SESSION['year_label']  = $user['year_label'];
                        $_SESSION['gender']      = $user['gender'];
                        $_SESSION['profile_pic'] = $user['profile_pic'] ?? 'default.png';
                        $_SESSION['registered']  = $user['registered']  ?? '';
                        $_SESSION['login_time']  = date('Y-m-d H:i:s');

                        // Redirect to dashboard
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $error = "Incorrect password. Please try again.";
                    }
                    break;
                }
            }

            if (!$found) {
                $error = "No account found for that username.";
            }
        }
    }
}

// Show success flash message if redirected after registration
if (isset($_GET['registered'])) {
    $success = "Registration complete! Please log in with your new credentials.";
}

// Show logout message
if (isset($_GET['logged_out'])) {
    $name    = htmlspecialchars(urldecode($_GET['name'] ?? 'Student'));
    $success = "Goodbye, $name! You have been logged out successfully.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – EduPortal</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>🎓 <span>Edu</span>Portal</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php" class="active">Login</a></li>
    </ul>
</nav>

<main>
    <section style="max-width: 480px; margin: 0 auto;">
        <h2>🔐 Student Login</h2>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">✅ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       placeholder="Enter your username"
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                       required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Enter your password" required>
            </div>

            <div class="form-submit">
                <button type="submit" class="btn btn-primary">Sign In</button>
                <a href="register.php">No account? Register here</a>
            </div>

        </form>

        <div class="alert alert-info" style="margin-top: 25px;">
            ℹ️ Don't have an account yet? <a href="register.php"><strong>Register as a new student</strong></a>.
        </div>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> EduPortal &mdash; GROUP(1)ONE &mdash; Student Course Registration System</p>
</footer>

</body>
</html>
