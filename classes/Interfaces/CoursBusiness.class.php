<?php

interface CoursBusiness
{
    public function create(Cours $cours);
    public function getById($id);
    public function getAll();
    public function update(Cours $cours);
    public function delete($id);
    public function getCount();
}

?>
