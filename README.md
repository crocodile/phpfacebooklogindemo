
***Facebook login flow demo*** with the Php SDK 4.0.0 using long-lived access tokens stored in a back-end DB

The following files are important:
* index.php     - Main file, where most things happen
* logout.php    - For the sake of the demo, this is a separate page for logout which requires the user to manually log back in with a button click. If we automatically redirect to index.php, the user will  automatically log-in in the background when there is a password stored in the DB
* tokenDatabase.php - This is the class where database drivers for storing the access token should be implemented.
* util.php          - Miscellaneous stuff goes here, like logging to the console.
* my_fb_config.php  - Set appId app, app secret, url... etc.. here. Don't forget...


See comments in the code for a detailed explanation. Don't forget to configure the Facebook developer portal correctly. Hope this helps.


For a production quality code please consider:
* Removing logging/debugging bits
* Remove the inclusion of any unused Facebook library files
* Implement all necessary Facebook error scenarios additional to the ones already there
