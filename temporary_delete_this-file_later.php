<?php

public function fbAccessToken($app_id=NULL,$app_secret=NULL,$ad_acc_id=NULL,$campaign=NULL)
{

    //echo "fb access:" . $app_id." ". $app_secret;

    $objFb = $this->load->library('facebook');
    //print_r(get_class_methods($this->load->library('facebook')));

    error_reporting(E_ALL);
    $session = '';
//	$app_id = '1518548155074741';
//	$app_secret = '582e5edc750a0a0c533db8aa36b044aa';
//$app_secret = 'XWSk5aRmSp0LGtHDs1K1pkeQZN4';
    //$fb_obj = new Facebook($app_id,$app_secret);
    //include_once(APPPATH . 'libraries/facebook/src/Facebook/FacebookRedirectLoginHelper.php');

    //$helper = new FacebookRedirectLoginHelper('http://localhost:81/facebook-php-sdk/tests/access_token.php', $app_id, $app_secret);
    //$helper = new FacebookRedirectLoginHelper('http://localhost:81/facebook-php-sdk/tests/access_token.php', $app_id, $app_secret);
    try {
        $helper = $objFb->getExtAccessToken(base_url()."index/fbApiDetails?appid=$app_id&secret=$app_secret"
            , $app_id, $app_secret);

    }
    catch(FacebookRequestException $e) {
        echo "re exp";
        print_r($e);
    }
    catch(Exception $ex) {
        // When validation fails or other local issues
        echo "other exp";
        print_r($ex);
    }


    $session = $helper->getSessionFromRedirect();

    echo "<pre>"; print_r($session);

    if ($session) {

        // User logged in, get the AccessToken entity.
        $accessToken = $session->getAccessToken();
        // Exchange the short-lived token for a long-lived token.
        $longLivedAccessToken = $accessToken->extend($app_id,$app_secret);
        // Now store the long-lived token in the database
        // . . . $db->store($longLivedAccessToken);
        // Make calls to Graph with the long-lived token.
        // . . .

        return $longLivedAccessToken;
    } else {
        //echo '<a href="' . $helper->getLoginUrl() . '">Login with Facebook</a>';

        redirect($objFb->login_url());

    }

}

public function fbApiDetails()
{
    if(!$this->ion_auth->logged_in())
    {
        redirect('index/login');
    }


    //	error_reporting(E_ALL);
    $ad_acc_id = '';
    $campaign_id = '';
    $fb_appId = '975196449164641';
    $fb_app_secret = '40c4acf372eead6199a572d9a457d3e3';
    $my_url = base_url()."index/fbApiDetails?appid=$fb_appId&secret=$fb_app_secret";

    $access_token = $this->fbAccessToken($fb_appId,$fb_app_secret,$ad_acc_id,$campaign_id);

    $token = "hi#".$access_token;
    $token_string = explode("#",$token);

    $this->session->set_userdata("fb_access_token",$token_string[1]);


    $urlToCall = "https://graph.facebook.com/".$this->config->item('fb_api_version')."/me/applications/developer?access_token=".$access_token."jjkkk";

    $ch = curl_init($urlToCall);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(

    ));


    $responseArray = json_decode(curl_exec($ch));

    curl_close($ch);

    if($responseArray->error)
    {
        echo "here";
        print_r($responseArray);

        if($responseArray->error->type== "OAuthException" && $responseArray->error->code=="190")
        {
            /*	$dialog_url= "https://www.facebook.com/dialog/oauth?"
                . "client_id=" . $fb_appId
                . "&redirect_uri=" . urlencode($my_url);
              echo("<script> top.location.href='" . $dialog_url
              . "'</script>");*/

            $this->fbApiDetails();

        }
    }

    //	echo "<pre>";
    //	print_r($responseArray);

    $responseData = (array)$responseArray;

    $valData = $responseData['data'];
    $apps = (array)$valData;


    $app_list = "<select name='app_list' id='app_list'>";
    foreach($apps as $val)
    {
        $val = (array)$val;
        //echo $val['name']."====". $val['id']; echo "<br/>";
        $app_list .= "<option value=". $val['id'] .">". $val['name'] . "</option>";
    }

    $app_list .= "</select>";



    $data['app_list'] = $app_list;

    $this->template->load('fbApiDetails', 'home',$data);

}