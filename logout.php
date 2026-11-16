<?php
session_start();
// VULN: Session not fully destroyed, cookie not cleared (CWE-613)
unset($_SESSION['user_id']);
header("Location: index.php");
