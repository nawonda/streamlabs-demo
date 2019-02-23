<html>
    <body onload="init()">
        <!-- Add a placeholder for the Twitch embed -->
        <div id="twitch-embed"></div>
        
        <h3>10 most recent events</h3>
        <div id="envent-list"></div>
        
        <!-- Load the Twitch embed script -->
        <script src="https://embed.twitch.tv/embed/v1.js"></script>

        <!-- Create a Twitch.Embed object that will render within the "twitch-embed" root element. -->
        <script type="text/javascript">
            var vars = {};
            var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                vars[key] = value;
            });
            
            new Twitch.Embed("twitch-embed", {
                width: 854,
                height: 480,
                channel: vars["display_name"],
                // layout: "video",
                // autoplay: false
            });

            embed.addEventListener(Twitch.Embed.VIDEO_READY, () => {
                var player = embed.getPlayer();
                player.play();
            });
        
            // websocket client
            var socket;            

            function init(){
                // var x = window.location.hostname;
                // var loc = window.location;
                // if (loc.protocol === "https:") {
                //     new_uri = "wss:";
                // } else {
                //     new_uri = "ws:";
                // }
                // // new_uri += "//" + loc.hostname;
                // // var host = new_uri + ":1222";
                // // console.log(host);

                var host = "ws://peaceful-retreat-23083.herokuapp.com";

                try{
                socket = new WebSocket(host);
                log('WebSocket - status '+socket.readyState);

                socket.onopen    = function(msg){ log("Welcome - status "+this.readyState); };
                socket.onmessage = function(msg){ log("Received: "+msg.data); };
                socket.onclose   = function(msg){ log("Disconnected - status "+this.readyState); };
                }
                catch(ex){ log(ex); }
                $("msg").focus();
            }

            // Utilities
            function $(id){ return document.getElementById(id); }
            function log(msg){ $("envent-list").innerHTML+="\n"+msg; }
            function onkey(event){ if(event.keyCode==13){ send(); } }
        </script>
    </body>
</html>

<?php    

    $target_user_id = $_GET['userId'];

    $topic = "https://api.twitch.tv/helix/users/follows?first=1&to_id=".$target_user_id;

    webhook($topic);

    $topic = "https://api.twitch.tv/helix/users/follows?first=1&from_id=".$target_user_id;
    webhook($topic);

    $topic = "https://api.twitch.tv/helix/streams?user_id=".$target_user_id;
    webhook($topic);

    $topic = "https://api.twitch.tv/helix/users?id=".$target_user_id;
    webhook($topic);
    
    function webhook($topic){
        echo "webhook accepted state code = ";
        
        $original_json_array = json_decode(file_get_contents('./_config.txt'), true); 
        $data = $original_json_array["dev"];
        $url = $data['url'];
        $clientId = $data['clientId'];
        $clientSecret = $data['clientSecret'];
        $port = $data['port'];

        $mode = "subscribe";
        $callback_url = $url.$port."/webhook.php";
        $target_user_id = $_GET['userId'];
        $lease_seconds = "864000";

        $subscribe_to_event_url = "https://api.twitch.tv/helix/webhooks/hub";

        $data = array(
        'hub.mode' => $mode,
        'hub.topic' => $topic,
        'hub.callback' => $callback_url,
        'hub.lease_seconds' => $lease_seconds
        );
        $data_string = json_encode($data);

        $ch = curl_init($subscribe_to_event_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string),
        'Client-ID: '.$clientId
        ));

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        echo $httpcode."
        ".$result."</br>";
    }   
?>