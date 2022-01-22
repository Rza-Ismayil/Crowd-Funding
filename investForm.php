<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="Views/indexStyle.css" media="screen">
    <link rel="stylesheet" type="text/css" href="Views/Error.css" media="screen">
    <title>Invest Form</title>
</head>
<body>
    <div>
        <?php

        $DataBase = require_once "DBConnect.php";

        session_start();


        if(!isset($_GET["projectID"]))
        {
            header("location: home.php");
            die;
        }
        $userID = $_SESSION["UserID"];
        $projectID = $_GET["projectID"];

        $dup_check_query = "SELECT idProject FROM projects_investors WHERE idUser = $userID AND idProject = $projectID";
        $dup_investments = $DataBase->query($dup_check_query);
        if($dup_investments->num_rows > 0)
        {
            header("location: home.php");
            die;
        }

        $date_check_query = "SELECT * FROM projects WHERE idProject = $projectID AND projects.projectEndDate > CURRENT_DATE";
        $date_check_res = $DataBase->query($date_check_query);
        if($date_check_res->num_rows == 0)
        {
            header("location: home.php");
            die;
        }

        $last_date = $date_check_res->fetch_assoc()["projectEndDate"];
        $last_date = date("d-m-Y", strtotime($last_date));

        $project_name_query = "SELECT projectName FROM projects WHERE idProject = $projectID";
        $project_name_res = $DataBase->query($project_name_query);
        $project_name = $project_name_res->fetch_assoc()["projectName"];

        $totalFundQuery =
            "SELECT projects_investors.idProject, projects.requestedFund, SUM(projects_investors.investmentFund) as fund 
            FROM projects_investors, projects WHERE projects.idProject = projects_investors.idProject AND
            projects_investors.idProject = $projectID GROUP BY projects_investors.idProject";

        $Fund = $DataBase->query($totalFundQuery)->fetch_assoc();
        $req = $Fund["requestedFund"];
        $given = $Fund["fund"];

        $_SESSION["UserID"] = $userID;
        $_SESSION["projectID"] = $projectID;
        $_SESSION["lastDate"] = $last_date;
        ?>
        <div>
            <div class="Heading"><b>Investing for <b class="box"><?php echo $project_name ?></b></b></div><br>
            <form method="post" action="invest.php" class="loginForm">
                <?php
                if(isset($_SESSION["amountERROR"]))
                {
                    echo "<b class='loginError'>".$_SESSION["amountERROR"]."</b><br>";
                    unset($_SESSION["amountERROR"]);
                }
                echo "<p><b>MAX AMOUNT: ".($req - $given)." $</b></p>";
                ?>
                <input type="number" name="amount" placeholder="amount" style="text-align: center" required><b> $</b><br><br>
                <p><b>last date: <?php echo $last_date; ?></b></p>
                <input type="date" name="date" style="text-align: center" required><br><br>
                <input type="submit" name="submit" value="CONFIRM INVEST" class="Button">
            </form>
        </div>
    </div>
</body>
</html>

