<?php

require_once __DIR__ . '/../../config/autoload.php';

class DomaineBusinessImpl implements DomaineBusiness
{
    private $domaineDao;

    public function __construct(IDomaineDal $domaineDao)
    {
        $this->domaineDao = $domaineDao;
    }

    public function create(Domaine $domaine)
    {
        return $this->domaineDao->create($domaine);
    }

    public function getById($id)
    {
        return $this->domaineDao->getById($id);
    }

    public function getAll()
    {
        return $this->domaineDao->getAll();
    }

    public function update(Domaine $domaine)
    {
        return $this->domaineDao->update($domaine);
    }

    public function delete($id)
    {
        return $this->domaineDao->delete($id);
    }

    public function getCount()
    {
        return $this->domaineDao->getCount();
    }
}
