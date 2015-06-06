<?php

namespace ResDesMS2\Middleware;

use Slim\Middleware;

class BeforeMiddleware extends Middleware
{
	public function call()
	{
		$this->app->hook('slim.before', [$this, 'run']);

		$this->next->call();
	}

	public function run()
	{
		if (isset($_SESSION[$this->app->config->get('auth.session')])) {
			$this->app->auth = $this->app->user->where('id', $_SESSION[$this->app->config->get('auth.session')])->first();
		}

		$this->checkRememberMe();

		$this->app->view->appendData([
			'auth' => $this->app->auth,
			'baseUrl' => $this->app->config->get('app.url')
		]);
	}

	protected function checkRememberMe()
	{
		if ($this->app->getCookie($this->app->config->get('auth.remember')) && !$this->app->auth) {
			$data = $this->app->getCookie($this->app->config->get('auth.remember'));
			$credentials = explode('___', $data);

			$trimData = trim($data);

			if (empty($trimData)) || count($credentials) !== 2) {
				$this->app->response->redirect($this->app->urlFor('home'));
			} else {
				$ident = $credentials[0];
				$token = $this->app->hash->hash($credentials[1]);

				$user = $this->app->user
					->where('remember_ident', $ident)
					->first();

				if ($user) {
					if ($this->app->hash->hashCheck($token, $user->remember_token)) {
						// Log the user in
					} else {
						$user->removeRememberCredentials();
					}
				}
			}
		}	
	}
}