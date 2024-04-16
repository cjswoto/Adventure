<?php
// Define the JSON schema to validate against
$schema = [
    'scenes' => 'array'
];

// Function to validate the JSON structure
function validateJson($json, $schema) {
    foreach ($schema as $key => $type) {
        if (!isset($json[$key])) {
            return false; // Key not found in JSON
        }
        if (gettype($json[$key]) !== $type) {
            return false; // Type does not match
        }
    }

    // If scenes is an array, check each scene
    if (isset($json['scenes']) && is_array($json['scenes'])) {
        foreach ($json['scenes'] as $scene) {
            if (!isset($scene['name'], $scene['description'], $scene['image'], $scene['items'], $scene['furniture'], $scene['options'])) {
                return false; // Scene does not have all required properties
            }
        }
    }

    return true; // JSON is valid
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonInput = $_POST['json'];
    $fileName = $_POST['filename'];

    // Attempt to decode the JSON input
    $jsonDecoded = json_decode($jsonInput, true);

    // Validate the JSON structure
    if ($jsonDecoded && validateJson($jsonDecoded, $schema)) {
        // Save the JSON to a file
        file_put_contents($fileName, $jsonInput);
        $message = "JSON saved successfully.";
    } else {
        $message = "Invalid JSON structure.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Save JSON to File</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        line-height: 1.6;
    }
    .container {
        width: 80%;
        margin: auto;
        overflow: hidden;
    }
    .header {
        background: #333;
        color: #fff;
        padding: 20px;
    }
    .header h1 {
        text-align: center;
    }
    form {
        margin-top: 20px;
        background: #fff;
        padding: 20px;
    }
    input[type="text"], textarea {
        width: 100%;
        padding: 8px;
        margin: 10px 0;
    }
    input[type="submit"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-top: 20px;
        background: #333;
        color: #fff;
        border: none;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background: #555;
    }
    .message {
        margin-top: 20px;
        padding: 10px;
        background: lightgreen;
        color: green;
    }
          #game-title {
            text-align: center;
            font-size: 4em;
            color: #e74c3c;
            text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.5);
            margin-top: 0;
            margin-bottom: 0.5em;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        #game-title a {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }

        #game-title a:hover, #game-title a:focus {
            text-decoration: underline;
        }
    </style>


</head>
<body>
    <!-- Place this within your <body> tag, at the top -->
<h1 id="game-title">
    <a href="http://thegrandgame.rf.gd/" title="The Grand Game">The Grand Game</a>
</h1>

<div class="container">
    <div class="header">
        <h1>Save JSON to File</h1>
    </div>
    
    <?php if (!empty($message)): ?>
    <div class="message">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="filename" placeholder="Enter the filename (e.g., scene.json)" required>
        <textarea name="json" placeholder="Paste your JSON code here" required></textarea>
        <input type="submit" value="Save JSON">
    </form>
</div>
</body>
</html>
