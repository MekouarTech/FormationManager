<?php

interface IFormateurDal
{
    public function create(Formateur $formateur);
    public function getById($id);
    public function getAll();
    public function update(Formateur $formateur);
    public function delete($id);
    public function getCount();
}

?>