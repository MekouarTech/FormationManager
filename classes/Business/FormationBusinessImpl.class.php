<?php

require_once __DIR__ . '/../../config/autoload.php';

class FormationBusinessImpl implements FormationBusiness
{
    private $formationDal;

    public function __construct($dao)
    {
        $this->formationDal = $dao;
    }

    public function create(Formation $formation)
    {
        return $this->formationDal->create($formation);
    }

    public function getById($id)
    {
        return $this->formationDal->getById($id);
    }

    public function getAll()
    {
        return $this->formationDal->getAll();
    }

    public function update(Formation $formation)
    {
        return $this->formationDal->update($formation);
    }

    public function delete($id)
    {
        return $this->formationDal->delete($id);
    }

    public function searchFormations($search, $domaineId, $sujetId, $coursId)
    {
        return $this->formationDal->searchFormations($search, $domaineId, $sujetId, $coursId);
    }

    public function getCount()
    {
        return $this->formationDal->getCount();
    }
}
?>
