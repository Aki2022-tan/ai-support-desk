<?php
// Optional: Handle logging if this file is used for submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logData = [
        'subject' => $_POST['subject'] ?? '',
        'department' => $_POST['department'] ?? '',
        'timestamp' => date('Y-m-d H:i:s')
    ];
    file_put_contents(__DIR__ . "/logs/ai-test.log", json_encode($logData) . PHP_EOL, FILE_APPEND);
    echo "<p style='color:green'>✔ Data sent and logged successfully.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AI Subject Suggestion Test</title>
  <style>
    body {
      font-family: sans-serif;
      padding: 2em;
    }
    label {
      display: block;
      margin-bottom: 10px;
    }
    input, select, button {
      padding: 8px;
      width: 100%;
      max-width: 300px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<h2>🧪 Test AI Suggestion API</h2>

<form method="POST" action="api/ai-suggest-subject.php" target="_blank">
  <label>
    Subject:
    <input type="text" name="subject" required>
  </label>

  <label>
    Department:
    <select name="department" required>
      <option value="">-- Select Department --</option>
      <option value="Technical">Technical</option>
      <option value="Billing">Billing</option>
      <option value="Sales">Sales</option>
    </select>
  </label>

  <button type="submit">Submit to API</button>
</form>

<hr>

<h4>📦 Log Tester</h4>
<form method="POST">
  <label>
    Subject (log only):
    <input type="text" name="subject">
  </label>
  <label>
    Department (log only):
    <select name="department">
      <option value="Technical">Technical</option>
      <option value="Billing">Billing</option>
      <option value="Sales">Sales</option>
    </select>
  </label>
  <button type="submit">Log This Data</button>
</form>

</body>
</html>