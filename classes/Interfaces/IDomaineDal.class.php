<?php

interface IDomaineDAL
{
    public function create(Domaine $domaine);
    public function update(Domaine $domaine);
    public function delete(int $id);
    public function getById(int $id);
    public function getAll();
    public function getCount();
}

?>
