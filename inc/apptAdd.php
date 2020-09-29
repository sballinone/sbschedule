<?php
switch(strip_tags($_GET['fkt'])) {
	case 'save':
		$title = strip_tags($_POST['title']);

		$apptDate = strip_tags($_POST['apptDate']);
		$apptDate = str_replace("/","-",$apptDate);
		if(strlen($apptDate) < 19) {
			$apptDate .= ":00";
		}
		
		$address = strip_tags($_POST['address']);

		$apptResponse = strip_tags($_POST['apptResponse']);
		if($apptResponse == "") {
			$apptResponse = 'NULL';
		} else {
			$apptResponse = str_replace("/","-",$apptResponse);
			if(strlen($apptResponse) < 19 && strlen($apptResponse) != "") {
				$apptResponse .= ":00";
			}
			$apptResponse = "'".$apptResponse."'";
		}
		
		$maxtn = strip_tags($_POST['maxtn']);
		if($maxtn == "") {
			$maxtn = 'NULL';
		} else {
			$maxtn = "'".$maxtn."'";
		}

		$sql = "INSERT INTO appointment VALUES (
			NULL,
			'".$user->userid."',
			'".$title."',
			'".$address."',
			'".$apptDate."',
			'".date('Y-m-d H:i:s')."',
			NULL,
			".$apptResponse.",
			".$maxtn.",
			'1');";
		$db->query($sql);
		$newID = $db->insert_id;
		
		$sql = "SELECT users.email, users.firstname FROM users;";
		$result = $db->query($sql);

		if($result->num_rows) {
			while($data = $result->fetch_assoc()) {
				$mtxt = "Hi ".$data['firstname'].",\r\n\r\n";
				$mtxt .= $output['apptNew']."\r\n";
				$mtxt .= date('d. M Y, H:i', strtotime($apptDate)).": ".$title."\r\n".$output['apptResponseDate']." ".$apptResponse;
				$mtxt .= $mtxtfooter;

				mail($data['email'],"SB Schedule · Event ".$title." created",$mtxt,"From: info@saskiabrueckner.com");
			}
		}

		echo "<script>location.href='index.php?do=showAppt&id=".$newID."';</script>";
		echo "Event created. <a href='index.php'>Continue</a>";
		exit;
	break;
}

?>

<div class='heading'><?=$output['apptAdd'];?></div>





<form action="javascript:verifyData()" method="POST" id="frmApptAdd">

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="box">
			<br /><?=$output['apptTitle'];?><br />
			<input type="text" name="title" id="frmApptTitle" placeholder="" value="" class="thenexttripgoesto">
		</div>    
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['apptDate'];?><br />
			<input type="text" name="apptDate" id="frmApptDate" placeholder="YYYY-MM-DD HH:MM:SS" value="">
		</div>    
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['apptLocation'];?><br />
			<input type="text" name="address" id="frmAddress" placeholder="" value="">
		</div>    
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['apptResponseDate'];?><br />
			<input type="text" name="apptResponse" id="frmApptResponse" placeholder="YYYY-MM-DD HH:MM:SS" value="">
		</div>    
	</div>
	
	<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
		<div class="box">
			<br /><?=$output['apptMax'];?><br />
			<input type="text" name="maxtn" placeholder="" value="">
		</div>    
	</div>
	<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
		<div class="box">
			<br /><?=$output['apptStatus'];?><br />
			
			<input type="text" value="<?=$output['new'];?>" readonly>

		</div>    
	</div>
</div>



<br />



<div style="text-align: center">
	<input type="submit" value="<?=$output['save'];?>" class="btn">
</div>

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

	$('#tnCount').html("<?=$tnCount;?>");
});

function verifyData() {

	var valid = false;
	
	if($("#frmApptTitle").val() == "") {
		$("#frmApptTitle").css({
			backgroundColor: "#990000",
			color: "#ffffff"
		});
		valid = false;
	} else {
		$("#frmApptTitle").css({
			backgroundColor: "#ffffff",
			color: "#000000"
		});
		valid = true;
	}

	if($("#frmApptDate").val() == "") {
		$("#frmApptDate").css({
			backgroundColor: "#990000",
			color: "#ffffff"
		});
		valid = false;
	} else {
		$("#frmApptDate").css({
			backgroundColor: "#ffffff",
			color: "#000000"
		});
		valid = true;
	}
	
	if($("#frmAddress").val() == "") {
		$("#frmAddress").css({
			backgroundColor: "#990000",
			color: "#ffffff"
		});
		valid = false;
	} else {
		$("#frmAddress").css({
			backgroundColor: "#ffffff",
			color: "#000000"
		});
		valid = true;
	}
	
	if(valid) {
		$("#frmApptAdd").attr('action', 'index.php?do=apptAdd&fkt=save');
	}

	window.setTimeout("javascript:verifyData()",500);
}

window.setTimeout("javascript:verifyData()",500);

</script>
