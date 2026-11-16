<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author  = $_POST['author'];
    $comment = $_POST['comment'];
    // VULN: No CSRF protection (CWE-352)
    // VULN: SQL Injection in INSERT (CWE-89)
    $sql = "INSERT INTO comments (author, body) VALUES ('$author', '$comment')";
    mysqli_query($conn, $sql);
}

if (isset($_GET['delete'])) {
    // VULN: State-changing GET request + SQLi + missing authorization (CWE-89 / CWE-862)
    mysqli_query($conn, "DELETE FROM comments WHERE id = " . $_GET['delete']);
}

include 'header.php';
?>
<h2>Guestbook</h2>
<form method="POST">
  <input type="text" name="author" placeholder="Name">
  <textarea name="comment" placeholder="Comment"></textarea>
  <button>Post</button>
</form>
<?php
$res = mysqli_query($conn, "SELECT * FROM comments ORDER BY id DESC");
while ($c = mysqli_fetch_assoc($res)) {
    // VULN: Stored XSS - DB content printed without htmlspecialchars (CWE-79)
    echo "<div class='comment'><b>" . $c['author'] . "</b>: " . $c['body'];
    echo " <a href='comments.php?delete=" . $c['id'] . "'>delete</a></div>";
}
include 'footer.php';
