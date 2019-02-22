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
}


function waitForTwitch(){

}

function notifySocketClient(){

}

?>