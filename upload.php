<?php
// Optional: Hide errors to prevent polluting JSON
// Temporarily turn on errors at the top of upload.php for debugging:
ini_set('upload_max_filesize', '60G');
ini_set('post_max_size', '60G'); // Set both upload and post max size
ini_set('max_execution_time', '300');
ini_set('max_input_time', '300');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// If you need sessions, do this at the top (optional)
session_start();

// Always return JSON
header('Content-Type: application/json; charset=utf-8');

// Base upload directory
$targetDirectory = 'servers/';

// Check for POST method and existence of files
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])) {
    // Use provided folder name or default
    $folderName = $_POST['folderName'] ?? 'default_folder';
    $uploadPath = $targetDirectory . basename($folderName) . '/';

    // Create the folder if it doesn’t exist
    if (!file_exists($uploadPath)) {
        if (!mkdir($uploadPath, 0777, true)) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create directory'
            ]);
            exit;
        }
    }

    $uploadSuccess = true;
    $failedFiles = [];

    // Loop through each file
    foreach ($_FILES['files']['tmp_name'] as $index => $tmpFilePath) {
        $fileName = $_FILES['files']['name'][$index];
        $destinationPath = $uploadPath . $fileName;

        if (!move_uploaded_file($tmpFilePath, $destinationPath)) {
            $uploadSuccess = false;
            $failedFiles[] = $fileName; // Keep track of failed files
        }
    }

    // Return JSON result
    if ($uploadSuccess) {
        echo json_encode([
            'success' => true,
            'message' => 'Files uploaded successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Some files failed to upload',
            'failedFiles' => $failedFiles // Include which files failed
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No files received'
    ]);
}
?>
