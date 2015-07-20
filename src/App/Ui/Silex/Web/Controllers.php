<?php

$controller = function ($controller) {
    return ''
        . 'App\Ui\Silex\Web\Controller\\'
        . $controller
        . '::__invoke';
};

// Web
$app
    ->get('/', $controller('Web\Home'))
    ->bind('home');

$app
    ->get('/u/{filename}', function ($filename) use ($app) {
        return $app->sendFile(BASE_DIR . 'var/uploads/' . $filename);
    })
    ->bind('download');


// Api
$app
    ->get('/api/photos', $controller('Api\Photos\GetCollection'))
    ->bind('api:photos:get:collection');

$app
    ->get('/api/photos/{id}', $controller('Api\Photos\GetResource'))
    ->bind('api:photos:get:resource');

$app
    ->post('/api/photos', $controller('Api\Photos\PostResource'))
    ->bind('api:photos:post:resource');
