<?php

require_once __DIR__ . '/../../config/autoload.php';

class VillesBusinessImpl implements VillesBusiness
{
    private $villeDal;

    public function __construct(IVilleDal $villeDal)
    {
        $this->villeDal = $villeDal;
    }

    public function create(Ville $ville)
    {
        return $this->villeDal->create($ville);
    }

    public function getById($id)
    {
        return $this->villeDal->getById($id);
    }

    public function getAll()
    {
        return $this->villeDal->getAll();
    }

    public function update(Ville $ville)
    {
        return $this->villeDal->update($ville);
    }

    public function delete($id)
    {
        return $this->villeDal->delete($id);
    }

    public function getPaysById($id)
    {
        return $this->villeDal->getPaysById($id);
    }

    public function getCount()
    {
        return $this->villeDal->getCount();
    }

}

?>
