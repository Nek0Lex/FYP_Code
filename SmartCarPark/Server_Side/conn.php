<?php
$hostname = "128.199.237.162:3306";
$username = "jacky";
$password = "12345678";
$database = "FYP";
$conn = mysqli_connect($hostname, $username, $password, $database)
    or die(mysqli_connect_error());