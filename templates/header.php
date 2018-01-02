<?php

header('Content-type: text/html; charset=utf-8');

?><!DOCTYPE html>
<html>
	<head>
		<base href="/">

		<script type="text/javascript" src="lib/jquery/dist/jquery.min.js"></script>
		<script type="text/javascript" src="lib/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="lib/barista/dist/js/barista.js"></script>
		<script type="text/javascript" src="js/script.js"></script>

		<link rel="stylesheet" type="text/css" href="lib/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="lib/barista/dist/css/barista.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<header class="navbar orange">
			<div class="wrapper">
				<div class="icon">
					<a href="/"><i class="fa fa-comments"></i> Polyglot</a>
				</div>
				<?php if ($user = get_session_user()) : ?>
					<div id="main-menu">
						<ul class="nav">
							<li>
								<a href="projects">
									<i class="fa fa-folder-open"></i>
									<span class="navlbl">Projects</span>
								</a>
							</li>
							<?php if ($user->admin) : ?>
								<li>
									<a href="languages">
										<i class="fa fa-flag"></i>
										<span class="navlbl">Languages</span>
									</a>
								</li>
								<li>
									<a href="users">
										<i class="fa fa-user"></i>
										<span class="navlbl">Users</span>
									</a>
								</li>
								<li>
									<a href="roles">
										<i class="fa fa-group"></i>
										<span class="navlbl">Roles</span>
									</a>
								</li>
							<?php endif; ?>
						</ul>
						<ul class="nav right">
							<li>
								<a data-action="menu">
									<i class="fa fa-user"></i>
									<span class="navlbl">Hello, <?php echo $user->display_name; ?></span>
									<i class="caret"></i>
								</a>
								<ul>
									<li><a href="logout"><i class="fa fa-sign-out"></i> Sign Out</a></li>
								</ul>
							</li>
						</ul>
					</div>
					<a class="icon more" data-action="toggle" data-target="#main-menu">
						<i class="fa fa-bars"></i>
					</a>
				<?php endif; ?>
			</div>
	    </header>
		<article class="wrapper">
