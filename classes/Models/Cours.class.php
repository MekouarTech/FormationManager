<?php

class Cours
{
    private $id;
    private $name;
    private $content;
    private $description;
    private $audience;
    private $duration;
    private $testIncluded;
    private $testContent;
    private $logo;
    private $sujetId;

    public function __construct(
        $id = null,
        $name = "",
        $content = "",
        $description = "",
        $audience = "",
        $duration = 0,
        $testIncluded = false,
        $testContent = "",
        $logo = null,
        $sujetId = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->content = $content;
        $this->description = $description;
        $this->audience = $audience;
        $this->duration = $duration;
        $this->testIncluded = $testIncluded;
        $this->testContent = $testContent;
        $this->logo = $logo;
        $this->sujetId = $sujetId;
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
        return "ID: $this->id, Nom: $this->name, Description: $this->description, Durée: $this->duration, Test inclus: " . ($this->testIncluded ? 'Oui' : 'Non');
    }
}

?>
