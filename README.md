# Enpii Base plugin
We understand the pains it brings to the developers so we create this plugin to allow to use [Laravel framework](https://laravel.com/)

This plugin would bring all the features of Laravel framework to WordPress development: Container concepts, Service Providers, ORMs, Queue system, Routing system ... (everything that works with Laravel will work with WordPress with Enpii Base plugin)

Imagine, you can do this for the iconic template file `index.php` (using Blade template syntax)
```html
@extends('layouts/main')

@section('content')
	<h1><?php echo 'WP App'; ?></h1>
	<p>{{ 'Welcome to WP App from Enpii Base' }}</p>
@endsection
```
and on `layouts/main.blade.php`
```html
<html>
<body>
	<main class="site-body">
		@yield('content')
	</main>
</body>
</html>
```

Interesting!? Let's get started

## Installation
```
composer require enpii/enpii-base
```
or you can find it on WordPress plugin hub

Docs are here:
1. [Basic Concepts](dev-docs/01-basic-concepts.md)
2. We log our blockers, solutions and crazy things in [Development Logs](dev-docs/02-development-logs.md)
3. [Development Guides](dev-docs/03-development-guides.md)

## License

The Enpii Base plugin is open-sourced software licensed under the [MIT license](LICENSE.md).

[Trac Nguyen](mailto:npbtrac@yahoo.com) since
