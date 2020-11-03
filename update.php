<?php
include "config.php";

$db = new mysqli($dbhost, $dbuser, $dbpass, $dbbase, $dbport);
if($db->connect_error) {
    echo "Mysql connection error";
    exit;
}

$sql = "ALTER TABLE `users` CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
$db->query($sql);

mysql_close($db);

echo "Update run. <a href='index.php'>Continue</a>";
exit;
