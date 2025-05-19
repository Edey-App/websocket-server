<!DOCTYPE html>
<html>
<head>
    <title>WebSocket Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.min.js"></script>
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f7f7f7;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .message-box {
            height: 300px;
            overflow-y: scroll;
            padding: 15px;
            background: white;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            border-radius: 3px;
        }
        .message-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-bottom: 10px;
        }
        .send-button {
            padding: 10px 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .message {
            margin-bottom: 10px;
            padding: 8px;
            background: #e9f5ff;
            border-radius: 3px;
        }
        .connection-status {
            margin-bottom: 15px;
            padding: 5px 10px;
            border-radius: 3px;
            display: inline-block;
        }
        .connected {
            background: #d4edda;
            color: #155724;
        }
        .disconnected {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Laravel WebSockets Test</h1>
    
    <div class="connection-status disconnected" id="status">Disconnected</div>
    
    <div class="message-box" id="messages"></div>
    
    <input type="text" class="message-input" id="message" placeholder="Type a message...">
    <button class="send-button" id="send">Send Message</button>
</div>

<script>
    $(document).ready(function() {
        // Pusher configuration
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            wsHost: '{{ env('PUSHER_HOST') }}',
            wsPort: {{ env('PUSHER_PORT') }},
            wssPort: {{ env('PUSHER_PORT') }},
            wsPath: null,
            disableStats: true,
            // authEndpoint: '/broadcasting/auth',
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });
        
        // Connection status updates
        pusher.connection.bind('connected', function() {
            $('#status').removeClass('disconnected').addClass('connected').text('Connected');
            addMessage('System', 'Connected to WebSocket server!');
        });
        
        pusher.connection.bind('disconnected', function() {
            $('#status').removeClass('connected').addClass('disconnected').text('Disconnected');
            addMessage('System', 'Disconnected from WebSocket server!');
        });
        
        pusher.connection.bind('error', function(err) {
            addMessage('Error', 'WebSocket error: ' + JSON.stringify(err));
        });
        
        // Subscribe to test channel
        const channel = pusher.subscribe('test-channel');
        
        channel.bind('App\\Events\\TestMessage', function(data) {
            addMessage('Received', data.message);
        });
        
        // Send message functionality
        $('#send').click(function() {
            const message = $('#message').val();
            if (message.trim() !== '') {
                $.ajax({
                    url: '/send-websocket-message',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        message: message
                    },
                    success: function() {
                        addMessage('Sent', message);
                        $('#message').val('');
                    },
                    error: function(error) {
                        addMessage('Error', 'Failed to send message: ' + JSON.stringify(error));
                    }
                });
            }
        });
        
        // Enter key to send message
        $('#message').keypress(function(e) {
            if (e.which === 13) {
                $('#send').click();
                return false;
            }
        });
        
        // Helper function to add messages to the message box
        function addMessage(type, message) {
            const time = new Date().toLocaleTimeString();
            $('#messages').append(
                `<div class="message">
                    <strong>${type} (${time}):</strong><br>
                    ${message}
                </div>`
            );
            
            // Auto-scroll to bottom
            const messageBox = document.getElementById('messages');
            messageBox.scrollTop = messageBox.scrollHeight;
        }
    });
</script>
</body>
</html>