<?php
// Load necessary configurations and database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Retrieve input message and ticket ID from POST request
$message = $_POST['message'] ?? '';
$ticket_id = (int) ($_POST['ticket_id'] ?? 0);

// Validate that both message and ticket ID are present
if (!$message || !$ticket_id) {
  echo json_encode(['error' => 'Missing message or ticket ID']);
  exit; // Stop execution if required input is missing
}

// Construct the AI prompt with strict behavioral boundaries
// Ensures that responses remain professional, respectful, and safe
$prompt = "You are a professional and respectful AI support assistant. Never respond with sexual, profane, violent, or inappropriate content. If the input is offensive, respond with: 'I'm unable to assist with that request.'\n\n" . $message;

// Prepare the payload for the Cohere API call
$data = [
  "model" => "command-r-plus",       // Specify the language model
  "prompt" => $prompt,               // Inject user message into prompt
  "max_tokens" => 150,               // Limit response length
  "temperature" => 0.7               // Control randomness/creativity
];

// Initialize and configure CURL request to Cohere API
$ch = curl_init("https://api.cohere.ai/v1/generate");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,    // Ensure the response is returned
  CURLOPT_POST => true,              // Set request type to POST
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer $cohere_api_key", // Inject API key for auth
    "Content-Type: application/json"         // Define content format
  ],
  CURLOPT_POSTFIELDS => json_encode($data)   // Attach JSON payload
]);

// Execute API call and capture the response
$response = curl_exec($ch);
curl_close($ch); // Close CURL handle

// Decode the response from JSON to array
$result = json_decode($response, true);

// Extract and sanitize the generated AI response
$reply = trim($result['generations'][0]['text'] ?? '');

// Proceed only if a reply was successfully generated
if ($reply) {
  try {
    // Prepare a secure SQL statement to update the ticket with AI reply
    $stmt = $conn->prepare("UPDATE support_tickets SET ai_response = :reply WHERE id = :ticket_id");
    $stmt->bindParam(':reply', $reply, PDO::PARAM_STR);
    $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
    $stmt->execute();

    // Return a success response with the AI-generated reply
    echo json_encode(['success' => true, 'reply' => $reply]);
  } catch (PDOException $e) {
    // Return a database error message in case of failure
    echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
  }
} else {
  // Handle case when the AI fails to generate a reply
  echo json_encode(['error' => 'No reply generated']);
}
?>