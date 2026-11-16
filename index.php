<?php
require_once 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // VULN: Weak hashing algorithm MD5 (CWE-327 / CWE-916)
    $hashed = md5($password);

    // VULN: SQL Injection via string concatenation (CWE-89)
    $sql = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $hashed . "'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // VULN: Session fixation - no session_regenerate_id() (CWE-384)
        $_SESSION['user_id']  = $row['id'];
        $_SESSION['username'] = $row['username'];
        // VULN: Client-controlled role cookie, no Secure/HttpOnly flags (CWE-565 / CWE-614)
        setcookie("role", $row['role']);
        header("Location: dashboard.php");
        exit;
    } else {
        // VULN: Reflected XSS - username echoed unescaped (CWE-79)
        $error = "Login failed for user: " . $username;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>VulnApp Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/app.js"></script>
</head>
<body>
<div class="login-box">
  <h1>VulnApp Login</h1>
  <?php if ($error) { echo "<p class='error'>" . $error . "</p>"; } ?>
  <!-- VULN: No CSRF token, autocomplete on password (CWE-352 / CWE-522) -->
  <form method="POST" action="index.php">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password" autocomplete="on">
    <button type="submit">Login</button>
  </form>
  <p id="welcome-msg"></p>
</div>
</body>
</html>
