<?php
session_start();

// directories/arrays
$serverDir = __DIR__ . '/servers';
$servers = is_dir($serverDir) ? array_values(array_diff(scandir($serverDir), ['.', '..'])) : [];

// define variables
$selectedServer = $_SESSION['selected_server'] ?? null;
$port = $_SESSION['ports'][$selectedServer] ?? null;

$serverDisplayText = "Selected Server: " . ($selectedServer ? htmlspecialchars($selectedServer) : "None");
$portDisplayText = "Selected Port: " . ($port ? htmlspecialchars($port) : "None");
$throwError = ""; // Initialize error message

// Handle port setting
if (isset($_POST['set_port']) && isset($_POST['port'])) {
    $port = $_POST['port'];
    $_SESSION['ports'][$selectedServer] = $port;
    header("Location: index.php"); // Redirect to avoid resubmission
    exit;
}

// server selection handler
if (isset($_GET['select_server'])) {
    $selectedServer = $_GET['select_server'];
    if (in_array($selectedServer, $servers)) {
        $_SESSION['selected_server'] = $selectedServer; // store this globally

        header('location: index.php');

        // if (!$port == "None") {
            header("Location: console.php");
        //     exit;
        // }        
    } else {
        // Handle invalid server selection if needed
        $throwError = "Invalid server selected.";
    }
}

// for server parsing
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_servers']) && $_GET['fetch_servers'] == 'true') {
    header('Content-Type: application/json');
    echo json_encode($servers); // Output the server list as JSON
    exit;
}

// Fetch port for a specific server
if (isset($_GET['server'])) {
    $serverName = $_GET['server'];
    $ports = $_SESSION['ports'] ?? [];

    // Return the port for the selected server
    if (isset($ports[$serverName])) {
        echo json_encode(['port' => $ports[$serverName]]);
    } else {
        echo json_encode(['port' => null]);
    }
    exit; // Stop further script execution
}

// Check if a server is selected but no port is set
if ($selectedServer && !$port) {
    // If a server is selected but no port is set, log to the console
    $throwError = "Please set a port before trying to access the console.";
    echo "<script>console.error('Error: Server is selected but port is not set for ' + '" . htmlspecialchars($selectedServer) . "');</script>";
} else if ($selectedServer && $port) {
    echo "<script>console.log('Port is set for the selected server.');</script>";
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
<body>
<div class="container">
    <aside>
        <div class="top">
            <div class="logo">
                <h2 id="brand">Molly Web Panel</h2>
            </div>
        </div>
        <div class="sidebar">
        <a href="index.php" class="active">
            <span class="material-symbols-rounded">grid_view</span>
            <h3>Home</h3>
          </a>
          <a href="manager.php" class=" <?php echo $selectedServer ? '' : 'disabled-link'; ?>">
            <span class="material-symbols-rounded">computer</span>
            <h3>Manager</h3>
          </a>
          <a href="console.php" class=" <?php echo $port ? '' : 'disabled-link'; ?>">
            <span class="material-symbols-rounded">terminal</span>
            <h3>Console</h3>
          </a>
          <a href="files.php">
            <span class="material-symbols-rounded">folder</span>
            <h3>Files</h3>
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
        <p style="color: white;">Molly Web Panel Version: 1.0</p>

        <!-- error message handling -->
        <?php if ($throwError): ?>
            <p style="color: red;" class="errors">
                <?php echo htmlspecialchars($throwError); ?>
            </p>
        <?php endif; ?>

        <div class="item-list">
            <div class="item create-button" onclick="window.location.href='create_server.php'">
                <h3>Create Server</h3>
            </div>
            <div class="item create-button" onclick="window.location.href='upload_server.php'">
                <h3>Upload your own</h3>
            </div>
        </div>

        <div class="item-list" id="servers">
            <?php foreach ($servers as $server): ?>
                <div class="item">
                    <h3><?php echo htmlspecialchars($server); ?></h3>

                    <!-- Display port if it exists -->
                    <?php if (isset($_SESSION['ports'][$server])): ?>
                        <p style="margin-top: -10px;">Port: <?php echo htmlspecialchars($_SESSION['ports'][$server]); ?></>
                    <?php endif; ?>

                    <a href="?select_server=<?php echo urlencode($server); ?>" class="button">Click to manage</a>

                    <div class="icons">
                        <span class="icon" onclick="event.preventDefault(); editServer('<?php echo htmlspecialchars($server); ?>')">
                            <span class="material-symbols-rounded">edit</span>
                        </span>
                        <span class="icon" onclick="event.preventDefault(); deleteServer('<?php echo htmlspecialchars($server); ?>')">
                            <span class="material-symbols-rounded">delete</span>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Modal for Setting Port -->
    <div id="portModal" class="modal">
        <div class="modal-content">
            <h4>Set Port for Server</h4>
            <form method="POST">
                <label for="port">Port:</label>
                <input type="number" id="port" name="port" value="<?php echo htmlspecialchars($port); ?>" required />
                <div class="modal-actions">
                    <button type="submit" name="set_port" id="setPortBtn">Set Port</button>
                    <button type="button" id="closeModalBtn" onclick="closeModal()">Close</button>
                </div>
            </form>
        </div>
    </div>

    <div class="right">
        <button id="menu-btn" class="menu-button">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById("portModal").style.display = "none";
    }

    // Show modal if port is not set
    <?php if (!$port && $throwError): ?>
        document.getElementById("portModal").style.display = "block";
    <?php endif; ?>

    function editServer(serverName) {
        alert("Edit functionality for server: " + serverName);
        // Add logic to edit the server
    }

    function deleteServer(serverName) {
        if (confirm("Are you sure you want to delete server: " + serverName + "?")) {
            alert("Delete functionality for server: " + serverName);
            // Add logic to delete the server
        }
    }
</script>

<script type="text/javascript" src="js/checkserverdir.js"></script>

</body>
</html>
