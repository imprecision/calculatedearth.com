<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CalculatedEarth</title>
    <!--[if IE]>
	<style type="text/css">
		@import url(inc/style_ie.css);
	</style>
    <![endif]-->
    <link rel="stylesheet" href="<?=CA_URL_ROOT?>inc/style.220317.css" crossorigin="anonymous" media="screen">
	<meta name="description" content="CalculatedEarth. Maps and animations of global sea-level changes.">
	<meta name="keywords" content="calculatedearth, calculated earth, sea level, global warming, co2, temperature, climate, environment, flood, economical, environmentally friendly, wind power, solar power">
	<meta name="robots" content="index, follow">
	<link rel="shortcut icon" href="<?=CA_URL_ROOT?>res/favicon.ico" type="image/x-icon">
</head>
<body>

<header>
	<h1>
		<a href="./">calculated<span style="color: #ffffff;">earth</span>.com</a><?=($zoomed)?'<br />':''?>
	</h1>
	<ul>
		<li><a href="<?=CA_URL_ROOT?>">home</a></li>
		<li><a href="<?=CA_URL_ROOT?>about">about</a></li>
		<li><a href="<?=CA_URL_ROOT?>links">links</a></li>
<?php
if ($zoomed) {
?>
		<li><a title="Click to go back to main map..." href="<?=CA_URL_ROOT?>" class="buttonClose">back ^</a></li>
<?php
}
?>
	</ul>
</header>
