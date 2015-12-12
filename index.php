<?php

require_once 'include/DbHandler.php';
require_once 'include/PassHash.php';
require './libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$user_id = NULL;

$app->get('/', function() use ($app) {
    $app->render('login.php', array('title' => 'Login'));    
});

$app->get('/home', function() use ($app) {
    $app->render('home.php', array('title' => 'Home'));    
});

$app->get('/category', function() use ($app) {
    $app->render('category.php', array('title' => 'Home'));    
});

$app->run();
?>
