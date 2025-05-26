<?php

interface ISujetDal
{
    public function create(Sujet $sujet);
    public function getById($id);
    public function getAll();
    public function update(Sujet $sujet);
    public function delete($id);
    public function getCount();
}
?>