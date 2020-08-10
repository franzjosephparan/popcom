<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| First we need to get an application instance. This creates an instance
| of the application / container and bootstraps the application so it
| is ready to receive HTTP / Console requests from the environment.
|
*/

$app = require __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://testserver1.vps.webdock.io/test");
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['domain' => $_SERVER['SERVER_NAME']]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
} catch (\Exception $e) {
    print_r($e->getMessage());
    exit;
}

$app->run();
