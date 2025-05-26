<?php

require_once __DIR__ . '/../../config/autoload.php';

class FormationDateBusinessImpl implements FormationDateBusiness
{
    private $formationDateDal;

    public function __construct($dao)
    {
        $this->formationDateDal = $dao;
    }

    public function create(FormationDate $formationDate)
    {
        return $this->formationDateDal->create($formationDate);
    }

    public function getById($id)
    {
        return $this->formationDateDal->getById($id);
    }

    public function getAll()
    {
        return $this->formationDateDal->getAll();
    }

    public function update(FormationDate $formationDate)
    {
        return $this->formationDateDal->update($formationDate);
    }

    public function delete($id)
    {
        return $this->formationDateDal->delete($id);
    }

    public function getCount()
    {
        return $this->formationDateDal->getCount();
    }
}
?>
