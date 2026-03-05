<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$errors   = [];
$success  = "";
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── Sanitize & collect inputs ──────────────────────────────────────────
    $full_name   = trim(htmlspecialchars($_POST['full_name']   ?? ''));
    $student_id  = trim(htmlspecialchars($_POST['student_id']  ?? ''));
    $username    = trim(htmlspecialchars($_POST['username']    ?? ''));
    $email       = trim(htmlspecialchars($_POST['email']       ?? ''));
    $phone       = trim(htmlspecialchars($_POST['phone']       ?? ''));
    $program     = trim(htmlspecialchars($_POST['program']     ?? ''));
    $year_level  = trim(htmlspecialchars($_POST['year_level']  ?? ''));
    $gender      = trim(htmlspecialchars($_POST['gender']      ?? ''));
    $password    = $_POST['password']        ?? '';
    $confirm_pw  = $_POST['confirm_password'] ?? '';

    $formData = compact('full_name','student_id','username','email','phone','program','year_level','gender');

    // ── Validation ─────────────────────────────────────────────────────────

    // Full Name
    if (empty($full_name)) {
        $errors[] = "Full name is required.";
    } elseif (strlen($full_name) < 3) {
        $errors[] = "Full name must be at least 3 characters.";
    }

    // Student ID
    if (empty($student_id)) {
        $errors[] = "Student ID is required.";
    } elseif (!preg_match('/^[A-Za-z0-9\-]+$/', $student_id)) {
        $errors[] = "Student ID may only contain letters, numbers, and hyphens.";
    }

    // Username
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
        $errors[] = "Username must be 4–20 characters (letters, numbers, underscore only).";
    } else {
        // Check username uniqueness against stored file
        $users_file = 'profiles/users.txt';
        if (file_exists($users_file)) {
            $lines = file($users_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $user = json_decode($line, true);
                if ($user && strtolower($user['username']) === strtolower($username)) {
                    $errors[] = "That username is already taken.";
                    break;
                }
            }
        }
    }

    // Email
    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Phone (optional but validated if provided)
    if (!empty($phone) && !preg_match('/^[0-9\+\-\s]{7,15}$/', $phone)) {
        $errors[] = "Phone number format is invalid.";
    }

    // Program
    if (empty($program)) {
        $errors[] = "Please select a program.";
    }

    // Year Level – switch/case demonstration
    $year_label = '';
    switch ($year_level) {
        case '1': $year_label = 'First Year';   break;
        case '2': $year_label = 'Second Year';  break;
        case '3': $year_label = 'Third Year';   break;
        case '4': $year_label = 'Fourth Year';  break;
        default:
            $errors[] = "Please select a valid year level.";
    }

    // Gender
    if (empty($gender)) {
        $errors[] = "Please select a gender.";
    }

    // Password
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_pw) {
        $errors[] = "Passwords do not match.";
    }

    // ── File Upload ────────────────────────────────────────────────────────
    $profile_pic = 'default.png';

    if (!empty($_FILES['profile_pic']['name'])) {
        $file      = $_FILES['profile_pic'];
        $allowed   = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $ext_map   = ['image/jpeg' => 'jpg', 'image/png' => 'png',
                      'image/gif'  => 'gif', 'image/webp' => 'webp'];
        $max_size  = 2 * 1024 * 1024; // 2 MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "File upload failed (error code: {$file['error']}).";
        } elseif (!in_array($file['type'], $allowed)) {
            $errors[] = "Only JPEG, PNG, GIF, or WEBP images are allowed.";
        } elseif ($file['size'] > $max_size) {
            $errors[] = "Profile picture must be smaller than 2 MB.";
        } else {
            $ext         = $ext_map[$file['type']];
            $new_filename = 'profile_' . strtolower($username) . '_' . time() . '.' . $ext;
            $dest         = 'profiles/' . $new_filename;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $profile_pic = $new_filename;
            } else {
                $errors[] = "Could not save uploaded file. Please try again.";
            }
        }
    }

    // ── Save user if no errors ─────────────────────────────────────────────
    if (empty($errors)) {
        $user_record = [
            'full_name'   => $full_name,
            'student_id'  => $student_id,
            'username'    => $username,
            'email'       => $email,
            'phone'       => $phone,
            'program'     => $program,
            'year_level'  => $year_level,
            'year_label'  => $year_label,
            'gender'      => $gender,
            'password'    => password_hash($password, PASSWORD_DEFAULT),
            'profile_pic' => $profile_pic,
            'registered'  => date('Y-m-d H:i:s'),
        ];

        $users_file = 'profiles/users.txt';
        file_put_contents($users_file, json_encode($user_record) . PHP_EOL, FILE_APPEND | LOCK_EX);
        $success = "Registration successful! You can now <a href='login.php'>log in</a>.";
        $formData = []; // clear form
    }
}

$programs = [
    "Bachelor of Technology in Computer Science",
    "Bachelor of Technology in Software Engineering",
    "Bachelor of Technology in Information Technology",
    "Bachelor of Technology in Information Systems",
    "Bachelor of Engineering",
    "Bachelor of Business Administration",
    "Bachelor of Education",
    "Bachelor of Arts",
    "Bachelor of Technology in Hospitality Management",
    "Bachelor of Technology in Building Technology",
	"Bachelor of Technology in Estate Management",
    "Bachelor of Science in Nursing",
    "Bachelor of Science in Midwifery",
    "Bachelor of Science in Public Health",
    "Bachelor of Science in Environmental Science",
    "Bachelor of Science in Agriculture",
    "Bachelor of Science in Forestry",
    "Bachelor of Science in Fisheries and Aquaculture",
    "Bachelor of Science in Renewable Energy",
    "Bachelor of Science in Biotechnology",
    "Bachelor of Science in Data Science",
    "Bachelor of Science in Cybersecurity",
    "Bachelor of Science in Psychology",
    "Bachelor of Science in Sociology",
    "Bachelor of Science in Economics",
    "Bachelor of Science in Political Science",
    "Bachelor of Science in International Relations",
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – EduPortal</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>🎓 <span>Edu</span>Portal</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="register.php" class="active">Register</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<main>
    <section>
        <h2>📝 Student Registration</h2>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">✅ <?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin-top:8px; margin-left:18px;">
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo $err; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($success)): ?>
        <form method="POST" action="register.php" enctype="multipart/form-data">

            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" placeholder="e.g. orlando owusu"
                           value="<?php echo htmlspecialchars($formData['full_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="student_id">Student ID *</label>
                    <input type="text" id="student_id" name="student_id" placeholder="e.g. STU05254136001"
                           value="<?php echo htmlspecialchars($formData['student_id'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" placeholder="4–20 chars, no spaces"
                           value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" placeholder="group1@example.com"
                           value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="Optional"
                           value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Gender *</label>
                    <select id="gender" name="gender" required>
                        <option value="">-- Select --</option>
                        <?php foreach (['Male','Female','Non-binary','Prefer not to say'] as $g): ?>
                            <option value="<?php echo $g; ?>"
                                <?php echo (($formData['gender'] ?? '') === $g) ? 'selected' : ''; ?>>
                                <?php echo $g; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="program">Program *</label>
                    <select id="program" name="program" required>
                        <option value="">-- Select Program --</option>
                        <?php foreach ($programs as $p): ?>
                            <option value="<?php echo $p; ?>"
                                <?php echo (($formData['program'] ?? '') === $p) ? 'selected' : ''; ?>>
                                <?php echo $p; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year_level">Year Level *</label>
                    <select id="year_level" name="year_level" required>
                        <option value="">-- Select Year --</option>
                        <?php foreach (['1'=>'First Year','2'=>'Second Year','3'=>'Third Year','4'=>'Fourth Year'] as $v=>$l): ?>
                            <option value="<?php echo $v; ?>"
                                <?php echo (($formData['year_level'] ?? '') === $v) ? 'selected' : ''; ?>>
                                <?php echo $l; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" placeholder="Min 6 characters" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
                </div>
            </div>

            <div class="form-group">
                <label for="profile_pic">Profile Picture (JPEG/PNG/GIF/WEBP, max 2 MB)</label>
                <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
            </div>

            <div class="form-submit">
                <button type="submit" class="btn btn-primary">Create Account</button>
                <a href="login.php">Already registered? Sign in</a>
            </div>

        </form>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> EduPortal &mdash; GROUP(1)ONE &mdash; Student Course Registration System</p>
</footer>

</body>
</html>
