<?php

class UserModel
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;

    public function __construct($Id, $Firstname, $Lastname, $Email)
    {
        $this->id = $Id;
        $this->firstname = $Firstname;
        $this->lastname = $Lastname;
        $this->email = $Email;
    }
}
