<?php

$apiKey = '';
$username = $_GET['username'];

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
if (!isset($membershipId)) {echo "<h1>Cannot find user</h1><p>The supplied username was: " . $username;}

//Continue if user is found
else {

	$stats = get_bungie("https://www.bungie.net/platform/destiny/Stats/Account/2/$membershipId/");	
	$i = 0;
	
	while ($stats->characters[$i]->deleted > 0) {$i++;};
	$char1ID = $stats->characters[$i]->characterId;
	$pveStatsChar1 = $stats->characters[$i]->results->allPvE->allTime;
	$i++;
	
	while ($stats->characters[$i]->deleted > 0) {$i++;};
	$char2ID = $stats->characters[$i]->characterId;
	$pveStatsChar2 = $stats->characters[$i]->results->allPvE->allTime;
	$i++;
	
	while ($stats->characters[$i]->deleted > 0) {$i++;};
	$char3ID = $stats->characters[$i]->characterId;
	$pveStatsChar3 = $stats->characters[$i]->results->allPvE->allTime;
	
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
	$pvePlaytime = $stats->mergedAllCharacters->results->allPvE->allTime->secondsPlayed->basic->displayValue;

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
	echo "<h1><a href=http://api.kemta.net/destiny/stats.php?username=" . $username . ">" . $username . "</a></h1>";
	echo "<p><b>Total playtime: </b>" . $stats->mergedAllCharacters->merged->allTime->secondsPlayed->basic->displayValue . "</p>" ;
	echo "<p><b>PvE playtime: </b>" . $stats->mergedAllCharacters->results->allPvE->allTime->secondsPlayed->basic->displayValue . "</p>" ;
	echo "<p><b>PvP playtime: </b>" . $stats->mergedAllCharacters->results->allPvP->allTime->secondsPlayed->basic->displayValue . "</p>" ;
	echo "<p><b>Score: </b>" . $stats->mergedAllCharacters->merged->allTime->score->basic->displayValue . "</p>" ;
	echo "<p><b>Combat rating: </b>" . $stats->mergedAllCharacters->merged->allTime->combatRating->basic->displayValue . "</p>" ;
	echo "<p><b>Grimoire score: </b>" . $char1Summary->data->characterBase->grimoireScore . "<a href=http://api.kemta.net/destiny/pokemon.php?username=" . $username . "> Pokemon</a></p>";
	echo "<br>";
	
	//Defining container div
	echo "<div class=container>";

	/*
		Character 1
	*/

	echo '<div class="char1" style="background-image: url(' . $baselink . $char1Summary->data->backgroundPath . ');"background-size: contain>';
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
		
	
		//Finishing char3
		echo '		</div>';
		}
		


		//Finishing container div 
	echo '		</div>';
	







	//Debug stuff:
		//echo "<h1>Stats: </h1>";
		//print_r($stats);
		//echo "<h1>Char1ID: </h1>";
		//print_r($char1ID);
		//echo "<h1>Char1Summary: </h1>";
		//print_r($char1Summary);
		//echo "<h1>Char1Stats: </h1>";
		//print_r($pveStatsChar1);
	}
?>