<?php
require_once 'config.php';
include 'header.php';
?>
<h2>Network Diagnostic Tool</h2>
<form method="GET">
  <input type="text" name="host" placeholder="IP address or hostname">
  <button>Ping</button>
</form>
<pre>
<?php
if (isset($_GET['host'])) {
    $host = $_GET['host'];
    // VULN: OS Command Injection (CWE-78)
    $output = shell_exec("ping -c 2 " . $host);
    echo $output;

    // VULN: Command injection via system() as well
    if (isset($_GET['dns'])) {
        system("nslookup " . $_GET['dns']);
    }
}
?>
</pre>
<?php include 'footer.php'; ?>
