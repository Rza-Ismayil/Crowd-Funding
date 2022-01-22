<?php

include_once "Models/UserModel.php";
include_once "Models/ProjectModel.php";

$DataBase = require ("DBConnect.php");
$projects = array();

session_start();

if(!isset($_SESSION["User"]))
{
    header("location: index.php");
    die;
}
$user = $_SESSION["User"];

$id = $user->id;

$_SESSION["UserID"] = $id;

$projects_query =
    "SELECT projects.idProject, projectName, projectDescription,
    projectStartDate,projectEndDate, requestedFund, projects.idUser,
    SUM(projects_investors.investmentFund) as totalFund FROM users, projects, projects_investors
    WHERE users.idUser = projects.idUser AND projects_investors.idProject = projects.idProject
    GROUP BY projects_investors.idProject";

$projects_table = $DataBase->query($projects_query);

while ($row = $projects_table->fetch_assoc())
{
    $projects[] = new ProjectModel(
        $row["idProject"],
        $row["projectName"],
        $row["projectDescription"],
        $row["projectStartDate"],
        $row["projectEndDate"],
        $row["requestedFund"],
        $row["totalFund"],
        $row["idUser"]
    );
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="Views/projectBox.css" media="screen">
</head>
<body>
        <?php
            echo
            "<div class='userHello'>".
                "<a href='logout.php' class='investLink'>Log out</a>".
                "<div style='text-align: right; margin: 30px' >".
                    $user->firstname . " " . $user->lastname .
                "</div>".
            "</div>";
            echo "<br>";
            echo "<table class='projectTable'>";

        for($i = 0; $i < sizeof($projects); $i++)
            {
                $progress = 100 - 100 * ($projects[$i]->investedFund / $projects[$i]->requestedFund );
                if($id == $projects[$i]->idUser)
                {
                    echo "<tr><td class='ProjectBox2'>".
                        "<h3>". $projects[$i]->projectName ." (your project)</h3>".
                        "<p>". $projects[$i]->projectDescription ."</p>".
                        "<p>starting date: ". $projects[$i]->projectStartDate . "<br>Ending date: " . $projects[$i]->projectEndDate ."</p>".
                        "<p>Fund: ". $projects[$i]->investedFund ." of ". $projects[$i]->requestedFund ."</p>".
                        "<div class='out'><div class='in' style='margin-right: $progress%'>.</div></div>".
                        "<a class='investLink' href='details.php'>SEE DETAILS</a><br><br>".
                    "</td></tr>";
                    echo "<tr><td class='ProjectBox'><br><b>Other projects</b><br><br></td></tr>";
                }
            }
            for($i = 0; $i < sizeof($projects); $i++)
            {
                $dup_check_query = "SELECT idProject FROM projects_investors WHERE idUser = $id AND idProject = ".$projects[$i]->idProject.";";
                $dup_investments = $DataBase->query($dup_check_query);
                $progress = 100 - 100 * ($projects[$i]->investedFund / $projects[$i]->requestedFund );
                if($id != $projects[$i]->idUser)
                {
                    echo "<tr><td class='ProjectBox'>".
                        "<h3>". $projects[$i]->projectName ."</h3>".
                        "<p>". $projects[$i]->projectDescription ."</p>".
                        "<p>starting date: ". $projects[$i]->projectStartDate . "<br>Ending date: " . $projects[$i]->projectEndDate ."</p>".
                        "<p>Fund: ". $projects[$i]->investedFund ." of ". $projects[$i]->requestedFund ."</p>".
                        "<div class='out'><div class='in' style='margin-right: $progress%'>.</div></div>";
                    if($dup_investments->num_rows > 0)
                        echo "<b class='investLink' style='color: gray'>YOU ALREADY INVESTED TO THIS PROJECT</b><br><br>";
                    else if($projects[$i]->projectEndDate < date("Y-m-d"))
                        echo "<b class='investLink' style='color: gray'>THIS PROJECT IS OUT OF DATE</b><br><br>";
                    else if($projects[$i]->requestedFund <= $projects[$i]->investedFund)
                        echo "<b class='investLink' style='color: gray'>INVESTMENT FOR THIS PROJECT IS COMPLETED</b><br><br>";
                    else if($projects[$i]->projectEndDate > date("Y-m-d"))
                        echo "<a class='investLink' href='investForm.php?projectID=".$projects[$i]->idProject."'>INVEST</a><br><br>";

                    echo "</td></tr>";
                }

            }
            echo "</table>";
        ?>
</body>
</html>
