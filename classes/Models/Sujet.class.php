<?php

class Sujet
{
    public $id;
    public $name;
    public $shortDescription;
    public $longDescription;
    public $individualBenefit;
    public $businessBenefit;
    public $logo;
    public $domaineId;

    public function __construct($id, $name, $shortDescription, $longDescription, $individualBenefit, $businessBenefit, $logo, $domaineId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->shortDescription = $shortDescription;
        $this->longDescription = $longDescription;
        $this->individualBenefit = $individualBenefit;
        $this->businessBenefit = $businessBenefit;
        $this->logo = $logo;
        $this->domaineId = $domaineId;
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }
}
?>