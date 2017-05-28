<?php

define('MYSQL_HOST', 'sql12.freemysqlhosting.net' );
define('MYSQL_USER', 'sql12176876');
define('MYSQL_PASSWORD', 'WGalxjmTb9');
define('MYSQL_DB', 'sql12176876');

/*
define('MYSQL_HOST', 'localhost' );
define('MYSQL_USER', 'gdg');
define('MYSQL_PASSWORD', '2017');
define('MYSQL_DB', 'gdg17');
*/
//enter database connection credentials
//should be kept in a different file, named someting like config.php
$db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB) or mysqli_connect_errno();

//if connection failed, show error, and stop loading the page
if($db->connect_error){
	die('Connect Error: ' . $db->connect_error);
}


/*
Server: sql12.freemysqlhosting.net
Name: sql12176876
Username: sql12176876
Password: WGalxjmTb9
Port number: 3306
*/