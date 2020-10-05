var locked = false;

$("#tripSumDetails").hide();
$("#elementDetail").hide();
$("#shadow").hide();

$("#logout").mouseover(function() {
	if(!locked) {
		$("#logout").animate({
			backgroundColor: '#666666'
		});
	}
});

$("#logout").mouseout(function() {
	window.setTimeout("javascript:logoutButton()",300);
});

$("#logout a").mouseover(function() {
	locked = true;
});

$("#logout a").mouseout(function() {
	locked = false;
});

function logoutButton() {
	if(!locked) {
		$("#logout").animate({
			backgroundColor: '#000000'
		});
	}
}



// Notif

var calcState = false;
function toggleNotif() {
	if(calcState) {
		$("#tripSumDetails").slideUp(function() {
			$("#uiContent").fadeIn();
		});
		calcState = false;
	} else {
		$("#uiContent").fadeOut(function() {
			$("#tripSumDetails").slideDown();
		});
		calcState = true;
	}
}




function resizeUI() {
	if($( window ).width() < 1075) {
		$('#userInteraction').animate({
			top: $('#uiContent').outerHeight()
		});
	} else {
		$('#userInteraction').animate({
			top: 0
		});
	}
}

$(window).on('resize', function(){
	resizeUI();
});

$("#uiContent").on('resize', function(){
	resizeUI();
});




// Initialize UI
$(document).ready(function() {
	resizeUI();
});



// Change content of UI
$("#addElement").click(function() {
	addElement();
});

function addElement() {
	$("#elementDetail").load("inc/apptAdd.php");
	$("#elementDetail").show("slide", {direction: "left"});
	$("#shadow").fadeIn();
}

function closeElementDetail() {
	$("#elementDetail").hide("slide", {direction: "right"});
	$("#elementDetail").html("");
	$("#shadow").fadeOut();
}

$(".toggle-password").click(function() {
	$(this).toggleClass("icofont-eye icofont-eye-blocked");
	var input = $($(this).attr("toggle"));
	if ($("#frmPass").attr("type") == "password") {
		$("#frmPass").attr("type", "text");
	} else {
		$("#frmPass").attr("type", "password");
	}
});
