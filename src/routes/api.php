<?php

$app->group('/api', function () use ($app) {
    $app->get('/contacts[/{id}]', '\App\controllers\api\Contacts:getContacts');
    $app->post('/contacts', '\App\controllers\api\Contacts:addContact');
    $app->delete('/contacts/{id}', '\App\controllers\api\Contacts:delContact');
});
