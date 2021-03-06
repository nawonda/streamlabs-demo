<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>streamlabs-demo</title>

  <!-- Bootstrap Core CSS -->
  <link href="./css/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="./css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

  <!-- Custom CSS -->
  <link href="./css/stylish-portfolio.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body id="page-top">

<?php        

    require 'twitch.php';

    $original_json_array = json_decode(file_get_contents('./_config.txt'), true); 

    $data = $original_json_array["dev"];
    $url = $data['url'];
    $clientId = $data['clientId'];
    $clientSecret = $data['clientSecret'];
    $port = $data['port'];

    $provider = new TwitchProvider([
        'clientId'                => $clientId,     // The client ID assigned when you created your application
        'clientSecret'            => $clientSecret, // The client secret assigned when you created your application
        'redirectUri'             => $url.$port,  // Your redirect URL you specified when you created your application
        "scopes" => [
            "channel_commercial",
            "channel_editor",
            "channel_subscriptions",
            "user_read",
            "user_follows_edit"
        ],
    ]);
    // If we don't have an authorization code then get one
    if (!isset($_GET['code'])) {
        // Fetch the authorization URL from the provider, and store state in session
        $authorizationUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();
        // Display link to start auth flow
        echo "
        <header class=\"masthead d-flex\">
            <div class=\"container text-center my-auto\">
            <h1 class=\"mb-1\">streamlabs</h1>
            <h3 class=\"mb-5\">
                <em>See you favorite streamer's Twitch events in real-time</em>
            </h3>
            <a class='btn btn-primary btn-xl js-scroll-trigger' href=\"$authorizationUrl\">Login With Your Twitch Account</a>
            </div>
            <div class=\"overlay\"></div>
        </header>
        ";
        exit;
    // Check given state against previously stored one to mitigate CSRF attack
    } elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
        if (isset($_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
        }
        exit('Invalid state');
    } else {
        try {
            
            // Get an access token using authorization code grant.
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Using the access token, get user profile
            $resourceOwner = $provider->getResourceOwner($accessToken);
            $user = $resourceOwner->toArray()['data'][0];            
            
            echo '
            <div class="container">
                <div class="panel panel-info" style="margin-top:30px">
                <div class="panel-heading">
                <h3 class="panel-title">'. htmlspecialchars($user['display_name']) .'</h3>
                </div>
                <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="' . htmlspecialchars($user['profile_image_url']) . '" class="img-circle img-responsive"> </div>
                
                    <div class=" col-md-6 col-lg-6 " style="margin-left:40px"> 
                        <tr>
                            <th>
                                <div class="input-group">
                                    <input class="form-control channelId" type="text" placeholder="Enter Channel ID">
                                    <span class="input-group-btn">
                                        <button type="submit" class="add-streamer-button btn btn-primary mb-2">ADD</button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <table class="table table-user-information">
                            <tbody>
                            <tr>
                                <td>Access Token:</td>
                                <td>' . htmlspecialchars($accessToken->getToken()) . '</td>
                            </tr>
                            <tr>
                                <td>Refresh Token:</td>
                                <td>' . htmlspecialchars($accessToken->getRefreshToken()) . '</td>
                            </tr>
                            <tr>
                                <td>User ID</td>
                                <td>' . htmlspecialchars($user['id']) . '</td>
                            </tr>                   
                            <tr>
                                <td>Bio</td>
                                <td>' . htmlspecialchars($user['description']) . '</td>
                            </tr>
                            </tbody>
                        </table>                                        
                    </div>
                </div>
                </div>                
            </div>
            <div class="temp1"></div>
            </div>
            ';
          echo '';
            
            
            //a hacky way to store access token
            $original_json_array = json_decode(file_get_contents('./_access.txt'), true); 

            $original_json_array[$_GET['code']] = $accessToken->getToken();

            file_put_contents('./_access.txt', json_encode($original_json_array));

        } catch (Exception $e) {
            exit('Caught exception: '.$e->getMessage());
        }
    }  
?>

<script>
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    var code = vars['code'];
    showStreamers();

    $(".add-streamer-button").click(function(){
        var channelId = $(".channelId").val();
        console.log(channelId);
        if(channelId){
            $.ajax({url: "./api.php?code=" + code + "&api=followStreamer&channelId=" + channelId, success: function(result){
                $( ".temp1" ).empty();
                showStreamers();
            }});
        }        
    });

    function showStreamers(){
        $.ajax({url: "./api.php?code=" + code + "&api=showStreamers", success: function(result){

        var temp = JSON.parse(result);
        var streamers = temp["streams"];

        streamers.forEach(function (streamer) {
            console.log(streamer);
            var display_name = streamer['channel']['display_name'];
            var user_id = streamer['_id'];
            var game = streamer['game'];
            var streamer_info = "<div style='margin-top:20px'> User ID = " + user_id + " | Channel ID = <a href='./detail.php?display_name=" + display_name + "&userId=" + user_id + "'>" + display_name  + "</a> | Game = " + game + "</div>";            
            $( ".temp1" ).append(streamer_info);            
        });

            
        }});
    }

   

</script>  
</div>  
</body>

</html>