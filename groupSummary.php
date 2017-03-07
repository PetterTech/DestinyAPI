<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="main.css"
	<meta charset="utf-8">
</head>

<body>
	<form action="groupSummary.php" method="GET">
	<input name="clanname" type="text" placeholder="clan name">
	<input id="submit" type="submit" value="Search">
	</form>
	<br>

<?php

$apiKey = '072ed475fb5443bd9d5b619dd9372b95';
//$groupid = '1501516';
$clanname = $_GET['clanname'];

if (!isset($clanname)) {
	echo "<p>Input clan name and click search</p>";
	}

else {

	/*
		Declaring function
	*/
		
	function get_bungie($url) {
		global $apiKey;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));
		$json = json_decode(curl_exec($ch));
		return($json->Response);
	}

	/*
	Pulling all the data to be used globally
	*/

	$clan = get_bungie("https://www.bungie.net/platform/Group/Name/$clanname");
	$groupid = $clan->detail->groupId;
	
	//error if groupid is empty
	if (!isset($groupid)) {echo "<h1>Clan not found</h1><p>The supplied clan name was: " . $clanname;}
	
	//continue if not
	else {
		$membersofgroup = get_bungie("https://www.bungie.net/platform/Group/$groupid/ClanMembers/?currentPage=1&platformType=2");
		$numberofmembers = count($membersofgroup->results);
		
		/* 
			Showing some info on the webpage
		*/

		echo "<div class=clanName>" . $clan->detail->name . "</div>";
		
		//BungieTime
		date_default_timezone_set("America/Los_Angeles");
		$BungieTime = date("h:ia");
		echo "<div class=BungieTime>Bungie time (PST): " . $BungieTime . "</div>";

		//Header img
		echo "<div class=headerImg>";
		echo "<img src=https://www.bungie.net" . $clan->detail->bannerPath . " style=max-width:100%>";
		echo "</div>";
		
		//Intro text
		echo "<p>The clan " . $clan->detail->name . " was founded by " . $clan->founder->displayName . " at " . $clan->detail->creationDate . ". It now has " . $numberofmembers . " members. Those members are: </p>";

		echo "<p>";
		for ($i = 0; $i < $numberofmembers; $i++) {
				if (isset($membersofgroup->results[$i]->destinyUserInfo->displayName)) {
					$users[$i] = $membersofgroup->results[$i]->destinyUserInfo->displayName;
					echo " " . $membersofgroup->results[$i]->destinyUserInfo->displayName . ",";
				}
			}
		echo "</p>";
			
		echo "<br>";
		echo "<p>Here is some info on the members:</p>";
		echo "<br>";

		/*
			Grabbing info on each user
		*/


		for ($i = 0; $i < $numberofmembers; $i++) {
				if (isset($membersofgroup->results[$i]->destinyUserInfo->displayName)) {
					$membername = $membersofgroup->results[$i]->destinyUserInfo->displayName;
					$ch_1 = curl_init();
					curl_setopt($ch_1, CURLOPT_URL, "http://api.kemta.net/destiny/user.php?username=$membername");
					curl_setopt($ch_1, CURLOPT_HEADER, 0);
					curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, true);

					$output = curl_exec($ch_1);
					curl_close($ch_1);
					echo "<div class=player>";
					echo $output;
					echo "</div>";
				}
			}
		}
	}	
?>


</body>
</html>
