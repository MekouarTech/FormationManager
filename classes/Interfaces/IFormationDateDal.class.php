<?php

interface IFormationDateDal {
    public function getAll();
    public function getById(int $id);
    public function create(FormationDate $formationDate);
    public function update(FormationDate $formationDate);
    public function delete(int $id);
    public function getCount();
}

?>