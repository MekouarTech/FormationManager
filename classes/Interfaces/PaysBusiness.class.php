<?php

interface PaysBusiness
{
    public function create(Pays $pays);
    public function getById($id);
    public function getAll();
    public function update(Pays $pays);
    public function delete($id);
    public function getVillesByPaysId($paysId);
    public function getCount();
}
?>