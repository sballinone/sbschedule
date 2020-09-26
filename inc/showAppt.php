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
?>

<form action="index.php?do=showAppt&save=true&id=<?=$current->appointmentid;?>" method="POST">

<span class='heading'><?=$current->title;?></span><br />
<small><?=date('d. M Y H:i', strtotime($current->apptDate));?></small>

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
	<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
		<div class="box">
			<br /><?=$output['apptMax'];?><br />
			<input type="text" name="max" placeholder="" value="<?=$current->max;?>" <?php if(!$user->admin || !$current->enabled) echo "readonly"; ?>>
		</div>    
	</div>
	<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
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



<?php if($current->enabled) { 
	
	// Check response
	$result = $db->query("SELECT response FROM responses WHERE userid = ".$user->userid." AND appointmentid = ".$current->appointmentid);
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
	}
	
	?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="box">
				<input type="button" class="btnWhite" value="<?=$output['apptSayNo'];?>" <?=$addAttrNo;?>>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="box">
				<input type="button" class="btnWhite" value="<?=$output['apptSayYes'];?>" <?=$addAttrYes;?>>
			</div>
		</div>
	</div>
<?php } ?>



<br />



<div style="text-align: center">
	<?php 
	if($user->admin && $found == 1 && $current->enabled) {
		echo '<input type="submit" value="'.$output['apptSave'].'" class="btn">';
		echo '<a href="index.php?do=showAppt&cancel=true&id='.$current->appointmentid.'" class="btnDelete">'.$output['apptCancel'].'</a>';
	} 
	?>
</div>

<?php if($user->admin && $current->enabled) { ?>
	<script>
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
	});
	</script>
<?php } ?>
