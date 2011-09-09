<!doctype html>
<html class="no-js">
	<head>
		<meta charset="utf-8">

		<title>Atom - <?php echo (isset($title) and $title) ? $title : 'Elegant &amp; Awesome, Simple &amp; Powerful'; ?></title>
		<meta name="description" content="Atom is a component-based PHP 5.3 library that implements self-contained, scalable and non-blocking development tools and functionality. Its primary goal is performance, without loss of functionality.">
		<meta name="author" content="<?php echo Url::create('humans.txt'); ?>">

		<style>
		@import url(http://fonts.googleapis.com/css?family=Lato);
		body { background:#eee; font: normal normal normal 14px/1.253 Lato, sans-serif; margin:0; min-width:800px; padding:0}
		#fork-me { -moz-transition: opacity 300ms ease-in; -o-transition: opacity 300ms ease-in; -webkit-transition: opacity 300ms ease-in; display: block; height: 149px; left: 0; position: fixed; top: 0; transition: opacity 300ms ease-in; width:149px}
		#fork-me:hover { -khtml-opacity: 0.6; -moz-opacity: 0.6; -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=60); filter:alpha(opacity=60); opacity:0.6 }
		#main { -moz-background-clip: padding; -moz-border-radius:5px; -moz-box-shadow: 0 0 10px #cdcdcd; -moz-transition: all 300ms ease-in; -o-transition: all 300ms ease-in; -webkit-background-clip: padding-box; -webkit-border-radius: 5px; -webkit-box-shadow: 0 0 10px #cdcdcd; -webkit-transition: all 300ms ease-in; background-clip: padding-box; background-color: #fff; border:1px solid #ccc; border-radius: 5px; box-shadow: 0 0 10px #cdcdcd; margin: 50px auto 0; padding: 30px; transition: all 300ms ease-in; width: 700px }
		#main:hover { -moz-box-shadow: 0 0 10px #9a9a9a; -webkit-box-shadow: 0 0 10px #9a9a9a; border-color: #999; box-shadow: 0 0 10px #9a9a9a }
		#main h1 { border-bottom: 1px solid #d0d0d0; font-size: 22px; margin: 0 0 20px; padding:0 0 10px }
		#main h2,h3 { border-bottom: 1px dashed #d0d0d0; margin: 15px 0; padding:0 0 8px }
		#main h3 { font-size: 18px }
		#main p { margin: 10px 0 }
		#main pre { background-color: #f0f0f0; border-left: 1px solid #d8d8d8; border-top: 1px solid #d8d8d8; padding: 10px; text-shadow: 0 1px 0 #fff }
		#main ul { margin: 10px 0; padding: 0 30px }
		#main li { margin:5px 0 }
		#footer { color: #888; font-size: 12px; margin: 20px auto; text-align: center; text-shadow: 0 1px 0 #fff; width:780px}
		#footer p { margin: 3px 0}
		</style>
	</head>
	<body>

		<div id="main" role="main">

<?php echo $content.PHP_EOL; ?>

		</div>

		<div id="footer">
			<p>Page rendered using Atom v<?php echo Version::FULL; ?> in {exec_time} using {mem_usage} of memory.</p>
		</div>

	</body>
</html>