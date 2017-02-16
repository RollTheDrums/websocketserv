<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<body>
    <button type="submit" class="insert">Insert row</button>
</body>

<script type="text/javascript">



//    var socket;
//    var host = "ws://localhost:9999/"; // SET THIS TO YOUR SERVER
//    function init() {
//        socket = new WebSocket(host);
//        console.log('WebSocket - status ' + socket.readyState);
//
//        socket.onopen = function(msg) {
//            if(this.readyState == 1) {
//                console.log("We are now connected to websocket server. readyState = " + this.readyState);
//            }
//        };
//
//        socket.onmessage = function(msg) {
//            $('body').append("<p class='row'>"+msg.data+"</p>");
//            console.log(msg.data);
//        };
//
//        socket.onclose = function(msg) {
//            console.log("Disconnected - status " + this.readyState);
//        };
//
//        socket.onerror = function(e) {
//            console.log(e);
//        }
//    }


    $(document).ready(function () {
        init();
    });


    $('.insert').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'insert.php',
            data: {
                from_id: 32,
                message_text : ('some text ' + Math.random()),
                message_time: Date.now()
            },
            success: function (data) {

            }
        })
    })
</script>
</html>

