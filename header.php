<?php

// Include configuration
include "config.php";

// Include classes
include "./classes/CUser.php";
include "./classes/CAppointment.php";

$db = new mysqli($dbhost, $dbuser, $dbpass, $dbbase, $dbport);
if($db->connect_error) {
    echo "Mysql connection error";
    exit;
}

if(isset($_SESSION['userid'])) {
    // Initialize classes
    $user = new CUser($db);

    $_SESSION['lang'] = $user->lang;
    if(file_exists("./lang/".$user->lang.".php")) {
        include "./lang/".$user->lang.".php";
    } else {
        include "./lang/de.php";
    }
    
    // initialize classes 
    $allAppts = [];
    $appt;
    $result = $db->query("SELECT * FROM appointment WHERE apptDate >= CURDATE() ORDER BY apptDate");
    
    while($data = $result->fetch_assoc()) {
        $appt = new CAppointment($db, $data['appointmentid']);
        array_push($allAppts, $appt);
    }

} else {
    if(isset($_GET['lang'])) {
        $_SESSION['lang'] = strip_tags($_GET['lang']);
    } else {
        if(!isset($_SESSION['lang'])) {
            $_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }
    }
    
    if(file_exists("./lang/".$_SESSION['lang'].".php")) {
        include "./lang/".$_SESSION['lang'].".php";
    } else {
        include "./lang/de.php";
    }
}
