<?php
session_start(); /* Starts the session */

$selectedServer = $_SESSION['selected_server'] ?? null;

$serverJarPath = 'servers/' . $selectedServer . '/server.jar'; // Update this to the correct path
$serverDirectory = dirname(path: $serverJarPath); // Get the directory containing the jar file

// Define memory settings for the server
$minMemory = '1024M'; // Minimum memory
$maxMemory = '1024M'; // Maximum memory

// Function to send debug messages to the console
function debugToConsole($message) {
    echo "<script>console.log('$message');</script>";
}

function getServerPID($selectedServer) {
    // Use wmic to get the PID and command line of all running java.exe processes
    $command = 'wmic process where "name=\'java.exe\'" get commandline, processid';
    exec($command, $output);

    // Loop through the output to extract PID and command line
    foreach ($output as $line) {
        // Skip lines that do not contain the necessary information
        if (empty($line)) {
            continue;
        }

        // Check if the line contains the server name
        if (strpos($line, "server: $selectedServer") !== false) {
            // Extract the PID using regex
            if (preg_match('/\s+(\d+)$/', $line, $matches)) {
                $pid = $matches[1];
                echo "Found PID for $selectedServer: $pid\n";
                return $pid;
            }
        }
    }

    // Return null if no matching server was found
    echo "Server $selectedServer is not running.\n";
    return null;
}

function isServerRunning($selectedServer) {
    $pid = getServerPID($selectedServer);
    if ($pid !== null) {
        debugToConsole("Server $selectedServer is running with PID: $pid.");
        return true; // Server is running
    }

    debugToConsole("Server $selectedServer is not running.");
    return false; // Server is not running
}

function startServer($selectedServer, $serverJarPath, $serverDirectory, $minMemory, $maxMemory) {
    debugToConsole("Starting server $selectedServer in directory: $serverDirectory");

    // Command to start the server with unique parameters and logs
    $command = "start /D \"$serverDirectory\" /B java -Xms$minMemory -Xmx$maxMemory -jar " . escapeshellarg(basename($serverJarPath)) . " nogui \"server: $selectedServer\" 2>&1";
    
    // Debugging output for the start command
    debugToConsole("Executing command: $command");

    pclose(popen("cmd /c \"$command\"", "r"));
    
    // Debugging output after starting the server
    debugToConsole("Minecraft server started in directory: $serverDirectory");
}

function stopServer($selectedServer) {
    debugToConsole("Attempting to stop the Minecraft server: $selectedServer");

    $pid = getServerPID($selectedServer);
    if ($pid !== null) {
        exec("taskkill /PID $pid /F");
        debugToConsole("Minecraft server $selectedServer stopped.");
    } else {
        debugToConsole("Minecraft server $selectedServer is not running.");
    }
}

// Handling actions via $_GET
if (isset($_GET["stop"])) {
    stopServer($selectedServer);
}
if (isset($_GET["start"])) {
    if (!isServerRunning($selectedServer)) {
        startServer($selectedServer, $serverJarPath, $serverDirectory, $minMemory, $maxMemory);
    }
}
?>
