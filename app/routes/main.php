<?php

$app->get('/', function() use($app) {
	$app->render('main.php');
})->name('home');