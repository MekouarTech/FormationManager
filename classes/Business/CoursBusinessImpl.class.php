<?php

require_once __DIR__ . '/../../config/autoload.php';

class CoursBusinessImpl implements CoursBusiness
{
    private $coursDao;

    public function __construct(ICoursDal $coursDao)
    {
        $this->coursDao = $coursDao;
    }

    public function create(Cours $cours)
    {
        return $this->coursDao->create($cours);
    }

    public function getById($id)
    {
        return $this->coursDao->getById($id);
    }

    public function getAll()
    {
        return $this->coursDao->getAll();
    }

    public function update(Cours $cours)
    {
        return $this->coursDao->update($cours);
    }

    public function delete($id)
    {
        if ($this->coursDao->isUsedInFormations($id)) {
            throw new Exception("Impossible de supprimer ce cours car il est utilisé dans une ou plusieurs formations.");
        }

        return $this->coursDao->delete($id);
    }

    public function getCount()
    {
        return $this->coursDao->getCount();
    }
}
