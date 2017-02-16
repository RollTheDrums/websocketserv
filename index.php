<?php ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<style>
    .wrapper {
        max-width: 1000px;
        min-height: 500px;
        margin: 50px auto;
        border: 1px solid black;
        position: relative;
    }
    .conv {
        min-height: 400px;
        max-height: 400px;
        overflow: auto;
    }
    .bottom {
        width: 100%;
        position: absolute;
        bottom: 0;
        text-align: center;
    }
</style>


<body>
    <div class="wrapper">
        <div class="conv"></div>
        <div class="bottom">
            <textarea class="typewriter" cols="100" rows="5"></textarea>
            <button type="submit" class="sendmsg">Send msg</button>
        </div>
    </div>
</body>
</html>

<script type="text/javascript">

    var socket;
    var counter = 0;
    function init() {
        var host = "ws://localhost:1111/"; // SET THIS TO YOUR SERVER
        try
        {
            socket = new WebSocket(host);
            console.log('WebSocket - status ' + socket.readyState);

            socket.onopen = function(msg) {
                if(this.readyState == 1) {
                    console.log("We are now connected to websocket server. readyState = " + this.readyState);
                }
            };

            socket.onmessage = function(msg) {
                $('.conv').html(counter++)
            };

            socket.onclose = function(msg) {
                console.log("Disconnected - status " + this.readyState);
            };

            socket.onerror = function(e) {
                console.log(e);
            }
        }

        catch(ex)
        {
            console.log('Some exception : '  + ex);
        }

    }


    $(document).ready(function () {
        init();
    });


    $('.sendmsg').on('click', function () {
        socket.send($('.typewriter').val());
        $('.typewriter').val('');
    });
</script>
