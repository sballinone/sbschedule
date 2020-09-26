<?php
session_start();
session_destroy();
session_start();

include("header.php");

if (isset($_POST['pwd'])) {
	$sql = "SELECT userid FROM users WHERE email = '".strip_tags($_POST['email'])."' AND password = '".md5(strip_tags($_POST['pwd']))."' AND active = 1;";

	$result = $db->query($sql);

	if(!$result->num_rows) {
		echo "<script>alert('".$output['loginFail']."');</script>";
	} else {
		$data = $result->fetch_assoc();
		$_SESSION['userid'] = $data['userid'];
		$_SESSION['lang'] = $data['lang'];
		header("Location: index.php");
		exit;
	}
}

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
</head>
<body>

<div id="header">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="box" id="logo">
				<img src="./assets/img/logo/white/Logo-Square-White.png" alt="World of SB">
				Schedule
				<span style="color: #ff8a00"><?=$groupName;?></span>
			</div>
		</div>
	</div>
</div>

<div id="wrap">
	<div id="ui">
		<div id="userInteraction">
			<div class='heading' style='color: #ffffff'><?=$output['welcome'];?><br /><br /></div>

            <form action="signin.php" method="POST">
                <input type="text" placeholder="<?=$output['email']; ?>" name="email">&nbsp;&nbsp;&nbsp;
                <input type="password" placeholder="<?=$output['password']; ?>" name="pwd">&nbsp;&nbsp;&nbsp;
                <input type="submit" value="<?=$output['login']; ?>">
            </form>
		</div>
		<div id="uiContentWrap">
            <div id="uiContent" style="background-image: url('https://source.unsplash.com/1600x900/?dinner');"></div>
			
			</div>
		</div>
	</div>
</div>

<style>

@media only screen and (min-width: 1075px) {
	#userInteraction {
		width: calc(100vw / 3);
	}

	#uiContentWrap {
		left: calc(100vw / 3);
		width: calc(100vw / 3 * 2);
	}

	#uiContent {
		width: calc(100vw / 3 * 2);
	}
}
</style>


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

<!-- Script -->
<script src="./assets/js/scripts.js"></script>

</body>
</html>

<?php
$db->close();
?>
