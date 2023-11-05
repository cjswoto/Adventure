<?php
// list_json_files.php

$files = glob('*.json'); // Get all json files in the current directory
echo json_encode($files); // Encode the list of files as JSON and return it
?>
