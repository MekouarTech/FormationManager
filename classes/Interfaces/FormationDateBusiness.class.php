<?php

interface FormationDateBusiness
{
    public function create(FormationDate $formationDate);
    public function getById($id);
    public function getAll();
    public function update(FormationDate $formationDate);
    public function delete($id);
    public function getCount();
}

?>