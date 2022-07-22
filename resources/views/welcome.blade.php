<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forum App</title>
</head>

<body>
    @php
        if (env('APP_ENV') == 'local') {
            $typeEnv = 'LOCAL';
        } elseif (env('APP_ENV') == 'production') {
            $typeEnv = 'PRODUCTION';
        } elseif (env('APP_ENV') == 'staging') {
            $typeEnv = 'STAGING';
        } else {
            $typeEnv = 'UNKNOWN';
        }
        echo "<h1>{$typeEnv} - ENVIRONMENT</h1><p>Hello Everyone 👋, the API forum app has been released !</p>";
    @endphp
</body>

</html>
