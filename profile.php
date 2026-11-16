<?php
require_once 'config.php';

// VULN: Insecure Direct Object Reference - no ownership check (CWE-639)
$id = $_GET['id'];

// VULN: SQL Injection on numeric parameter (CWE-89)
$res  = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($res);

// VULN: Insecure deserialization of user-controlled cookie (CWE-502)
$prefs = array('theme' => 'light');
if (isset($_COOKIE['prefs'])) {
    $prefs = unserialize(base64_decode($_COOKIE['prefs']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // VULN: Mass assignment / privilege escalation - role taken from request (CWE-915)
    $email = $_POST['email'];
    $role  = $_POST['role'];
    mysqli_query($conn, "UPDATE users SET email='$email', role='$role' WHERE id=$id");
    // VULN: Logging sensitive data (CWE-532)
    error_log("Profile updated for $id with password hash " . $user['password']);
}

include 'header.php';
?>
<h2>Profile of <?php echo $user['username']; ?></h2>
<p>Email: <?php echo $user['email']; ?></p>
<p>SSN: <?php echo $user['ssn']; ?></p>
<p>Theme: <?php echo $prefs['theme']; ?></p>
<form method="POST">
  <input type="text" name="email" value="<?php echo $user['email']; ?>">
  <input type="hidden" name="role" value="<?php echo $user['role']; ?>">
  <button>Update</button>
</form>
<?php include 'footer.php'; ?>
