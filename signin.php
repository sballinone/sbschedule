<?php
session_start();
session_destroy();
session_start();

include("header.php");

if (isset($_POST['pwd'])) {
	$sql = "SELECT * FROM users WHERE email = '".strip_tags(strtolower($_POST['email']))."' AND active = 1;";
	$result = $db->query($sql);
	
	if(!$result->num_rows) {
		echo "<script>alert('".$output['loginFail']."');</script>";
	} else {
		$data = $result->fetch_assoc();
		$pass = $data['password'];

		
		
		// Switch to the new hash for users of release 0.4.7 or earlier.
		// Will be removed within the next releases!
		// Please make sure that all of your members sign in once asap.
		if($pass == md5($_POST['pwd'])) {
			$hashed_pass = password_hash($_POST['pwd'],PASSWORD_DEFAULT);

			$sql = "UPDATE users SET password = '".$hashed_pass."' WHERE userid = ".$_SESSION['userid'].";";
			$db->query($sql);

			$pass = $hashed_pass;
		}



		if(password_verify(strip_tags($_POST['pwd']), $pass)) {
			if(password_needs_rehash($pass,PASSWORD_DEFAULT)) {
				$hashed_pass = password_hash($_POST['pwd'],PASSWORD_DEFAULT);

				$sql = "UPDATE users SET password = '".$hashed_pass."' WHERE userid = ".$data['userid'].";";
				$db->query($sql);
			}

			$_SESSION['userid'] = $data['userid'];
			$_SESSION['lang'] = $data['lang'];

			header("Location: index.php");
			exit;
		} else {
			echo "<script>alert('".$output['loginFail']."');</script>";
		}
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
	
	<link rel="apple-touch-icon" sizes="57x57" href="./assets/img/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="./assets/img/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="./assets/img/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="./assets/img/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="./assets/img/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="./assets/img/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="./assets/img/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="./assets/img/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="./assets/img/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="./assets/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="./assets/img/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./assets/img/favicon/favicon-16x16.png">
  <link rel="manifest" href="./assets/img/favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#000000">
  <meta name="msapplication-TileImage" content="./assets/img/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#000000">
  
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
                <input type="text" placeholder="<?=$output['email']; ?>" name="email" value="<?=strip_tags(strtolower($_POST['email']));?>">&nbsp;&nbsp;&nbsp;
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
