<?php
$current = new CUser($db);

switch(strip_tags($_GET['fkt'])) {
	case 'save':
		$firstname = strip_tags($_POST['firstname']);
		$lastname = strip_tags($_POST['lastname']);
		$email = strtolower(strip_tags($_POST['email']));
		$lang = strip_tags($_POST['lang']);
		$pwd1 = strip_tags($_POST['pwd1']);

		if($lastname != $current->lastname) {
			$sql = "UPDATE users SET lastname = '".$lastname."' WHERE userid = ".$current->userid.";";
			$db->query($sql);
			$current->lastname = $lastname;
		}

		if($email != $current->email) {
			$sql = "SELECT * FROM users WHERE email LIKE '".$email."';";
			$result = $db->query($sql);

			if($result->num_rows) {
				echo "<div class='notif'>".$output['memberAlreadyExists']."</div>";
			} else {
				$sql = "UPDATE users SET email = '".$email."' WHERE userid = ".$current->userid.";";
				$db->query($sql);
				$current->email = $email;
			}
		}

		if($lang != $current->lang) {
			$sql = "UPDATE users SET lang = '".$lang."' WHERE userid = ".$current->userid.";";
			$db->query($sql);
			$current->lang = $lang;
			$_SESSION['lang'] = $lang;
		}

		if($pwd1 != "") {
			$sql = "UPDATE users SET password = '".md5($pwd1)."' WHERE userid = ".$current->userid.";";
			$db->query($sql);
		}

		echo "<div class='notif'>".$output['memberSaved']."</div>";
	break;
}
?>

<div class='heading'><?=$output['yourAccount'];?></div>





<form action="javascript:verifyData()" method="POST" id="frmMemberAdd">

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['memberFirstname'];?><br />
			<input type="text" name="firstname" id="frmFirstname" placeholder="" value="<?=$current->firstname;?>" class="thenexttripgoesto" readonly>
		</div>    
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['memberLastname'];?><br />
			<input type="text" name="lastname" id="frmLastname" placeholder="" value="<?=$current->lastname;?>" class="thenexttripgoesto">
		</div>    
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['email'];?><br />
			<input type="text" name="email" id="frmEmail" placeholder="" value="<?=$current->email;?>">
		</div>    
	</div>
	<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
		<div class="box">
			<br /><?=$output['lang'];?><br />
			<select name="lang" id="frmLang">
				<?php
				$dir = opendir("./lang/");
				while($item = readdir($dir)) {
					if($item != "." && $item != "..") {
						$data = explode(".",$item);
						echo "<option value='".$data[0]."' ";
						if($data[0] == $current->lang) {
							echo "selected";
						}
						echo ">".strtoupper($data[0]);
					}
				}
				?>
			</select>
		</div>    
	</div>
	<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
		<div class="box">
			<br /><?=$output['admin'];?><br />
			<select name="admin" id="frmAdmin" disabled>
				<option value="1"<?php if($current->admin) { echo " selected"; }?>><?=$output['yes'];?>
				<option value="0"<?php if(!$current->admin) { echo " selected"; }?>><?=$output['no'];?>
			</select>
		</div>    
	</div>
</div>



<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['password'];?><br />
			<input type="password" name="pwd1" id="frmPwd1" placeholder="" value="">
		</div>    
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><br />
			<input type="password" name="pwd2" id="frmPwd2" placeholder="Repeat" value="">
		</div>    
	</div>
</div>



<br />



<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="box" style='border: 3px solid #990000; padding: 15px' id="gdprWrap">
			<input type="checkbox" name="gdpr" id="gdpr" style='vertical-align: middle;' checked disabled> <small><?=$output['gdpr'];?></small>
		</div>    
	</div>
</div>



<br />



<div style="text-align: center">
	<input type="submit" value="<?=$output['save'];?>" class="btn">
</div>

<script>

function verifyData() {

	var valid = 0;
	var validcount = 5;
	
	if($("#frmFirstname").val() == "") {
		$("#frmFirstname").css({
			backgroundColor: "#990000",
			color: "#ffffff"
		});
	} else {
		$("#frmFirstname").css({
			backgroundColor: "#ffffff",
			color: "#000000"
		});
		valid++;
	}

	if($("#frmLastname").val() == "") {
		$("#frmLastname").css({
			backgroundColor: "#990000",
			color: "#ffffff"
		});
	} else {
		$("#frmLastname").css({
			backgroundColor: "#ffffff",
			color: "#000000"
		});
		valid++;
	}
	
	if($("#frmEmail").val() == "") {
		$("#frmEmail").css({
			backgroundColor: "#990000",
			color: "#ffffff"
		});
	} else {
		$("#frmEmail").css({
			backgroundColor: "#ffffff",
			color: "#000000"
		});
		valid++;
	}

	var gdpr = document.getElementById("gdpr");
	$("#gdprEmail").html($("#frmEmail").val());
	if(!gdpr.checked) {
		$("#gdprWrap").css({
			backgroundColor: "#990000",
			color: "#ffffff"
		});
	} else {
		$("#gdprWrap").css({
			backgroundColor: "#ffffff",
			color: "#000000"
		});
		valid++;
	}

	if($("#frmPwd1").val() != $("#frmPwd2").val()) {
		$("#frmPwd1").css({
			backgroundColor: "#990000",
			color: "#ffffff"
		});
		$("#frmPwd2").css({
			backgroundColor: "#990000",
			color: "#ffffff"
		});
	} else {
		$("#frmPwd1").css({
			backgroundColor: "#ffffff",
			color: "#000000"
		});
		$("#frmPwd2").css({
			backgroundColor: "#ffffff",
			color: "#000000"
		});
		valid++;
	}
	
	if(valid == validcount) {
		$("#frmMemberAdd").attr('action', 'index.php?do=yourAccount&fkt=save');
	}

	window.setTimeout("javascript:verifyData()",500);
}

window.setTimeout("javascript:verifyData()",500);

</script>
