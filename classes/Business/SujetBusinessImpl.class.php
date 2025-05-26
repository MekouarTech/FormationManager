<?php

require_once __DIR__ . '/../../config/autoload.php';

class SujetBusinessImpl implements SujetBusiness
{
    private $dal;

    public function __construct(ISujetDal $dal)
    {
        $this->dal = $dal;
    }

    public function create(Sujet $sujet)
    {
        return $this->dal->create($sujet);
    }

    public function getById($id)
    {
        return $this->dal->getById($id);
    }

    public function getAll()
    {
        return $this->dal->getAll();
    }

    public function update(Sujet $sujet)
    {
        return $this->dal->update($sujet);
    }

    public function delete($id)
    {
        return $this->dal->delete($id);
    }

    public function getCount()
    {
        return $this->dal->getCount();
    }
}
