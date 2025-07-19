<?php
// Include configuration settings and database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Capture the user-submitted support message and ticket ID
$message = $_POST['message'] ?? '';
$ticket_id = $_POST['ticket_id'] ?? '';

// Validate essential inputs; terminate early if either is missing
if (!$message || !$ticket_id) {
  echo json_encode(['error' => 'Missing message or ticket ID']);
  exit;
}

// Construct a prompt explicitly instructing the AI to classify urgency
$prompt = "Classify the urgency of this support request as High, Medium, or Low:\n" . $message;

// Prepare request payload to Cohere AI with defined parameters
$data = [
  "model" => "command-r-plus", // Specify the target model
  "prompt" => $prompt,         // Attach the crafted classification prompt
  "max_tokens" => 10           // Limit the response length to a single label
];

// Initialize CURL to communicate with Cohere API
$ch = curl_init("https://api.cohere.ai/v1/generate");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,    // Return the output instead of printing
  CURLOPT_POST => true,              // Use POST request
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer $cohere_api_key", // Secure API key in header
    "Content-Type: application/json"         // Specify JSON content type
  ],
  CURLOPT_POSTFIELDS => json_encode($data)   // Send data as JSON
]);

// Execute API call and capture response
$response = curl_exec($ch);
curl_close($ch); // Clean up CURL handler

// Decode API response and extract classification result
$result = json_decode($response, true);
$priority = strtoupper(trim($result['generations'][0]['text'] ?? ''));

// Validate the priority value to ensure conformity with allowed categories
$valid = ['HIGH', 'MEDIUM', 'LOW'];
if (!in_array($priority, $valid)) {
  $priority = 'MEDIUM'; // Apply default fallback if invalid or unrecognized
}

// Perform secure database update to store the AI-classified priority
$stmt = $conn->prepare("UPDATE support_tickets SET ai_priority = :priority WHERE id = :id");
$stmt->bindParam(':priority', $priority);           // Bind classified priority
$stmt->bindParam(':id', $ticket_id, PDO::PARAM_INT); // Bind ticket ID securely
$stmt->execute(); // Commit the update to the database

// Respond with success confirmation and the resulting priority classification
echo json_encode(['success' => true, 'priority' => $priority]);
?>