# VulnApp — Intentionally Vulnerable PHP/JS/HTML App for SonarQube Testing

> ⚠️ **WARNING:** This application is deliberately insecure. Use it only for
> static analysis (SAST) testing and training. Never deploy it to a public,
> shared, or production server. The docker-compose file binds to 127.0.0.1 only.

## Vulnerability map

| File | Vulnerabilities |
|---|---|
| `config.php` | Hardcoded DB credentials & secrets, error disclosure, insecure session cookies |
| `index.php` | SQL injection, MD5 password hashing, reflected XSS, session fixation, insecure cookie, no CSRF |
| `dashboard.php` | Client-side cookie authorization, sensitive data exposure |
| `search.php` | Reflected XSS, SQL injection (LIKE), error message leakage |
| `comments.php` | Stored XSS, SQL injection, CSRF, state-changing GET, missing authorization |
| `ping.php` | OS command injection (`shell_exec`, `system`) |
| `upload.php` | Unrestricted file upload, path traversal, `chmod 0777` |
| `view.php` | Local/remote file inclusion, path traversal, arbitrary file download |
| `profile.php` | IDOR, SQL injection, insecure deserialization, mass assignment, sensitive logging |
| `redirect.php` | Open redirect |
| `api.php` | `eval()` code injection, SSRF, XXE, weak crypto (DES-ECB, SHA1, MD5), insecure random, permissive CORS, regex injection, `phpinfo()` |
| `assets/js/app.js` | DOM XSS (`innerHTML`, `document.write`), `eval`/`new Function`/string `setTimeout`, hardcoded secrets, `Math.random` tokens, localStorage secrets, postMessage w/o origin check, prototype pollution, credentials in URL |
| `about.html` | Reverse tabnabbing, unsandboxed iframe, HTTP form, inline DOM XSS |
| `header.php` | Third-party script over HTTP without SRI |

## Setup: GitHub Actions + Sonar

1. Push this folder to a GitHub repository.
2. Create a project in SonarQube (self-hosted) or SonarQube Cloud with key `vuln-webapp`.
   For SonarQube Cloud, also set `sonar.organization` in `sonar-project.properties`.
3. In GitHub → **Settings → Secrets and variables → Actions**, add:
   - `SONAR_TOKEN` — token generated in Sonar (My Account → Security)
   - `SONAR_HOST_URL` — e.g. `https://sonarcloud.io` or `https://sonar.yourcompany.com`
4. For SonarQube Cloud, disable **Automatic Analysis** in project settings (CI analysis and
   automatic analysis can't run together).
5. Push a commit or run the workflow manually from the **Actions** tab.

The workflow lints the code, runs the scan, checks the Quality Gate, exports
vulnerabilities/hotspots as JSON artifacts, and writes a summary to the run page.

## Important note on detection

Injection findings (SQLi, XSS, command injection, path traversal, SSRF, etc.) come from
Sonar's **taint analysis**, which is available in SonarQube Cloud and in SonarQube
Developer Edition and above. The free **Community Build** will still flag hardcoded
credentials, weak crypto, `eval`, insecure cookies, and many security hotspots, but
will miss most data-flow injection issues.

## Running locally (optional)

```bash
docker compose up --build
# open http://127.0.0.1:8080  (admin / admin123)
```
