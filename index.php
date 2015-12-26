<?php

require_once 'include/DbHandler.php';
require_once 'include/PassHash.php';
require './libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'slim_session',
    'secret' => 'CHANGE_ME',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));
// User id from db - Global Variable
$user_id = NULL;

// $authenticateForRole = function () {
//     return function () {
//         //Match cookie to existing user with role, else redirect to login page
//         // $app = Slim::getInstance('my_cookie');
//         //
//         // // Check for password in the cookie
//         // if($app->getEncryptedCookie('my_cookie',false) != 'YOUR_PASSWORD'){
//         //
//         //     $app->redirect('/login');
//         // }
//         if (!isset($_SESSION['user'])) {
//           $_SESSION['urlRedirect'] = $app->request()->getPathInfo();
//           $app->flash('error', 'Login required');
//           $app->redirect('/login');
//         }
//     }
// }

$app->get('/', function() use ($app) {
    $app->render('login.php', array('title' => 'Login'));
});

$app->get('/home', function() use ($app) {
    $app->render('home.php', array('title' => 'Home'));
});

$app->get('/add-category', function() use ($app) {
    $app->render('addcat.php', array('title' => 'Category'));
});

$app->get('/view-category', function() use ($app) {
    $app->render('viewcat.php', array('title' => 'Category'));
});

$app->get('/edit-category', function() use ($app) {
    $app->render('editcat.php', array('title' => 'Category'));
});

$app->get('/manage-category', function() use ($app) {
    $app->render('category.php', array('title' => 'Category'));
});

$app->get('/add-article', function() use ($app) {
    $app->render('addmag.php', array('title' => 'Article'));
});

$app->get('/edit-article', function() use ($app) {
    $app->render('editmag.php', array('title' => 'Article'));
});

$app->get('/manage-article', function() use ($app) {
    $app->render('managemag.php', array('title' => 'Article'));
});

$app->get('/view-article', function() use ($app) {
    $app->render('viewarticle.php', array('title' => 'Article'));
});

$app->run();
?>
