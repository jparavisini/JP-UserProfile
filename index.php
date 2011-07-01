<?php 
require_once('bootstrap.php');

?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<title><?=$page_title.' | '.$site_title?></title>
	<meta charset="utf-8">
	<style>
	body,html {
		font-family: "Gill Sans", Sans-Serif;
		font-size: 14px;
		color: #443937;
		background-color: #E8E8E8;
		margin: 0;
		padding: 0;
	}
	body > section {
		min-height: 400px;
		width: 100%;
		border-bottom: 30px solid #443937;
		background-color: #EEE7BC;
	}
	article {
		width: 960px;
		margin: 0 auto;
	}
	header {
		background-color: #F1592A;
		padding: 10px;
	}
	header h1 {
		margin: 10px auto;
		width: 150px;
		font-color: 
	}
	nav {
		width: 100%;
		background-color: #F1592A;
		height: 30px;
	}
	nav > ul {
		width: 960px;
		margin: 0 auto;
		
	}
	nav > ul > li{
		padding: 20px;
		float: left;
		font-size: 18px;
		list-style-type: none;
		font-weight: bold;
	}
	
	nav a:link, nav a:visited, nav a:hover, nav a:active {

	text-decoration: none;
	}
	footer {
		width: 100%;
		height: 100px;
		border-top: 10px solid #FFEB00;
	}
	footer p {
		color: #989898;
		
		width: 960px;
		margin: 20px auto;
		font-family: Georgia, Serif;
		font-size: 10pt;
	}
	h1 {
		font-size: 14px;
	}
	h2 {
		font-size: 12px;
	}
	h3 {
		font-size: 10px;
	}
	a:link, a:visited, a:hover, a:active {
		color: #443937;
	}
	.clear {
		clear:both;
	}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	$(document).ready(function(){
		
	});
	</script>
	</head>
	<body>
		<section>
			<header>
				<h1><?=$site_title?></h1>
				<nav>
					<ul>
					<li><a href="/userprofile">Home</a></li>
					<li><a href="/userprofile/new">Create New Profile</a></li>
					<li><a href="/userprofile/edit">Edit Profile</a></li>
					<li><a href="/userprofile/delete">Delete Profile</a></li>
					</ul>
				</nav><br class="clear">
			</header>
			<article>
			<h1><?=$page_title?></h1>
			<?=$content?>
			</article>
		</section>
			
		<footer>
			<p>Copyright &copy; 2113 Joe Paravisini. All Rights Reserved.</p>
		</footer>
	</body>
</html>