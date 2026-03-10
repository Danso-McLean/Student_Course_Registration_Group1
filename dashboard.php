<?php
session_start();

// Protect this page – redirect to login if not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Pull session data
$full_name   = $_SESSION['full_name']   ?? 'Unknown';
$username    = $_SESSION['username']    ?? '';
$student_id  = $_SESSION['student_id'] ?? 'N/A';
$email       = $_SESSION['email']       ?? 'N/A';
$phone       = $_SESSION['phone']       ?? 'Not provided';
$program     = $_SESSION['program']     ?? 'N/A';
$year_label  = $_SESSION['year_label']  ?? 'N/A';
$gender      = $_SESSION['gender']      ?? 'N/A';
$profile_pic = $_SESSION['profile_pic'] ?? 'default.png';
$registered  = $_SESSION['registered'] ?? 'N/A';
$login_time  = $_SESSION['login_time']  ?? date('Y-m-d H:i:s');

// Determine status using if / elseif / else based on year level
$year_level = $_SESSION['year_level'] ?? '1';
if ($year_level == '1') {
    $academic_status = "Freshman";
    $status_note     = "Welcome to your first year! Focus on building a strong foundation.";
} elseif ($year_level == '2') {
    $academic_status = "Sophomore";
    $status_note     = "Great progress! You are halfway through your foundational courses.";
} elseif ($year_level == '3') {
    $academic_status = "Junior";
    $status_note     = "You are now tackling advanced coursework. Keep it up!";
} elseif ($year_level == '4') {
    $academic_status = "Senior";
    $status_note     = "Final stretch! Prepare for your capstone project and graduation.";
} else {
    $academic_status = "Enrolled";
    $status_note     = "Your academic journey is in progress.";
}

// Get first letter of name for avatar placeholder
$avatar_letter = strtoupper(substr($full_name, 0, 1));

// Profile picture path
$pic_path = 'profiles/' . $profile_pic;
$has_pic  = ($profile_pic !== 'default.png' && file_exists($pic_path));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – EduPortal</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>🎓 <span>Edu</span>Portal</h1>
    <span style="color:#c5cae9; font-size:0.9rem;">
        Logged in as <strong style="color:white;"><?php echo htmlspecialchars($username); ?></strong>
        &nbsp;|&nbsp; Session started: <?php echo htmlspecialchars($login_time); ?>
    </span>
</header>

<nav>
    <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<main>

    <!-- Welcome Banner -->
    <section>
        <div class="dashboard-header">
            <?php if ($has_pic): ?>
                <img src="<?php echo htmlspecialchars($pic_path); ?>"
                     alt="Profile picture" class="profile-avatar">
            <?php else: ?>
                <div class="profile-avatar-placeholder"><?php echo $avatar_letter; ?></div>
            <?php endif; ?>
            <div class="dashboard-header-info">
                <h2>Welcome back, <?php echo htmlspecialchars($full_name); ?>!</h2>
                <p>
                    <?php echo htmlspecialchars($academic_status); ?> &mdash;
                    <?php echo htmlspecialchars($program); ?>
                </p>
                <p style="margin-top:5px; color:#3949ab; font-style:italic; font-size:0.9rem;">
                    <?php echo htmlspecialchars($status_note); ?>
                </p>
            </div>
        </div>
        <a href="logout.php" class="btn btn-danger" style="float:right; margin-top:-10px;">🚪 Logout</a>
        <div style="clear:both;"></div>
    </section>

    <!-- Student Details -->
    <section>
        <h2>📋 Student Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Full Name</div>
                <div class="info-value"><?php echo htmlspecialchars($full_name); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Student ID</div>
                <div class="info-value"><?php echo htmlspecialchars($student_id); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Username</div>
                <div class="info-value"><?php echo htmlspecialchars($username); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value"><?php echo htmlspecialchars($email); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Phone</div>
                <div class="info-value"><?php echo htmlspecialchars($phone); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Gender</div>
                <div class="info-value"><?php echo htmlspecialchars($gender); ?></div>
            </div>
        </div>
    </section>

    <!-- Academic Status -->
    <section>
        <h2>🎓 Academic Details</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Program</div>
                <div class="info-value"><?php echo htmlspecialchars($program); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Year Level</div>
                <div class="info-value"><?php echo htmlspecialchars($year_label); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Academic Standing</div>
                <div class="info-value">
                    <span class="status-badge status-active">
                        ✅ <?php echo htmlspecialchars($academic_status); ?>
                    </span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Enrolment Status</div>
                <div class="info-value">
                    <span class="status-badge status-active">✅ Active</span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Registration Date</div>
                <div class="info-value"><?php echo htmlspecialchars($registered); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Last Login</div>
                <div class="info-value"><?php echo htmlspecialchars($login_time); ?></div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section>
        <h2>⚡ Quick Actions</h2>
        <div class="features">
            <div class="feature-card">
                <div class="icon">🏠</div>
                <h3>Home</h3>
                <p><a href="index.html" style="color:#3949ab;">Back to home page</a></p>
            </div>
            <div class="feature-card">
                <div class="icon">📝</div>
                <h3>New Registration</h3>
                <p><a href="register.php" style="color:#3949ab;">Register another student</a></p>
            </div>
            <div class="feature-card">
                <div class="icon">🚪</div>
                <h3>Logout</h3>
                <p><a href="logout.php" style="color:#ef5350;">End your session securely</a></p>
            </div>
        </div>
    </section>

</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> EduPortal &mdash; GROUP(1)ONE &mdash; Student Course Registration System</p>
</footer>

</body>
</html>
