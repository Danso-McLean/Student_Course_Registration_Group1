<?php
session_start();

// Store name briefly for the goodbye message
$name = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Student';

// Destroy all session data
$_SESSION = [];
session_destroy();

// Redirect to login with logout flag
header("Location: login.php?logged_out=1&name=" . urlencode($name));
exit();
?>
