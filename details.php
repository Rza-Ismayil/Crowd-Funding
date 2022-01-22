<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="Views/indexStyle.css" media="screen">
    <link rel="stylesheet" type="text/css" href="Views/projectBox.css" media="screen">
    <title>Project Details</title>
</head>
<body>
    <?php

    $DataBase = require_once "DBConnect.php";

    session_start();
    if(!isset($_SESSION["UserID"]))
    {
        header("location: home.php");
        die;
    }
    $userID = $_SESSION["UserID"];

    $project_query = "SELECT projects.*, SUM(projects_investors.investmentFund) AS totalFund FROM users, projects, projects_investors
        WHERE users.idUser = $userID AND users.idUser = projects.idUser AND projects.idProject = projects_investors.idProject GROUP BY projects_investors.idProject";
    $project_res = $DataBase->query($project_query);

    if($project_res->num_rows == 0)
    {
        header("location: home.php");
        die;
    }

    $project = $project_res->fetch_assoc();

    $projectID = $project["idProject"];



    ?>
    <div class="Heading"><b>Details of <b class="box"><?php echo $project["projectName"] ?></b></b></div>
    <div class="infoBox"><?php echo $project["projectDescription"] ?></div>
    <div class="infoBox">
        <?php
        $progress = 100 - 100 * ($project["totalFund"] / $project["requestedFund"] );
        echo "Start date: ".$project["projectStartDate"]."<br>".
            "End date: ".$project["projectEndDate"]."<br><br>".
            "Fund: ".$project["totalFund"]." of ".$project["requestedFund"]." $<br>".
            "Remaining: ".($project["requestedFund"]-$project["totalFund"])." $<br><br>".
            "<div class='out'><div class='in' style='margin-right: $progress%'>.</div></div>";
        ?>
    </div><br>
    <div class="infoBox"><b>Investors</b></div>
    <table class="userDetail">
        <?php
        $investors_query = "SELECT users.firstname, users.lastname, users.email, SUM(projects_investors.investmentFund) AS fund FROM users, projects_investors
            WHERE users.idUser = projects_investors.idUser AND projects_investors.idProject = $projectID GROUP BY projects_investors.idUser";

        $investors_res = $DataBase->query($investors_query);

        echo "<tr>";
        echo "<th class='ftableBoxL'>First Name</th>";
        echo "<th class='tableBox'>Last Name</th>";
        echo "<th class='tableBox'>Email</th>";
        echo "<th class='ftableBoxR'>Investment</th>";
        echo "</tr>";

        $i = $investors_res->num_rows;
        //echo $i;

        while($investor = $investors_res->fetch_assoc())
        {
            $userFirstName = $investor["firstname"];
            $userLastName = $investor["lastname"];
            $userEmail = $investor["email"];
            $userFund = $investor["fund"];

            if($i != 1)
            {
                echo "<tr>";
                echo "<td class='tableBox'>$userFirstName</td>";
                echo "<td class='tableBox'>$userLastName</td>";
                echo "<td class='tableBox'>$userEmail</td>";
                echo "<td class='tableBox'>$userFund</td>";
                echo "</tr>";
            }
            else
            {
                echo "<tr>";
                echo "<td class='ltableBoxL'>$userFirstName</td>";
                echo "<td class='tableBox'>$userLastName</td>";
                echo "<td class='tableBox'>$userEmail</td>";
                echo "<td class='ltableBoxR'>$userFund</td>";
                echo "</tr>";
            }
            $i--;
        }
        ?>
    </table>



</body>
</html>
