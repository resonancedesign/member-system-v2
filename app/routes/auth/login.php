<?php

$app->get('/login', function() use($app) {
	$app->render('auth/login.php');
})->name('login');

$app->post('/login', function() use($app) {
	
	$request = $app->request;

	$ident = $request->post('ident');
	$password = $request->post('password');

	$v = $app->validation;

	$v->validate([
		'ident' => [$ident, 'required'],
		'password' => [$password, 'required']
	]);

	if ($v->passes()) {
		$user = $app->user
			->where('username', $ident)
			->orWhere('email', $ident)
			->first();

		if ($user && $app->hash->passwordCheck($password, $user->password)) {
			$_SESSION[$app->config->get('auth.session')] = $user->id;
			$app->flash('global', 'You have been logged in.');
		} else {
			$app->flash('global', 'Invalid login credentials. Please try again.');
		}

		$app->response->redirect($app->urlFor('login'));
	}

	$app->render('auth/login.php', [
		'errors' => $v->errors(),
		'request' => $request
	]);

})->name('login.post');