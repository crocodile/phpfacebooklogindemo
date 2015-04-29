
This is a basic demo demonstrating login with the Facebook Php SDK 4.0.0

The following files are important:
* index.php     - Main file, where most things happen
* logout.php    - For the sake of the demo, this is a separate page for logout which requires a user to press a button to log in. If we automatically redirect t index.php the user will always automatically log in in the background when there is a password in the DB
* tokenDatabase.php - This is the class where database drivers for storing the access token should be implemented.
* util          - Miscellaneous stuff goes here, like logging to the console.
* my_fb_config  - Set appId app, app secret, url... etc.. here. DOn't forget...


See comments in the code for a detailed explanation. Don't forget to configure the Facebook developer portal correctly. Hope this helps.


For a production quality code please consider:
* Removing logging/debugging bits
* Remove unused Facebook library files
* Fully implement all Facebook error scenarios additional to the ones already there.