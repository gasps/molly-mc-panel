<?php
session_start();

// Check if the port is set in the session, or get it from the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the port is submitted
    if (isset($_POST['port']) && !empty($_POST['port'])) {
        $_SESSION['port'] = $_POST['port']; // Store the port in session
    } else {
        $error = "Please set the port before starting.";
    }
}

// Get the currently selected server
$selectedServer = $_SESSION['selected_server'] ?? null;
$serverDisplayText = "Selected Server: " . ($selectedServer ? htmlspecialchars($selectedServer) : "None");

// Check if a server is selected to enable/disable console access
$consoleLink = $selectedServer ? 'console.php' : 'index.php?error=no_server_selected';
$consoleClass = $selectedServer ? '' : 'disabled-link';

// return to index if no server is selected

if (!$selectedServer) {
    header("Location: index.php?error=no_server_selected");
    exit();
}

if ($selectedServer) {
    $port = $_SESSION['ports'][$selectedServer] ?? null;

    if ($port) {
    } else {
        header("location: index.php");
    }
} else {
    echo "No server selected.";
}

if (!isset($_SESSION['server_running'])) {
  $_SESSION['server_running'] = false;
  $_SESSION['current_server'] = null;
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
    <style>
        /* Modal Style */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
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
        <a href="index.php">
            <span class="material-symbols-rounded">grid_view</span>
            <h3>Home</h3>
          </a>
          <a href="manager.php">
            <span class="material-symbols-rounded">computer</span>
            <h3>Manager</h3>
          </a>
          <a href=" <?php echo $consoleLink; ?>" class="<?php echo $consoleClass; ?> active">
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
                <a>
                    <h3 id="selected-server"><?php echo htmlspecialchars($serverDisplayText); ?></h3>
                </a>
            </div>
        </div>
    </aside>

    <main>
        <h1 id="welcome">Welcome, <span id="username"><?php echo htmlspecialchars($username ?? "Unknown"); ?></span></h1>
      <div class="insights">
        <div class="sales">
        <div style="display: flex; align-items: center;">
          <span class="material-symbols-rounded" style="margin-right: 8px;">terminal</span>
          <h1 style="margin: 0;"><?php echo htmlspecialchars($selectedServer); ?></h1>
        </div>
          <div class="middle">
            <div class="left">
              <br>
              <iframe id="theconsoleitselflol" src="app/log.php" title="LOG" width="800px" height="500px" style="border:1px solid black;"></iframe>
              <br>
              <iframe name="hid1" style="display:none;"></iframe>

              <form action="backend.php" method="get" target="hid1">
                  <input type="text" placeholder="Command..." id="command" name="command"></input>
                  <input type="hidden" name="ad_id" value="2">   
                  <input type="submit" name="run" id="sendCommand" value="Send">
              </form>

              <div>

              <script>
    // Wait for the DOM to be ready
    document.addEventListener("DOMContentLoaded", function() {
        // Get the start form and the iframe element
        const startForm = document.querySelector("#startForm");
        const logIframe = document.querySelector("#theconsoleitselflol");

        // Listen for form submission
        startForm.addEventListener("submit", function(event) {
            // Reload the iframe after the form is submitted
            logIframe.src = logIframe.src;
        });
    });
                </script>

                <form action="backend.php" method="get" target="hid1" id="startForm">
                  <input type="submit" value="Start">
                  <input type="hidden" name="ad_id" value="2">      
                  <input type="hidden" name="start" value="2">             
                </form>
                <form action="backend.php" method="get" target="hid1">
                    <input type="submit" value="Stop">
                    <input type="hidden" name="ad_id" value="2">      
                    <input type="hidden" name="stop" value="2">             
                </form>
                <form action="backend.php" method="get" target="hid1">
                    <input type="submit" value="Restart">
                    <input type="hidden" name="ad_id" value="2">    
                    <input type="hidden" name="restart" value="2">               
                </form>
                <form action="backend.php" method="get" target="hid1">
                    <input type="submit" value="Kill">
                    <input type="hidden" name="ad_id" value="2">    
                    <input type="hidden" name="kill" value="2">               
                </form>

              </div>
            </div>
          </div>
          <small class="text-muted">Terminal</small>
        </div>
      </div>  
    </main>



</body>
</html>
