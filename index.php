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
	$ch_1 = curl_init();
	curl_setopt($ch_1, CURLOPT_URL, "/ad.php");
	curl_setopt($ch_1, CURLOPT_HEADER, 0);
	curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, true);

	$output = curl_exec($ch_1);
	curl_close($ch_1);
	echo $output;
?>

</html>
