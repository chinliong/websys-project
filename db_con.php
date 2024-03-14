<?php

$config = parse_ini_file('/var/www/private/db-config.ini');
if (!$config)
{
    $errorMsg = "Failed to read database config file.";
    $success = false;
}
else{
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );
}

// Check connection
if ($conn->connect_error)
{
    $errorMsg = "Connection failed: " . $conn->connect_error;
    $success = false;
}