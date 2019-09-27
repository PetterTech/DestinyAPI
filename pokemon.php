<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="pokemon.css"
	<meta charset="utf-8">
</head>

<form action="pokemon.php" method="GET">
<input name="username" type="text" placeholder="PSN ID">
<input id="submit" type="submit" value="Search">
</form>

<br>

<?php

$apiKey = '';
$username = $_GET['username'];

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
	$usergrimoire = get_bungie("http://www.bungie.net/Platform/Destiny/Vanguard/grimoire/2/$membershipId/");

	//Provide error message if username is incorrect
	if (!isset($membershipId)) {echo "<h1>Incorrect username</h1><p>The supplied username was: " . $username;}

	else {

		//Defining some variables for later use
		$numberofcards = count($usergrimoire->data->cardCollection);
		$missingCards = array();
		$exoticCards = array("302020","302040","302035","302055","302030","302060","302080","302095","302100","302150","302037","303020","303030","303050","303060","303065","303080","303090","303092","303093","303034","303210","303220","303310","304020","304030","304050","304060","304032","700140","700301","700150","700160","700302","700170","700300","700220","700230","700210","700290","700291","700292");
		$numberofexoticCards = count($exoticCards);

		//Writing on page
		echo "<p>There are " . $numberofexoticCards . " exotic weapons in the game and " . $username . " is missing these weapons: </p>";

		//Finding the weapons the user DO have
		$heldcards = array();
		for ($i = 0; $i < $numberofcards; $i++) {
				foreach ($exoticCards as $card) {
					if ($usergrimoire->data->cardCollection[$i]->cardId == $card) {
						$heldcards[] = $card;
					}
				}
		}

		//Checking held cards against known exotic weapons, and populating the missingCards array
		foreach ($exoticCards as $card) {
			if (!in_array($card, $heldcards)) {
				$missingCards[] = $card;
			}
		}	

		//Showing the image of each missing weapon
		foreach ($missingCards as $card) {
			if ($card == 302020) {
				?> <div class=weapon><img alt="SUROS Regime" title="SUROS Regime" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302020_c4f2faea89cf5c401197330cc592c7c4.jpg></div><?php;
				}
			elseif ($card == 302040) {
				?> <div class=weapon><img alt="Hard Light" title="Hard Light" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302040_4d97077e9ec825257a683c6153889ee5.jpg></div><?php;
				}
			elseif ($card == 302035) {
				?> <div class=weapon><img alt="Monte Carlo" title="Monte Carlo" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302035_2b4fc406c1b27593775fe689fffe582e.jpg></div><?php;
				}
			elseif ($card == 302037) {
				?> <div class=weapon><img alt="Necrochasm" title="Necrochasm" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302037_ae92f9a2fa9af71280258316678cfceb.jpg></div><?php;
				}
			elseif ($card == 302055) {
				?> <div class=weapon><img alt="MIDA Multi-Tool" title="MIDA Multi-Tool" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302055_0695fe0212203fcbda1272823689f920.jpg></div><?php;
				}
			elseif ($card == 302030) {
				?> <div class=weapon><img alt="The Fate of all Fools" title="The Fate of all Fools" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302030_24b8932c2f1c3866d81b2551b00c0133.jpg></div><?php;
				}
			elseif ($card == 302060) {
				?> <div class=weapon><img alt="Bad Juju" title="Bad Juju" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302060_30c02269f0ffbdcf31401c8dce3ff750.jpg></div><?php;
				}
			elseif ($card == 302080) {
				?> <div class=weapon><img alt="Red Death" title="Red Death" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302080_bedd57685bc99d3e7c21e679e9df3949.jpg></div><?php;
				}
			elseif ($card == 302095) {
				?> <div class=weapon><img alt="Hawkmoon" title="Hawkmoon" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302095_e2f7a199c3e55d1f807de197a6cbfc16.jpg></div><?php;
				}
			elseif ($card == 302100) {
				?> <div class=weapon><img alt="The Last Word" title="The Last Word" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302100_0a391c4b14240d0eaccb069abdd8e2e3.jpg></div><?php;
				}
			elseif ($card == 302150) {
				?> <div class=weapon><img alt="Thorn" title="Thorn" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/302150_b137c04fe0c5e9d1376ce6377fa60799.jpg></div><?php;
				}
			elseif ($card == 303020) {
				?> <div class=weapon><img alt="Universal Remote" title="Universal Remote" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303020_30795597387ec5c2633c345920e8d829.jpg></div><?php;
				}
			elseif ($card == 303030) {
				?> <div class=weapon><img alt="Invective" title="Invective" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303030_afb6ecfd3a0d30e439f9f789833a37a8.jpg></div><?php;
				}
			elseif ($card == 303093) {
				?> <div class=weapon><img alt="The 4th Horseman" title="The 4th Horseman" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303093_2d9cc101cb79845044c8c55856cf4456.jpg></div><?php;
				}
			elseif ($card == 303034) {
				?> <div class=weapon><img alt="Lord of Wolves" title="Lord of Wolves" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303034_433d1145112319c4c0150fb8d866cb77.jpg></div><?php;
				}
			elseif ($card == 303050) {
				?> <div class=weapon><img alt="Pocket Infinity" title="Pocket Infinity" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303050_3ebea0face59eea8813a89673feb5639.jpg></div><?php;
				}
			elseif ($card == 303060) {
				?> <div class=weapon><img alt="Plan C" title="Plan C" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303060_6923b2cc2367d14eae0a36cae8175ecd.jpg></div><?php;
				}
			elseif ($card == 303065) {
				?> <div class=weapon><img alt="Vex Mythoclast" title="Vex Mythoclast" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303065_4764d811ab0299d5728e9775d3f65dba.jpg></div><?php;
				}
			elseif ($card == 303310) {
				?> <div class=weapon><img alt="Queenbreakers' Bow" title="Queenbreakers' Bow" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303310_9d9803b483886e8c39b289a2d658cfd4.jpg></div><?php;
				}
			elseif ($card == 303080) {
				?> <div class=weapon><img alt="Patience and Time" title="Patience and Time" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303080_31110dcc27258dfd76d454f7e68a8c03.jpg></div><?php;
				}
			elseif ($card == 303090) {
				?> <div class=weapon><img alt="Ice Breaker" title="Ice Breaker" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303090_225fedd6aee041a44f45a27781dbe756.jpg></div><?php;
				}
			elseif ($card == 303092) {
				?> <div class=weapon><img alt="No Land Beyond" title="No Land Beyond" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303092_f94e3fe0709a1359ed4376eeecc8b11d.jpg></div><?php;
				}
			elseif ($card == 303220) {
				?> <div class=weapon><img alt="Dreg's Promise" title="Dreg's Promise" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/303220_a37c865dd7fb1256a94176d07c437db1.jpg></div><?php;
				}
			elseif ($card == 304020) {
				?> <div class=weapon><img alt="Gjallarhorn" title="Gjallarhorn" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/304020_35e7f5377f68ac0194ef4ceb9937f1b3.jpg></div><?php;
				}
			elseif ($card == 304030) {
				?> <div class=weapon><img alt="Truth" title="Truth" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/304030_edf44c11792d5fcd5f4ee87ca563c303.jpg></div><?php;
				}
			elseif ($card == 304032) {
				?> <div class=weapon><img alt="Dragon's Breath" title="Dragon's Breath" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/304032_efcf59a0f37e7cfef6d93b6836d317eb.jpg></div><?php;
				}
			elseif ($card == 304050) {
				?> <div class=weapon><img alt="Thunderlord" title="Thunderlord" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/304050_f2aae25a1d28910c3c34f33501ba0a41.jpg></div><?php;
				}
			elseif ($card == 304060) {
				?> <div class=weapon><img alt="Super Good Advice" title="Super Good Advice" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/304060_dd28552f262e49ea5338d3b9545df0db.jpg></div><?php;
				}
			elseif ($card == 700140) {
				?> <div class=weapon><img alt="Zhalo Supercell" title="Zhalo Supercell" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700140_a6f02f9480fe69914fb8c82c070b9b5e.jpg></div><?php;
				}
			elseif ($card == 700301) {
				?> <div class=weapon><img alt="Fabian Strategy" title="Fabian Strategy" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700301_f51775c8b996647483817bb8e4c2e0a4.jpg></div><?php;
				}
			elseif ($card == 700150) {
				?> <div class=weapon><img alt="The Jade Rabbit" title="The Jade Rabbit" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700150_a3edfe9ea64c8aae5bc885bf5a09e2a3.jpg></div><?php;
				}
			elseif ($card == 700160) {
				?> <div class=weapon><img alt="Boolean Gemini" title="Boolean Gemini" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700160_00172b9909c612692f522c4157b202da.jpg></div><?php;
				}
			elseif ($card == 700302) {
				?> <div class=weapon><img alt="Tlaloc" title="Tlaloc" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700302_1d201e18baecce0e24dedab7735f9055.jpg></div><?php;
				}
			elseif ($card == 700170) {
				?> <div class=weapon><img alt="The First Curse" title="The First Curse" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700170_dc20da449cfc46f4c4c8882198237c60.jpg></div><?php;
				}
			elseif ($card == 700300) {
				?> <div class=weapon><img alt="Ace of Spades" title="Ace of Spades" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700300_f65ae8f0312a6598a661706542d6f678.jpg></div><?php;
				}
			elseif ($card == 700220) {
				?> <div class=weapon><img alt="The Chaperone" title="The Chaperone" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700220_d751bb818feff0b7c32042c0c1183065.jpg></div><?php;
				}
			elseif ($card == 700230) {
				?> <div class=weapon><img alt="Telesto" title="Telesto" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700230_34b3e01d48413043dafdb88d095eac10.jpg></div><?php;
				}
			elseif ($card == 700210) {
				?> <div class=weapon><img alt="Hereafter" title="Hereafter" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700210_bd79c217003f341443d312a3dcee554c.jpg></div><?php;
				}
			elseif ($card == 700290) {
				?> <div class=weapon><img alt="Bolt-Caster" title="Bolt-Caster" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700290_c312b85ef74d5cd23f0d950b6d976f47.jpg></div><?php;
				}
			elseif ($card == 700291) {
				?> <div class=weapon><img alt="Raze-Lighter" title="Raze-Lighter" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700291_e4b7fdc4158a214a7b924f8fb8487a86.jpg></div><?php;
				}
			elseif ($card == 700292) {
				?> <div class=weapon><img alt="Dark-Drinker" title="Dark-Drinker" src=http://www.bungie.net/common/destiny_content/grimoire/hr_images/700292_bc0d47e599c9895edc19394ff1029864.jpg></div><?php;
				}

			
			
			else {
				echo '<div class=weapon>Ooops</div>';
			}
		//End of loop
		}
			
		//Showing how many weapons the user is missing
		$numberofmissingcards = count($missingCards);
		echo "<p>Thats is a total of " . $numberofmissingcards . " missing weapons</p>";
		

		//print_r($missingCards);
	}	
}
<br>
<br>
<br>
include 'ad.php';
?>
 
</body>
</html>
