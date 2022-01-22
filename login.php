<?php

include_once "Models/UserModel.php";
include_once "Models/ProjectModel.php";

$DataBase = require("DBConnect.php");

session_start();

if(!isset($_POST["submit"]))
{
    header("location:index.php");
}
else
{
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT users.idUser, firstname, lastname, email, password FROM users WHERE users.email = '$email' AND users.password = '$password'";

    $table = $DataBase->query($query);

    if($table->num_rows == 0)
    {
        $_SESSION["ERROR"] = "
            <br>
            <div class='errorBox'>
                <b class='loginError'>
                    Email and password does not match.
                </b>
            </div>
        ";
        header("location:index.php");
    }
    else
    {
        $row = $table->fetch_assoc();
        $_SESSION["User"] = new UserModel($row["idUser"], $row["firstname"], $row["lastname"], $row["email"]);
        header("location: home.php");
    }
}

