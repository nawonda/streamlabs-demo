<?php
/*
Subscription Verify Request (from Twitch to Client)
GET https://yourwebsite.com/path/to/callback/handler? \
hub.mode=subscribe& \
hub.topic=https://api.twitch.tv/helix/users/follows?first=1&to_id=1337& \
hub.lease_seconds=864000& \
hub.challenge=HzSGH_h04Cgl6VbDJm7IyXSNSlrhaLvBi9eft3bw
*/

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers:*");
ini_set("allow_url_fopen", true);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $printString = "";
    
    foreach ($_GET as $key => $value) {
        
        $printString = $printString  . "Key: " . $key . " Val: " . trim($value) . "\n";
    }
    
    file_put_contents("webhook_log.txt", $printString, FILE_APPEND);
    
    if (isset($_GET['hub_challenge'])) {
        $challenge = $_GET['hub_challenge'];
        echo trim($challenge);
        file_put_contents("webhook_log.txt", "<<<return = ".trim($challenge).">>>\n", FILE_APPEND);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = "POST received\n";
    //$payload = file_get_contents('php://input');
    file_put_contents("webhook_log.txt", $payload,FILE_APPEND);
}

?>