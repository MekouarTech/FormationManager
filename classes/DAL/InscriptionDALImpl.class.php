<?php

require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';

class InscriptionDALImpl implements InscriptionDal
{
    private $pdo;

    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function insert(Inscription $inscription)
    {
        $sql = "INSERT INTO Inscription (firstName, lastName, phone, email, company, paid, formationDateId)
                VALUES (:firstName, :lastName, :phone, :email, :company, :paid, :formationDateId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':firstName', $inscription->firstName);
        $stmt->bindValue(':lastName', $inscription->lastName);
        $stmt->bindValue(':phone', $inscription->phone);
        $stmt->bindValue(':email', $inscription->email);
        $stmt->bindValue(':company', $inscription->company);
        $stmt->bindValue(':paid', $inscription->paid, PDO::PARAM_BOOL);
        $stmt->bindValue(':formationDateId', $inscription->formationDateId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById(int $id)
    {
        $sql = "SELECT * FROM Inscription WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Inscription(...array_values($row)) : null;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM Inscription";
        $stmt = $this->pdo->query($sql);
        $inscriptions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $inscriptions[] = new Inscription(...array_values($row));
        }
        return $inscriptions;
    }

    public function getByEmail(string $email): array
    {
        $sql = "SELECT * FROM Inscription WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $inscriptions = [];
        foreach ($rows as $row) {
            $inscriptions[] = new Inscription(...array_values($row));
        }

        return $inscriptions;
    }


    public function isUserAlreadyRegistered($email, $formationDateId)
    {
        $sql = "SELECT COUNT(*) FROM Inscription WHERE email = :email AND formationDateId = :formationDateId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':formationDateId', $formationDateId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getByFormationDateId(int $formationDateId)
    {
        $sql = "SELECT * FROM Inscription WHERE formationDateId = :formationDateId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':formationDateId', $formationDateId, PDO::PARAM_INT);
        $stmt->execute();
        $inscriptions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $inscriptions[] = new Inscription(...array_values($row));
        }
        return $inscriptions;
    }

    public function update(Inscription $inscription)
    {
        $sql = "UPDATE Inscription SET
                    firstName = :firstName,
                    lastName = :lastName,
                    phone = :phone,
                    email = :email,
                    company = :company,
                    paid = :paid,
                    formationDateId = :formationDateId
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':firstName', $inscription->firstName);
        $stmt->bindValue(':lastName', $inscription->lastName);
        $stmt->bindValue(':phone', $inscription->phone);
        $stmt->bindValue(':email', $inscription->email);
        $stmt->bindValue(':company', $inscription->company);
        $stmt->bindValue(':paid', $inscription->paid, PDO::PARAM_BOOL);
        $stmt->bindValue(':formationDateId', $inscription->formationDateId, PDO::PARAM_INT);
        $stmt->bindValue(':id', $inscription->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete(int $id)
    {
        $sql = "DELETE FROM Inscription WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
    public function getCount()
    {
        $sql = "SELECT COUNT(*) FROM inscription";
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
    }

}
?>
