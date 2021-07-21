<!DOCTYPE HTML>
<html>
	<head>
		<!-- Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-175462372-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-175462372-1');
		</script>

		<!-- Dynamic Title -->
		<title>GoodBot {{ !empty($server) ? ' | ' . $server->name : '' }}</title>
		
		<!-- meta/viewport -->
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

		<!-- Main CSS -->
		<link rel="stylesheet" href="/assets/css/main.css" />
		<noscript><link rel="stylesheet" href="/assets/css/noscript.css" /></noscript>
		
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

		<!-- jQuery UI -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		
		<!-- jQuery Timepicker -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">

		<!-- jQuery Spectrum (color picker) -->
		<link rel="stylesheet" href="/assets/js/spectrum/spectrum.css">
       
	   	<!-- Custom CSS -->
		<link rel="stylesheet" href="/assets/css/goodbot.css">

		@if (session()->get('darkmode'))
			<!-- Darkmode CSS -->
			<link rel="stylesheet" href="/assets/css/darkmode.css">
		@endif

		<!-- Additional Styles -->
		@yield('style')
	</head>
	<body class="index is-preload">
		<div id="page-wrapper">
			<!-- Header -->
			<style>
				li.current {
					position: relative;
				}
				.header-button {
					position: absolute;
					background: #772200;
					top: -30px;
					right: 0;
					line-height: 30px;
				}
				#characterSearch {
					height: 32px;
					padding: 0 15px;
				}
			</style>
			<header id="header" class="alt">
				<h1 id="logo"><a href="/">GoodBot</a></h1>
				<nav id="nav">
					<ul>
					<li class="current">
						<input id="characterSearch" placeholder="Character Name" />
						<button class="header-button" onclick="characterSearch();"><span class="icon solid fa-search"></span></button>
					</li>
					<li class="current"><a href="/">Home</a></li>
					<li class="current"><a class="button primary" target="_blank" href="https://discordapp.com/oauth2/authorize?client_id=525115228686516244&permissions=8&scope=bot">Add GoodBot</a></li>
						@if (empty(session()->get('user')))
							<li><a href="/characters" class="button">Sign In</a></li>
						@else
							<li class="submenu">
								<a href="#" class="">Welcome, {{ session()->get('user')->username }}</a>
								<ul>
								<li><a href="/dashboard">Servers</a></li>
								<li><a href="/raids">Raids</a></li>
								<li><a href="/characters">Characters</a></li>
								<li>
									@if (!session()->get('darkmode'))
									<a href="/darkmode">Dark Mode</a>
									@else
									<a href="/darkmode">Light Mode</a>
									@endif
								</li>
									<li><a href="/logout">Log Out</a></li>
								</ul>
							</li>
						@endif

					</ul>
				</nav>
			</header>

			<!-- Banner -->
			<section id="banner"></section>

			<!-- Main -->
			<article id="main">
			@if (!empty($server))
			<header class="special container">
				<img style="border-radius: 64px;" src="https://cdn.discordapp.com/icons/{{ $server->id }}/{{ $server->icon }}.png" />
				<h2>{{ $server->name }}</h2>
			</header>
			@endif
			@yield('content')

			<!-- Footer -->
			<footer id="footer">
				<ul class="copyright">
					<li>&copy; {{ date('Y') }} GoodBot</li>
				</ul>
			</footer>
		</div>

		<!-- jQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

		<!-- Scripts -->
		<script src="/assets/js/jquery.dropotron.min.js"></script>
		<script src="/assets/js/jquery.scrolly.min.js"></script>
		<script src="/assets/js/jquery.scrollex.min.js"></script>
		<script src="/assets/js/browser.min.js"></script>
		<script src="/assets/js/breakpoints.min.js"></script>
		<script src="/assets/js/util.js"></script>
		<script src="/assets/js/main.js"></script>

		<!-- Custom Scripts -->
		<script src="/assets/js/goodbot.js"></script>

		<!-- jQuery UI -->
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

		<!-- jQuery Timepicker -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>

		<!-- jQuery Spectrum (Color Picker) -->
		<script src="/assets/js/spectrum/spectrum.js"></script>

		<!-- jQuery Validate -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

		<!-- jQuery TableSorter -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
		@yield('scripts')
		<script>
			function characterSearch() {
				var character = $('#characterSearch').val();
				window.location = '/characters/search/' + character
			}
			$(window).ready(function() {
				$('#characterSearch').on('keyup', function(e) {
					if (e.keyCode == 13) {
						characterSearch();
					}
				});
				$('.date').datepicker({'dateFormat': 'yy-mm-dd'});
				$('.time').timepicker();
				$('.spectrum').spectrum({preferredFormat: "hex"});
				$('#raid-form').validate({
					submitHandler: goodbot.raid.save
				});
			});
		</script>
	</body>
</html>