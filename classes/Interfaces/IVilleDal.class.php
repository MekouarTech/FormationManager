<?php

interface IVilleDal
{
    public function create(Ville $ville);
    public function getById($id);
    public function getAll();
    public function update(Ville $ville);
    public function delete($id);
    public function getPaysById($id);
    public function getCount();
}

?>
