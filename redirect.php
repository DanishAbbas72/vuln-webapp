<?php
// VULN: Open Redirect (CWE-601)
$url = $_GET['url'];
header("Location: " . $url);
exit;
