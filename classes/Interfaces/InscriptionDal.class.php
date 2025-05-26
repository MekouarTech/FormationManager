<?php

interface InscriptionDal {
    public function insert(Inscription $inscription);
    public function getById(int $id);
    public function getAll();
    public function getByEmail(string $email);
    public function isUserAlreadyRegistered($email, $formationDateId);
    public function getByFormationDateId(int $formationDateId);
    public function update(Inscription $inscription);
    public function delete(int $id);
    public function getCount();
}
?>