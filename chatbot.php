<?php
header('Content-Type: application/json');

// Collect the user's message from the AJAX request
$data = json_decode(file_get_contents('php://input'), true);
$userMessage = $data['message'];

$apiKey = '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';  

$endpoint = 'https://api.openai.com/v1/chat/completions';

$requestData = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        ['role' => 'user', 'content' => $userMessage]
    ]
];

// Convert data to JSON format
$postData = json_encode($requestData);

// Initialize cURL session
$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

// Execute cURL request
$response = curl_exec($ch);
curl_close($ch);

// Decode the response
$responseData = json_decode($response, true);
$botReply = $responseData['choices'][0]['message']['content'] ?? 'Sorry, I could not understand that.';

// Return the bot's reply as JSON
echo json_encode(['reply' => $botReply]);
?>