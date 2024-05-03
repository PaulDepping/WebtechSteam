<?php
// Basic connection settings
$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = '050324';
$databaseName = 'Webtech';

// Connect to the database
$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);
