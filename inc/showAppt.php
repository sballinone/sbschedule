<?php
$current = strip_tags($_GET['id']);

$found = 0;
foreach($allAppts as $appt) {
	if($appt->appointmentid == $current) {
		$found = 1;
		$current = $appt;
		break;
	}
}

if(!$found) { // if the event is a past one, $found will be 0. Important for future usage.
	$current = new CAppointment($db, $current);
}

// Check response
$responsed = false;
$result = $db->query("SELECT response, people FROM responses WHERE userid = ".$user->userid." AND appointmentid = ".$current->appointmentid);
if($result->num_rows) {
	$data = $result->fetch_assoc();
	switch($data['response']) {
		case '1': 
			$addAttrYes = " style='background-color: #009900; color: #ffffff'";
		break;
		
		case '-1':
			$addAttrNo = " style='background-color: #990000; color: #ffffff'";
		break;
	}
	$tn = $data['people'];
	$responsed = true;
}

$tnCount = 0;



switch(strip_tags($_GET['fkt'])) {
	case 'cancel':
		$sql="UPDATE appointment SET enabled = 0 WHERE appointmentid = ".$current->appointmentid.";";
		$result = $db->query($sql);

		$sql="SELECT users.email, users.firstname, users.lang FROM users left join responses ON responses.userid = users.userid WHERE responses.appointmentid = ".$current->appointmentid.";";
		$result = $db->query($sql);

		if($result->num_rows) {
			while($data = $result->fetch_assoc()) {

				if(file_exists("./lang/".$data['lang'].".php")) {
					include "./lang/".$data['lang'].".php";
				} else {
					include "./lang/".$langdefault.".php";
				}

				$mtxt = "Hi ".$data['firstname'].",\r\n\r\n";
				$mtxt .= $output['apptCancelMailBeforeDetails']."\r\n";
				$mtxt .= date('d. M Y, H:i', strtotime($current->apptDate)).": ".$current->title."\r\n\r\n";
				$mtxt .= $output['apptCancelMailAfterDetails'];
				$mtxt .= $mtxtfooter;

				mail($data['email'],"SB Schedule · Event ".$current->title." canceled",$mtxt,"From: info@saskiabrueckner.com");

				if(file_exists("./lang/".$_SESSION['lang'].".php")) {
					include "./lang/".$_SESSION['lang'].".php";
				} else {
					include "./lang/".$langdefault.".php";
				}
			}
		}

		$current->enabled = 0;

		echo "<div class='notif'>".$output['apptCanceled']."</div>";
	break;

	case 'save':
		$apptDate = strip_tags($_POST['apptDate']);
		$apptDate = str_replace("/","-",$apptDate);
		if(strlen($apptDate) < 19) {
			$apptDate .= ":00";
		}
		if($apptDate != $current->apptDate) {
			$sql="UPDATE appointment SET apptDate = '".$apptDate."', updated = '".date('Y-m-d H:i:s')."' WHERE appointmentid = ".$current->appointmentid.";";
			$db->query($sql);
		}
		
		$address = strip_tags($_POST['address']);
		if($address != $current->address) {
			$sql="UPDATE appointment SET address = '".$address."', updated = '".date('Y-m-d H:i:s')."' WHERE appointmentid = ".$current->appointmentid.";";
			$db->query($sql);
		}

		$apptResponse = strip_tags($_POST['apptResponse']);
		$apptResponse = str_replace("/","-",$apptResponse);
		if(strlen($apptResponse) < 19 && strlen($apptResponse) != "") {
			$apptResponse .= ":00";
		}
		if($apptResponse != $current->response) {
			$sql="UPDATE appointment SET response = '".$apptResponse."', updated = '".date('Y-m-d H:i:s')."' WHERE appointmentid = ".$current->appointmentid.";";
			$db->query($sql);
		}
		
		$maxtn = strip_tags($_POST['maxtn']);
		if($maxtn != $current->max) {
			$sql="UPDATE appointment SET max = '".$maxtn."', updated = '".date('Y-m-d H:i:s')."' WHERE appointmentid = ".$current->appointmentid.";";
			$db->query($sql);
		}
		
		$sql="SELECT users.email, users.firstname, users.lang FROM users left join responses ON responses.userid = users.userid WHERE responses.appointmentid = ".$current->appointmentid.";";
		$result = $db->query($sql);

		if($result->num_rows) {
			while($data = $result->fetch_assoc()) {

				if(file_exists("./lang/".$data['lang'].".php")) {
					include "./lang/".$data['lang'].".php";
				} else {
					include "./lang/".$langdefault.".php";
				}

				$mtxt = "Hi ".$data['firstname'].",\r\n\r\n";
				$mtxt .= $output['apptChangedMail']."\r\n";
				$mtxt .= date('d. M Y, H:i', strtotime($current->apptDate)).": ".$current->title."\r\n".$output['apptResponseDate']." ".$current->response;
				$mtxt .= $mtxtfooter;

				mail($data['email'],"SB Schedule · Event ".$current->title." changed",$mtxt,"From: info@saskiabrueckner.com");

				if(file_exists("./lang/".$_SESSION['lang'].".php")) {
					include "./lang/".$_SESSION['lang'].".php";
				} else {
					include "./lang/".$langdefault.".php";
				}
			}
		}

		$current = new CAppointment($db, $current->appointmentid);

		echo "<div class='notif'>".$output['apptChanged']."</div>";
	break;

	case 'accept':
		if($responsed) {
			$sql="UPDATE responses SET response = 1, changed = '".date('Y-m-d H:i:s')."', people = ".strip_tags($_GET['people'])." WHERE appointmentid = ".$current->appointmentid." AND userid = ".$user->userid.";";
		} else {
			$sql="INSERT INTO responses VALUES (
				NULL,
				'".$user->userid."',
				'".$current->appointmentid."',
				'1',
				'".strip_tags($_GET['people'])."',
				'".date('Y-m-d H:i:s')."',
				NULL);";
		}
		$result = $db->query($sql);

		$mtxt = "Hi ".$user->firstname.",\r\n\r\n";
		$mtxt .= $output['apptConfirmedMail']."\r\n";
		$mtxt .= date('d. M Y, H:i', strtotime($current->apptDate)).": ".$current->title.", ".strip_tags($_GET['people'])." PAX";
		$mtxt .= $mtxtfooter;

		mail($user->email,"SB Schedule · Event ".$current->title." confirmed",$mtxt,"From: info@saskiabrueckner.com");

		$sql = "SELECT users.email, users.lang FROM users LEFT JOIN appointment ON users.userid = appointment.userid;";
		$result = $db->query($sql);
		$data = $result->fetch_assoc();


		if(file_exists("./lang/".$data['lang'].".php")) {
			include "./lang/".$data['lang'].".php";
		} else {
			include "./lang/".$langdefault.".php";
		}

		$mtxt = "New confirmation for ".date('d. M Y, H:i', strtotime($current->apptDate)).": ".$current->title."\r\n";
		$mtxt .= $user->firstname." ".$user->lastname.", ".strip_tags($_GET['people'])." PAX";
		$mtxt .= $mtxtfooter;

		mail($data['email'],"SB Schedule · Event ".$current->title." confirmed",$mtxt,"From: info@saskiabrueckner.com");
		
		if(file_exists("./lang/".$_SESSION['lang'].".php")) {
			include "./lang/".$_SESSION['lang'].".php";
		} else {
			include "./lang/".$langdefault.".php";
		}


		echo "<div class='notif'>".$output['apptConfirmed']."</div>";

		$addAttrYes = " style='background-color: #009900; color: #ffffff'";
		$addAttrNo = "";
	break;
	
	case 'decline':
		if($responsed) {
			$sql="UPDATE responses SET response = -1, changed = '".date('Y-m-d H:i:s')."', people = 0 WHERE appointmentid = ".$current->appointmentid." AND userid = ".$user->userid.";";
		} else {
			$sql="INSERT INTO responses VALUES (
				NULL,
				'".$user->userid."',
				'".$current->appointmentid."',
				'-1',
				'0',
				'".date('Y-m-d H:i:s')."',
				NULL);";
		}
		$result = $db->query($sql);

		$mtxt = "Hi ".$user->firstname.",\r\n\r\n";
		$mtxt .= $output['apptDeclinedMail']."\r\n";
		$mtxt .= date('d. M Y, H:i', strtotime($current->apptDate)).": ".$current->title.", 0 PAX";
		$mtxt .= $mtxtfooter;

		mail($user->email,"SB Schedule · Event ".$current->title." declined",$mtxt,"From: info@saskiabrueckner.com");

		$sql = "SELECT users.email, users.lang FROM users LEFT JOIN appointment ON users.userid = appointment.userid;";
		$result = $db->query($sql);
		$data = $result->fetch_assoc();


		if(file_exists("./lang/".$data['lang'].".php")) {
			include "./lang/".$data['lang'].".php";
		} else {
			include "./lang/".$langdefault.".php";
		}

		$mtxt = "New cancelation for ".date('d. M Y, H:i', strtotime($current->apptDate)).": ".$current->title."\r\n";
		$mtxt .= $user->firstname." ".$user->lastname.", 0 PAX";
		$mtxt .= $mtxtfooter;

		mail($data['email'],"SB Schedule · Event ".$current->title." declined",$mtxt,"From: info@saskiabrueckner.com");
		
		if(file_exists("./lang/".$_SESSION['lang'].".php")) {
			include "./lang/".$_SESSION['lang'].".php";
		} else {
			include "./lang/".$langdefault.".php";
		}


		echo "<div class='notif'>".$output['apptDeclined']."</div>";

		$addAttrYes = "";
		$addAttrNo = " style='background-color: #990000; color: #ffffff'";
	break;
}

?>


<span class='heading'><?=$current->title;?></span><br />
<small>
	<form method="post" action="inc/ics.php">
		<?php
			echo "<strong>".date('d. M Y H:i', strtotime($current->apptDate))."</strong> · ";
			echo "<span id='tnCount'>".$tnCount."</span> ".$output['apptSaidYes']."<br />";
			
			$sql = "SELECT firstname, lastname, email FROM users WHERE userid = ".$current->userid.";";
			$result = $db->query($sql);
			$data = $result->fetch_assoc();
			
			echo $output['organizer']." ".$data['firstname']." ".$data['lastname']." <a href='mailto:".$data['email']."' style='text-decoration: none; color: #ffffff; background-color: #000000; padding: 2px 5px; padding-right: 0'><i class='icofont-envelope'> </i></a>&nbsp;";

			$utcTz = new DateTimeZone('UTC');
			$cetTz = new DateTimeZone($groupTimezone);

			$starttime = new DateTime(date('Ymd\THis',strtotime($current->apptDate)), $cetTz);
			$starttime->setTimezone($cetTz);
			$endtime = new DateTime(date('Y-m-d H:i', strtotime($current->apptDate)), $cetTz);
			$endtime->modify("+3 hours");
			$endtime->setTimezone($cetTz);
		?>
		<input type="hidden" name="date_start" value="<?=$starttime->format('Ymd\THis');?>">
		<input type="hidden" name="date_end" value="<?=$endtime->format('Ymd\THis');?>">
		<input type="hidden" name="location" value="<?=$current->address;?>">
		<input type="hidden" name="description" value="Imported by SB Schedule">
		<input type="hidden" name="summary" value="<?=$current->title;?>">
		<input type="hidden" name="url" value="<?=$group_url;?>">
		<input type="submit" value="Add to Calendar" style='text-decoration: none; color: #ffffff; background-color: #000000; padding: 2px 5px; width: auto; font-size: 10pt; height: 22px;'>
	</form>
</small>

<form action="index.php?do=showAppt&fkt=save&id=<?=$current->appointmentid;?>" method="POST">

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['apptDate'];?><br />
			<input type="text" name="apptDate" id="frmApptDate" placeholder="YYYY-MM-DD HH:MM:SS" value="<?=$current->apptDate;?>" <?php if(!$user->admin || !$current->enabled) echo "readonly"; ?>>
		</div>    
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['apptLocation'];?><br />
			<input type="text" name="address" placeholder="<?=$output['address'];?>" value="<?=$current->address;?>" <?php if(!$user->admin || !$current->enabled) echo "readonly"; ?>>
		</div>    
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['apptResponseDate'];?><br />
			<input type="text" name="apptResponse" id="frmApptResponse" placeholder="YYYY-MM-DD HH:MM:SS" value="<?=$current->response;?>" <?php if(!$user->admin || !$current->enabled) echo "readonly"; ?>>
		</div>    
	</div>
	


	<?php 
	if($user->admin) {
		?>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
			<div class="box">
				<br /><?=$output['apptMax'];?><br />
				<input type="text" name="maxtn" placeholder="" value="<?=$current->max;?>" <?php if(!$user->admin || !$current->enabled) echo "readonly"; ?>>
			</div>    
		</div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
		<?php 
	} else {
		echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">';
	} ?>



		<div class="box">
			<br /><?=$output['apptStatus'];?><br />
			
			<?php
			echo '<input type="text" value="';
			if($current->enabled) {
				if($current->response > date('Y-m-d H:i:s')) {
					echo $output['apptWaitForTN'];
				} else if($current->apptDate < date('Y-m-d H:i:s')) {
					echo $output['apptFinished'].'" style="color: #c0c0c0;';
				} else {
					echo $output['apptReady'].'" style="background-color: #009900; color: #ffffff;';
				}
			} else {
				echo $output['apptDisabled'].'" style="background-color: #990000; color: #ffffff;';
			}
			echo '" readonly>';
			?>

		</div>    
	</div>
</div>



<br />



<?php if($current->enabled && ($current->response >= date('Y-m-d h:i:s') || !$current->response) && ($current->apptDate >= date('Y-m-d h:i:s'))) { 
	?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="box">
				<input type="button" class="btnWhite" value="<?=$output['apptSayNo'];?>" <?=$addAttrNo;?> onclick="location.href='index.php?do=showAppt&fkt=decline&id=<?=$current->appointmentid;?>'">
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="box">
				<input type="button" class="btnWhite" value="<?=$output['apptSayYes'];?>" <?=$addAttrYes;?> onclick="location.href='javascript:accept()'">
			</div>
		</div>
	</div>
<?php } else { ?>
	<?php if($current->enabled && $responsed && ($current->apptDate >= date('Y-m-d h:i:s'))) { 
		?>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="box">
					<input type="button" class="btnWhite" value="<?=$output['apptSayNo'];?>" <?=$addAttrNo;?> onclick="location.href='index.php?do=showAppt&fkt=decline&id=<?=$current->appointmentid;?>'">
				</div>
			</div>
		</div>
	<?php } 
}?>



<br />



<div class="row">
	<?php
	$result = $db->query("SELECT * FROM users ORDER BY lastname");
	while($data = $result->fetch_assoc()) {
		if($data['active']) {
			echo '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">';

			$result2 = $db->query("SELECT * FROM responses WHERE userid = ".$data['userid']." AND appointmentid = ".$current->appointmentid.";");
			
			if($result2->num_rows) {
				$data2 = $result2->fetch_assoc();

				switch($data2['response']) {
					case '1':
						echo '<div class="box apptSayYes">';
						echo $data['firstname']." ".$data['lastname']." (".$data2['people'].")";
						$tnCount += $data2['people'];
					break;
					
					case '-1':
						echo '<div class="box apptSayNo">';
						echo $data['firstname']." ".$data['lastname'];
					break;
					
					case '0':
						echo '<div class="box apptSayWait">';
						echo $data['firstname']." ".$data['lastname'];
					break;
				}
			} else {
				echo '<div class="box apptSayWait">';
				echo $data['firstname']." ".$data['lastname'];
			}

			echo "</div></div>";
		}
	}
	?>
</div>



<br>



<div style="text-align: center">
	<?php 
	if($user->admin && $found == 1 && $current->enabled) {
		echo '<input type="submit" value="'.$output['apptSave'].'" class="btn">';
		echo '<a href="javascript:cancel()" class="btnDelete">'.$output['apptCancel'].'</a>';
	} 
	?>
</div>

<script>
<?php if($user->admin && $current->enabled) { ?>
	document.addEventListener("DOMContentLoaded", function(event) {
		jQuery('#frmApptDate').datetimepicker({
			formatDate: 'Y-m-d H:i',
			minDate: 0,
			mask: true,
			value: '<?=$current->apptDate;?>'
		});

		jQuery('#frmApptResponse').datetimepicker({
			formatDate: 'Y-m-d H:i',
			minDate: 0,
			mask: true,
			value: '<?=$current->response;?>'
		});

		$('#tnCount').html("<?=$tnCount;?>");
	});
<?php } ?>

function cancel() {
	var uri = "index.php?do=showAppt&fkt=cancel&id=<?=$current->appointmentid;?>";

	if(confirm("<?=$output['apptCancelConfirm'];?>")) {
		location.href=uri;
	}
}

function accept() {
	var uri = "index.php?do=showAppt&fkt=accept&id=<?=$current->appointmentid;?>";

	var people = prompt("<?=$output['apptAcceptHowMany'];?>","1");
	
	<?php
		if($responsed) {
			echo "var responsed = true;";
			echo "var ownedplaces = ".$tn.";";
		} else {
			echo "var responsed = false;";
			echo "var ownedplaces = 0;";
		}

		if($current->max) {
			echo "var max = ".$current->max."; ";
		} else {
			echo "var max = 32000000; ";
		}
		echo "var tnCount = ".$tnCount."; ";
	?>

	if((parseInt(tnCount) + parseInt(people) - parseInt(ownedplaces)) > max) {
		alert("<?=$output['apptTooMany'];?>");
	} else {
		location.href=uri + "&people=" + people;
	}
}
</script>
