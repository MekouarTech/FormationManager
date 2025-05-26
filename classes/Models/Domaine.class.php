<?php

class Domaine
{
    private $id;
    private $name;
    private $description;

    public function __construct($id = null, $name = "", $description = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    // Getter
    public function __get($attr)
    {
        if (property_exists($this, $attr)) {
            return $this->$attr;
        }
        return null;
    }

    // Setter
    public function __set($attr, $value)
    {
        if (property_exists($this, $attr)) {
            $this->$attr = $value;
        }
    }

    public function toString()
    {
        return "ID: " . $this->id . ", Nom: " . $this->name . ", Description: " . $this->description;
    }
}

?>
