<?php
require_once 'config.php';
include 'header.php';

if (isset($_GET['page'])) {
    // VULN: Local/Remote File Inclusion (CWE-98)
    include($_GET['page']);
}

if (isset($_GET['file'])) {
    // VULN: Path traversal on file read (CWE-22)
    $content = file_get_contents("files/" . $_GET['file']);
    echo "<pre>" . $content . "</pre>";
}

if (isset($_GET['download'])) {
    // VULN: Arbitrary file download / path traversal (CWE-22)
    $path = $_GET['download'];
    header('Content-Disposition: attachment; filename="' . basename($path) . '"');
    readfile($path);
    exit;
}
include 'footer.php';
