<!DOCTYPE HTML>
<html>
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-175462372-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-175462372-1');
		</script>
		<title>GoodBot</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="/assets/css/main.css" />
		<noscript><link rel="stylesheet" href="/assets/css/noscript.css" /></noscript>
		
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<!-- JQuery UI -->
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

		<style>
			body {
				background: #222;
			}
			.row { 
				margin: 0!important;
			}
			.row .col-6 {
				padding:0 25px!important;
			}
			.wrapper.style2, .wrapper.style3 {
				background: #101;
				border: solid 1px #c90;
			}
			#header, #header.alt, #footer {
				background: #101;
				color: #CCC;
			}
			#footer {
				margin-top: 5em;
			}
			#banner {
				background: #101!important;
			}
			#header.alt {
				padding: 2.75em 3em;
			}
			#header.alt nav {
				top: 2.5em;
			}
			h1#logo {
				color: #CCC;
			}
			.container {
				margin: 2em auto 0;
				min-height: auto;
			}
			table {
				border: solid 1px #333;
				margin-bottom: 0!important;
			}
			table td {
				background: #101!important;
			}
			table th, table td a {
				color: #CCC;
			}
			h2 {
				color: #CCC;
			}
			#main section {
				color: #CCC;
			}
			.button.primary {
				background: #CC9900;
				border: solid 1px #CCC;
			}
			#nav .button.primary:hover {
				background: #101010!important;
				border: solid 1px #CC9900!important;
			}
			.dropotron {
				background: #CC9900;
				color: #CCC;
				z-index: 10000!important;
			}
			section.wrapper {
				padding: 1em;
			}
			.image:before {
				background: none!important;
			}
			.image.featured {
				min-height: auto!important;
			}
		</style>
	</head>
	<body class="index is-preload">
		<div id="page-wrapper">

			<!-- Header -->
				<header id="header" class="alt">
					<h1 id="logo"><a href="/">GoodBot</a></h1>
					<nav id="nav">
						<ul>
                        <li class="current"><a href="/">Home</a></li>
                        <li class="current"><a href="http://discord.goodbot.me">Support</a></li>
                        <li class="current"><a class="button primary" target="_blank" href="https://discordapp.com/oauth2/authorize?client_id=525115228686516244&permissions=8&scope=bot">Add GoodBot</a></li>
						@if (!empty(session()->get('user')))
							<li><a href="/logout" class="button">Log Out</a></li>
						@endif
					</nav>
				</header>

			<!-- Banner -->
				<section id="banner">



				</section>

			<!-- Main -->
				<article id="main">

				@yield('content')

			<!-- Footer -->
				<footer id="footer">

					<ul class="copyright">
						<li>&copy; {{ date('Y') }} GoodBot</li>
					</ul>

				</footer>

		</div>

		<!-- Scripts -->
			<script src="/assets/js/jquery.min.js"></script>
			<script src="/assets/js/jquery.dropotron.min.js"></script>
			<script src="/assets/js/jquery.scrolly.min.js"></script>
			<script src="/assets/js/jquery.scrollex.min.js"></script>
			<script src="/assets/js/browser.min.js"></script>
			<script src="/assets/js/breakpoints.min.js"></script>
			<script src="/assets/js/util.js"></script>
			<script src="/assets/js/main.js"></script>

			<!-- JQuery UI -->
			<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

			<!-- TableSorter -->
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
			@yield('scripts')

	</body>
</html>