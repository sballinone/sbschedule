// Update check
var txtStatus = '<div class="box" id="tripSum" onclick="toggleNotif()"><i class="icofont-cloud-download"> </i></div>';
var txtStatusDev = '<div class="box" id="tripSum" onclick="toggleNotif()"><i class="icofont-code-alt"> </i></div>';

$("#downloadAddress").html("<a href='"+updateurl+"' target='_blank' border='0'>"+updateurl+"</a>");
$("#availableRelease").html(major + "." + minor + "." + built);

if(major > relmajor) {
    $("#update").html(txtStatus);
} else if(minor > relminor) {
    $("#update").html(txtStatus);
} else if(built > relbuilt && minor == relminor) {
    $("#update").html(txtStatus);
} else if(major < relmajor || (major == relmajor && minor < relminor) || (major == relmajor && minor == relminor && built < relbuilt)) {
    $("#update").html(txtStatusDev);
    $("#devRelease").html("<div style='border: 3px solid #990000; padding: 15px;'>You're running a release in development. <strong>A developement release is not meant for production usage. Please do not run the update, cause this will install an older release (the current stable one) of SB&nbsp;Schedule.</strong> A downgrade may cause a loss of features and/or data.</div><br />");
}
