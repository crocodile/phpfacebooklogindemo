<?php

require_once('Facebook/HttpClients/FacebookHttpable.php');
require_once('Facebook/HttpClients/FacebookCurl.php');
require_once('Facebook/HttpClients/FacebookCurlHttpClient.php');

require_once('Facebook/Entities/AccessToken.php');
require_once('Facebook/Entities/SignedRequest.php');

require_once('Facebook/FacebookSession.php');
require_once('Facebook/FacebookRedirectLoginHelper.php');
require_once('Facebook/FacebookRequest.php');
require_once('Facebook/FacebookResponse.php');
require_once('Facebook/FacebookSDKException.php');
require_once('Facebook/FacebookRequestException.php');
require_once('Facebook/FacebookOtherException.php');
require_once('Facebook/FacebookAuthorizationException.php');
require_once('Facebook/GraphObject.php');
require_once('Facebook/GraphSessionInfo.php');

use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;

use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;

require_once('my_fb_config.php');   // This is where we put global configuration variables
require_once('util.php');  // Logging
require_once('tokenDatabase.php');   //To store the long term access token



session_start();    // start session
if (session_id() == '') {
    debug_to_console("This needs attention. SESSION has not started!");
} // This is for debugging only, can be removed during optimisation


// initialise app with app id and secret
FacebookSession::setDefaultApplication($my_app_id, $my_app_secret);
$helper = new FacebookRedirectLoginHelper($my_base_url . 'index.php');  // login helper with redirect_uri




/**************** CHECK IF THE ACCESS TOKEN ALREADY EXISTS IN THE PHP SESSION AND VALIDATE IT *************/
if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
    debug_to_console("SESSION exists");

    // create new session from saved access_token
    $session = new FacebookSession($_SESSION['fb_token']);

    // validate the access_token to make sure it's still valid
    try {
        if (!$session->validate()) {
            debug_to_console("session -> validate " . "Access token is not valid");
            $session = null;
        }
    } catch (Exception $e) {
        // catch any exceptions
        $session = null;
    }
}



/****** IF NO SESSION EXISTS STILL AT THIS POINT, CHECK IF THE ACCESS TOKEN ALREADY EXISTS IN THE DB AND VALIDATE IT ******/
if (!isset($session) || $session === null) {
    $storedLongLivedAccessToken = TokenDataBase::getLongLivedAccessToken();
    if ($storedLongLivedAccessToken !== null) {  // in DB
        // create new session from access_token saved in db
        $session = new FacebookSession($storedLongLivedAccessToken);

        // validate the access_token to make sure it's still valid
        try {
            if (!$session->validate()) {
                debug_to_console("session -> validate " . "Access token is not valid");
                $session = null;
            }
        } catch (Exception $ex) {
            // catch any exceptions
            $session = null;
        }
    }
}



/**************** IF NO SESSION EXISTS STILL AT THIS POINT,  LOOK FOR DATA FROM PREVIOUS REDIRECT *************/
if (!isset($session) || $session === null) {

    debug_to_console("No session exist");

    try {
        $session = $helper->getSessionFromRedirect();
    } catch (FacebookRequestException $ex) {
        // When Facebook returns an error handle this better in production code
        debug_to_console("FacebookRequestException: " . $ex);
    } catch (Exception $ex) {
        // When validation fails or other local issues handle this better in production code
        debug_to_console("Exception: " . $ex);
    }

}



/**************** IF WE HAVE A FACEBOOK SESSION THEN GO AHEAD *************/
if (isset($session)) {
    $session->getLongLivedSession($my_app_id, $my_app_secret);  // After this the session is long lived 60 days
    TokenDataBase::storeLongLivedAccessToken($session->getToken());
    $_SESSION['fb_token'] = $session->getToken();  // Save the Facebook token session to the browser session
    $session = new FacebookSession($_SESSION['fb_token']);
    try {
        // graph api request for user data
        $response = (new FacebookRequest($session, 'GET', '/me/applications/developer'))->execute();
        // get response
        $graphObject = $response->getGraphObject()->asArray();
        echo $graphObject;
    } catch (FacebookRequestException $ex) {
        debug_to_console("FacebookRequestException: " . $ex);

        /* https://developers.facebook.com/docs/graph-api/using-graph-api/v2.3
        460 Password Changed
        463 Expired
        467 Invalid access token */
        if ($ex->getSubErrorCode() === 460 || $ex->getSubErrorCode() === 463 || $ex->getSubErrorCode() === 467) {
            debug_to_console("Facebook Authentication Error. The subcode is " . $ex->getSubErrorCode());
            $session = null;
            $_SESSION['fb_token'] = $session;

            // REDIRECT BACK TO THE LOGIN PAGE WHEN A REQUEST FAILS IF ANY OF THOSE ERROR CONDITIONS ABOVE ARE PRESENT
            header('Location: ' . $helper->getLoginUrl($facebook_api_permissions));

        }

    } catch (Exception $ex) {
        debug_to_console("Exception: " . $ex);
    }


    // print profile data
    echo '<pre>' . print_r($graphObject, 1) . '</pre>';

    // print logout url using session and redirect_uri (logout.php page should destroy the session)
    echo '<a href="' . $helper->getLogoutUrl($session, $my_base_url . 'logout.php') . '">Logout</a>';

} else {
    // show login url
    echo '<a href="' . $helper->getLoginUrl($facebook_api_permissions) . '">Login</a>';
}
