<?php

class ProjectModel
{
    public $idProject;
    public $projectName;
    public $projectDescription;
    public $projectStartDate;
    public $projectEndDate;
    public $requestedFund;
    public $investedFund;
    public $idUser;

    public function __construct($IdProject, $ProjectName, $ProjectDescription, $ProjectStartDate, $ProjectEndDate, $RequestedFund, $InvestedFund, $IdUser)
    {
        $this->idProject = $IdProject;
        $this->projectName = $ProjectName;
        $this->projectDescription = $ProjectDescription;
        $this->projectStartDate = $ProjectStartDate;
        $this->projectEndDate = $ProjectEndDate;
        $this->requestedFund = $RequestedFund;
        $this->investedFund = $InvestedFund;
        $this->idUser = $IdUser;
    }
}
