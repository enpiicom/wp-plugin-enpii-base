<?php 
/**
 * @package Enpii Base
 */ 
?>

@php
	$locale = config('app.locale');
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>@yield('title')</title>

		<!-- Google Font Import -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

	</head>
	<body style="background-color: #fff; color: #636b6f; font-family: 'Raleway', sans-serif; font-weight: 100; height: 100vh; margin: 0;">
		<div class="flex-center position-ref full-height" style="height: 100vh; align-items: center; display: flex; justify-content: center; position: relative;">
			<div class="code" style="border-right: 2px solid; font-size: 66px; padding: 0 15px; text-align: center;">
				@yield('code')
			</div>

			<div class="message" style="font-size: 24px; text-align: center; padding: 10px;">
				@yield('message')
			</div>
		</div>
	</body>
</html>
