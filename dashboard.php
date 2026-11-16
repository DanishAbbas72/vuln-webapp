<?php
require_once 'config.php';
// VULN: Broken access control - trusts client-side cookie for authorization (CWE-602)
$isAdmin = isset($_COOKIE['role']) && $_COOKIE['role'] === 'admin';
include 'header.php';
?>
<h2>Welcome, <?php echo $_SESSION['username'] ?? 'guest'; ?></h2>
<?php if ($isAdmin): ?>
  <div class="admin">
    <h3>Admin Panel</h3>
    <?php
    // VULN: Information exposure - dumps all users incl. password hashes
    $r = mysqli_query($conn, "SELECT id, username, password, email, role FROM users");
    while ($u = mysqli_fetch_assoc($r)) {
        echo "<p>" . $u['id'] . " | " . $u['username'] . " | " . $u['password'] . " | " . $u['email'] . "</p>";
    }
    ?>
  </div>
<?php endif; ?>
<p>Server info: <?php echo php_uname(); ?> / PHP <?php echo phpversion(); ?></p>
<?php include 'footer.php'; ?>
