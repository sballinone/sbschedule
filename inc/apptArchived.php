<span class='heading'><?=$output['archived'];?></span><br />

<div class="elements" style='margin-top: 50px'>	

	<?php
	// initialize archived events classes 
	$archivedAppts = [];
	$appt;
	$result = $db->query("SELECT * FROM appointment WHERE apptDate < CURDATE() ORDER BY apptDate");

	while($data = $result->fetch_assoc()) {
		$appt = new CAppointment($db, $data['appointmentid']);
		array_push($archivedAppts, $appt);
	}

	// List all archived appointments
	if(count($archivedAppts) > 0) {
		foreach($archivedAppts as $appt) {


			// Check response
			$styleBACK = $styleBTN = "";

			$result = $db->query("SELECT response FROM responses WHERE userid = ".$user->userid." AND appointmentid = ".$appt->appointmentid);
			if($result->num_rows) {
				$data = $result->fetch_assoc();
				switch($data['response']) {
					case '1': 
						$styleBTN = "background-color: #009900;";
						$styleBACK = "";
					break;

					case '-1':
						$styleBTN = "background-color: #990000;";
						$styleBACK = "background-color: #999999";
					break;
				}
			}

			if(!$appt->enabled) {
				$styleBACK = "background-color: #990000; color: #ffffff;";
			}


			echo "<div class='attraction' onclick='javascript:location.href=\"index.php?do=showAppt&id=".$appt->appointmentid."\"' style='border: 1px #000000 solid; ".$styleBACK."'>";
				echo "<div class='itemIcon' style='".$styleBTN."'>";
					echo "<i class='icofont-spoon-and-fork'></i>";
				echo "</div>";
				echo "<strong>".$appt->title."</strong><br />";
				echo "<small><span class='addr'>".date('d. M Y',strtotime($appt->apptDate))." · ".$appt->address."</span></small>";
			echo "</div>";
		} 
	} else {
		echo "<p style='text-align: center; font-size: 10pt;'>".$output['apptNone']."</p>";
	}
	?>
</div>
