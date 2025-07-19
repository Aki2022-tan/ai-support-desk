<?php
// Load global configuration file (contains API keys, constants, etc.)
require_once __DIR__ . '/../includes/config.php';

// Establish database connection via included PDO instance
require_once __DIR__ . '/../includes/db.php';

// Securely retrieve the customer message and associated ticket ID from the POST payload
$message = $_POST['message'] ?? '';        // The message to analyze for emotional tone
$ticket_id = $_POST['ticket_id'] ?? '';    // The ticket this message is associated with

// Perform basic input validation; exit early with error if required fields are missing
if (!$message || !$ticket_id) {
  echo json_encode(['error' => 'Missing message or ticket ID']);
  exit;
}

// Construct the AI prompt with clear instructions to classify the emotional tone
$prompt = "What is the emotional tone of this support message? Reply with only one word: Polite, Angry, Rude, Neutral, or Frustrated.\n\nMessage:\n" . $message;

// Define the payload for the Cohere API call
$data = [
  "model" => "command-r-plus",  // Specify AI model to be used for tone detection
  "prompt" => $prompt,          // Insert user message into the prompt template
  "max_tokens" => 10            // Limit the AI response to ensure concise output
];

// Initialize a cURL session to send the request to the Cohere AI API
$ch = curl_init("https://api.cohere.ai/v1/generate");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,              // Capture response as string
  CURLOPT_POST => true,                        // Set HTTP method to POST
  CURLOPT_HTTPHEADER => [                      // Include headers for authorization and content type
    "Authorization: Bearer $cohere_api_key",
    "Content-Type: application/json"
  ],
  CURLOPT_POSTFIELDS => json_encode($data)     // Convert payload array to JSON
]);

// Execute the API request and store the response
$response = curl_exec($ch);
curl_close($ch); // Cleanly close the cURL session

// Decode the JSON response to extract the tone classification
$result = json_decode($response, true);
$tone = ucfirst(strtolower(trim($result['generations'][0]['text'] ?? ''))); // Normalize case and trim output

// Validate if the AI response is within the set of approved tone labels
$allowed = ['Polite', 'Angry', 'Rude', 'Neutral', 'Frustrated'];
if (!in_array($tone, $allowed)) {
  $tone = 'Neutral'; // Use Neutral as a safe fallback for invalid or unexpected output
}

// Update the corresponding support ticket with the detected tone using a prepared SQL statement
$stmt = $conn->prepare("UPDATE support_tickets SET ai_tone = :tone WHERE id = :id");
$success = $stmt->execute([
  ':tone' => $tone,             // Bind detected tone
  ':id' => $ticket_id           // Bind ticket identifier
]);

// Respond with a JSON payload indicating success and returning the evaluated tone
echo json_encode(['success' => $success, 'tone' => $tone]);