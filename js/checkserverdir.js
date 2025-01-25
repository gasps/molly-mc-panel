let lastServerList = []; // Store the previous server list

// Fetch the server list and port information using JavaScript
function fetchServers() {
    fetch('index.php?fetch_servers=true')  // The URL of your PHP script with fetch_servers query
        .then(response => {
            // Check if the response is OK (status 200)
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();  // Parse the JSON response
        })
        .then(data => {
            // Ensure data is an array before calling forEach
            if (Array.isArray(data)) {
                console.log('Fetched server data:', data); // Log the fetched server data
                compareAndDisplayServers(data);
            } else {
                console.error('Received data is not an array:', data);
            }
        })
        .catch(error => console.error('Error fetching servers:', error));
}

// Function to compare the current and last server list and display changes
function compareAndDisplayServers(servers) {
    // Check if the server list has changed by comparing the length of the arrays and their content
    if (JSON.stringify(servers) !== JSON.stringify(lastServerList)) {
        console.log('Server list has changed. Updating...');

        // Update the last server list
        lastServerList = [...servers]; // Use spread operator to create a new array

        // Clear existing content and display new server list
        displayServers(servers);
    } else {
        console.log('No changes in server list.');
    }
}

// Function to display the servers in your HTML
async function displayServers(servers) {
    const serversContainer = document.getElementById('servers');
    serversContainer.innerHTML = ''; // Clear any existing servers in the container

    for (const server of servers) {
        // Fetch the port for each server asynchronously
        const port = await getPortForServer(server);  // Fetch the port for the server
        console.log(`Server: ${server}, Port: ${port}`);  // Log the server and its port to the console

        // Create a new div for each server
        const serverElement = document.createElement('div');
        serverElement.className = 'item';
        serverElement.innerHTML = `
            <h3>${server}</h3>
            ${port ? `<p style="margin-top: -10px;">Port: ${port}</p>` : ''}  <!-- Display port if it exists -->
            <a href="?select_server=${encodeURIComponent(server)}" class="button">Click to manage</a>
            <div class="icons">
                <span class="icon" onclick="event.preventDefault(); editServer('${server}')">
                    <span class="material-symbols-rounded">edit</span>
                </span>
                <span class="icon" onclick="event.preventDefault(); deleteServer('${server}')">
                    <span class="material-symbols-rounded">delete</span>
                </span>
            </div>
        `;
        // Append the server div to the container
        serversContainer.appendChild(serverElement);
    }
}

// Dummy function to simulate getting the port for a server
// Replace this with actual logic to retrieve the port from the server or API
// Function to fetch the port for a server asynchronously
function getPortForServer(serverName) {
    return fetch(`index.php?server=${encodeURIComponent(serverName)}`)
        .then(response => response.json())
        .then(data => {
            if (data.port) {
                return data.port; // Return the port if found
            } else {
                return null; // Return null if no port is found
            }
        })
        .catch(error => {
            console.error('Error fetching port:', error);
            return null; // Return null in case of an error
        });
}


// Call the function to fetch and display servers when the page loads
window.onload = fetchServers;

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

// Set an interval to fetch and compare the server list every 2 seconds
setInterval(fetchServers, 2000);
