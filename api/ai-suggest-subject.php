<?php
header('Content-Type: application/json');

require_once '../includes/config.php'; // contains $cohere_api_key

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = trim($_POST['department'] ?? '');
    $subject = trim($_POST['subject'] ?? '');

    if (!$department || !$subject) {
        echo json_encode(['error' => 'Missing input values']);
        exit;
    }

$prompt = "Suggest 3 short, relevant support ticket subject lines based only on the department: \"$department\" and subject: \"$subject\". No explanations. No quotes. Each subject on a new line.";

$data = [
    'model' => 'command',
    'prompt' => $prompt,
    'max_tokens' => 40,
    'temperature' => 0.5,
    'k' => 0,
    'stop_sequences' => [],
    'return_likelihoods' => 'NONE'
];

    $headers = [
        "Authorization: Bearer $cohere_api_key",
        "Content-Type: application/json"
    ];

    $ch = curl_init('https://api.cohere.ai/generate');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(['error' => 'cURL Error: ' . curl_error($ch)]);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    $response = json_decode($result, true);

    if (isset($response['text'])) {
        // Extract numbered lines only
        preg_match_all('/\d+\.\s*"(.*?)"/', $response['text'], $matches);
        $suggestions = $matches[1] ?? [];

        // Fallback: if no matches, try splitting lines as-is
        if (empty($suggestions)) {
            $suggestions = array_filter(array_map('trim', explode("\n", $response['text'])));
        }

        // ✅ Filter out blanks and limit to top 3
        $suggestions = array_filter($suggestions);
        $suggestions = array_slice($suggestions, 0, 3);

        echo json_encode(['suggestions' => array_values($suggestions)]);
    } else {
        echo json_encode(['error' => 'AI response error', 'raw' => $response]);
    }
    exit;
}

echo json_encode(['error' => 'Invalid request']);
exit;