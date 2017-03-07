<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="stats.css"
	<meta charset="utf-8">
</head>

<form action="pveStats.php" method="GET">
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
		Getting info from Bungie
	*/

	$user = get_bungie("https://www.bungie.net/Platform/Destiny/SearchDestinyPlayer/2/$username/");
	$membershipId = $user[0]->membershipId;

	//Provide error message if username is incorrect
	if (!isset($membershipId)) {echo "<h1>Incorrect username</h1><p>The supplied username was: " . $username;}

	//Continue if user is found
	else {

		$stats = get_bungie("https://www.bungie.net/platform/destiny/Stats/Account/2/$membershipId/");	
		$char1ID = $stats->characters[0]->characterId;
		$char2ID = $stats->characters[1]->characterId;
		$char3ID = $stats->characters[2]->characterId;
		$char1Summary = get_bungie("https://www.bungie.net/platform/destiny/2/Account/$membershipId/Character/$char1ID/");
		if (!empty($char2ID)) {
			$char2Summary = get_bungie("https://www.bungie.net/platform/destiny/2/Account/$membershipId/Character/$char2ID/");
			if (!empty($char3ID)) {
				$char3Summary = get_bungie("https://www.bungie.net/platform/destiny/2/Account/$membershipId/Character/$char3ID/");
				}
			}
		$pveStatsChar1 = $stats->characters[0]->results->allPvE->allTime;
		$pveStatsChar2 = $stats->characters[1]->results->allPvE->allTime;
		$pveStatsChar3 = $stats->characters[2]->results->allPvE->allTime;
		
		//	Finished gathering info from Bungie

		/*
			Putting together data and variables
		*/

		$baselink = "https://www.bungie.net";
		//$pvePlaytime = $stats->mergedAllCharacters->results->allPvE->allTime->secondsPlayed->basic->displayValue;

		$femaleHash = '2204441813';
		$maleHash = '3111576190';

		$exoHash = '898834093';
		$humanHash = '3887404748';
		$awokenHash = '2803282938';

		$warlockHash = '2271682572';
		$titanHash = '3655393761';
		$hunterHash = '671679327';

		/*
			Constructing page
		*/

		//Account info
		echo "<h1>" . $username . "</h1>";
		echo "<h2>Account wide stats:</h2>";
		echo "<p><b>Grimoire score: </b>" . $char1Summary->data->characterBase->grimoireScore . "<a href=http://cod.kemta.net/pokemon.php?username=" . $username . "> Pokemon</a></p>";
		echo "<p><b>PvE playtime: </b>" . $stats->mergedAllCharacters->results->allPvE->allTime->secondsPlayed->basic->displayValue . "</p>" ;
		echo "<p><b>Activities cleared: </b>" . $stats->mergedAllCharacters->results->allPvE->allTime->activitiesCleared->basic->displayValue . "</p>" ;
		echo "<p><b>Kills: </b>" . $stats->mergedAllCharacters->results->allPvE->allTime->kills->basic->displayValue . "</p>" ;
		echo "<p><b>Deaths: </b>" . $stats->mergedAllCharacters->results->allPvE->allTime->deaths->basic->displayValue . "</p>" ;
		echo "<p><b>Most used weapon type: </b>" . $stats->mergedAllCharacters->results->allPvE->allTime->weaponBestType->basic->displayValue . "</p>" ;
		echo "<p><b>Longest kill spree: </b>" . $stats->mergedAllCharacters->results->allPvE->allTime->longestKillSpree->basic->displayValue . "</p>" ;
		echo "<p><b>Longest lifespan: </b>" . $stats->mergedAllCharacters->results->allPvE->allTime->longestSingleLife->basic->displayValue . "</p>" ;
		echo "<p><b>Average lifespan: </b>" . $stats->mergedAllCharacters->results->allPvE->allTime->averageLifespan->basic->displayValue . "</p>" ;
		echo "<br>";
		
		//Defining container div
		echo '<div class="container">';

		/*
			Character 1
		*/

		echo '<div class="char1" style="background-image: url(' . $baselink . $char1Summary->data->backgroundPath . ');background-size: contain">';
		echo '<div class=icon>';
		echo '<img src=' . $baselink . $char1Summary->data->emblemPath . ' height="61" width="61">';
		echo '</div>';

		//Class div
		if ($char1Summary->data->characterBase->classHash == $warlockHash) {
			echo '    <div class="class">Warlock</div>';
			}
		elseif ($char1Summary->data->characterBase->classHash == $titanHash) {
			echo '    <div class="class">Titan</div>';
			}
		elseif ($char1Summary->data->characterBase->classHash == $hunterHash) {
			echo '    <div class="class">Hunter</div>';
			}
		else {
			echo '    <div class="class">Oops</div>';
			}	
		
		//Level div
			echo '<div class="level" style="color:#E6E65C">' . $char1Summary->data->characterBase->powerLevel . '</div>';
			
		//Race and gender div
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

		if ($char1Summary->data->characterBase->genderHash == $femaleHash) {
				$char1Gender = 'Female';
				}
			elseif ($char1Summary->data->characterBase->genderHash == $maleHash) {
				$char1Gender = 'Male';
				}
		else {
			$char1Gender = 'Oops';
			}
				
		echo '<div class="raceandgender">' . $char1Race . ' ' . $char1Gender . '</div>';

		//Stats
		echo '    <div class=statsContainer>';
		echo '      <table>';
		echo '        <tbody>';
		
		//start of data rows
		echo '          <tr class="stats">';
		echo '            <td class="statheader">PvE playtime:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->secondsPlayed->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Activities cleared:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->activitiesCleared->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->kills->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Deaths:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->deaths->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Suicides:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->suicides->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">K/D:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->killsDeathsRatio->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Longest killing spree:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->longestKillSpree->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Average lifespan:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->averageLifespan->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Longest life:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->longestSingleLife->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Revived:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->resurrectionsReceived->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Revives:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->resurrectionsPerformed->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Orbs created:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->orbsDropped->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Orbs picked up:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->orbsGathered->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Public events joined:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->publicEventsJoined->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Public events completed:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->publicEventsCompleted->basic->displayValue . '</td>';
		echo '          </tr>';		
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Average kill distance:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->averageKillDistance->basic->displayValue . 'm</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Longest kill distance:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->longestKillDistance->basic->displayValue . 'm</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Super kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsSuper->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Melee kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsMelee->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Grenade kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsGrenade->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Ability kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->abilityKills->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Most used weapon type:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponBestType->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Auto rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsAutoRifle->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Hand cannon kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsHandCannon->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Pulse rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsPulseRifle->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Scout rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsScoutRifle->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Fusion rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsFusionRifle->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Shotgun kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsShotgun->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Sniper kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsSniper->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Sidearm kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsSideArm->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Machinegun kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsMachinegun->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Rocket kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsRocketLauncher->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Sword kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsSword->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Relic kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar1->weaponKillsRelic->basic->displayValue . '</td>';
		echo '          </tr>';		
		
			//End of data rows
		echo '        </tbody>';
		echo '      </table>';
		echo '    </div>';
		
		
		//Finishing char1
		echo '		</div>';

		//Finishing container div if no more characters exists
		if (empty($char2ID)) {
			echo '	</div>';
			}
			
		//Continuing if more characters exists
		else {
		
			/*
				Character 2
			*/
			echo '<div class="char1" style="background-image: url(' . $baselink . $char2Summary->data->backgroundPath . ');background-size: contain">';
			echo '<div class=icon>';
			echo '<img src=' . $baselink . $char2Summary->data->emblemPath . ' height="61" width="61">';
			echo '</div>';

			//Class div
			if ($char2Summary->data->characterBase->classHash == $warlockHash) {
				echo '    <div class="class">Warlock</div>';
				}
			elseif ($char2Summary->data->characterBase->classHash == $titanHash) {
				echo '    <div class="class">Titan</div>';
				}
			elseif ($char2Summary->data->characterBase->classHash == $hunterHash) {
				echo '    <div class="class">Hunter</div>';
				}
			else {
				echo '    <div class="class">Oops</div>';
				}	
			
			//Level div
				echo '<div class="level" style="color:#E6E65C">' . $char2Summary->data->characterBase->powerLevel . '</div>';
				
			//Race and gender div
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

			if ($char2Summary->data->characterBase->genderHash == $femaleHash) {
					$char2Gender = 'Female';
					}
				elseif ($char2Summary->data->characterBase->genderHash == $maleHash) {
					$char2Gender = 'Male';
					}
			else {
				$char2Gender = 'Oops';
				}
					
			echo '<div class="raceandgender">' . $char2Race . ' ' . $char2Gender . '</div>';

			//Stats
			echo '    <div class=statsContainer>';
			echo '      <table>';
			echo '        <tbody>';

			//start of data rows
		echo '          <tr class="stats">';
		echo '            <td class="statheader">PvE playtime:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->secondsPlayed->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Activities cleared:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->activitiesCleared->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->kills->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Deaths:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->deaths->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Suicides:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->suicides->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">K/D:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->killsDeathsRatio->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Longest killing spree:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->longestKillSpree->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Average lifespan:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->averageLifespan->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Longest life:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->longestSingleLife->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Revived:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->resurrectionsReceived->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Revives:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->resurrectionsPerformed->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Orbs created:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->orbsDropped->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Orbs picked up:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->orbsGathered->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Public events joined:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->publicEventsJoined->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Public events completed:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->publicEventsCompleted->basic->displayValue . '</td>';
		echo '          </tr>';		
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Average kill distance:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->averageKillDistance->basic->displayValue . 'm</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Longest kill distance:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->longestKillDistance->basic->displayValue . 'm</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Super kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsSuper->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Melee kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsMelee->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Grenade kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsGrenade->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Ability kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->abilityKills->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Most used weapon type:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponBestType->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Auto rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsAutoRifle->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Hand cannon kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsHandCannon->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Pulse rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsPulseRifle->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Scout rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsScoutRifle->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Fusion rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsFusionRifle->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Shotgun kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsShotgun->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Sniper kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsSniper->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Sidearm kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsSideArm->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Machinegun kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsMachinegun->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Rocket kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsRocketLauncher->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Sword kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsSword->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Relic kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar2->weaponKillsRelic->basic->displayValue . '</td>';
		echo '          </tr>';

				//End of data rows
			echo '        </tbody>';
			echo '      </table>';
			echo '    </div>';
		
			
			//Finishing char2
			echo '		</div>';
			}

		//Finishing container div if no more characters exists
		if (empty($char3ID)) {
			echo '	</div>';
			}
			
		//Continuing if more characters exists
		else {
		
			/*
				Character 3
			*/
		
			echo '<div class="char1" style="background-image: url(' . $baselink . $char3Summary->data->backgroundPath . ');background-size: contain">';
			echo '<div class=icon>';
			echo '<img src=' . $baselink . $char3Summary->data->emblemPath . ' height="61" width="61">';
			echo '</div>';

			//Class div
			if ($char3Summary->data->characterBase->classHash == $warlockHash) {
				echo '    <div class="class">Warlock</div>';
				}
			elseif ($char3Summary->data->characterBase->classHash == $titanHash) {
				echo '    <div class="class">Titan</div>';
				}
			elseif ($char3Summary->data->characterBase->classHash == $hunterHash) {
				echo '    <div class="class">Hunter</div>';
				}
			else {
				echo '    <div class="class">Oops</div>';
				}	
			
			//Level div
				echo '<div class="level" style="color:#E6E65C">' . $char3Summary->data->characterBase->powerLevel . '</div>';
				
			//Race and gender div
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

			if ($char3Summary->data->characterBase->genderHash == $femaleHash) {
					$char3Gender = 'Female';
					}
				elseif ($char3Summary->data->characterBase->genderHash == $maleHash) {
					$char3Gender = 'Male';
					}
			else {
				$char3Gender = 'Oops';
				}
					
			echo '<div class="raceandgender">' . $char3Race . ' ' . $char3Gender . '</div>';
			
			//Stats
			echo '    <div class=statsContainer>';
			echo '      <table>';
			echo '        <tbody>';

			//start of data rows
		echo '          <tr class="stats">';
		echo '            <td class="statheader">PvE playtime:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->secondsPlayed->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Activities cleared:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->activitiesCleared->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->kills->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Deaths:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->deaths->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Suicides:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->suicides->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">K/D:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->killsDeathsRatio->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Longest killing spree:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->longestKillSpree->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Average lifespan:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->averageLifespan->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Longest life:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->longestSingleLife->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Revived:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->resurrectionsReceived->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Revives:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->resurrectionsPerformed->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Orbs created:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->orbsDropped->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Orbs picked up:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->orbsGathered->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Public events joined:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->publicEventsJoined->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Public events completed:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->publicEventsCompleted->basic->displayValue . '</td>';
		echo '          </tr>';		
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Average kill distance:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->averageKillDistance->basic->displayValue . 'm</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Longest kill distance:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->longestKillDistance->basic->displayValue . 'm</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Super kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsSuper->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Melee kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsMelee->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Grenade kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsGrenade->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Ability kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->abilityKills->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Most used weapon type:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponBestType->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Auto rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsAutoRifle->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Hand cannon kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsHandCannon->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Pulse rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsPulseRifle->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Scout rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsScoutRifle->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Fusion rifle kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsFusionRifle->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Shotgun kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsShotgun->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Sniper kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsSniper->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Sidearm kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsSideArm->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Machinegun kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsMachinegun->basic->displayValue . '</td>';
		echo '          </tr>';	
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Rocket kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsRocketLauncher->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Sword kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsSword->basic->displayValue . '</td>';
		echo '          </tr>';
		echo '          <tr class="stats">';
		echo '            <td class="statheader">Relic kills:</td>';
		echo '            <td class="statvalue">' . $pveStatsChar3->weaponKillsRelic->basic->displayValue . '</td>';
		echo '          </tr>';

				//End of data rows
			echo '        </tbody>';
			echo '      </table>';
			echo '    </div>';
		

			
			//Finishing char3
			echo '		</div>';
			}
			


			//Finishing container div 
		echo '		</div>';
		


		}
	}
<br>
<br>
<br>
include 'ad.php';
?>
 
</body>
</html>
