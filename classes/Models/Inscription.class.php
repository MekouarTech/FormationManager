<?php

class Inscription{
    public $id;
    public $firstName;
    public $lastName;
    public $phone;
    public $email;
    public $company;
    public $paid;
    public $formationDateId;
    
    public function __construct($id = null, $firstName = null, $lastName = null, $phone = null, $email = null, $company = null, $paid = false, $formationDateId = null) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->email = $email;
        $this->company = $company;
        $this->paid = $paid;
        $this->formationDateId = $formationDateId;
    }

    public function __get($prop) {
        return $this->$prop;
    }

    public function __set($prop, $value) {
        $this->$prop = $value;
    }
}

?>