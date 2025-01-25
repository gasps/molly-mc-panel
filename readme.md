# mollyinduced minecraft server panel

## requirements
- custom web console plugin – coming soon

this is a project i forked (though i’ve forgotten from who), but i completely revamped it. it no longer uses their source code and has become my own project.

no external libraries or dependencies are used, except for those needed for browser-based applications (i.e., javascript, html, css).

currently, it's compatible with windows 10, but i'm planning to add support for linux, macos, and maybe even mobile. right now, this is intended for localhost only, but i'll expand it to allow remote connections without the need to be local.

started this project on 12/26/24. i have plenty of ideas and plans for the future, so stay tuned!

## how to use?
1. create a "servers" folder if it doesn't exist, then add or create your server inside.
2. find the `webconsole.yml` file in your server folder (for WebConsole Plugin or Spigot).
3. set your WebConsole port in `webconsole.yml`.
4. go to the web panel's "Home" tab, where your server should appear.
5. select your server and set the websocket port to match the one in `webconsole.yml`.
6. select your server again and it should automatically take you to the console, where you can start and stop your server!


Opt. If you don't know how to run the web panel, download an application called 'xampp', and follow these steps: [How to setup XAMPP](#how-to-setup-xampp)

## features (more coming soon)
- start and stop server
- web console (websocket/port)
- multiple server support

## coming soon HOPEFULLY!
- server file manager
- create/upload your own server
- custom server files

















- [How to setup XAMPP](#how-to-setup-xampp)

# How to setup XAMPP

## Step 1: Download XAMPP

1. Go to the [official XAMPP website](https://www.apachefriends.org/index.html).
2. Choose the appropriate version for your operating system (Windows, Linux, macOS).
3. Download the installer and follow the installation instructions.

## Step 2: Install XAMPP

1. Run the downloaded installer.
2. Follow the prompts and choose the components you want to install (the default options should work fine for most users).
3. Complete the installation process.

## Step 3: Start XAMPP

1. Open the XAMPP Control Panel (installed along with XAMPP).
2. In the Control Panel, click the **Start** button next to **Apache** to start the web server.
3. Optionally, you can also start **MySQL** if you plan to use a database for your website.

## Step 4: Create Your Website in the `htdocs` Directory

1. Navigate to the `htdocs` folder in the XAMPP installation directory (by default, it’s located in `C:\xampp\htdocs` on Windows).
2. Inside `htdocs`, create a new folder for your website (e.g., `mywebsite`).
3. Create an `index.html` file in the folder (this will be the homepage of your website). For example:
   ```html
   <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>My Simple Website</title>
   </head>
   <body>
       <h1>Welcome to My Simple Website!</h1>
   </body>
   </html>

## Step 5: View Your Website
1. Open your web browser.
2. In the address bar, type http://localhost/mywebsite (replace mywebsite with the name of your folder).
3. You should now see your simple website displayed in the browser.

## Step 6: Stop XAMPP
1) When you’re done, you can stop the server by clicking the Stop button next to Apache in the XAMPP Control Panel.


README MADE @ 09:04:23 AM 01/25/25