<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Test</title>
    
</head>
<body>
    <h1>WebSocket Client Test</h1>


</body>
 

    <!-- <script>
        setTimeout(() => {
            window.Echo.channel('uchenna-working')
                .listen('.App\\Events\\testWebsocket', (e) => {
                    console.log('Received event:', e);
                });
        }, 200);
    </script> -->

    <!-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                if (window.Echo) {
                    window.Echo.channel('uchenna-working')
                        .listen('.App\\Events\\testWebsocket', (e) => {
                            console.log('Received event:', e);
                        });
                } else {
                    console.error('window.Echo is undefined');
                }
            }, 200);
        });
    </script> -->

    @vite('resources/js/app.js')
    <script>
        setTimeout(() => {
            console.log('Subscribing to channel: testing');

            window.Echo.channel('testing')
                .listen('.App\\Events\\testWebsocket', (e) => {
                    console.log('Received event:', e);
                })
                .error((error) => {
                    console.error('Error subscribing to channel:', error);
                });
        }, 200);
    </script>
</html>
