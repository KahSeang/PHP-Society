<?php include'header.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Chatbot</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f7f7f7;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

#chatbox {
    height: 300px;
    overflow-y: scroll;
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 10px;
}

#userInput {
    width: calc(100% - 70px);
    padding: 5px;
    margin-right: 5px;
    border-radius: 3px;
    border: 1px solid #ccc;
}

button {
    padding: 5px 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

.message {
    padding: 5px 10px;
    margin-bottom: 5px;
    border-radius: 3px;
}

.message.right {
    background-color: #d4eefc;
    text-align: right;
}

.message.left {
    background-color: #f7f7f7;
    text-align: left;
}
</style>
</head>
<body>
<div class="container">
    <h1>AI Chatbot</h1>
    <div id="chatbox"></div>
    <input type="text" id="userInput" />
    <button onclick="sendMessage()">Send</button>
</div>

<script>
function sendMessage() {
    const inputField = document.getElementById('userInput');
    const message = inputField.value;
    inputField.value = '';

    displayMessage('You: ' + message, 'right');

    // Sending the message to PHP server
    fetch('chatbot.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        displayMessage('Bot: ' + data.reply, 'left');
    })
    .catch(error => console.error('Error:', error));
}

function displayMessage(message, align) {
    const node = document.createElement("div");
    node.className = 'message ' + align;
    node.textContent = message;
    document.getElementById('chatbox').appendChild(node);
}
</script>
</body>
</html>
