<?php

class Formateur
{
    private $id;
    private $firstName;
    private $lastName;
    private $description;
    private $photo;

    public function __construct($id = null, $firstName = "", $lastName = "", $description = "", $photo = "")
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->description = $description;
        $this->photo = $photo;
    }

    // Getters
    public function __get($attr)
    {
        if (property_exists($this, $attr)) {
            return $this->$attr;
        }
        return null;
    }

    public function getPhotoAsBase64($mimeType = 'jpeg')
    {
        if (!empty($this->photo)) {
            return 'data:image/' . $mimeType . ';base64,' . base64_encode($this->photo);
        }
        return null;
    }



    // Setters
    public function __set($attr, $value)
    {
        if (property_exists($this, $attr)) {
            $this->$attr = $value;
        }
    }

    public function toString()
    {
        return "ID: " . $this->id . ", First Name: " . $this->firstName . ", Last Name: " . $this->lastName . ", Description: " . $this->description . ", Photo: " . $this->photo;
    }
}
