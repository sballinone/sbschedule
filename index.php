<?php
$release="0.3.2";

session_start();

if(!isset($_SESSION['userid'])) {
	session_destroy();
	header("Location: signin.php");
	exit;
}

require_once "header.php";
?>

<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>SB Schedule</title>

	<link rel="stylesheet" href="./assets/css/fonts.css" type="text/css">
	<link rel="stylesheet" href="./assets/css/style.css" type="text/css">
	<link rel="stylesheet" href="./assets/external/icofont/icofont.min.css" type="text/css">
	<link rel="stylesheet" href="./assets/external/flexboxgrid/flexboxgrid.min.css" type="text/css">
	<link rel="stylesheet" href="./assets/external/jquery/jquery.datetimepicker.min.css" type="text/css">
</head>
<body>

<div id="header">
	<div class="row">
		<div class="col-xs-7 col-sm-5 col-md-5 col-lg-5">
			<div class="box" id="logo">
				<img src="./assets/img/logo/white/Logo-Square-White.png" alt="World of SB">
				Schedule
				<span style="color: #ff8a00"><?=$groupName;?></span>
			</div>
		</div>
		<div class="col-xs-1 col-sm-4 col-md-5 col-lg-5">
			<div class="box">
				&nbsp;
			</div>
		</div>
		<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
			<!--<div class="box" id="tripSum">
				<i class='icofont-spoon-and-fork'></i> 1
			</div>-->
		</div>
		<div class="col-xs-2 col-sm-1 col-md-1 col-lg-1">
			<div class="box" id="logout" onclick="javascript:location.href='signin.php'">
				<a href='signin.php'><i class="icofont-exit"></i></a>
			</div>
		</div>
	</div>
</div>

<div id="wrap">
	<div id="ui">
		<div id="userInteraction">

			<div class='heading' style='color: #ffffff'><?=$output['hi'].' '.$user->firstname.'<br />'.$output['nicetoseeyou'];?></div>

				<div class="elements" style='margin-top: 50px'>			

					<!-- Add Appt -->
					<?php if($user->admin) { ?>
						<div class='attraction' onclick='location.href="index.php?do=apptAdd"' style='background-color: #333333; border: 1px #ffffff solid; color: #efefef'>
							<strong><?=$output['apptAdd'];?></strong>
						</div>
					<?php } ?>

					<?php
					// List all future appointments
					if(count($allAppts) > 0) {
						foreach($allAppts as $appt) {


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


							echo "<div class='attraction' onclick='javascript:location.href=\"index.php?do=showAppt&id=".$appt->appointmentid."\"' style='".$styleBACK."'>";
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
					<!-- /Add Appt -->

				</div>

				<!-- User Menu -->
				<div class="elements" style='margin-top: 50px;'>
					<!--<div class='attraction' onclick='javascript:location.href="index.php?do=apptRegistrations"' style='background-color: #333333; border: 1px #ffffff solid; color: #efefef'>
						<strong><?=$output['apptRegistrations'];?></strong>
					</div>-->
					<div class='attraction' onclick='javascript:location.href="index.php?do=apptArchived"' style='background-color: #333333; border: 1px #ffffff solid; color: #efefef'>
						<strong><?=$output['apptArchived'];?></strong>
					</div>

					<br />

					<!--<div class='attraction' onclick='javascript:location.href="index.php?do=yourAccount"' style='background-color: #333333; border: 1px #ffffff solid; color: #efefef'>
						<strong><?=$output['yourAccount'];?></strong>
					</div>-->
					<!--<?php if($user->admin) { ?>
						<div class='attraction' onclick='javascript:location.href="index.php?do=members"' style='background-color: #333333; border: 1px #ffffff solid; color: #efefef'>
							<strong><?=$output['members'];?></strong>
						</div>
					<?php } ?>-->
				</div>
				<!-- /User Menu -->

				<div id='credits'><br />
					&copy; <?=date('Y');?> <a href='https://saskiabrueckner.com' target='_blank'>World of SB</a> · Images: Dinner on <a href='https://unsplash.com' target='_blank'>unsplash.com</a><br /><br />
					<a href='https://saskiabrueckner.com' target='_blank' style="text-decoration: none;">
						<img src="./assets/img/logo/white/Logo-Full-White.png" height="62px" style="border: 0" onmouseover="this.src='./assets/img/logo/pink/Logo-Full-Pink.png';" onmouseout="this.src='./assets/img/logo/white/Logo-Full-White.png';">
					</a><br />
					Saskia Brückner Kreativagentur<br />Reichenberger Str. 21 · 71638 Ludwigsburg<br />
					07141 309 747 0 · sales@saskiabrueckner.com
				</div>

			</div>
			<div id="uiContentWrap">
				<?php
				echo '<div id="uiContent"';
				if(!isset($_GET['do'])) {
					echo ' style="background-image: URL(\'https://source.unsplash.com/1600x900/?dinner\')"';
				}
				echo '>';
			
					if(isset($_GET['do'])) {
						if(!file_exists("./inc/".strip_tags($_GET['do']).".php")) {
							echo "Function ".strip_tags($_GET['do'])." not yet implemented.";
						} else {
							include("./inc/".strip_tags($_GET['do']).".php");
						}
					}

				echo '</div>';
				?>
			<div id="tripSumDetails">
				Neue Termine:<br />
				Deine Anmeldungen:<br />
			</div>
		</div>
	</div>
</div>



<div id="shadow"></div>
<div id="elementDetail">
	Loading content...
</div>

<noscript>
	<div id="noscript">
		<img src="./assets/img/logo/black/Logo-Full.png" style="width: 150px"><br /><br />
		<strong>Oh no...</strong><br />
		I need JavaScript to work.<br /><br />
		<small>
			Today, almost every website contains JavaScript for specific functions. If it is disabled, websites may be limited or not available. Those functionalities may include navigations or sliders. If you do not know, how to enable JavaScript, you may want to visit the following website: <br />
			<a href='https://www.enable-javascript.com' target='_blank' style='color: #000000;'>How to enable JavaScript and why</a>
		</small>
	</div>
	<style>
		#noscript {
			background-color: #ffffff; 
			box-sizing: border-box;
			color: #000000; 
			font-family: 'PT Sans', serif; 
			font-size: 15pt; 
			height: 100%; 
			left: 0; 
			line-height: 1.5;
			padding: 100px 40% 50px 20%;
			position: fixed;
			text-align: justify;
			top: 25px; 
			width: 100%; 
			z-index:99; 
		}
	</style>
</noscript>

<script src="./assets/external/jquery/jquery-3.5.1.min.js"></script>
<script src="./assets/external/jquery/jquery-ui.min.js"></script>
<script src="./assets/external/jquery/jquery.datetimepicker.full.min.js"></script>

<!-- PHP to JS -->
<?php/*
echo "<script>";
echo "var name='value';";
echo "</script>"; */
?>

<!-- Script -->
<script src="./assets/js/scripts.js"></script>

</body>
</html>

<?php
$db->close();
?>
