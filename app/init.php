<?php
// Slim name-spaces
use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
// Config name-spaces
use Noodlehaus\Config;
// RandomLib name-spaces
use RandomLib\Factory as RandomLib;
// Custom name-spaces
use ResDesMS2\User\User;
use ResDesMS2\Mail\Mailer;
use ResDesMS2\Helpers\Hash;
use ResDesMS2\Validation\Validator;

use ResDesMS2\Middleware\BeforeMiddleware;

session_cache_limiter(false);
session_start();

ini_set('display_errors', 'On');

define('INC_ROOT', dirname(__DIR__));

require INC_ROOT . '/vendor/autoload.php';
require INC_ROOT . '/extensions/autoload.php';

$app = new Slim([
	'mode' => file_get_contents(INC_ROOT . '/mode.php'),
	'view' => new Twig(),
	'templates.path' => INC_ROOT . '/app/views'
]);

$app->add(new BeforeMiddleware);

$app->configureMode($app->config('mode'), function() use($app) {
	$app->config = Config::load(INC_ROOT . "/app/config/{$app->mode}.php");
});

require 'database.php';
require 'filters.php';
require 'routes.php';

$app->auth = false;

$app->container->set('user', function(){
	return new User;
});

$app->container->singleton('hash', function() use ($app) {
	return new Hash($app->config);
});

$app->container->singleton('validation', function() use ($app) {
	return new Validator($app->user);
});

$app->container->singleton('mail', function() use ($app) {
	$mailer = new PHPMailer;

	$mailer->IsSMTP();
	$mailer->Host = $app->config->get('mail.host');
	$mailer->SMTPAuth = $app->config->get('mail.smtp_auth');
	$mailer->SMTPSecure = $app->config->get('mail.smtp_secure');
	$mailer->Port = $app->config->get('mail.port');
	$mailer->Username = $app->config->get('mail.username');
	$mailer->Password = $app->config->get('mail.password');

	$mailer->isHTML($app->config->get('mail.html'));

	return new Mailer($app->view, $mailer);
});

$app->container->singleton('randomlib', function() {
	$factory = new RandomLib;
	return $factory->getMediumStrengthGenerator();
});

$view = $app->view();

$view->parserOptions = [
	'debug' => $app->config->get('twig.debug')
];

$view->parserExtensions = [
	new TwigExtension
];