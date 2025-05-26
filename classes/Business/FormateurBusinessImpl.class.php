<?php

require_once __DIR__ . '/../../config/autoload.php';

class FormateurBusinessImpl implements FormateurBusiness
{
    private $formateurDal;

    public function __construct($dao)
    {
        $this->formateurDal = $dao;
    }

    public function create(Formateur $formateur)
    {
        return $this->formateurDal->create($formateur);
    }

    public function getById($id)
    {
        return $this->formateurDal->getById($id);
    }

    public function getAll()
    {
        return $this->formateurDal->getAll();
    }

    public function update(Formateur $formateur)
    {
        return $this->formateurDal->update($formateur);
    }

    public function delete($id)
    {
        return $this->formateurDal->delete($id);
    }

    public function getCount()
    {
        return $this->formateurDal->getCount();
    }
}
?>