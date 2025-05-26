<?php

interface FormationBusiness
{
    public function create(Formation $formation);
    public function getById($id);
    public function getAll();
    public function update(Formation $formation);
    public function delete($id);
    public function searchFormations($search, $domaineId, $sujetId, $coursId);
    public function getCount();
}

?>