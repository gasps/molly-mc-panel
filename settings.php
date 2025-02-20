<?php session_start();

// Get the currently selected server
$selectedServer = $_SESSION['selected_server'] ?? null;
$serverDisplayText = "Selected Server: " . ($selectedServer ? htmlspecialchars($selectedServer) : "None");

// Check if a server is selected to enable/disable console access
$consoleLink = $selectedServer ? 'console.php' : 'index.php?error=no_server_selected';
$consoleClass = $selectedServer ? '' : 'disabled-link';
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
<a href="index.php">
            <span class="material-symbols-rounded">grid_view</span>
            <h3>Home</h3>
          </a>
          <a href="manager.php">
            <span class="material-symbols-rounded">computer</span>
            <h3>Manager</h3>
          </a>
          <a href=" <?php echo $consoleLink; ?>" class=" <?php echo $consoleClass; ?>">
            <span class="material-symbols-rounded">terminal</span>
            <h3>Console</h3>
          </a>
          <a href="settings.php" class="active">
            <span class="material-symbols-rounded">settings</span>
            <h3>Settings</h3>
          </a>
          <a href="debug.php">
            <span class="material-symbols-rounded">debug</span>
          </a>
<div class="selected-server">
  <a>
    <h3 id="selected-server"><?php echo htmlspecialchars(string: $serverDisplayText);?></h3>
  </a>
</div>

</div>
</aside>
    <main>
    <h1 id="welcome">Welcome, <span id="username"><?php echo htmlspecialchars($username ?? "Unknown"); ?></span></h1>
    <div class="insights">
        <div class="sales">
          <span class="material-symbols-rounded">settings</span>
          
          <div class="middle">
            <div class="left">
              <br>
                <iframe name="hid1" style="display:none;"></iframe>
                <form action="backend.php" method="get" target="hid1">
                    <input type="hidden" name="ad_id" value="2">    
                    <input type="hidden" name="installserver" value="2">               
                
                    <select name="servertype">
                    <option value="bukkit">Bukkit</option>
                    <option value="spigot">Spigot</option>
                    <option value="paper">Paper</option>
                    <option value="fabric">Fabric</option>
                    <option value="forge">Forge</option>
                    <option value="vanilla" selected>Vanilla</option>
                    </select>
                    <br><br>
                    <input type="submit" onclick="alert('Server Installed!'); location.reload();" value="Install Server">
                    
                </form>

              <br>
              
              
              <div>
                
               
              </div>
            </div>
            
          </div>
          <small class="text-muted">Server Setup</small>
        </div>
      </div>  

       
      

    </main>

    <div class="right">
        <button id="menu-btn" class="menu-button">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </div>

  </div>
  
  <script type="text/javascript" src="app.js"></script>
  
</body>
</html>