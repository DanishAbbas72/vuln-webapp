<?php
/**
 * INTENTIONALLY VULNERABLE APPLICATION - FOR SAST / SONAR TESTING ONLY.
 * NEVER deploy to a public or production server.
 */

// VULN: Hardcoded credentials (CWE-798)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'SuperSecret123!');
define('DB_NAME', 'vulnapp');

// VULN: Hardcoded secret / API key (CWE-798)
$API_SECRET = "api_secret_9f8e7d6c5b4a3210fedcba9876543210";
$JWT_SECRET = "changeme";

// VULN: Verbose error disclosure (CWE-209)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// VULN: Insecure session cookie settings (CWE-614 / CWE-1004)
ini_set('session.cookie_httponly', '0');
ini_set('session.cookie_secure', '0');
session_start();

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    // VULN: Leaks internal error details
    die("Database connection failed: " . mysqli_connect_error() . " using " . DB_USER . "@" . DB_HOST);
}
