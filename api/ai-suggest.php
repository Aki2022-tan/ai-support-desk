<?php
// Import configuration constants and sensitive credentials (e.g., API keys)
require_once __DIR__ . '/../includes/config.php';

// Import DB connection config (included for structural consistency; not directly used)
require_once __DIR__ . '/../includes/db.php';

// Define the content type for the HTTP response to ensure proper client-side parsing
header('Content-Type: application/json');

// Capture and sanitize the 'q' parameter from the HTTP GET query string
$query = trim($_GET['q'] ?? '');

// If no query was provided, terminate early and return an empty suggestions array
if ($query === '') {
  echo json_encode(['suggestions' => []]);
  exit;
}

// Dynamically construct a safe, context-aware prompt for the language model
// The prompt enforces strict content guidelines and instructs the AI to output
// only relevant, professional ticket subject lines
$prompt = <<<EOT
Generate 5 short and clear customer support ticket subjects based on this query:

$query

Important: Do not suggest or generate any ticket subject that includes or implies profanity, sexual content, explicit language, hate speech, or any offensive terms — even in slang or other languages. Only include safe, appropriate, and professional topics suitable for a customer support environment.

List only the subjects, each on a new line.
EOT;

// Define the request payload for Cohere's AI text generation API
$data = [
  "model" => "command-r-plus",     // Specify the target AI model
  "prompt" => $prompt,             // Use the constructed prompt with user input
  "max_tokens" => 80,              // Limit the response to a reasonable length
  "temperature" => 0.7             // Allow moderate creativity in suggestions
];

// Initialize and configure the cURL session for API communication
$ch = curl_init("https://api.cohere.ai/v1/generate");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,            // Ensure response is returned, not output directly
  CURLOPT_POST => true,                      // Define HTTP method as POST
  CURLOPT_HTTPHEADER => [                    // Provide authorization and content type
    "Authorization: Bearer $cohere_api_key",
    "Content-Type: application/json"
  ],
  CURLOPT_POSTFIELDS => json_encode($data)   // Attach the request payload as JSON
]);

// Execute the request and store the response string
$response = curl_exec($ch);
curl_close($ch); // Properly close the cURL session to free resources

// Decode the JSON-formatted response from the AI API
$result = json_decode($response, true);

// Safely extract the generated text block from the response
$text = $result['generations'][0]['text'] ?? '';

// Initialize an array to hold clean, finalized subject suggestions
$suggestions = [];

// If the AI provided text, parse each line into individual subject entries
if ($text) {
  foreach (explode("\n", $text) as $line) {
    // Strip out numbering, bullets, or asterisks from the beginning of each line
    $clean = trim(preg_replace('/^[0-9\.\-\*]+\s*/', '', $line));

    // Remove any wrapping quotation marks or unnecessary whitespace
    $clean = trim($clean, "\"'");

    // Only include non-empty lines in the final suggestions list
    if ($clean !== '') $suggestions[] = $clean;
  }
}

// Return the first five cleaned and validated subject suggestions in JSON format
echo json_encode(['suggestions' => array_slice($suggestions, 0, 5)]);