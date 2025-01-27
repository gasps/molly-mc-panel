<?php 
ob_start();
session_start();

// Get the currently selected server
$selectedServer = $_SESSION['selected_server'] ?? null;

// Retrieve or initialize the port for the selected server
$port = isset($selectedServer, $_SESSION['ports'][$selectedServer]) 
    ? $_SESSION['ports'][$selectedServer] 
    : null;


// display text
$serverDisplayText = "Selected Server: " . ($selectedServer ? htmlspecialchars($selectedServer) : "None");
$portDisplayText   = "Selected Port: "   . ($port ? htmlspecialchars($port) : "None");

// Handle port submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_port']) && isset($_POST['port'])) {
    $port = $_POST['port'];
    $_SESSION['ports'][$selectedServer] = $port;
    header("Location: manager.php"); // Redirect to avoid form resubmission
    exit;
}


$directoryPath = 'servers/' . $selectedServer . '/';

// handle rename of server folder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_name']) && isset($_POST['name'])) {
    $newName = $_POST['name'];
    debugToConsole('You are trying to change ' . $selectedServer . ' to ' . $newName);

    $oldFolderPath = 'servers/' . $selectedServer;
    $newFolderPath = 'servers/' . $newName;

    // Check if the source folder exists
    if (is_dir($oldFolderPath)) {
        // Attempt to rename the folder
        if (rename($oldFolderPath, $newFolderPath)) {
            // Update session data for the server name
            $_SESSION['ports'][$newName] = $_SESSION['ports'][$selectedServer]; // Update port for the new name
            // unset($_SESSION['ports'][$selectedServer]); // Remove old entry

            // Optionally update the selected server if it's renamed
            $_SESSION['selected_server'] = $newName;


            
            $directoryPath = 'servers/' . $newName . '/'; // Update directory path to the new folder

            debugToConsole('Successfully renamed and port updated!');

            header('location: manager.php');
        } else {
            debugToConsole('Failed to rename the folder.');
        }
    } else {
        debugToConsole('The source folder does not exist.');
    }
}



// Check if a server is selected to enable/disable console access
$consoleLink = ($selectedServer && $port) ? 'console.php' : 'index.php?error=no_server_or_port';
$consoleClass = ($selectedServer && $port) ? '' : 'disabled-link';

// Read server directory contents
$files = [];
if (is_dir($directoryPath)) {
    $files = scandir($directoryPath);
    $files = array_diff($files, ['.', '..']); // Remove '.' and '..' from the list
}



ob_end_flush();

// debugging function
function debugToConsole($message) {
    echo "<script>console.log('$message');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Panel</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body id="body" ondragover="blurBackground(event)">
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                    <h2 id="brand">Molly Web Panel</h2>
                </div>
            </div>
            <div class="sidebar">
                <a href="index.php">
                    <span class="material-symbols-rounded">grid_view</span>
                    <h3>Home</h3>
                </a>
                <a href="manager.php" class="active">
                    <span class="material-symbols-rounded">computer</span>
                    <h3>Manager</h3>
                </a>
                <a href="<?php echo $consoleLink; ?>" class="<?php echo $consoleClass; ?>">
                    <span class="material-symbols-rounded">terminal</span>
                    <h3>Console</h3>
                </a>
                <a href="settings.php">
                    <span class="material-symbols-rounded">settings</span>
                    <h3>Settings</h3>
                </a>
                <a href="debug.php">
                    <span class="material-symbols-rounded">debug</span>
                </a>
                <div class="selected-server">
              <div class="selection-info">
                  <h3 id="selected-server"><?php echo htmlspecialchars($serverDisplayText); ?></h3>
                  <h3 id="selected-port"><?php echo htmlspecialchars($portDisplayText); ?></h3>
              </div>
          </div>
            </div>
        </aside>

        <main>
            <h1 id="welcome">Welcome, <span id="username"><?php echo htmlspecialchars($username ?? "Unknown"); ?></span></h1>

<!-- Port Setting Form -->
<div class="settings-container">
    <h2>Set Server Port</h2>
    <form method="POST" class="port-form">
        <label for="port">Port:</label>
        <input type="number" id="port" name="port" value="<?php echo htmlspecialchars($port ?? ''); ?>" required>
        <button class="item create-button" type="submit" name="set_port">Set Port</button>
    </form>

    <form method="POST" class="port-form">
        <label for="name">Name:</label> <!-- Changed id to name to make it unique -->
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($newName ?? ''); ?>" required> <!-- Changed input type -->
        <button class="item create-button" type="submit" name="set_name">Set Name</button> <!-- Changed name attribute -->
    </form>
</div>


<!-- File List Container -->
<div class="files-container">
    <h2>Files in Directory:</h2>
    <table class="file-table">
        <thead>
            <tr>
                <th>Icon</th>
                <th>File Name</th>
                <th>Last Modified</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!empty($files)) {
                foreach ($files as $file): 
                    $filePath = $directoryPath . $file;
                    $lastModified = date("Y-m-d H:i:s", filemtime($filePath));
                    $icon = pathinfo($file, PATHINFO_EXTENSION) == 'php' ? 'code' : 'description';
            ?>
            <tr>
                <td><span class='material-symbols-rounded'><?= htmlspecialchars($icon) ?></span></td>
                <td><a href='edit_file.php?file=<?= urlencode($file) ?>' target='_blank'><?= htmlspecialchars($file) ?></a></td>
                <td><?= htmlspecialchars($lastModified) ?></td>
            </tr>
            <?php 
                endforeach;
            } else {
                echo "<tr><td colspan='3'>No files found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
        </main>

        <div class="right">
            <button id="menu-btn" class="menu-button">
                <span class="material-symbols-rounded">menu</span>
            </button>
        </div>
    </div>

    <script>
        function setPort() {
            let port = document.getElementById("port").value;
            if (!port) {
                alert("Please enter a valid port.");
            }
        }
    </script>
</body>
</html>
