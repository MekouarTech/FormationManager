<?php

interface DomaineBusiness
{
    public function create(Domaine $domaine);
    public function getById($id);
    public function getAll();
    public function update(Domaine $domaine);
    public function delete($id);
    public function getCount();
}
?>