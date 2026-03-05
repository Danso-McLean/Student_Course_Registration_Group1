<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPortal – Student Course Registration</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>🎓 <span>Edu</span>Portal</h1>
    <?php if (isset($_SESSION['username'])): ?>
        <span style="color:#c5cae9; font-size:0.9rem;">
            Welcome, <strong style="color:white;"><?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></strong>
        </span>
    <?php endif; ?>
</header>

<nav>
    <ul>
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Login</a></li>
        <?php if (isset($_SESSION['username'])): ?>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>

    <!-- Hero Section -->
    <section class="hero">
        <h2>Student Course Registration System</h2>
        <p>Manage your courses, track your academic progress, and stay connected with your institution — all in one place.</p>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
        <?php else: ?>
            <a href="register.php" class="btn btn-primary">Get Started</a>
            <a href="login.php" class="btn btn-secondary">Sign In</a>
        <?php endif; ?>
    </section>

    <!-- About Section -->
    <section>
        <h2>About This System</h2>
        <p>
            The <strong>EduPortal Student Course Registration System</strong> is a web-based platform
            designed to simplify the process of student enrolment and course management. Students can
            register with their personal information, securely log in, and manage their academic profile.
        </p>
        <p>
            Built using <strong>HTML5</strong>, <strong>CSS3</strong>, and <strong>PHP</strong>, this
            system demonstrates core web development concepts including form handling, session management,
            file uploads, and input validation.
        </p>
    </section>

    <!-- Features Section -->
    <section>
        <h2>Key Features</h2>
        <div class="features">
            <div class="feature-card">
                <div class="icon">📝</div>
                <h3>Student Registration</h3>
                <p>Register with personal details and upload a profile picture.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🔐</div>
                <h3>Secure Login</h3>
                <p>Authenticate with credentials and maintain a secure session.</p>
            </div>
            <div class="feature-card">
                <div class="icon">📊</div>
                <h3>Personal Dashboard</h3>
                <p>View your profile, course status, and account information.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🖼️</div>
                <h3>Profile Upload</h3>
                <p>Upload and manage your student profile photo securely.</p>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section>
        <h2>How It Works</h2>
        <p>Getting started with EduPortal is simple:</p>
        <ol style="margin-left: 25px; line-height: 2; color: #555;">
            <li>Click <strong>Register</strong> to create your student account.</li>
            <li>Fill in your personal details and upload a profile picture.</li>
            <li>Use your credentials to <strong>Log In</strong>.</li>
            <li>Access your <strong>Dashboard</strong> to view your registration details.</li>
            <li>Log out securely when you are done.</li>
        </ol>
    </section>

</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> EduPortal &mdash; GROUP(1)ONE &mdash; Student Course Registration System &nbsp;|&nbsp;
    <a href="register.php">Register</a> &nbsp;|&nbsp; <a href="login.php">Login</a></p>
</footer>

</body>
</html>
