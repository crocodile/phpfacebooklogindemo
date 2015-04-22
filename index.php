<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 22/04/15
 * Time: 11:05
 */


require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );

require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php' );

require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookOtherException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphSessionInfo.php' );

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


// start session
session_start();
echo $_SESSION['fb_state'];

echo 'Start';
// init app with app id (APPID) and secret (SECRET)
FacebookSession::setDefaultApplication('1554672364787321','e9c6df1b9d123b0549f99ef6edb59b0c');

// login helper with redirect_uri
//$current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$current_url = 'http://localhost:63342/phplogindemo/index.php';
//echo $current_url;
$helper = new FacebookRedirectLoginHelper($current_url);

try {
    echo ' $session is: ' ;
    echo $session;
    echo 'We gonna get session from redirect';
    $session = $helper->getSessionFromRedirect();
    echo ' $session is: ' ;
    echo $session;
} catch( FacebookRequestException $ex ) {
    // When Facebook returns an error
    echo '-- FacebookRequestException --';
} catch( Exception $ex ) {
    // When validation fails or other local issues
    echo '-- Exception --';
}

// see if we have a session
if ($session) {
    echo 'We  have a session';
    // graph api request for user data
    $request = new FacebookRequest( $session, 'GET', '/me/applications/developer
' );
    $response = $request->execute();
    // get response
    $graphObject = $response->getGraphObject();

    // print data
    //echo  print_r( $graphObject, 1 );
} else {
    // show login url
    echo 'We  dont have a session';
    echo '<a href="' . $helper->getLoginUrl(array( 'email', 'user_friends' )) . '">Login</a>';
}
