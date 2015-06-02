{% if auth %}
	Hello, {{ auth.getFullNameOrUsername }}!
{% endif %}
<ul>
	<li><a href="http://www.resonance-designs.com" target="_blank">Resonance Design</a></li>
	<li><a href="{{ urlFor('home') }}">Member System</a></li>

	{% if auth %}
		<li><a href="#">Profile</a></li>
	{% else %}
		<li><a href="{{ urlFor('login') }}">Login</a></li>
		<li><a href="{{ urlFor('register') }}">Register</a></li>
	{% endif %}
</ul>