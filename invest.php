<?php

$DataBase = require_once "DBConnect.php";

session_start();

if(!isset($_POST["submit"]))
{
    header("location: investForm.php");
    die;
}

$userID = $_SESSION["UserID"];
$projectID = $_SESSION["projectID"];
$amount = $_POST["amount"];

$totalFundQuery =
    "SELECT projects_investors.idProject, projects.requestedFund, SUM(projects_investors.investmentFund) as fund 
    FROM projects_investors, projects WHERE projects.idProject = projects_investors.idProject AND
    projects_investors.idProject = $projectID GROUP BY projects_investors.idProject";

$Fund = $DataBase->query($totalFundQuery)->fetch_assoc();
$req = $Fund["requestedFund"];
$given = $Fund["fund"];
echo $req . " " . $given;
if($amount < 0)
{
    $_SESSION["amountERROR"] = "You cannot invest a minus value!";
    header("location: investForm.php?projectID=$projectID");
    die;
}
else if(($req - ($given + $amount)) < 0)
{
    $_SESSION["amountERROR"] = "You cannot invest more than required!";
    header("location: investForm.php?projectID=$projectID");
    die;
}
$last_date = $_SESSION["lastDate"];
$_entered_date = $_POST["date"];

$last_date = strtotime($last_date);
$entered_date = strtotime($_entered_date);

if($entered_date > $last_date)
{
    $_SESSION["amountERROR"] = "You cannot invest later last date!";
    header("location: investForm.php?projectID=$projectID");
    die;
}
else if($_entered_date < date("Y-m-d"))
{
	$_SESSION["amountERROR"] = "You cannot invest before current date!";
    header("location: investForm.php?projectID=$projectID");
    die;
}

header("location: home.php");

$insert_query = "INSERT INTO projects_investors (idUser, idProject, investmentFund, investmentDate)
    VALUES ( $userID, $projectID, $amount, '$_entered_date')";

$DataBase->query($insert_query);

