<?php
switch(strip_tags($_GET['fkt'])) {
	case 'save':
		$firstname = strip_tags($_POST['firstname']);
		$lastname = strip_tags($_POST['lastname']);
		$email = strtolower(strip_tags($_POST['email']));
		$lang = strip_tags($_POST['lang']);
		$enabled = strip_tags($_POST['enabled']);
		$admin = strip_tags($_POST['admin']);

		$pwd = rand(10000,99999);

		$sql = "SELECT * FROM users WHERE email LIKE '".$email."';";
		$result = $db->query($sql);

		if($result->num_rows) {
			echo "<div class='notif'>".$output['memberAlreadyExists']."<br />".$email."</div>";
			break;
		} else {
			$sql = "INSERT INTO users VALUES (
				NULL,
				'".$firstname."',
				'".$lastname."',
				'".$email."',
				'".md5($pwd)."',
				'".$lang."',
				'".$enabled."',
				'".$admin."');";
			$db->query($sql);
			
			
			if(file_exists("./lang/".$lang.".php")) {
				include "./lang/".$lang.".php";
			} else {
				include "./lang/".$langdefault.".php";
			}

			$mtxt = "Hi ".$firstname.",\r\n\r\n";
			$mtxt .= $output['memberNew']."\r\n";
			$mtxt .= $output['password'].": ".$pwd."\r\n";
			$mtxt .= "Link: https://".$_SERVER['HTTP_HOST'];
			$mtxt .= $mtxtfooter;
			
			mail($data['email'],"SB Schedule · Your user account",$mtxt,"From: info@saskiabrueckner.com");

			if(file_exists("./lang/".$_SESSION['lang'].".php")) {
				include "./lang/".$_SESSION['lang'].".php";
			} else {
				include "./lang/".$langdefault.".php";
			}
			
			
			echo "<script>location.href='index.php?do=members';</script>";
			echo "Member created. <a href='index.php'>Continue</a>";
			exit;
		}
	break;
}
?>

<div class='heading'><?=$output['memberAdd'];?></div>





<form action="javascript:verifyData()" method="POST" id="frmMemberAdd">

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['memberFirstname'];?><br />
			<input type="text" name="firstname" id="frmFirstname" placeholder="" value="" class="thenexttripgoesto">
		</div>    
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['memberLastname'];?><br />
			<input type="text" name="lastname" id="frmLastname" placeholder="" value="" class="thenexttripgoesto">
		</div>    
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="box">
			<br /><?=$output['email'];?><br />
			<input type="text" name="email" id="frmEmail" placeholder="" value="">
		</div>    
	</div>
	<div class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
		<div class="box">
			<br /><?=$output['lang'];?><br />
			<select name="lang" id="frmLang">
				<?php
				$dir = opendir("./lang/");
				while($item = readdir($dir)) {
					if($item != "." && $item != "..") {
						$data = explode(".",$item);
						echo "<option value='".$data[0]."'>".strtoupper($data[0]);
					}
				}
				?>
			</select>
		</div>    
	</div>
	<div class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
		<div class="box">
			<br /><?=$output['enabled'];?><br />
			<select name="enabled" id="frmEnabled">
				<option value="1" selected><?=$output['yes'];?>
				<option value="0"><?=$output['no'];?>
			</select>
		</div>    
	</div>
	<div class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
		<div class="box">
			<br /><?=$output['admin'];?><br />
			<select name="admin" id="frmAdmin">
				<option value="1"><?=$output['yes'];?>
				<option value="0" selected><?=$output['no'];?>
			</select>
		</div>    
	</div>
</div>



<br />



<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="box" style='border: 3px solid #990000; padding: 15px' id="gdprWrap">
			<input type="checkbox" name="gdpr" id="gdpr" style='vertical-align: middle;'> <small><?=$output['gdpr'];?></small>
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
	var validcount = 4;
	
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
	
	if(valid == validcount) {
		$("#frmMemberAdd").attr('action', 'index.php?do=newMember&fkt=save');
	}

	window.setTimeout("javascript:verifyData()",500);
}

window.setTimeout("javascript:verifyData()",500);

</script>
