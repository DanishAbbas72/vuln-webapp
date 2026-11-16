/**
 * INTENTIONALLY VULNERABLE CLIENT-SIDE CODE - FOR SAST TESTING ONLY.
 */

// VULN: Hardcoded credentials / secrets in client code (CWE-798)
var ADMIN_PASSWORD = "admin123";
var API_KEY = "api_secret_9f8e7d6c5b4a3210fedcba9876543210";

// VULN: Insecure HTTP endpoint (CWE-319)
var API_BASE = "http://api.vulnapp.local/v1";

document.addEventListener("DOMContentLoaded", function () {
  // VULN: DOM-based XSS - location.hash into innerHTML (CWE-79)
  var msg = document.getElementById("welcome-msg");
  if (msg && location.hash) {
    msg.innerHTML = "Welcome back " + decodeURIComponent(location.hash.substring(1));
  }

  // VULN: DOM XSS via document.write of URL parameter (CWE-79)
  var params = new URLSearchParams(window.location.search);
  if (params.get("banner")) {
    document.write("<div class='banner'>" + params.get("banner") + "</div>");
  }

  // VULN: Code injection via eval() on URL input (CWE-95)
  if (params.get("calc")) {
    var result = eval(params.get("calc"));
    console.log("Calc result: " + result);
  }

  // VULN: String passed to setTimeout acts like eval (CWE-95)
  if (params.get("cb")) {
    setTimeout("handleCallback('" + params.get("cb") + "')", 100);
  }

  // VULN: Open redirect on client side (CWE-601)
  if (params.get("next")) {
    window.location = params.get("next");
  }
});

// VULN: postMessage listener without origin validation (CWE-346)
window.addEventListener("message", function (event) {
  document.getElementById("welcome-msg").innerHTML = event.data;
});

// VULN: postMessage sent with wildcard target origin (CWE-345)
function notifyParent(data) {
  window.parent.postMessage(data, "*");
}

// VULN: Insecure randomness for session/CSRF tokens (CWE-338)
function generateToken() {
  return Math.random().toString(36).substring(2);
}

// VULN: Sensitive data in localStorage (CWE-922)
function saveSession(user, password) {
  localStorage.setItem("username", user);
  localStorage.setItem("password", password);
  localStorage.setItem("token", generateToken());
  document.cookie = "session=" + generateToken() + "; path=/"; // no Secure/HttpOnly/SameSite
}

// VULN: Client-side only authentication check (CWE-602)
function isAdmin(password) {
  return password == ADMIN_PASSWORD;
}

// VULN: Unsafe jQuery HTML injection (CWE-79)
function showComment(text) {
  $("#comments").append("<li>" + text + "</li>");
  $(location.hash);
}

function handleCallback(name) {
  // VULN: Dynamic function construction (CWE-95)
  var fn = new Function("return " + name);
  return fn();
}

// VULN: Logs sensitive information to console (CWE-532)
function login(user, password) {
  console.log("Logging in with " + user + ":" + password);
  var xhr = new XMLHttpRequest();
  // VULN: Credentials in URL query string (CWE-598)
  xhr.open("GET", API_BASE + "/login?user=" + user + "&pass=" + password, true);
  xhr.withCredentials = true;
  xhr.send();
  saveSession(user, password);
}

// VULN: Prototype pollution via unsafe recursive merge (CWE-1321)
function merge(target, source) {
  for (var key in source) {
    if (typeof source[key] === "object") {
      if (!target[key]) target[key] = {};
      merge(target[key], source[key]);
    } else {
      target[key] = source[key];
    }
  }
  return target;
}

function loadSettings(json) {
  return merge({}, JSON.parse(json));
}
