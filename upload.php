<?php
require_once 'config.php';

$msg = '';
if (isset($_FILES['file'])) {
    $name = $_FILES['file']['name'];
    // VULN: Unrestricted file upload - no type/extension check (CWE-434)
    // VULN: Path traversal via client-supplied filename (CWE-22)
    $target = "uploads/" . $name;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        // VULN: Insecure permissions (CWE-732)
        chmod($target, 0777);
        $msg = "Uploaded to <a href='" . $target . "'>" . $target . "</a>";
        mysqli_query($conn, "INSERT INTO files (filename) VALUES ('" . $name . "')");
    } else {
        $msg = "Upload failed";
    }
}
include 'header.php';
?>
<h2>File Upload</h2>
<form method="POST" enctype="multipart/form-data">
  <input type="file" name="file">
  <button>Upload</button>
</form>
<p><?php echo $msg; ?></p>
<?php include 'footer.php'; ?>
