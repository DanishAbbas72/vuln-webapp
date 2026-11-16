<?php
require_once 'config.php';

// VULN: Overly permissive CORS policy (CWE-942)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

$action = $_REQUEST['action'] ?? 'help';

switch ($action) {
    case 'calc':
        // VULN: Code injection via eval() (CWE-95)
        $expr = $_POST['expr'];
        eval('$result = ' . $expr . ';');
        echo json_encode(['result' => $result]);
        break;

    case 'token':
        // VULN: Insecure randomness for security token (CWE-338)
        $token = md5(rand() . time());
        echo json_encode(['reset_token' => $token]);
        break;

    case 'hash':
        // VULN: Weak hashing algorithm SHA1 for passwords (CWE-328)
        echo json_encode(['hash' => sha1($_GET['pw'])]);
        break;

    case 'fetch':
        // VULN: Server-Side Request Forgery (CWE-918)
        $data = file_get_contents($_GET['url']);
        echo json_encode(['body' => $data]);
        break;

    case 'xml':
        // VULN: XML External Entity injection (CWE-611)
        $xml = file_get_contents('php://input');
        $doc = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOENT | LIBXML_DTDLOAD);
        echo json_encode(['parsed' => (string) $doc->name]);
        break;

    case 'encrypt':
        // VULN: Weak cipher (DES) in ECB mode with hardcoded key (CWE-327 / CWE-321)
        $enc = openssl_encrypt($_GET['data'], 'des-ecb', 'hardkey1');
        echo json_encode(['encrypted' => $enc]);
        break;

    case 'user':
        // VULN: SQL Injection + sensitive data exposure (CWE-89 / CWE-200)
        $r = mysqli_query($conn, "SELECT * FROM users WHERE username = '" . $_GET['name'] . "'");
        echo json_encode(mysqli_fetch_all($r, MYSQLI_ASSOC));
        break;

    case 'regex':
        // VULN: ReDoS / regex injection with user-controlled pattern (CWE-1333)
        $ok = preg_match('/' . $_GET['pattern'] . '/', $_GET['subject']);
        echo json_encode(['match' => $ok]);
        break;

    case 'debug':
        // VULN: Exposes environment and config (CWE-200)
        phpinfo();
        break;

    default:
        echo json_encode(['actions' => ['calc', 'token', 'hash', 'fetch', 'xml', 'encrypt', 'user', 'regex', 'debug']]);
}
