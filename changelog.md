# i barely log anything so if anything gets logged luckily its here.. lol
01/27/25 -
 - cleaned up some connection issues with the form in console.
 - added restart button functionality (working on send next)
 - added auto sorting (date, and file type) for files


 - you can upload your own files to make a server, (has to be a folder, dont have logic for rar's or anything else.)
 - you have to set limit size for xampp, preferred size would be 2G's for servers to upload (typically servers need 10G (each instance) Block Storage on VM's to run properly)
 - change the name of server (refreshes directory, and all other variables)

==============

01/26/25 -

 - able to change/set port in manager tab
 - files are able to be viewed/edited in the manage tab (very experiemental, and buggy)

==============

01/25/25 -

 - error support for simple tasks at the start
 - start and stop fully functioning
 - port setting; you can have more than one server by define ports of the websocket
 - cleaned up a little bit
 - added a function to go to manager page
 - fixed everything with starting and stopping and made it so each server is defined to their own console, etc
 - stupid logging from the spigot/plugin code that sent json responses of cpu/ram/player output that wasn't being used