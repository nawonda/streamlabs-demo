<?php
    //A very very simple websocket server 
    $original_json_array = json_decode(file_get_contents('./_config.txt'), true);     
    $data = $original_json_array["dev"];
    $url = $data['url'];
    $address = preg_replace('#^https?://#', '', rtrim($url,'/'));

    $port = '1222';

    // Create WebSocket.
    $server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);


    if (!is_resource($server))
	console("socket_create() failed: ".socket_strerror(socket_last_error()), true);

    if (!socket_bind($server, $address, $port))
        console("socket_bind() failed: ".socket_strerror(socket_last_error()), true);

    if(!socket_listen($server, 20))
        console("socket_listen() failed: ".socket_strerror(socket_last_error()), true);

    
    $client = socket_accept($server);

    // Send WebSocket handshake headers.
    $request = socket_read($client, 5000);
    preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
    $key = base64_encode(pack(
        'H*',
        sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
    ));
    
    $headers = "HTTP/1.1 101 Switching Protocols\r\n";
    $headers .= "Upgrade: websocket\r\n";
    $headers .= "Connection: Upgrade\r\n";
    $headers .= "Sec-WebSocket-Version: 13\r\n";
    $headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
    socket_write($client, $headers, strlen($headers));

    // Send messages into WebSocket in a loop.
    while (true) {
        sleep(1);
        $content = 'Now: ' . time();
        $response = chr(129) . chr(strlen($content)) . $content;
        socket_write($client, $response);
    }

    function console($text){
        $File = "log.txt"; 
        $Handle = fopen($File, 'a');
        fwrite($Handle, $text); 
        fclose($Handle);
    }