<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// API 1 : enregistre ip, user_agent, mobile, senderid, sms dans le log
$router->post('/sms/log', 'SmsLogController@store');
$router->get('/sms/log', 'SmsLogController@store');

// API 2 : lit (JSON/texte) ou télécharge (?download=1) le dernier fichier de log
$router->get('/sms/log/latest', 'SmsLogController@latest');
