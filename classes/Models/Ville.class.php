<?php

class Ville
{
    private $id;
    private $value;
    private $paysId;

    public function __construct($id = null, $value = "", $paysId = null)
    {
        $this->id = $id;
        $this->value = $value;
        $this->paysId = $paysId;
    }

    public function __get($attr)
    {
        if (property_exists($this, $attr)) {
            return $this->$attr;
        }
        return null;
    }

    public function __set($attr, $value)
    {
        if (property_exists($this, $attr)) {
            $this->$attr = $value;
        }
    }

    public function toString()
    {
        return "ID: " . $this->id . ", Ville: " . $this->value . ", Pays ID: " . $this->paysId;
    }
}

?>
