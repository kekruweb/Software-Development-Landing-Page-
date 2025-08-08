<?php
// contact.php — handles POST from contact form
// Security: basic validation, simple rate-limiting via session, logs if mail fails

session_start();

function redirect_with($params) {
  $base = 'index.html';
  $qs = http_build_query($params);
  header('Location: ' . $base . '?' . $qs);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect_with(['status' => 'error', 'msg' => 'invalid_request']);
}

// Simple rate limit: one submission every 10 seconds per session
$now = time();
if (isset($_SESSION['last_submit']) && ($now - (int)$_SESSION['last_submit'] < 10)) {
  redirect_with(['status' => 'error', 'msg' => 'too_many_requests']);
}
$_SESSION['last_submit'] = $now;

// Collect and sanitize
$name    = trim((string)($_POST['name'] ?? ''));
$email   = trim((string)($_POST['email'] ?? ''));
$company = trim((string)($_POST['company'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $email === '' || $message === '') {
  redirect_with(['status' => 'error', 'msg' => 'missing_fields']);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  redirect_with(['status' => 'error', 'msg' => 'invalid_email']);
}

// Compose email
$to = 'hello@devspark.agency'; // TODO: change to your real inbox
$subject = 'New Project Inquiry from ' . $name;
$body_lines = [
  'You have a new contact form submission:',
  '---',
  'Name: ' . $name,
  'Email: ' . $email,
  'Company: ' . ($company !== '' ? $company : 'N/A'),
  'Message:',
  $message,
  '---',
  'IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'),
  'Agent: ' . ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'),
  'Time: ' . date('c'),
];
$body = implode("\r\n", $body_lines);

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/plain; charset=utf-8';
$headers[] = 'From: DevSpark <no-reply@devspark.local>'; // update domain if available
$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
$headers_str = implode("\r\n", $headers);

$mailed = false;
try {
  // On local XAMPP, mail() may not be configured — that's OK, we'll log instead
  $mailed = @mail($to, $subject, $body, $headers_str);
} catch (Throwable $e) {
  $mailed = false;
}

if (!$mailed) {
  // Fallback: log the message to a file
  $logDir = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'logs';
  if (!is_dir($logDir)) {
    @mkdir($logDir, 0777, true);
  }
  $logFile = $logDir . DIRECTORY_SEPARATOR . 'contacts-' . date('Y-m-d') . '.log';
  $entry = "==== NEW ENTRY " . date('c') . " ====\n" . $body . "\n\n";
  @file_put_contents($logFile, $entry, FILE_APPEND);
  redirect_with(['status' => 'ok', 'msg' => 'logged']);
}

redirect_with(['status' => 'ok', 'msg' => 'mailed']);
