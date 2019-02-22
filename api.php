<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $clientId = 'rrgh6zsu4jmubowaczix3te2a51tmq';

    if (!isset($_GET['code'])) {
        header('Location: https://quiet-falls-57041.herokuapp.com');
    }else{
        try {            
            $original_json_array = json_decode(file_get_contents('./_access.txt'), true); 

            $accessToken = $original_json_array[$_GET['code']];
    
            if( $_GET['api'] == 'userInfo' ){
                
                getUserInfo($accessToken, $clientId);

            }elseif( $_GET['api'] == 'showStreamers' ){

                showStreamers($accessToken, $clientId);   

            }elseif( $_GET['api'] == 'followStreamer' ){
                $channelId = $_GET['channelId'];
                followStreamer($accessToken, $clientId, $channelId);

            }else{
                
                echo "please use valid API call";

            }
        } catch (Exception $e) {
            exit('Caught exception: '.$e->getMessage());            
        }                
    }

    function getUserInfo($accessToken, $clientId){        
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "https://api.twitch.tv/kraken/user");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(            
            'Authorization: OAuth '.$accessToken,
            'Accept: application/vnd.twitchtv.v5+json',
            'Content-Type: application/json',
            'Client-ID: '.$clientId
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        // echo $response;
    }
    
    function showStreamers($accessToken, $clientId){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "https://api.twitch.tv/kraken/streams/followed");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(            
            'Authorization: OAuth '.$accessToken,
            'Accept: application/vnd.twitchtv.v5+json',
            'Content-Type: application/json',
            'Client-ID: '.$clientId
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        // echo $response;        
    }

    function followStreamer($accessToken, $clientId, $channelId){
        $ch = curl_init();
        // curl_setopt($ch,CURLOPT_URL, "https://api.twitch.tv/kraken/users/< User ID >/follows/channels/< Channel ID>");
        // channel ids = 5690948, 62597620, 59635827, 7855103, 55782451...
        curl_setopt($ch,CURLOPT_URL, "https://api.twitch.tv/kraken/users/417689348/follows/channels/".$channelId);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: OAuth '.$accessToken,
            'Accept: application/vnd.twitchtv.v5+json',
            'Content-Type: application/json',
            'Client-ID: '.$clientId
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        // echo $response;
    }
    
?>