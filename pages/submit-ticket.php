<?php
include('../includes/client-header.php');

$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

// Offensive word filter
function containsOffensiveWords($text) {
  $offensiveWords = [
    'sex', 'sexual', 'intercourse', 'fuck', 'nude', 'horny', 'porn', 'dick', 'boobs', 'vagina', 'penis', 'rape',
    'bitch', 'asshole', 'slut', 'cum', 'masturbate', 'orgasm', 'blowjob', 'xxx', 'fetish', 'nsfw', 'suck', 'cock'
  ];
  $text = strtolower($text);
  foreach ($offensiveWords as $word) {
    if (strpos($text, $word) !== false) {
      return true;
    }
  }
  return false;
}

// Translate message
function translateToEnglish($message, $ticket_id) {
  $ch = curl_init("http://localhost:8000/ai-support-desk/api/ai-translate.php");
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
      'message' => $message,
      'ticket_id' => $ticket_id
    ])
  ]);
  $response = curl_exec($ch);
  curl_close($ch);

  if ($response === false) {
    return $message; // Fallback to original message on failure
  }

  $data = json_decode($response, true);
  return $data['translated'] ?? $message;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $name    = $_SESSION['user_name'];
  $email   = $_SESSION['user_email'];
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if (empty($subject) || empty($message)) {
    $_SESSION['error'] = "Subject and message are required.";
  } elseif (containsOffensiveWords($subject) || containsOffensiveWords($message)) {
    $_SESSION['error'] = "Your message contains inappropriate language. Please revise it.";
  } else {
    try {
      $checkStmt = $conn->prepare("SELECT id FROM support_tickets WHERE user_id = ? AND subject = ? AND created_at > NOW() - INTERVAL 30 SECOND");
      $checkStmt->execute([$user_id, $subject]);

      if ($checkStmt->fetch()) {
        $_SESSION['error'] = "Please wait a moment before submitting the same ticket again.";
      } else {
        $stmt = $conn->prepare("INSERT INTO support_tickets (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $email, $subject, $message]);
        $ticket_id = $conn->lastInsertId();

        // Process file attachments
        if (!empty($_FILES['attachments']['name'][0])) {
          foreach ($_FILES['attachments']['name'] as $i => $origName) {
            $tmp = $_FILES['attachments']['tmp_name'][$i];
            $safeName = time() . '_' . basename($origName);
            $path = $uploadDir . $safeName;

            // Validate MIME type before moving
            if (is_uploaded_file($tmp)) {
              $mime = mime_content_type($tmp);
              if (str_starts_with($mime, 'image/') || str_starts_with($mime, 'application/')) {
                if (move_uploaded_file($tmp, $path)) {
                  $rel = '../uploads/' . $safeName;
                  $stmt2 = $conn->prepare("INSERT INTO ticket_attachments (ticket_id, file_name, file_path) VALUES (?, ?, ?)");
                  $stmt2->execute([$ticket_id, $origName, $rel]);
                }
              }
            }
          }
        }

        // AI Enhancements
        $translated = translateToEnglish($message, $ticket_id);
        $apis = ['ai-summary', 'ai-priority', 'ai-tone-check', 'ai-generate-reply'];

        foreach ($apis as $api) {
          $ch = curl_init("http://localhost:8000/ai-support-desk/api/{$api}.php");
          curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
              'message'    => $translated,
              'ticket_id'  => $ticket_id
            ])
          ]);
          curl_exec($ch);
          curl_close($ch);
        }

        $_SESSION['success'] = "Your ticket has been submitted successfully.";
        header("Location: ticket-detail.php?ticket_id=" . urlencode($ticket_id));
        exit;
      }
    } catch (Exception $e) {
      // Fail silently but securely
      $_SESSION['error'] = "An unexpected error occurred. Please try again later.";
    }
  }
}
?>

<section class="bg-blue-600 text-white text-center py-10 shadow-md">
  <h2 class="text-3xl font-bold mb-2">Need Help? Let Our AI Assist You</h2>
  <p class="text-blue-100 max-w-xl mx-auto">Submit your concern — our AI will summarize, prioritize, and help the support team respond better.</p>
</section>

<main class="flex-grow px-4 py-10">
  <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-lg">
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="bg-red-100 text-red-800 border border-red-300 rounded px-4 py-2 mb-4">
        <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-6 border-b pb-2">
      <p class="text-gray-600 text-sm">Logged in as: <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></p>
    </div>

<form method="POST" enctype="multipart/form-data" class="space-y-6">
  <!-- Subject Field -->
  <div class="relative group">
    <label for="subject" class="text-sm font-medium text-gray-700">Subject</label>
    <input 
      type="text" 
      id="subjectInput" 
      name="subject" 
      placeholder="Start typing your concern..."
      autocomplete="off"
      class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
    >
    <div id="aiLoader" class="absolute top-10 right-4 hidden">
      <svg class="h-5 w-5 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
      </svg>
    </div>
    <ul id="suggestionList" class="absolute z-10 mt-2 max-h-60 w-full overflow-y-auto rounded-md border border-gray-200 bg-white shadow-md text-sm hidden"></ul>
  </div>

  <!-- Message Field -->
  <div>
    <label for="message" class="text-sm font-medium text-gray-700">Describe your issue</label>
    <textarea 
      name="message" 
      id="message" 
      rows="5" 
      required
      placeholder="Tell us what's going on..."
      class="mt-1 w-full resize-none rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
    ></textarea>
  </div>

  <!-- Attachments -->
  <div>
    <label class="text-sm font-medium text-gray-700">Attachments <span class="text-gray-400">(optional)</span></label>
    <input 
      type="file" 
      name="attachments[]" 
      multiple
      class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-blue-700 hover:file:bg-blue-100 transition"
    >
  </div>

  <!-- Submit Button -->
  <div>
    <button 
      type="submit" 
      class="w-full rounded-xl bg-blue-600 px-6 py-3 text-white font-semibold shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
    >
      Submit Ticket
    </button>
  </div>
</form>
  </div>
</main>

<footer class="text-center text-sm text-gray-400 py-6">
  © <?= date('Y') ?> AI Support Desk. Built by Rogienald.
</footer>

<!-- Scripts -->
<script src="../assets/js/submit-ticket.js"></script>
<script src="../assets/js/ai-suggestions.js"></script>
<script src="../assets/js/content-filter.js"></script>
</body>
</html>