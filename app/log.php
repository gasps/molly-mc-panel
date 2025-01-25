<?php 
session_start(); /* Starts the session */

// Get the currently selected server
$selectedServer = $_SESSION['selected_server'] ?? null;
$serverDisplayText = "Selected Server: " . ($selectedServer ? htmlspecialchars($selectedServer) : "None");

if ($selectedServer) {
    $port = $_SESSION['ports'][$selectedServer] ?? null;
} else {
    echo "No server selected.";
    $port = null; // Ensure port is null if no server is selected
}

if (!$selectedServer)
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="favicon.png">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        <!-- Custom CSS -->
        <link rel="stylesheet" href="styles.css">
        
        <title>WebConsole</title>
    </head>
    <body>
        <!-- Begin page content -->
         <!-- move left 5px to the left, do it in css lazy bitch -->
        <div class="" style="margin-left: 5px;" id="consoleTextArea"></div>

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        
        <!-- WebConsole JS Scripts -->
        <script src="WebConsoleConnector.js?v=1.5.4"></script>
        <script src="WebConsoleManager.js?v=1.5.4"></script>
        <script src="WebConsolePersistenceManager.js?v=1.5.4"></script>
        <script src="WebConsole.js?v=1.5.4"></script>

        <script>
            $(document).ready(function() {

                // Pass the port from PHP to JavaScript
                <?php if ($port): ?>
                    var serverPort = "<?php echo $port; ?>";
                    openServer("ws://localhost:" + serverPort);
                <?php else: ?>
                    console.log("No port available.");
                <?php endif; ?>
            });
        </script>
    </body>
</html>
