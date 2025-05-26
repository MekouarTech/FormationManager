<?php

class FormationDate {
    public $id;
    public $date;
    public $formationId;

    public function __construct($id = null, $date = null, $formationId = null) {
        $this->id = $id;
        $this->date = $date;
        $this->formationId = $formationId;
    }

    public function __get($prop) {
        return $this->$prop;
    }

    public function __set($prop, $value) {
        $this->$prop = $value;
    }
}

?>