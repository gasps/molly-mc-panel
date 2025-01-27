<?php
session_start();

// Set the directory path

$selectedServer = $_SESSION['selected_server'] ?? null;
$directoryPath = 'servers/' . $selectedServer . '/';

// Ensure that a file is specified and sanitize the input
if (isset($_GET['file']) && !empty($_GET['file'])) {
    $fileName = basename($_GET['file']); // Prevent directory traversal attacks
    $filePath = $directoryPath . $fileName;

    // Ensure the file exists and is readable
    if (file_exists($filePath) && is_readable($filePath)) {
        // Handle file update
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
            if (is_writable($filePath)) {
                file_put_contents($filePath, $_POST['content']);
                echo "<p style='color: green;'>File saved successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error: File is not writable.</p>";
            }
        }

        // Read the file content safely
        $content = htmlspecialchars(file_get_contents($filePath), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    } else {
        die("<p style='color: red;'>Error: File does not exist or is not readable.</p>");
    }
} else {
    die("<p style='color: red;'>Error: No file specified.</p>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit File: <?php echo htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        textarea { width: 100%; height: 400px; font-family: monospace; }
        button { padding: 10px 20px; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Editing: <?php echo htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8'); ?></h1>
    <form method="post">
        <textarea name="content"><?php echo $content; ?></textarea><br>
        <button type="submit">Save Changes</button>
        <a href="manager.php"><button type="button">Back to Files</button></a>
    </form>
</body>
</html>
