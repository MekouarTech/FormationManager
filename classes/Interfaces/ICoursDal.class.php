<?php

interface ICoursDAL
{
    public function getAll();
    public function getById($id);
    public function create(Cours $cours);
    public function update(Cours $cours);
    public function delete($id);
    public function isUsedInFormations($coursId);
    public function getCount();
}

?>
