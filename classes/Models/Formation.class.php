<?php

class Formation
{
    public $id;
    public $price;
    public $mode;
    public $coursId;
    public $formateurId;
    public $villeId;

    public function __construct($id = null, $price = 0, $mode = '', $coursId = null, $formateurId = null, $villeId = null)
    {
        $this->id = $id;
        $this->price = $price;
        $this->mode = $mode;
        $this->coursId = $coursId;
        $this->formateurId = $formateurId;
        $this->villeId = $villeId;
    }

    public function __get($property)
    {
        return $this->$property ?? null;
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}
