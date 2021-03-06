{% extends 'templates/default.php' %}

{% block title %}Member System V2 - Login{% endblock %}

{% block content %}
	<p>Login to your account.</p>
	<form action="{{ urlFor('login.post') }}" method="post" autocomplete="off">
		<div>
			<label for="ident">Username/Email</label>
			<input type="text" name="ident" id="ident"{% if request.post('ident') %} value="{{ request.post('ident') }}" {% endif %}>
			{% if errors.first('ident') %} {{ errors.first('ident') }} {% endif %}
		</div>
		<div>
			<label for="password">Password</label>
			<input type="password" name="password" id="password">
			{% if errors.first('password') %} {{ errors.first('password') }} {% endif %}
		</div>
		<div>
			<input type="checkbox" name="remember" id="remember"><label for="remember">Remember me</label>
		</div>
		<div>
			<input type="submit" value="Login">
		</div>
		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
	</form>
{% endblock %}