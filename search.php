<?php
require_once 'config.php';
include 'header.php';

$q = isset($_GET['q']) ? $_GET['q'] : '';
?>
<h2>Product Search</h2>
<form method="GET">
  <input type="text" name="q" value="<?php echo $q; ?>">
  <button>Search</button>
</form>
<?php
if ($q !== '') {
    // VULN: Reflected XSS (CWE-79)
    echo "<p>Results for: " . $q . "</p>";

    // VULN: SQL Injection in LIKE clause (CWE-89)
    $sql = "SELECT name, price FROM products WHERE name LIKE '%" . $q . "%'";
    $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<div>" . $row['name'] . " - $" . $row['price'] . "</div>";
    }
}
include 'footer.php';
