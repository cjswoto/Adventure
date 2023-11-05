<?php
$chatFile = 'chat.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    // This is a new message submission
    $message = strip_tags($_POST['message']); // Basic XSS protection
    $chat = json_decode(file_get_contents($chatFile), true);
    
    if (!$chat) {
        $chat = ['messages' => []];
    }
    
    array_push($chat['messages'], $message);
    
    file_put_contents($chatFile, json_encode($chat));
    echo json_encode(['success' => true]);
} else {
    // This is a chat history load request
    if (file_exists($chatFile)) {
        echo file_get_contents($chatFile);
    } else {
        echo json_encode(['messages' => []]);
    }
}
