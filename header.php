<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>VulnApp - SAST Test Target</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- VULN: External script without Subresource Integrity (CWE-353) -->
  <script src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="assets/js/app.js"></script>
</head>
<body>
<nav>
  <a href="dashboard.php">Dashboard</a>
  <a href="search.php">Search</a>
  <a href="comments.php">Comments</a>
  <a href="ping.php">Network Tool</a>
  <a href="upload.php">Upload</a>
  <a href="view.php?page=files/about.txt">View File</a>
  <a href="profile.php?id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; ?>">Profile</a>
  <a href="api.php">API</a>
  <a href="logout.php">Logout</a>
</nav>
<main>
