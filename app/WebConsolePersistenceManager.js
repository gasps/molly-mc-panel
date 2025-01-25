class WebConsolePersistenceManager{
	/**
	* Create server list if not defined
	*/
	initializeLocalStorage(){
		if (typeof window.localStorage.WebConsole === 'undefined') {
			//Create empty object
			var storageObj = new Object();
			storageObj.servers = new Array();
			
			//Save to WebStorage
			window.localStorage.WebConsole = JSON.stringify(storageObj);
		}
	}

		/**
	* Get server details as object
	*/
	getServer(serverURI){
		var i;
		var servers = this.getAllServers();
		for (i = 0; i < servers.length; i++) { 
			if(servers[i].serverURI == serverURI){
				return servers[i];
			}
		}
	}
	
	/**
	* Get all servers
	*/
	getAllServers(){
		var storageObj = JSON.parse(window.localStorage.WebConsole);
		return storageObj.servers;
	}
	/**
	* Replaces all server list with provided list
	*/
	replaceAllServers(newServerList){
		//Retrieve saved data
		var storageObj = JSON.parse(window.localStorage.WebConsole);
		storageObj.servers = newServerList;
		
		//Save to WebStorage
		window.localStorage.WebConsole = JSON.stringify(storageObj);
	}

	/**
	* Create settings object if not defined or populate with new options if updating
	*/
	initializeSettings(){
		this.initializeLocalStorage();

		//Create settings object
		var currentSettings = JSON.parse(window.localStorage.WebConsole).settings;
		if (typeof currentSettings === 'undefined') {
			currentSettings = new Object();
		}

		// //Set settings
		// jQuery.each(settings, (key, settingObj) =>{
		// 	if(!currentSettings.hasOwnProperty(settingObj.name))
		// 		currentSettings[settingObj.name] = settingObj.defaultValue;
		// });

		//Save all
		var storageObj = JSON.parse(window.localStorage.WebConsole);
		storageObj.settings = currentSettings;
		window.localStorage.WebConsole = JSON.stringify(storageObj);
	}

	/**
	* Update setting value
	*/
	setSetting(name, value){
		var currentSettings = JSON.parse(window.localStorage.WebConsole).settings;
		currentSettings[name] = value;

		//Save all
		var storageObj = JSON.parse(window.localStorage.WebConsole);
		storageObj.settings = currentSettings;
		window.localStorage.WebConsole = JSON.stringify(storageObj);
	}

	/**
	* Get setting value
	*/
	getSetting(name){
		return JSON.parse(window.localStorage.WebConsole).settings[name];
	}
	
}

class WSServer{
	constructor(serverURI){
		this.serverURI = serverURI;
	}
}

class Setting{
	constructor(name, defaultValue){
		this.name = name;
		this.defaultValue = defaultValue;
	}
}