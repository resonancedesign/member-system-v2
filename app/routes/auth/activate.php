<?php

$app->get('/active', $guest(), function() use($app) {
	
	$request = $app->request;

	$email = $request->get('email');
	$ident = $request->get('ident');

	$hashedIdent = $app->hash->hash($ident);

	$user = $app->user->where('email', $email)
		->where('active', false)
		->first();

	if (!$user || !$app->hash->hashCheck($user->active_hash, $hashedIdent)) {
		$app->flash('global', 'There was a problem activating your account.');
		$app->response->redirect($app->urlFor('home'));
	} else {
		$user->activateAccount();

		$app->flash('global', 'Your account has been activated and you can now sign in.');
		$app->response->redirect($app->urlFor('home'));
	}

})->name('activate');