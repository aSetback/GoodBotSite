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
				#character-lookup {
					width: 334px;
					height: 50px;
					position: absolute;
					margin: 0;
					top: 30px;
					left: 50%;
					margin-left: -167px;
				}
				#character-lookup .button {
					font-size: 10px;
					line-height: 10px;
					padding: 10px;
					min-width: 0;
					background: #CC9900;
					position: absolute;
					right: 0;
					top: 0;
					margin: 0;
				}
				#characterSearch {
					position: absolute;
					width: 150px;
					font-size: 12px;
					padding: 1px 5px;
					line-height: 10px;
					height: 32px;
					top: 0;
					border: solid 1px #FFF;
					color: #333;
				}
				
				#characterSearch::placeholder, #characterServer::placeholder {
					color: #000;
					opacity: 1;
					top: 0;
				}
				#characterServer {
					position: absolute;
					left: 150px;
					width: 150px;
					font-size: 12px;
					padding: 1px 5px;
					line-height: 14px;
					height: 32px;
					top: 0;
					border: solid 1px #FFF;
					color: #333;
					text-align: right;
				}
			</style>
			<header id="header" class="alt">
				<h1 id="logo"><a href="/">GoodBot</a></h1>
					<div id="character-lookup">
						<input id="characterSearch" placeholder="Character Name" />
						<select id="characterServer">
							<option value="Anathema">Anathema</option>
							<option value="Arcanite Reaper">Arcanite Reaper</option>
							<option value="Arugal">Arugal</option>
							<option value="Ashkandi">Ashkandi</option>
							<option value="Atiesh">Atiesh</option>
							<option value="Azuresong">Azuresong</option>
							<option value="Benediction">Benediction</option>
							<option value="Bigglesworth">Bigglesworth</option>
							<option value="Blaumeux">Blaumeux</option>
							<option value="Bloodsail Buccaneers">Bloodsail Buccaneers</option>
							<option value="Deviate Delight">Deviate Delight</option>
							<option value="Earthfury">Earthfury</option>
							<option value="Faerlina">Faerlina</option>
							<option value="Fairbanks">Fairbanks</option>
							<option value="Felstriker">Felstriker</option>
							<option value="Grobbulus">Grobbulus</option>
							<option value="Heartseeker">Heartseeker</option>
							<option value="Herod">Herod</option>
							<option value="Incendius">Incendius</option>
							<option value="Kirtonos">Kirtonos</option>
							<option value="Kromcrush">Kromcrush</option>
							<option value="Kurinnaxx">Kurinnaxx</option>
							<option value="Loatheb">Loatheb</option>
							<option value="Mankrik" selected>Mankrik</option>
							<option value="Myzrael">Myzrael</option>
							<option value="Netherwind">Netherwind</option>
							<option value="Old Blanchy">Old Blanchy</option>
							<option value="Pagle">Pagle</option>
							<option value="Rattlegore">Rattlegore</option>
							<option value="Remulos">Remulos</option>
							<option value="Skeram">Skeram</option>
							<option value="Smolderweb">Smolderweb</option>
							<option value="Stalagg">Stalagg</option>
							<option value="Sul'thraze">Sul'thraze</option>
							<option value="Sulfuras">Sulfuras</option>
							<option value="Thalnos">Thalnos</option>
							<option value="Thunderfury">Thunderfury</option>
							<option value="Westfall">Westfall</option>
							<option value="Whitemane">Whitemane</option>
							<option value="Windseeker">Windseeker</option>
							<option value="Yojamba">Yojamba</option>
						</select>
						<button class="button" onclick="characterSearch();"><span class="icon solid fa-search"></span></button>
					</div>
				<nav id="nav">
					<ul>
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
				var server = $('#characterServer').val();
				window.location = '/characters/search/' + character + '/' + server;
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