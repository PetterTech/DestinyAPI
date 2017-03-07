<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="main.css"
	<meta charset="utf-8">
</head>

<form action="groupSummary.php" method="GET">
<input name="clanname" type="text" placeholder="Clan name">
<input id="submit" type="submit" value="Search">
</form>

<p>Input clan name above and click search for clan summary</p>

<br>

<h2>OR</h2>

<br>

<form action="stats.php" method="GET">
<input name="username" type="text" placeholder="User">
<input id="submit" type="submit" value="Search">
</form>

<p>Input username above and click search for userstats</p>

<br>
<br>
<br>

<?php
include 'ad.php';
?>

</html>
