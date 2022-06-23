<?php
$database = 'bagersha_attendance';
$host = '50.87.169.177';
$user = 'bagersha_henok';
$pass = ')hD%A9c,97A4';
$con = mysqli_connect("$host", "$user", "$pass", "$database") or die("Failed To Connect");
mysqli_select_db($con, "$database") or die("Failed to select database");
