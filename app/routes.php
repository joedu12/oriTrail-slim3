<?php

// Front-End
$app->get('/', \App\PagesControllers\LieuController::class.':home')->setName('home');

$app->get('/lieu', \App\PagesControllers\LieuController::class.':getLieu')->setName('lieu');

$app->get('/signup', \App\PagesControllers\UserController::class.':getSignup')->setName('signup');
$app->post('/signup', \App\PagesControllers\UserController::class.':postSignup')->setName('signup');
$app->get('/signin', \App\PagesControllers\UserController::class.':getSignin')->setName('signin');
$app->post('/signin', \App\PagesControllers\UserController::class.':postSignin')->setName('signin');
$app->get('/signout', \App\PagesControllers\UserController::class.':getSignout')->setName('signout');

// API lieu
$app->get('/api/lieux', \App\ApiControllers\LieuController::class.':getAll');
$app->get('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':get');
$app->get('/api/lieu/search/[{query}]', \App\ApiControllers\LieuController::class.':search');
$app->post('/api/lieu', \App\ApiControllers\LieuController::class.':add');
$app->delete('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':delete');
$app->put('/api/lieu/[{id}]', \App\ApiControllers\LieuController::class.':update');
