<span class='heading'><?=$output['members'];?></span><br />

<div class="elements" style='margin-top: 50px'>	
	<div class="row">

		<?php
		// initialize users 
		$members = [];
		$result = $db->query("SELECT * FROM users ORDER BY lastname;");

		while($data = $result->fetch_assoc()) {
			$member = new CUser($db, $data['userid']);
			array_push($members, $member);
		}

		// List all users
		foreach($members as $member) {
			echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'><div class='box'>";

				if(!$member->active) {
					$styleBTN = "background-color: #990000; color: #fff;";
					$styleBACK = "background-color: #c0c0c0; color: #333333;";
				} else {
					if($member->admin) {
						$styleBTN = "background-color: #336699; color: #fff;";
					} else {
						$styleBTN = "background-color: #009900; color: #fff;";
					}
				}


				echo "<div class='attraction' onclick='javascript:location.href=\"index.php?do=showMember&id=".$member->userid."\"' style='border: 1px #000000 solid; ".$styleBACK."'>";
					echo "<div class='itemIcon' style='".$styleBTN."'>";
						if($member->admin) {
							echo "<i class='icofont-user-suited'></i>";
						} else {
							echo "<i class='icofont-user-alt-5'></i>";
						}
					echo "</div>";
					echo "<strong>".$member->firstname." ".$member->lastname."</strong><br />";
					echo "<small><span class='addr'>".$member->email."</span></small>";
				echo "</div>";


			echo "</div></div>";
		} 
		?>


		<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
			<div class='box'>
				<div class='attraction' onclick='javascript:location.href=\"index.php?do=newMember\"' style='border: 1px #000000 solid; ".$styleBACK."'>
					<div class='itemIcon' style='".$styleBTN."'>
						<i class='icofont-plus'></i>
					</div>
					<strong><?=$output['add'];?></strong><br />
					<small><span class='addr'>&nbsp;</span></small>
				</div>
			</div>
		</div>


	</div>
</div>
