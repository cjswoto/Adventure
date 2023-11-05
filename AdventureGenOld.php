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
    $fileName = $_POST['filename'] . '.json';

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



<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>The Grand Game Scene Creator</title>
    <link rel="stylesheet" href="style.css">
    <script>
        var scenes = []; // Array to hold all scenes
        
        // Function to add a scene with its items, furniture, and options
        function addScene() {
            var sceneName = document.getElementById('sceneName').value;
            var sceneDescription = document.getElementById('sceneDescription').value;
            var sceneImage = document.getElementById('sceneImage').value;
            
            var items = collectItems('.item');
            var furniture = collectItems('.furniture');
            var options = collectOptions();
            
            // Create scene object
            var scene = {
                name: sceneName,
                description: sceneDescription,
                image: sceneImage,
                items: items,
                furniture: furniture,
                options: options
            };

            // Add the scene object to the scenes array
            scenes.push(scene);

            // Reset the form fields for the next input
            document.getElementById('sceneForm').reset();
            clearContainer('items');
            clearContainer('furniture');
            clearContainer('options');
            generateJSON()
        }

        // Function to collect items or furniture from their respective containers
        function collectItems(selector) {
            var elements = document.querySelectorAll(selector);
            return Array.from(elements).map(function(el) {
                return {
                    name: el.querySelector('.name').value,
                    image: el.querySelector('.image').value,
                    use: {
                        description: el.querySelector('.useDescription').value,
                        function: el.querySelector('.useFunction').value
                    }
                };
            });
        }

        // Function to collect options from their container
        function collectOptions() {
            var optionElements = document.querySelectorAll('.option');
            return Array.from(optionElements).map(function(opt) {
                return {
                    description: opt.querySelector('.optionDescription').value,
                    target: parseInt(opt.querySelector('.optionTarget').value, 10)
                };
            });
        }

        // Function to clear the innerHTML of a given container
        function clearContainer(containerId) {
            document.getElementById(containerId).innerHTML = '';
        }

        // Function to add a new item or furniture field
        function addItem(containerId, className) {
            var container = document.getElementById(containerId);
            var div = document.createElement('div');
            div.classList.add(className);
            div.innerHTML = `
                <input type="text" class="name" placeholder="Name">
                <input type="text" class="image" placeholder="Image Filename">
                <input type="text" class="useDescription" placeholder="Use Description">
                <input type="text" class="useFunction" placeholder="Function">
                <br/>
            `;
            container.appendChild(div);
        }

        // Function to add a new option field
        function addOption() {
            var container = document.getElementById('options');
            var div = document.createElement('div');
            div.classList.add('option');
            div.innerHTML = `
                <input type="text" class="optionDescription" placeholder="Option Description">
                <input type="number" class="optionTarget" placeholder="Target Scene ID">
                <br/>
            `;
            container.appendChild(div);
        }

        // Function to generate the JSON structure of all scenes
        function generateJSON() {
            var jsonOutput = JSON.stringify({ scenes: scenes }, null, 4);
            var outputElement = document.getElementById('jsonOutput');
            outputElement.textContent = jsonOutput;
            copyJsonToClipboard()
        }
        
        function copyJsonToClipboard() {
          // Get the element containing the JSON data and the hidden element
          const jsonOutput = document.getElementById('jsonOutput');
          const hiddenJson = document.getElementById('json'); // Assuming 'json' is an input field
        
          // Copy content from jsonOutput to hiddenJson
          if (hiddenJson.value !== undefined) {
            hiddenJson.value = jsonOutput.textContent; // For input elements
          } else {
            hiddenJson.textContent = jsonOutput.textContent; // For other elements like 'span' or 'div'
          }
        
          // Create a range to select the text of the hidden element
          const selection = window.getSelection();
          const range = document.createRange();
          range.selectNodeContents(hiddenJson); // Select the content of the hidden element
          selection.removeAllRanges();
          selection.addRange(range);
        
          try {
            // Execute the copy command
            const successful = document.execCommand('copy');
            const msg = successful ? 'successful' : 'unsuccessful';
            console.log('Copying text command was ' + msg);
            alert('JSON copied to clipboard!');
          } catch (err) {
            console.log('Oops, unable to copy');
            alert('Failed to copy JSON.');
          }
        
          // Deselect the text
          selection.removeAllRanges();
        }
        
    </script>
</head>
<style>
h1, h2 {
    color: #e74c3c;
    text-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);
}

p {
    font-size: 1.2em;
    line-height: 1.5em;
    color: #ecf0f1;
}

#links ul {
    list-style: none;
    padding: 0;
}

#links li {
    margin-bottom: 10px;
}

#links a {
    background-color: #3498db;
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 18px;
    border-radius: 12px;
    transition-duration: 0.4s;
    cursor: url("cursor.png"), auto;
}

#links a:hover {
    background-color: white;
    color: black;
    border: 2px solid #3498db;
}</style>

<body>
       <div id="game-container">
    <!-- Place this within your <body> tag, at the top -->
<h1 id="game-title">
    <a href="https://thedicelady.com/AdventureGame/" title="The Grand Game">The Grand Game</a>
</h1>


    <h1>Adventure Creator</h1>
    <p><ul>
        <li>The Grand Game adventures are .json files you can build with this tool or get chatgpt to do it. The build tool is fairly straight forward but a couple things to know. </li>
            <li><ul>
                <li></li>
                
                
                
                </ul></li>
        
    </ul></p>
    <form id="sceneForm" onsubmit="return false;">
        <input type="text" id="sceneName" placeholder="Scene Name">
        <textarea id="sceneDescription" placeholder="Scene Description"></textarea>
        <input type="text" id="sceneImage" placeholder="Scene Image Filename">
        
        <h2>Items</h2>
        <div id="items"></div>
        <button type="button" onclick="addItem('items', 'item')">Add Item</button>
        
        <h2>Furniture</h2>
        <div id="furniture"></div>
        <button type="button" onclick="addItem('furniture', 'furniture')">Add Furniture</button>
        
        <h2>Options</h2>
        <p>Set the Target number to the position in which the destination room appears in the Json file. So if The Entry room is the 1st room in the Json then any exit(option) going to The Entry room would have a target of 1, if it was the 2nd in the json file 2 and so on. </p>
        <div id="options"></div>
        <button type="button" onclick="addOption()">Add Exit(Option)</button>
        
        <button type="button" onclick="addScene()">Save Scene</button>
    </form>

<pre id="jsonOutput" onclick="copyJsonToClipboard()"></pre>
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
        <input type="hidden" id="json"  name="json">
        <input type="submit" value="Save JSON">
    </form>
</div>
 </div>

</body>
</html>
