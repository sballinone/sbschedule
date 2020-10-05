<?php
$fkt = strip_tags($_GET['fkt']);
$folder = strip_tags($_GET['folder']);
$file = strip_tags($_GET['file']);
$filepath = "./".$folder."/".$file;

switch($fkt) {
	case 'create':
		if(!file_exists($filepath)) {
			touch($filepath);
			echo "<div class='notif'>File ".$file." created.</div>";
		} else {
			echo "<div class='notif'>File ".$file." already exists.</div>";
		}
	break;

	case 'save':
		if(file_exists($filepath)) {
			$txt=fopen($filepath,"w");
			fwrite($txt,$_POST['filecontent']);
			fclose($txt);
			echo "<div class='notif'>File ".$file." saved.</div>";
		} else {
			echo "<div class='notif'>File ".$file." does not exists.</div>";
		}
	break;

	case 'delete':
		if(file_exists($filepath)) {
			unlink($filepath);
			echo "<div class='notif'>File ".$file." deleted.</div>";
		} else {
			echo "<div class='notif'>File ".$file." does not exists.</div>";
		}
		$file = "";
	break;
}
?>

<span class='heading'>Editor</span><br />

<div class='notif'>
WARNING: Changes in this files may cause future issues in the software.
</div>

<div class="row">
	<?php
	$dir = opendir("./".$folder);
	while($item = readdir($dir)) {
		if($item != "." && $item != "..") {
			echo "<div class='col-xs-6 col-sm-6 col-md-3 col-lg-3'><div class='box' style='border: 1px #000000 solid; padding: 5px 10px; cursor: pointer; margin-bottom: 5px;";
			
			if($item == $file) {
				echo "background-color: #000000; color: #ffffff;";
			}

			echo "' onclick='location.href=\"index.php?do=editor&folder=".strip_tags($_GET['folder'])."&file=".$item."\"'><i class='icofont-dotted-right'></i> ".$item."</div></div>";
		}
	}
	echo "<div class='col-xs-6 col-sm-6 col-md-3 col-lg-3'><div class='box' style='border: 1px #000000 solid; padding: 5px 10px; cursor: pointer;' onclick='addFile()'><i class='icofont-plus'></i> ".$output['new']."</div></div>";
	?>
</div>

<?php if($file != "") { ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="box">
				<form action='index.php?do=editor&fkt=save&file=<?=$file;?>&folder=<?=$folder;?>' method="POST">
					<input type="submit" value="<?=$output['save'];?>" class="btn"><br />
					<textarea class='editor' name='filecontent'><?php
						$txt=fopen($filepath,"r");
						while(!feof($txt)) {
							echo fgets($txt);
						}
						fclose($txt);
						?></textarea>
						<a href="index.php?do=editor&fkt=delete&file=<?=$file;?>&folder=<?=$folder;?>" class="btnDelete">Delete file <?=$filepath;?></a>
				</form>
			</div>
		</div>
	</div>
<?php } ?>

<style>
.editor {
	box-sizing: border-box;
	width: 100%;
	height: 60vh;
	font-family: monospace;
	background-color: #000000;
	color: #ffffff;
	padding: 25px;
}
</style>

<script>
function addFile() {
	var filename = prompt("Filename: ");
	if(filename == "") {
		alert("File not created. Filename missing.");
	} else {
		location.href="index.php?do=editor&folder=<?=strip_tags($_GET['folder']);?>&fkt=create&file=" + filename;
	}
}
</script>
