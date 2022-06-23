<?php
$database = 'attendance';
$host = 'localhost';
$user = 'root';
$pass = '';
$con = mysqli_connect("$host", "$user", "$pass","$database") or die("Failed To Connect");
mysqli_select_db($con, "$database") or die("Failed to select database");
