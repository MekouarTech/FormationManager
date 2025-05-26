<?php

require_once __DIR__ . '/../../config/autoload.php';

class InscriptionBusinessImpl implements InscriptionBusiness
{
    private $inscriptionDal;

    public function __construct(InscriptionDal $inscriptionDal)
    {
        $this->inscriptionDal = $inscriptionDal;
    }

    public function insert(Inscription $inscription)
    {
        return $this->inscriptionDal->insert($inscription);
    }

    public function getById(int $id)
    {
        return $this->inscriptionDal->getById($id);
    }

    public function getAll()
    {
        return $this->inscriptionDal->getAll();
    }

    public function getByEmail(string $email)
    {
        return $this->inscriptionDal->getByEmail($email);
    }

    public function isUserAlreadyRegistered($email, $formationDateId)
    {
        return $this->inscriptionDal->isUserAlreadyRegistered($email, $formationDateId);
    }

    public function getByFormationDateId(int $formationDateId)
    {
        return $this->inscriptionDal->getByFormationDateId($formationDateId);
    }

    public function update(Inscription $inscription)
    {
        return $this->inscriptionDal->update($inscription);
    }

    public function delete(int $id)
    {
        return $this->inscriptionDal->delete($id);
    }

    public function getCount()
    {
        return $this->inscriptionDal->getCount();
    }
}
?>
