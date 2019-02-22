<?php
/*
Subscription Verify Request (from Twitch to Client)
GET https://yourwebsite.com/path/to/callback/handler? \
hub.mode=subscribe& \
hub.topic=https://api.twitch.tv/helix/users/follows?first=1&to_id=1337& \
hub.lease_seconds=864000& \
hub.challenge=HzSGH_h04Cgl6VbDJm7IyXSNSlrhaLvBi9eft3bw
*/
if(isset($_GET['hub_challenge']) && !empty($_GET['hub_challenge'])){
    $r = $_GET['hub_challenge'];
    //Subscription Verify Response
    //HzSGH_h04Cgl6VbDJm7IyXSNSlrhaLvBi9eft3bw
    echo $r;
    file_put_contents('webhook_log.txt', $r);
    waitForTwitch();
}


function waitForTwitch(){
    $curl_handle=curl_init();
    //Define GET request header.
    $content_header = array('Authorization: Bearer ' . $bearer);
    curl_setopt_array($curl_handle, array(
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_URL => 'https://api.twitch.tv/helix/webhooks/subscriptions?first=10',
    CURLOPT_HTTPHEADER => $content_header,));

    //Exec curl_handle, receive a JSON decoded result on success, FALSE otherwise.
    $jsonUserObj = curl_exec($curl_handle);
    file_put_contents('webhook_log_1.txt', $r, FILE_APPEND);
    //Successfully received a response.
    if($jsonUserObj !== FALSE){
        //JSON decode $result.
        $subscription_data = json_decode($jsonUserObj);
        file_put_contents('webhook_log_1.txt', $r, FILE_APPEND);
    }
}

function notifySocketClient(){

}

?>