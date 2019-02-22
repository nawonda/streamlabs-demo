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
    file_put_contents('webhook_log.txt', $r, FILE_APPEND);
}

if(isset($_POST['data'])){
    $r = $_POST['data'];
    file_put_contents('webhook_log.txt', "get a call", FILE_APPEND);
    file_put_contents('webhook_log.txt', $r, FILE_APPEND);
}

function waitForTwitch(){
    $original_json_array = json_decode(file_get_contents('./_config.txt'), true); 
    $data = $original_json_array["dev"];
    $url = $data['url'];
    $clientId = $data['clientId'];
    $clientSecret = $data['clientSecret'];
    $port = $data['port'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_URL, 'https://api.twitch.tv/helix/webhooks/subscriptions?first=10');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string),
    'Client-ID: '.$clientId
    ));

    //Exec ch, receive a JSON decoded result on success, FALSE otherwise.
    $jsonUserObj = curl_exec($ch);
    file_put_contents('webhook_log_1.txt', $jsonUserObj, FILE_APPEND);
    //Successfully received a response.
    if($jsonUserObj !== FALSE){
        //JSON decode $result.
        $subscription_data = json_decode($jsonUserObj);
        file_put_contents('webhook_log_1.txt', $subscription_data, FILE_APPEND);
    }
}

function notifySocketClient(){

}

?>