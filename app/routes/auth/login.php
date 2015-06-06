<?php

use Carbon\Carbon;

$app->get('/login', $guest(), function() use($app) {
	$app->render('auth/login.php');
})->name('login');

$app->post('/login', $guest(), function() use($app) {
	
	$request = $app->request;

	$ident = $request->post('ident');
	$password = $request->post('password');
	$remember = $request->post('remember');

	$v = $app->validation;

	$v->validate([
		'ident' => [$ident, 'required'],
		'password' => [$password, 'required']
	]);

	if ($v->passes()) {
		$user = $app->user
			->where('username', $ident)
			->orWhere('email', $ident)
			->where('active', true)
			->first();

		if ($user && $app->hash->passwordCheck($password, $user->password)) {
			$_SESSION[$app->config->get('auth.session')] = $user->id;

			if ($remember === 'on') {
				$rememberIdent = $app->randomlib->generateString(128);
				$rememberToken = $app->randomlib->generateString(128);

				$user->updateRememberCredentials(
					$rememberIdent,
					$app->hash->hash($rememberToken)
				);

				$app->setCookie(
					$app->config->get("auth.remember"),
					"{$rememberIdent}___{$rememberToken}",
					Carbon::parse('+1 week')->timestamp
				);
			}

			$app->flash('global', 'You have been logged in.');
			$app->response->redirect($app->urlFor('home'));
		} else {
			$app->flash('global', 'Invalid login credentials. Please try again.');
			$app->response->redirect($app->urlFor('login'));
		}
	}

	$app->render('auth/login.php', [
		'errors' => $v->errors(),
		'request' => $request
	]);

})->name('login.post');