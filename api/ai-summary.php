<?php
// Load application-wide configuration constants and secrets
require_once __DIR__ . '/../includes/config.php';

// Initialize the database connection (PDO instance)
require_once __DIR__ . '/../includes/db.php';

// Retrieve input parameters securely from the POST request body
$message = $_POST['message'] ?? '';         // The full user message to be summarized
$ticket_id = $_POST['ticket_id'] ?? '';     // The ID of the related support ticket

// Validate critical input fields; fail fast if either is missing
if (!$message || !$ticket_id) {
  echo json_encode(['error' => 'Missing message or ticket ID']);
  exit;
}

// Define the AI prompt with strict instruction to produce a single-sentence summary
$prompt = "Summarize this customer support issue in one sentence:\n" . $message;

// Construct the request payload for the Cohere AI API
$data = [
  "model" => "command-r-plus",   // Target model for generation
  "prompt" => $prompt,           // Inject the user message into the instruction template
  "max_tokens" => 100            // Set a ceiling on the response length
];

// Initialize a cURL session to Cohere's text generation endpoint
$ch = curl_init("https://api.cohere.ai/v1/generate");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,              // Capture the API response as a return value
  CURLOPT_POST => true,                        // Set HTTP method to POST
  CURLOPT_HTTPHEADER => [                      // Configure required headers
    "Authorization: Bearer $cohere_api_key",   // API key for authentication
    "Content-Type: application/json"           // Specify content format
  ],
  CURLOPT_POSTFIELDS => json_encode($data)     // Encode and attach the request payload
]);

// Execute the API request and collect the response and HTTP status code
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Capture HTTP response code for diagnostics
curl_close($ch); // Ensure clean termination of the cURL session

// Optional logging of response data for debugging or auditing purposes
file_put_contents(__DIR__ . "/../summary_debug.txt", "CODE: $http_code\nRESPONSE:\n$response");

// Parse the AI response as JSON and safely extract the generated summary text
$result = json_decode($response, true);
$summary = trim($result['generations'][0]['text'] ?? ''); // Use null coalescence fallback

// If a valid summary was generated, persist it in the database
if ($summary) {
  // Use a prepared statement for secure database write operation
  $stmt = $conn->prepare("UPDATE support_tickets SET ai_summary = ? WHERE id = ?");
  $stmt->execute([$summary, $ticket_id]);

  // Return success payload with the AI-generated summary
  echo json_encode(['success' => true, 'summary' => $summary]);
} else {
  // Handle edge case where AI did not return valid output
  echo json_encode(['error' => 'No summary returned', 'raw' => $response]);
}
?>