<?php

$HostName = "127.0.0.1";
$UserName = "root";
$PassWord = "";
$Port = "3306";
$DataBaseName = "CrowdFunding";

$DataBase = new mysqli($HostName, $UserName, $PassWord, $DataBaseName, $Port);

return $DataBase;
