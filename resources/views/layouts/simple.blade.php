<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport"
			content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<title>{{ enpii_base_wp_app_web_page_title() }}</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	
	<style id="simple-layout-css" type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&display=swap');html{font-size:10px;line-height:1.5}body{margin:0;padding:0;font-family:"Plus Jakarta Sans",sans-serif}a{text-decoration:none}.site-toolbar{padding:1.2rem 2rem;display:flex;font-size:1.2rem;background:#1f2245;color:#e1e1e1;justify-content:space-between;align-items:center}.site-toolbar a,.site-toolbar h2{color:inherit}.site-toolbar a:hover{color:#e6b420}.site-toolbar .guide-link{padding:4px}.site-body{font-size:1.6rem;padding:1em 0}.site-body h1,.site-body h2{font-weight:800}.site-body h1,.site-body h2,.site-body h3,.site-body h4,.site-body h5,.site-body h6{color:#353859;line-height:1.2}.site-body h1{font-size:3rem}.site-body a{color:#353859}.site-body a:hover{color:#e6b420}.container{width:96vw;margin:0 auto;max-width:980px}.message-content{line-height:1.5}.message-content br{height:0.8rem;display:block;content:''}
	</style>
</head>

<body>
	@include('enpii-base::layouts/blocks/site-toolbar')
	<main class="site-body">
		@yield('content')
	</main>
</body>
</html>
