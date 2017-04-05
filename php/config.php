<?php

/**
 * Database config variables
 */
define("DB_HOST", "mysql.hostinger.in");
define("DB_USER", "u355834733_mds");
define("DB_PASSWORD", "mds123");
define("DB_DATABASE", "u355834733_mds");
$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die ("connection failed");

?>