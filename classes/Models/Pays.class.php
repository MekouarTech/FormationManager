<?php


class Pays
{
    private $id;
    private $value;

    public function __construct($id = null, $value=""){
        $this->id = $id;
        $this->value = $value;
    }

    // Getters
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
        return "ID: " . $this->id . ", Pays: " . $this->value;
    }
}

?>