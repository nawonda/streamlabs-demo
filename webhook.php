<?php
/*
Subscription Verify Request (from Twitch to Client)
GET https://yourwebsite.com/path/to/callback/handler? \
hub.mode=subscribe& \
hub.topic=https://api.twitch.tv/helix/users/follows?first=1&to_id=1337& \
hub.lease_seconds=864000& \
hub.challenge=HzSGH_h04Cgl6VbDJm7IyXSNSlrhaLvBi9eft3bw
*/
// if(isset($_GET['hub_challenge']) && !empty($_GET['hub_challenge'])){
//     $r = $_GET['hub_challenge'];
//     //Subscription Verify Response
//     //HzSGH_h04Cgl6VbDJm7IyXSNSlrhaLvBi9eft3bw
//     echo $r;
//     file_put_contents('webhook_log.txt', $r, FILE_APPEND);
// }

// $data = json_decode( file_get_contents( 'php://input' ), true );

// file_put_contents('webhook_log.txt', $data, FILE_APPEND);

// if(isset($_POST['data'])){
//     $r = $_POST['data'];
//     file_put_contents('webhook_log.txt', "get a call", FILE_APPEND);
//     file_put_contents('webhook_log.txt', $r, FILE_APPEND);
    
//     echo 200;
// }


echo $_GET['hub.challenge'];

file_put_contents('webhook_log.txt', $_GET['hub.challenge'], FILE_APPEND);

$body = file_get_contents('php://input');

file_put_contents('webhook_log.txt', $body, FILE_APPEND);

?>