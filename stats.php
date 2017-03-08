<!DOCTYPE html>
<html lang="en">
<head>
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="stats.css" <meta charset="utf-8">

    <script>
      $(document).ready(function() {
        $("#pvp1").click(function() {
          $("#pvpdiv1").show();
          $("#pvediv1").hide();
        });
        $("#pve1").click(function() {
          $("#pvediv1").show();
          $("#pvpdiv1").hide();
        });
		
        $("#pvp2").click(function() {
          $("#pvpdiv2").show();
          $("#pvediv2").hide();
        });
        $("#pve2").click(function() {
          $("#pvediv2").show();
          $("#pvpdiv2").hide();
        });		
		
        $("#pvp3").click(function() {
          $("#pvpdiv3").show();
          $("#pvediv3").hide();
        });
        $("#pve3").click(function() {
          $("#pvediv3").show();
          $("#pvpdiv3").hide();
        });		
		
      });
	</script>
</head>
	<body>
		<form action="stats.php" method="GET">
		<input name="username" type="text" placeholder="PSN ID">
		<input id="submit" type="submit" value="Search">
		</form>
		<br>

	<?php
		$apiKey = '072ed475fb5443bd9d5b619dd9372b95';
		$username = $_GET['username'];

		/*
			Checking if username is set
		*/

		if (!isset($username)) {
			echo "<p>Input username and click search</p>";
			}

		else {

			/*
				Declaring functions
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
			
			function writeTableRow($header,$value) {
				echo '					  <tr class="stats">';
				echo '					  <td class="statheader">' . $header . '</td>';
				echo '								<td class="statvalue">' . $value . "</td>";
				echo "					  </tr>";
			}

			/*
				Getting info from Bungie
			*/

			$user = get_bungie("https://www.bungie.net/Platform/Destiny/SearchDestinyPlayer/2/$username/");
			$membershipId = $user[0]->membershipId;

			//Provide error message if username is incorrect
			if (!isset($membershipId)) {echo "<h1>Incorrect username</h1><p>The supplied username was: " . $username;}

			//Continue if user is found
			else {
				$stats = get_bungie("https://www.bungie.net/platform/destiny/Stats/Account/2/$membershipId/");	
				$i = 0;
				
				while ($stats->characters[$i]->deleted === true) {$i++;};
				$char1ID = $stats->characters[$i]->characterId;
				$pveStatsChar1 = $stats->characters[$i]->results->allPvE->allTime;
				$pvpStatsChar1 = $stats->characters[$i]->results->allPvP->allTime;
				$i++;
				
				while ($stats->characters[$i]->deleted === true) {$i++;};
				$char2ID = $stats->characters[$i]->characterId;
				$pveStatsChar2 = $stats->characters[$i]->results->allPvE->allTime;
				$pvpStatsChar2 = $stats->characters[$i]->results->allPvP->allTime;
				$i++;
				
				while ($stats->characters[$i]->deleted === true) {$i++;};
				$char3ID = $stats->characters[$i]->characterId;
				$pveStatsChar3 = $stats->characters[$i]->results->allPvE->allTime;
				$pvpStatsChar3 = $stats->characters[$i]->results->allPvP->allTime;

				
				$char1Summary = get_bungie("https://www.bungie.net/platform/destiny/2/Account/$membershipId/Character/$char1ID/");
				if (!empty($char2ID)) {
					$char2Summary = get_bungie("https://www.bungie.net/platform/destiny/2/Account/$membershipId/Character/$char2ID/");
					if (!empty($char3ID)) {
						$char3Summary = get_bungie("https://www.bungie.net/platform/destiny/2/Account/$membershipId/Character/$char3ID/");
						}
					}
				
				//	Finished gathering info from Bungie

				/*
					Putting together data and variables
				*/

				$baselink = "https://www.bungie.net";

				$femaleHash = '2204441813';
				$maleHash = '3111576190';

				$exoHash = '898834093';
				$humanHash = '3887404748';
				$awokenHash = '2803282938';

				$warlockHash = '2271682572';
				$titanHash = '3655393761';
				$hunterHash = '671679327';
				
				//Finding class of each character
				if ($char1Summary->data->characterBase->classHash == $warlockHash) {
					$char1Class = "Warlock";
					}
					elseif ($char1Summary->data->characterBase->classHash == $titanHash) {
						$char1Class = "Titan";
						}
					elseif ($char1Summary->data->characterBase->classHash == $hunterHash) {
						$char1Class = "Hunter";
						}
				else {
					$char1Class = "Ooops";
					}
					
				if ($char2Summary->data->characterBase->classHash == $warlockHash) {
					$char2Class = "Warlock";
					}
					elseif ($char2Summary->data->characterBase->classHash == $titanHash) {
						$char2Class = "Titan";
						}
					elseif ($char2Summary->data->characterBase->classHash == $hunterHash) {
						$char2Class = "Hunter";
						}
				else {
					$char2Class = "Ooops";
					}
					
				if ($char3Summary->data->characterBase->classHash == $warlockHash) {
					$char3Class = "Warlock";
					}
					elseif ($char3Summary->data->characterBase->classHash == $titanHash) {
						$char3Class = "Titan";
						}
					elseif ($char3Summary->data->characterBase->classHash == $hunterHash) {
						$char3Class = "Hunter";
						}
				else {
					$char3Class = "Ooops";
					}

				//Finding race of each character	
				if ($char1Summary->data->characterBase->raceHash == $exoHash) {
						$char1Race = 'Exo';
						}
					elseif ($char1Summary->data->characterBase->raceHash == $humanHash) {
						$char1Race = 'Human';
						}
					elseif ($char1Summary->data->characterBase->raceHash == $awokenHash) {
						$char1Race = 'Awoken';
						}
				else {
					$char1Race = 'Oops';
					}
					
				if ($char2Summary->data->characterBase->raceHash == $exoHash) {
						$char2Race = 'Exo';
						}
					elseif ($char2Summary->data->characterBase->raceHash == $humanHash) {
						$char2Race = 'Human';
						}
					elseif ($char2Summary->data->characterBase->raceHash == $awokenHash) {
						$char2Race = 'Awoken';
						}
				else {
					$char2Race = 'Oops';
					}					
				
				if ($char3Summary->data->characterBase->raceHash == $exoHash) {
						$char3Race = 'Exo';
						}
					elseif ($char3Summary->data->characterBase->raceHash == $humanHash) {
						$char3Race = 'Human';
						}
					elseif ($char3Summary->data->characterBase->raceHash == $awokenHash) {
						$char3Race = 'Awoken';
						}
				else {
					$char3Race = 'Oops';
					}				
				
				//Finding sex of each character
				if ($char1Summary->data->characterBase->genderHash == $femaleHash) {
						$char1Gender = 'Female';
						}
					elseif ($char1Summary->data->characterBase->genderHash == $maleHash) {
						$char1Gender = 'Male';
						}
				else {
					$char1Gender = 'Oops';
					}	

				if ($char2Summary->data->characterBase->genderHash == $femaleHash) {
						$char2Gender = 'Female';
						}
					elseif ($char2Summary->data->characterBase->genderHash == $maleHash) {
						$char2Gender = 'Male';
						}
				else {
					$char2Gender = 'Oops';
				
				}
					
				if ($char3Summary->data->characterBase->genderHash == $femaleHash) {
						$char3Gender = 'Female';
						}
					elseif ($char3Summary->data->characterBase->genderHash == $maleHash) {
						$char3Gender = 'Male';
						}
				else {
					$char3Gender = 'Oops';
				}					
					
				/*
					Constructing page
				*/

				//Account info
				echo "<h1>" . $username . "</h1>";
				echo "<h2>Combined stats:</h2>";
				echo "<p><a href=http://api.kemta.net/pokemon.php?username=" . $username . ">Pokemon</a></p>";
				echo "<p><b>Total playtime: </b>" . $stats->mergedAllCharacters->merged->allTime->secondsPlayed->basic->displayValue . "</p>" ;
				echo "<p><b>Activities entered: </b>" . $stats->mergedAllCharacters->merged->allTime->activitiesEntered->basic->displayValue . "</p>" ;
				echo "<p><b>Kills: </b>" . $stats->mergedAllCharacters->merged->allTime->kills->basic->displayValue . "</p>" ;
				echo "<p><b>Deaths: </b>" . $stats->mergedAllCharacters->merged->allTime->deaths->basic->displayValue . "</p>" ;
				echo "<p><b>k/d: </b>" . $stats->mergedAllCharacters->merged->allTime->killsDeathsRatio->basic->displayValue . "</p>" ;
				echo "<p><b>Suicides: </b>" . $stats->mergedAllCharacters->merged->allTime->suicides->basic->displayValue . "</p>" ;
				echo "<p><b>Total kill distance: </b>" . $stats->mergedAllCharacters->merged->allTime->totalKillDistance->basic->displayValue . "</p>" ;
				echo "<p><b>Most used weapon type: </b>" . $stats->mergedAllCharacters->merged->allTime->weaponBestType->basic->displayValue . "</p>" ;
				echo "<p><b>Longest lifespan: </b>" . $stats->mergedAllCharacters->merged->allTime->longestSingleLife->basic->displayValue . "</p>" ;
				echo "<p><b>Average lifespan: </b>" . $stats->mergedAllCharacters->merged->allTime->averageLifespan->basic->displayValue . "</p>" ;
				echo "<br>";
				echo "<p><b>Highest character level: </b>" . $stats->mergedAllCharacters->merged->allTime->highestCharacterLevel->basic->displayValue . "</p>" ;
				echo "<p><b>Highest character light level: </b>" . $stats->mergedAllCharacters->merged->allTime->highestLightLevel->basic->displayValue . "</p>" ;
				echo "<p><b>Score: </b>" . $stats->mergedAllCharacters->merged->allTime->score->basic->displayValue . "</p>" ;
				echo "<br>";
				
				echo "<div class=container>";
				
				
				//Character 1
				echo "	<div class=char> <!-- char1 -->";
				echo '		<div class=charBadge style="background-image: url(' . $baselink . $char1Summary->data->backgroundPath . ')";"background-size:contain">';
				echo "			<div class=charIcon>";
				echo "				<img src=" . $baselink . $char1Summary->data->emblemPath . ' height="61" width="61">';
				echo "			</div>"; //exiting charIcon div for char1
				echo "			<div class=charRaceAndGender>";
				echo "   			 <h2>" . $char1Class . "</h2>";
				echo "   			 <p>" . $char1Race . " " . $char1Gender . "</p>";
				echo "			</div>"; //exiting charRaceAndGender div for char1
				echo '			<div class=charLevel style="color:#E6E65C">';
				echo $char1Summary->data->characterBase->powerLevel;
				echo "			</div>"; //exiting charLevel for char1
				echo "		</div>"; //exiting charBadge div for char1
				echo "		<div class=charController>";
				echo "			<button id=pvp1>PvP</button>";
				echo "			<button id=pve1>PvE</button>";
				echo "		</div>"; //exiting charController for char1
				echo "		<div class=charStatscontainer>";
				echo "			<div id=pvpdiv1 class=charStats style=display:block>";
				echo "				<table>";
				echo "					<tbody>";
				writeTableRow("PvP playtime",$pvpStatsChar1->secondsPlayed->basic->displayValue);
				writeTableRow("Activities entered",$pvpStatsChar1->activitiesEntered->basic->displayValue);
				writeTableRow("Total score",$pvpStatsChar1->score->basic->displayValue);
				writeTableRow("Combat rating",$pvpStatsChar1->combatRating->basic->displayValue);
				writeTableRow("Kills",$pvpStatsChar1->kills->basic->displayValue);
				writeTableRow("Assists",$pvpStatsChar1->assists->basic->displayValue);
				writeTableRow("Deaths",$pvpStatsChar1->deaths->basic->displayValue);
				writeTableRow("Suicides",$pvpStatsChar1->suicides->basic->displayValue);
				writeTableRow("K/D",$pvpStatsChar1->killsDeathsRatio->basic->displayValue);
				writeTableRow("Longest killing spree",$pvpStatsChar1->longestKillSpree->basic->displayValue);
				writeTableRow("Longest life",$pvpStatsChar1->longestSingleLife->basic->displayValue);
				writeTableRow("Average life",$pvpStatsChar1->averageLifespan->basic->displayValue);
				writeTableRow("Longest kill distance (m)",$pvpStatsChar1->longestKillDistance->basic->displayValue);
				writeTableRow("Average kill distance (m)",$pvpStatsChar1->averageKillDistance->basic->displayValue);
				writeTableRow("Orbs created",$pvpStatsChar1->orbsDropped->basic->displayValue);
				writeTableRow("Orbs picked up",$pvpStatsChar1->orbsGathered->basic->displayValue);
				writeTableRow("Ressurections Performed",$pvpStatsChar1->resurrectionsPerformed->basic->displayValue);
				writeTableRow("Ressurections Received",$pvpStatsChar1->resurrectionsReceived->basic->displayValue);
				writeTableRow("Most used Weapon",$pvpStatsChar1->weaponBestType->basic->displayValue);
				writeTableRow("Super kills",$pvpStatsChar1->weaponKillsSuper->basic->displayValue);
				writeTableRow("Grenade kills",$pvpStatsChar1->weaponKillsGrenade->basic->displayValue);
				writeTableRow("Melee kills",$pvpStatsChar1->weaponKillsMelee->basic->displayValue);
				writeTableRow("Auto Rifle kills",$pvpStatsChar1->weaponKillsAutoRifle->basic->displayValue);
				writeTableRow("Hand Cannon kills",$pvpStatsChar1->weaponKillsHandCannon->basic->displayValue);
				writeTableRow("Pulse Rifle kills",$pvpStatsChar1->weaponKillsPulseRifle->basic->displayValue);
				writeTableRow("Scout Rifle kills",$pvpStatsChar1->weaponKillsScoutRifle->basic->displayValue);
				writeTableRow("SMG kills",$pvpStatsChar1->weaponKillsSubmachinegun->basic->displayValue);
				writeTableRow("Fusion Rifle kills",$pvpStatsChar1->weaponKillsFusionRifle->basic->displayValue);
				writeTableRow("Sniper Rifle kills",$pvpStatsChar1->weaponKillsSniper->basic->displayValue);
				writeTableRow("Shotgun kills",$pvpStatsChar1->weaponKillsShotgun->basic->displayValue);
				writeTableRow("Sidearm kills",$pvpStatsChar1->weaponKillsSideArm->basic->displayValue);
				writeTableRow("Machinegun kills",$pvpStatsChar1->weaponKillsMachinegun->basic->displayValue);
				writeTableRow("Rocket kills",$pvpStatsChar1->weaponKillsRocketLauncher->basic->displayValue);
				writeTableRow("Sword kills",$pvpStatsChar1->weaponKillsSword->basic->displayValue);

				echo "					</tbody>"; //exiting tbody for pvp for char1
				echo "				</table>"; //exiting table for pvp for char1
				echo "			</div>"; //exiting pvpdiv1 for char1
				echo "			<div id=pvediv1 class=charStats>";
				echo "				<table>";
				echo "					<tbody>";
				writeTableRow("PvE playtime",$pveStatsChar1->secondsPlayed->basic->displayValue);
				writeTableRow("Activities cleared",$pveStatsChar1->activitiesCleared->basic->displayValue);
				writeTableRow("Public events joined",$pveStatsChar1->publicEventsJoined->basic->displayValue);
				writeTableRow("Public events cleared",$pveStatsChar1->publicEventsCompleted->basic->displayValue);
				writeTableRow("Court of Oryx attempts",$pveStatsChar1->courtOfOryxAttempts->basic->displayValue);
				writeTableRow("Court of Oryx completions",$pveStatsChar1->courtOfOryxCompletions->basic->displayValue);
				writeTableRow("Court of Oryx T1 wins",$pveStatsChar1->courtOfOryxWinsTier1->basic->displayValue);
				writeTableRow("Court of Oryx T2 wins",$pveStatsChar1->courtOfOryxWinsTier2->basic->displayValue);
				writeTableRow("Court of Oryx T3 wins",$pveStatsChar1->courtOfOryxWinsTier3->basic->displayValue);
				writeTableRow("Kills",$pveStatsChar1->kills->basic->displayValue);
				writeTableRow("Assists",$pveStatsChar1->assists->basic->displayValue);
				writeTableRow("Deaths",$pveStatsChar1->deaths->basic->displayValue);
				writeTableRow("Suicides",$pveStatsChar1->suicides->basic->displayValue);
				writeTableRow("K/D",$pveStatsChar1->killsDeathsRatio->basic->displayValue);
				writeTableRow("Longest killing spree",$pveStatsChar1->longestKillSpree->basic->displayValue);
				writeTableRow("Longest life",$pveStatsChar1->longestSingleLife->basic->displayValue);
				writeTableRow("Average life",$pveStatsChar1->averageLifespan->basic->displayValue);
				writeTableRow("Longest kill distance (m)",$pveStatsChar1->longestKillDistance->basic->displayValue);
				writeTableRow("Average kill distance (m)",$pveStatsChar1->averageKillDistance->basic->displayValue);
				writeTableRow("Orbs created",$pveStatsChar1->orbsDropped->basic->displayValue);
				writeTableRow("Orbs picked up",$pveStatsChar1->orbsGathered->basic->displayValue);
				writeTableRow("Ressurections Performed",$pveStatsChar1->resurrectionsPerformed->basic->displayValue);
				writeTableRow("Ressurections Received",$pveStatsChar1->resurrectionsReceived->basic->displayValue);				
				writeTableRow("Most used Weapon",$pveStatsChar1->weaponBestType->basic->displayValue);
				writeTableRow("Super kills",$pveStatsChar1->weaponKillsSuper->basic->displayValue);
				writeTableRow("Grenade kills",$pveStatsChar1->weaponKillsGrenade->basic->displayValue);
				writeTableRow("Melee kills",$pveStatsChar1->weaponKillsMelee->basic->displayValue);
				writeTableRow("Auto Rifle kills",$pveStatsChar1->weaponKillsAutoRifle->basic->displayValue);
				writeTableRow("Hand Cannon kills",$pveStatsChar1->weaponKillsHandCannon->basic->displayValue);
				writeTableRow("Pulse Rifle kills",$pveStatsChar1->weaponKillsPulseRifle->basic->displayValue);
				writeTableRow("Scout Rifle kills",$pveStatsChar1->weaponKillsScoutRifle->basic->displayValue);
				writeTableRow("SMG kills",$pveStatsChar1->weaponKillsSubmachinegun->basic->displayValue);
				writeTableRow("Fusion Rifle kills",$pveStatsChar1->weaponKillsFusionRifle->basic->displayValue);
				writeTableRow("Sniper Rifle kills",$pveStatsChar1->weaponKillsSniper->basic->displayValue);
				writeTableRow("Shotgun kills",$pveStatsChar1->weaponKillsShotgun->basic->displayValue);
				writeTableRow("Sidearm kills",$pveStatsChar1->weaponKillsSideArm->basic->displayValue);
				writeTableRow("Machinegun kills",$pveStatsChar1->weaponKillsMachinegun->basic->displayValue);
				writeTableRow("Rocket kills",$pveStatsChar1->weaponKillsRocketLauncher->basic->displayValue);
				writeTableRow("Sword kills",$pveStatsChar1->weaponKillsSword->basic->displayValue);

				echo "					</tbody>"; //exiting tbody for pve for char1
				echo "				</table>"; //exiting table for pve for char1
				echo "			</div>"; //exiting pvediv1 for char1
				echo "		</div>"; //exiting charStatscontainer for char1
				echo "  </div>"; //exiting char div for char1
				
				
				//Character 2
				if (!empty($char2ID)) {
					echo "	<div class=char> <!-- char2 -->";
					echo '		<div class=charBadge style="background-image: url(' . $baselink . $char2Summary->data->backgroundPath . ')";"background-size:contain">';
					echo "			<div class=charIcon>";
					echo "				<img src=" . $baselink . $char2Summary->data->emblemPath . ' height="61" width="61">';
					echo "			</div>"; //exiting charIcon div for char2
					echo "			<div class=charRaceAndGender>";
					echo "   			 <h2>" . $char2Class . "</h2>";
					echo "   			 <p>" . $char2Race . " " . $char2Gender . "</p>";
					echo "			</div>"; //exiting charRaceAndGender div for char2
					echo '			<div class=charLevel style="color:#E6E65C">';
					echo $char2Summary->data->characterBase->powerLevel;
					echo "			</div>"; //exiting charLevel for char2
					echo "		</div>"; //exiting charBadge div for char2
					echo "		<div class=charController>";
					echo "			<button id=pvp2>PvP</button>";
					echo "			<button id=pve2>PvE</button>";
					echo "		</div>"; //exiting charController for char2
					echo "		<div class=charStatscontainer>";
					echo "			<div id=pvpdiv2 class=charStats style=display:block>";
					echo "				<table>";
					echo "					<tbody>";
					writeTableRow("PvP playtime",$pvpStatsChar2->secondsPlayed->basic->displayValue);
					writeTableRow("Activities entered",$pvpStatsChar2->activitiesEntered->basic->displayValue);
					writeTableRow("Total score",$pvpStatsChar2->score->basic->displayValue);
					writeTableRow("Combat rating",$pvpStatsChar2->combatRating->basic->displayValue);
					writeTableRow("Kills",$pvpStatsChar2->kills->basic->displayValue);
					writeTableRow("Assists",$pvpStatsChar2->assists->basic->displayValue);
					writeTableRow("Deaths",$pvpStatsChar2->deaths->basic->displayValue);
					writeTableRow("Suicides",$pvpStatsChar2->suicides->basic->displayValue);
					writeTableRow("K/D",$pvpStatsChar2->killsDeathsRatio->basic->displayValue);
					writeTableRow("Longest killing spree",$pvpStatsChar2->longestKillSpree->basic->displayValue);
					writeTableRow("Longest life",$pvpStatsChar2->longestSingleLife->basic->displayValue);
					writeTableRow("Average life",$pvpStatsChar2->averageLifespan->basic->displayValue);
					writeTableRow("Longest kill distance (m)",$pvpStatsChar2->longestKillDistance->basic->displayValue);
					writeTableRow("Average kill distance (m)",$pvpStatsChar2->averageKillDistance->basic->displayValue);
					writeTableRow("Orbs created",$pvpStatsChar2->orbsDropped->basic->displayValue);
					writeTableRow("Orbs picked up",$pvpStatsChar2->orbsGathered->basic->displayValue);
					writeTableRow("Ressurections Performed",$pvpStatsChar2->resurrectionsPerformed->basic->displayValue);
					writeTableRow("Ressurections Received",$pvpStatsChar2->resurrectionsReceived->basic->displayValue);
					writeTableRow("Most used Weapon",$pvpStatsChar2->weaponBestType->basic->displayValue);
					writeTableRow("Super kills",$pvpStatsChar2->weaponKillsSuper->basic->displayValue);
					writeTableRow("Grenade kills",$pvpStatsChar2->weaponKillsGrenade->basic->displayValue);
					writeTableRow("Melee kills",$pvpStatsChar2->weaponKillsMelee->basic->displayValue);
					writeTableRow("Auto Rifle kills",$pvpStatsChar2->weaponKillsAutoRifle->basic->displayValue);
					writeTableRow("Hand Cannon kills",$pvpStatsChar2->weaponKillsHandCannon->basic->displayValue);
					writeTableRow("Pulse Rifle kills",$pvpStatsChar2->weaponKillsPulseRifle->basic->displayValue);
					writeTableRow("Scout Rifle kills",$pvpStatsChar2->weaponKillsScoutRifle->basic->displayValue);
					writeTableRow("SMG kills",$pvpStatsChar2->weaponKillsSubmachinegun->basic->displayValue);
					writeTableRow("Fusion Rifle kills",$pvpStatsChar2->weaponKillsFusionRifle->basic->displayValue);
					writeTableRow("Sniper Rifle kills",$pvpStatsChar2->weaponKillsSniper->basic->displayValue);
					writeTableRow("Shotgun kills",$pvpStatsChar2->weaponKillsShotgun->basic->displayValue);
					writeTableRow("Sidearm kills",$pvpStatsChar2->weaponKillsSideArm->basic->displayValue);
					writeTableRow("Machinegun kills",$pvpStatsChar2->weaponKillsMachinegun->basic->displayValue);
					writeTableRow("Rocket kills",$pvpStatsChar2->weaponKillsRocketLauncher->basic->displayValue);
					writeTableRow("Sword kills",$pvpStatsChar2->weaponKillsSword->basic->displayValue);

					echo "					</tbody>"; //exiting tbody for pvp for char2
					echo "				</table>"; //exiting table for pvp for char2
					echo "			</div>"; //exiting pvpdiv1 for char2
					echo "			<div id=pvediv2 class=charStats>";
					echo "				<table>";
					echo "					<tbody>";
					writeTableRow("PvE playtime",$pveStatsChar2->secondsPlayed->basic->displayValue);
					writeTableRow("Activities cleared",$pveStatsChar2->activitiesCleared->basic->displayValue);
					writeTableRow("Public events joined",$pveStatsChar2->publicEventsJoined->basic->displayValue);
					writeTableRow("Public events cleared",$pveStatsChar2->publicEventsCompleted->basic->displayValue);
					writeTableRow("Court of Oryx attempts",$pveStatsChar2->courtOfOryxAttempts->basic->displayValue);
					writeTableRow("Court of Oryx completions",$pveStatsChar2->courtOfOryxCompletions->basic->displayValue);
					writeTableRow("Court of Oryx T1 wins",$pveStatsChar2->courtOfOryxWinsTier1->basic->displayValue);
					writeTableRow("Court of Oryx T2 wins",$pveStatsChar2->courtOfOryxWinsTier2->basic->displayValue);
					writeTableRow("Court of Oryx T3 wins",$pveStatsChar2->courtOfOryxWinsTier3->basic->displayValue);
					writeTableRow("Kills",$pveStatsChar2->kills->basic->displayValue);
					writeTableRow("Assists",$pveStatsChar2->assists->basic->displayValue);
					writeTableRow("Deaths",$pveStatsChar2->deaths->basic->displayValue);
					writeTableRow("Suicides",$pveStatsChar2->suicides->basic->displayValue);
					writeTableRow("K/D",$pveStatsChar2->killsDeathsRatio->basic->displayValue);
					writeTableRow("Longest killing spree",$pveStatsChar2->longestKillSpree->basic->displayValue);
					writeTableRow("Longest life",$pveStatsChar2->longestSingleLife->basic->displayValue);
					writeTableRow("Average life",$pveStatsChar2->averageLifespan->basic->displayValue);
					writeTableRow("Longest kill distance (m)",$pveStatsChar2->longestKillDistance->basic->displayValue);
					writeTableRow("Average kill distance (m)",$pveStatsChar2->averageKillDistance->basic->displayValue);
					writeTableRow("Orbs created",$pveStatsChar2->orbsDropped->basic->displayValue);
					writeTableRow("Orbs picked up",$pveStatsChar2->orbsGathered->basic->displayValue);
					writeTableRow("Ressurections Performed",$pveStatsChar2->resurrectionsPerformed->basic->displayValue);
					writeTableRow("Ressurections Received",$pveStatsChar2->resurrectionsReceived->basic->displayValue);				
					writeTableRow("Most used Weapon",$pveStatsChar2->weaponBestType->basic->displayValue);
					writeTableRow("Super kills",$pveStatsChar2->weaponKillsSuper->basic->displayValue);
					writeTableRow("Grenade kills",$pveStatsChar2->weaponKillsGrenade->basic->displayValue);
					writeTableRow("Melee kills",$pveStatsChar2->weaponKillsMelee->basic->displayValue);
					writeTableRow("Auto Rifle kills",$pveStatsChar2->weaponKillsAutoRifle->basic->displayValue);
					writeTableRow("Hand Cannon kills",$pveStatsChar2->weaponKillsHandCannon->basic->displayValue);
					writeTableRow("Pulse Rifle kills",$pveStatsChar2->weaponKillsPulseRifle->basic->displayValue);
					writeTableRow("Scout Rifle kills",$pveStatsChar2->weaponKillsScoutRifle->basic->displayValue);
					writeTableRow("SMG kills",$pveStatsChar2->weaponKillsSubmachinegun->basic->displayValue);
					writeTableRow("Fusion Rifle kills",$pveStatsChar2->weaponKillsFusionRifle->basic->displayValue);
					writeTableRow("Sniper Rifle kills",$pveStatsChar2->weaponKillsSniper->basic->displayValue);
					writeTableRow("Shotgun kills",$pveStatsChar2->weaponKillsShotgun->basic->displayValue);
					writeTableRow("Sidearm kills",$pveStatsChar2->weaponKillsSideArm->basic->displayValue);
					writeTableRow("Machinegun kills",$pveStatsChar2->weaponKillsMachinegun->basic->displayValue);
					writeTableRow("Rocket kills",$pveStatsChar2->weaponKillsRocketLauncher->basic->displayValue);
					writeTableRow("Sword kills",$pveStatsChar2->weaponKillsSword->basic->displayValue);

					echo "					</tbody>"; //exiting tbody for pve for char2
					echo "				</table>"; //exiting table for pve for char2
					echo "			</div>"; //exiting pvediv1 for char2
					echo "		</div>"; //exiting charStatscontainer for char2
					echo "  </div>"; //exiting char div for char2
					
					} //exiting if loop (char2 exists)


				//Character 3
				if (!empty($char3ID)) {
					echo "	<div class=char> <!-- char3 -->";
					echo '		<div class=charBadge style="background-image: url(' . $baselink . $char3Summary->data->backgroundPath . ')";"background-size:contain">';
					echo "			<div class=charIcon>";
					echo "				<img src=" . $baselink . $char3Summary->data->emblemPath . ' height="61" width="61">';
					echo "			</div>"; //exiting charIcon div for char3
					echo "			<div class=charRaceAndGender>";
					echo "   			 <h2>" . $char3Class . "</h2>";
					echo "   			 <p>" . $char3Race . " " . $char3Gender . "</p>";
					echo "			</div>"; //exiting charRaceAndGender div for char3
					echo '			<div class=charLevel style="color:#E6E65C">';
					echo $char3Summary->data->characterBase->powerLevel;
					echo "			</div>"; //exiting charLevel for char3
					echo "		</div>"; //exiting charBadge div for char3
					echo "		<div class=charController>";
					echo "			<button id=pvp3>PvP</button>";
					echo "			<button id=pve3>PvE</button>";
					echo "		</div>"; //exiting charController for char3
					echo "		<div class=charStatscontainer>";
					echo "			<div id=pvpdiv3 class=charStats style=display:block>";
					echo "				<table>";
					echo "					<tbody>";
					writeTableRow("PvP playtime",$pvpStatsChar3->secondsPlayed->basic->displayValue);
					writeTableRow("Activities entered",$pvpStatsChar3->activitiesEntered->basic->displayValue);
					writeTableRow("Total score",$pvpStatsChar3->score->basic->displayValue);
					writeTableRow("Combat rating",$pvpStatsChar3->combatRating->basic->displayValue);
					writeTableRow("Kills",$pvpStatsChar3->kills->basic->displayValue);
					writeTableRow("Assists",$pvpStatsChar3->assists->basic->displayValue);
					writeTableRow("Deaths",$pvpStatsChar3->deaths->basic->displayValue);
					writeTableRow("Suicides",$pvpStatsChar3->suicides->basic->displayValue);
					writeTableRow("K/D",$pvpStatsChar3->killsDeathsRatio->basic->displayValue);
					writeTableRow("Longest killing spree",$pvpStatsChar3->longestKillSpree->basic->displayValue);
					writeTableRow("Longest life",$pvpStatsChar3->longestSingleLife->basic->displayValue);
					writeTableRow("Average life",$pvpStatsChar3->averageLifespan->basic->displayValue);
					writeTableRow("Longest kill distance (m)",$pvpStatsChar3->longestKillDistance->basic->displayValue);
					writeTableRow("Average kill distance (m)",$pvpStatsChar3->averageKillDistance->basic->displayValue);
					writeTableRow("Orbs created",$pvpStatsChar3->orbsDropped->basic->displayValue);
					writeTableRow("Orbs picked up",$pvpStatsChar3->orbsGathered->basic->displayValue);
					writeTableRow("Ressurections Performed",$pvpStatsChar3->resurrectionsPerformed->basic->displayValue);
					writeTableRow("Ressurections Received",$pvpStatsChar3->resurrectionsReceived->basic->displayValue);
					writeTableRow("Most used Weapon",$pvpStatsChar3->weaponBestType->basic->displayValue);
					writeTableRow("Super kills",$pvpStatsChar3->weaponKillsSuper->basic->displayValue);
					writeTableRow("Grenade kills",$pvpStatsChar3->weaponKillsGrenade->basic->displayValue);
					writeTableRow("Melee kills",$pvpStatsChar3->weaponKillsMelee->basic->displayValue);
					writeTableRow("Auto Rifle kills",$pvpStatsChar3->weaponKillsAutoRifle->basic->displayValue);
					writeTableRow("Hand Cannon kills",$pvpStatsChar3->weaponKillsHandCannon->basic->displayValue);
					writeTableRow("Pulse Rifle kills",$pvpStatsChar3->weaponKillsPulseRifle->basic->displayValue);
					writeTableRow("Scout Rifle kills",$pvpStatsChar3->weaponKillsScoutRifle->basic->displayValue);
					writeTableRow("SMG kills",$pvpStatsChar3->weaponKillsSubmachinegun->basic->displayValue);
					writeTableRow("Fusion Rifle kills",$pvpStatsChar3->weaponKillsFusionRifle->basic->displayValue);
					writeTableRow("Sniper Rifle kills",$pvpStatsChar3->weaponKillsSniper->basic->displayValue);
					writeTableRow("Shotgun kills",$pvpStatsChar3->weaponKillsShotgun->basic->displayValue);
					writeTableRow("Sidearm kills",$pvpStatsChar3->weaponKillsSideArm->basic->displayValue);
					writeTableRow("Machinegun kills",$pvpStatsChar3->weaponKillsMachinegun->basic->displayValue);
					writeTableRow("Rocket kills",$pvpStatsChar3->weaponKillsRocketLauncher->basic->displayValue);
					writeTableRow("Sword kills",$pvpStatsChar3->weaponKillsSword->basic->displayValue);

					echo "					</tbody>"; //exiting tbody for pvp for char3
					echo "				</table>"; //exiting table for pvp for char3
					echo "			</div>"; //exiting pvpdiv1 for char3
					echo "			<div id=pvediv3 class=charStats>";
					echo "				<table>";
					echo "					<tbody>";
					writeTableRow("PvE playtime",$pveStatsChar3->secondsPlayed->basic->displayValue);
					writeTableRow("Activities cleared",$pveStatsChar3->activitiesCleared->basic->displayValue);
					writeTableRow("Public events joined",$pveStatsChar3->publicEventsJoined->basic->displayValue);
					writeTableRow("Public events cleared",$pveStatsChar3->publicEventsCompleted->basic->displayValue);
					writeTableRow("Court of Oryx attempts",$pveStatsChar3->courtOfOryxAttempts->basic->displayValue);
					writeTableRow("Court of Oryx completions",$pveStatsChar3->courtOfOryxCompletions->basic->displayValue);
					writeTableRow("Court of Oryx T1 wins",$pveStatsChar3->courtOfOryxWinsTier1->basic->displayValue);
					writeTableRow("Court of Oryx T2 wins",$pveStatsChar3->courtOfOryxWinsTier2->basic->displayValue);
					writeTableRow("Court of Oryx T3 wins",$pveStatsChar3->courtOfOryxWinsTier3->basic->displayValue);
					writeTableRow("Kills",$pveStatsChar3->kills->basic->displayValue);
					writeTableRow("Assists",$pveStatsChar3->assists->basic->displayValue);
					writeTableRow("Deaths",$pveStatsChar3->deaths->basic->displayValue);
					writeTableRow("Suicides",$pveStatsChar3->suicides->basic->displayValue);
					writeTableRow("K/D",$pveStatsChar3->killsDeathsRatio->basic->displayValue);
					writeTableRow("Longest killing spree",$pveStatsChar3->longestKillSpree->basic->displayValue);
					writeTableRow("Longest life",$pveStatsChar3->longestSingleLife->basic->displayValue);
					writeTableRow("Average life",$pveStatsChar3->averageLifespan->basic->displayValue);
					writeTableRow("Longest kill distance (m)",$pveStatsChar3->longestKillDistance->basic->displayValue);
					writeTableRow("Average kill distance (m)",$pveStatsChar3->averageKillDistance->basic->displayValue);
					writeTableRow("Orbs created",$pveStatsChar3->orbsDropped->basic->displayValue);
					writeTableRow("Orbs picked up",$pveStatsChar3->orbsGathered->basic->displayValue);
					writeTableRow("Ressurections Performed",$pveStatsChar3->resurrectionsPerformed->basic->displayValue);
					writeTableRow("Ressurections Received",$pveStatsChar3->resurrectionsReceived->basic->displayValue);				
					writeTableRow("Most used Weapon",$pveStatsChar3->weaponBestType->basic->displayValue);
					writeTableRow("Super kills",$pveStatsChar3->weaponKillsSuper->basic->displayValue);
					writeTableRow("Grenade kills",$pveStatsChar3->weaponKillsGrenade->basic->displayValue);
					writeTableRow("Melee kills",$pveStatsChar3->weaponKillsMelee->basic->displayValue);
					writeTableRow("Auto Rifle kills",$pveStatsChar3->weaponKillsAutoRifle->basic->displayValue);
					writeTableRow("Hand Cannon kills",$pveStatsChar3->weaponKillsHandCannon->basic->displayValue);
					writeTableRow("Pulse Rifle kills",$pveStatsChar3->weaponKillsPulseRifle->basic->displayValue);
					writeTableRow("Scout Rifle kills",$pveStatsChar3->weaponKillsScoutRifle->basic->displayValue);
					writeTableRow("SMG kills",$pveStatsChar3->weaponKillsSubmachinegun->basic->displayValue);
					writeTableRow("Fusion Rifle kills",$pveStatsChar3->weaponKillsFusionRifle->basic->displayValue);
					writeTableRow("Sniper Rifle kills",$pveStatsChar3->weaponKillsSniper->basic->displayValue);
					writeTableRow("Shotgun kills",$pveStatsChar3->weaponKillsShotgun->basic->displayValue);
					writeTableRow("Sidearm kills",$pveStatsChar3->weaponKillsSideArm->basic->displayValue);
					writeTableRow("Machinegun kills",$pveStatsChar3->weaponKillsMachinegun->basic->displayValue);
					writeTableRow("Rocket kills",$pveStatsChar3->weaponKillsRocketLauncher->basic->displayValue);
					writeTableRow("Sword kills",$pveStatsChar3->weaponKillsSword->basic->displayValue);

					echo "					</tbody>"; //exiting tbody for pve for char3
					echo "				</table>"; //exiting table for pve for char3
					echo "			</div>"; //exiting pvediv1 for char3
					echo "		</div>"; //exiting charStatscontainer for char3
					echo "  </div>"; //exiting char div for char3
					
					} //exiting if loop (char3 exists)					
				
				echo "</div>"; //exiting container div
				
				} //exiting else loop (user found)
			} //exiting else loop (username set)
		include 'ad.php';
		?>
	

	</body>
</html>
