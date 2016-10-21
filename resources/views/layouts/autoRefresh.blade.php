<html>
    <head>
        <title>Auto Get and refresh</title>
        <meta http-equiv="refresh" content="20"/>
    </head>
    <body>
        <div>
        will be refreshed in 20 second;
        </div>
        <div>
        @foreach($data as $d)
            {!! $d !!}<br>
        @endforeach
        </div>
    </body>
</html>