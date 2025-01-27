/**
* Global variables
*/
var persistenceManager = new WebConsolePersistenceManager();
var connectionManager = new WebConsoleManager();
var autoPasswordCompleted = false; //When true, saved password was used. If a 401 is received, then saved password is not correct
var statusCommandsInterval = -1;
var commandHistoryIndex = -1; //Saves current command history index. -1 when not browsing history.

/**
* Prepare and show server to user
*/
function openServer(serverURI){

	$("#consoleTextArea").text("");
	
	//New server, new variables:
	autoPasswordCompleted = false;
	commandHistoryIndex = -1; //Reset command history index
	
	//Create or retrieve connection
	connectionManager.loadConnection(serverURI);
	
	//Load saved messages
	var i;
	var messages = connectionManager.activeConnection.messages;
	for(i = 0; i < messages.length; i++){
		if(messages[i].status != 401){
			onWebSocketsMessage(messages[i]);
		}
	}
	
	//Subscribe a function
	connectionManager.activeConnection.subscribe(onWebSocketsMessage);
}

function onWebSocketsMessage(message){
	switch (message.status) {
		case 10:
			//Console Output
			writeToWebConsole(message.message);
			break;
		case 200:
			//Processed
			writeToWebConsole(message.message);
			if(connectionManager.activeConnection.isLogged === false){
				connectionManager.activeConnection.isLogged = true;
				// if(persistenceManager.getSetting("retrieveLogFile") === true)
					connectionManager.askForLogs();
			}
			break;
		case 400:
			//Unknown Command
			writeToWebConsole(message.message);
			break;
		case 401:
			break;
		default:
			console.log('Unknown server response:');
	}
	console.log(message);
}

/**
* Write to console
*/
function writeToWebConsole(msg){
	var isScrolledDown = document.getElementById("consoleTextArea").scrollHeight - document.getElementById("consoleTextArea").scrollTop - 40 == $("#consoleTextArea").height();
	
	//Write to div, replacing < to &lt; (to avoid XSS) and replacing new line to br.
	msg = msg.replace(/</g, "&lt;");
	msg = msg.replace(/(?:\r\n|\r|\n)/g, "<br>");
	
	//Color filter for Windows (thanks to SuperPykkon)
	msg = msg.replace(/\[0;30;22m/g, "<span style='color: #000000;'>"); //&0
	msg = msg.replace(/\[0;34;22m/g, "<span style='color: #0000AA;'>"); //&1
	msg = msg.replace(/\[0;32;22m/g, "<span style='color: #00AA00;'>"); //&2
	msg = msg.replace(/\[0;36;22m/g, "<span style='color: #00AAAA;'>"); //&3
	msg = msg.replace(/\[0;31;22m/g, "<span style='color: #AA0000;'>"); //&4
	msg = msg.replace(/\[0;35;22m/g, "<span style='color: #AA00AA;'>"); //&5
	msg = msg.replace(/\[0;33;22m/g, "<span style='color: #FFAA00;'>"); //&6
	msg = msg.replace(/\[0;37;22m/g, "<span style='color: #AAAAAA;'>"); //&7
	msg = msg.replace(/\[0;30;1m/g, "<span style='color: #555555;'>");  //&8
	msg = msg.replace(/\[0;34;1m/g, "<span style='color: #5555FF;'>");  //&9
	msg = msg.replace(/\[0;32;1m/g, "<span style='color: #55FF55;'>");  //&a
	msg = msg.replace(/\[0;36;1m/g, "<span style='color: #55FFFF;'>");  //&b
	msg = msg.replace(/\[0;31;1m/g, "<span style='color: #FF5555;'>");  //&c
	msg = msg.replace(/\[0;35;1m/g, "<span style='color: #FF55FF;'>");  //&d
	msg = msg.replace(/\[0;33;1m/g, "<span style='color: #FFFF55;'>");  //&e
	msg = msg.replace(/\[0;37;1m/g, "<span style='color: #FFFFFF;'>");  //&f
	msg = msg.replace(/\[m/g, "</span>");  //&f
	
	//Color filter for UNIX (This is easier!)
	//span may not be closed every time but browsers will do for ourselves
	msg = msg.replace(/§0/g, "<span style='color: #000000;'>"); //&0
	msg = msg.replace(/§1/g, "<span style='color: #0000AA;'>"); //&1
	msg = msg.replace(/§2/g, "<span style='color: #00AA00;'>"); //&2
	msg = msg.replace(/§3/g, "<span style='color: #00AAAA;'>"); //&3
	msg = msg.replace(/§4/g, "<span style='color: #AA0000;'>"); //&4
	msg = msg.replace(/§5/g, "<span style='color: #AA00AA;'>"); //&5
	msg = msg.replace(/§6/g, "<span style='color: #FFAA00;'>"); //&6
	msg = msg.replace(/§7/g, "<span style='color: #AAAAAA;'>"); //&7
	msg = msg.replace(/§8/g, "<span style='color: #555555;'>"); //&8
	msg = msg.replace(/§9/g, "<span style='color: #5555FF;'>"); //&9
	msg = msg.replace(/§a/g, "<span style='color: #55FF55;'>"); //&a
	msg = msg.replace(/§b/g, "<span style='color: #55FFFF;'>"); //&b
	msg = msg.replace(/§c/g, "<span style='color: #FF5555;'>"); //&c
	msg = msg.replace(/§d/g, "<span style='color: #FF55FF;'>"); //&d
	msg = msg.replace(/§e/g, "<span style='color: #FFFF55;'>"); //&e
	msg = msg.replace(/§f/g, "<span style='color: #FFFFFF;'>"); //&f
	
	msg = msg.replace(/§l/g, "<span style='font-weight:bold;'>"); //&l
	msg = msg.replace(/§m/g, "<span style='text-decoration: line-through;'>"); //&m
	msg = msg.replace(/§n/g, "<span style='text-decoration: underline;'>"); //&n
	msg = msg.replace(/§o/g, "<span style='font-style: italic;'>"); //&o
	
	msg = msg.replace(/§r/g, "</span>");  //&r

	//Append datetime if enabled
	msg = "[" + new Date().toLocaleTimeString() + "] " + msg;
	
	$("#consoleTextArea").append(msg + "<br>");
	
	if(isScrolledDown){
		var textarea = document.getElementById('consoleTextArea');
		textarea.scrollTop = textarea.scrollHeight;
	}
}


/**
* Called from WebConsoleConnector only.
*/
function closedConnection(serverURI){
	if(connectionManager.activeConnection.serverURI == serverURI){
		//Disable command input and button
		$("#commandInput").prop("disabled", true);
		$("#sendCommandButton").prop("disabled", true);
		
		//Inform user
		$('#disconnectionModal').modal('show');
	}
	connectionManager.deleteConnection(serverURI, true);
}