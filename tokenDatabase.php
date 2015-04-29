<?php

class TokenDatabase {

    // This can be removed for production.
    private static $expired_token_for_testing_only="CAAWF914LlnkBAMyhUr83oDo28axSrkHE2mDoUFMSU8BspsFyZAPf1869NGSWzXiTJ6osJPOX3mkGHkXhtOQ9MI87kzfsrc3T4IMss4LOk3iOvW6qlUxsTlF9Th4fZBWjHh0MVXDOJJ9OBvNdHvLZArf0JRTOBSXs5M66oRC48dYL6A5s9FpFdo2xwvO9sXFZB4ZBKwgRJXXQsYCKbeNgn";

    private static $longLivedAccessToken = null;

    public function storeLongLivedAccessToken($token){
        if (self::$longLivedAccessToken !==$token) {
                // Add/Update in DB code here... but only if values is different    //TODO
        }
    }

    public static function getLongLivedAccessToken(){
        // Get from DB code here  BUT do not call the DB all the time, cache it locally   //TODO
        return self::$longLivedAccessToken;
    }
}

?>
