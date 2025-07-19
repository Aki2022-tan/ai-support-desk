<?php
// Import global configuration constants (e.g., API keys)
require_once __DIR__ . '/../includes/config.php';

// Import database connection (PDO instance as $conn)
require_once __DIR__ . '/../includes/db.php';

// Retrieve message content and associated ticket ID from the POST request
$message = $_POST['message'] ?? '';         // User's message intended for translation
$ticket_id = $_POST['ticket_id'] ?? '';     // Associated support ticket identifier

// Validate required inputs; terminate early if missing
if (!$message || !$ticket_id) {
  echo json_encode(['error' => 'Missing message or ticket ID']);
  exit;
}

// Define the prompt sent to Cohere's AI model
// The AI is instructed to:
// - Detect the original language (including regional Philippine dialects)
// - Translate to fluent English
// - Return both language and translation in a strict labeled format
$prompt = <<<PROMPT
You are a multilingual language expert AI. 

1. First, detect the original language of the message. 
2. If it's a regional dialect from the Philippines (Tagalog, Cebuano, Ilocano, Bicol, Hiligaynon, Waray, etc.), label it properly.
3. If you are unsure, guess the closest major language or say "Unknown".
4. Then translate the message into **clear English** as best you can.

Reply in this exact format:
Language: <language>
Translation: <translated text>

Message:
$message
PROMPT;

// Construct the payload for the Cohere API
$data = [
  "model" => "command-r-plus",  // Define the specific model to handle multilingual interpretation
  "prompt" => $prompt,          // Pass the structured translation prompt
  "max_tokens" => 200           // Set a reasonable limit for the output to avoid incomplete responses
];

// Initialize cURL session to send request to Cohere API
$ch = curl_init("https://api.cohere.ai/v1/generate");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,              // Capture output instead of printing
  CURLOPT_POST => true,                        // Use HTTP POST method
  CURLOPT_HTTPHEADER => [                      // Set authorization and content headers
    "Authorization: Bearer $cohere_api_key",
    "Content-Type: application/json"
  ],
  CURLOPT_POSTFIELDS => json_encode($data)     // Encode the payload as JSON
]);

// Execute the request and capture the raw response
$response = curl_exec($ch);
curl_close($ch); // Clean up the cURL resource

// Decode the JSON API response to extract the AI-generated output
$result = json_decode($response, true);
$text = trim($result['generations'][0]['text'] ?? ''); // Safely extract and clean the output text

// Initialize response variables for language detection and translation
$language = '';
$translated_msg = '';

// Use regular expressions to parse "Language:" and "Translation:" from the AI output
if (preg_match('/Language:\s*(.+)/i', $text, $lang_match)) {
  $language = trim($lang_match[1]); // Extract language if present
}
if (preg_match('/Translation:\s*(.+)/is', $text, $trans_match)) {
  $translated_msg = trim($trans_match[1]); // Extract translation if available
}

// Prepare SQL statement to update the support ticket with language and translation
$sql = "UPDATE support_tickets SET language = :language, translated_msg = :translated_msg WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
  ':language' => $language ?: 'Unknown',  // Use fallback if language not detected
  ':translated_msg' => $translated_msg ?: 'This message could not be auto-translated. Please review manually.',
  ':id' => $ticket_id
]);

// Return JSON response with processing outcome
echo json_encode([
  'success' => strtolower($language) !== 'unknown' ? true : false,   // Mark as success only if language was recognized
  'fallback' => strtolower($language) === 'unknown' ? true : false,  // Indicate fallback usage
  'language' => $language,                                           // Return detected language
  'translated' => $translated_msg                                    // Return translated output
]);