<?php

require_once __DIR__ . '/../../config/autoload.php';

class PaysBusinessImpl implements PaysBusiness
{
    private $Paysdal;

    public function __construct(IPaysDal $dal)
    {
        $this->Paysdal = $dal;
    }

    public function create(Pays $pays)
    {
        return $this->Paysdal->create($pays);
    }

    public function getById($id)
    {
        return $this->Paysdal->getById($id);
    }

    public function getAll()
    {
        return $this->Paysdal->getAll();
    }

    public function update(Pays $pays)
    {
        return $this->Paysdal->update($pays);
    }

    public function delete($id)
    {
        return $this->Paysdal->delete($id);
    }
    public function getVillesByPaysId($paysId)
    {
        return $this->Paysdal->getVillesByPaysId($paysId);
    }

    public function getCount()
    {
        return $this->Paysdal->getCount();
    }
}
