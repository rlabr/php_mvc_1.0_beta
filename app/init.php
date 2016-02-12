<?php

use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Noodlehaus\Config;
use rlabr\User\User;
use rlabr\Helpers\Hash;
use rlabr\Validation\Validator;
use rlabr\Middleware\BeforeMiddleware;
use rlabr\Mail\Mailer;

session_cache_limiter(false);
session_start();

ini_set('display_errors', 'On');

define('INC_ROOT', dirname(__DIR__));

require INC_ROOT.'/vendor/autoload.php';


$app = new Slim([
  'mode' => rtrim(file_get_contents(INC_ROOT.'/mode.php')),
  'view' => new Twig(),
  'templates.path' => INC_ROOT.'/app/Views'
]);

$app->add(new BeforeMiddleware);

$app->configureMode($app->config('mode'), function () use ($app) {
    $app->config = Config::load(INC_ROOT."/app/config/{$app->mode}.php");
});

require 'database.php';
require 'routes.php';

$app->auth = false;

$app->container->set('user', function() {
  return new User;
});

$app->container->singleton('hash', function() use($app) {
  return new Hash($app->config);
});

$app->container->singleton('validation', function() use($app) {
  return new Validator($app->user);
});

$app->container->singleton('mail', function() use($app) {
  return new PHPMailer;
  $mailer->IsSMTP();
  $mailer->Host = $app->config->get('email.host');
  $mailer->SMTPAuth = $app->config->get('email.smtp_auth');
  $mailer->SMTPSecure = $app->config->get('email.smtp_secure');
  $mailer->Username = $app->config->get('email.username');
  $mailer->Password = $app->config->get('email.password');
  $mailer->Port = $app->config->get('email.port');
  $mailer->ishtml = $app->config->get('email.html');

  return new Mailer($app->view, $mailer);

});

$view = $app->view();
$view->parserOptions = [
  'debug' => $app->config->get('twig.debug')
];
$view->parserExtensions = [
  new TwigExtension
];
