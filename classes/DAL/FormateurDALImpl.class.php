<?php

require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';


class FormateurDALImpl implements IFormateurDal
{
    private $pdo;

    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function create(Formateur $formateur)
    {
        $sql = "INSERT INTO formateur (firstName, lastName, description, photo) VALUES (:firstName, :lastName, :description, :photo)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':firstName', $formateur->firstName);
        $stmt->bindValue(':lastName', $formateur->lastName);
        $stmt->bindValue(':description', $formateur->description);
        $stmt->bindValue(':photo', $formateur->photo);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM formateur WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Formateur($row['id'], $row['firstName'], $row['lastName'], $row['description'], $row['photo']);
        }
        return null;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM formateur";
        $stmt = $this->pdo->query($sql);
        $formateurs = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $formateurs[] = new Formateur($row['id'], $row['firstName'], $row['lastName'], $row['description'], $row['photo']);
        }
        return $formateurs;
    }

    public function update(Formateur $formateur)
    {
        $sql = "UPDATE formateur SET firstName = :firstName, lastName = :lastName, description = :description, photo = :photo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':firstName', $formateur->firstName);
        $stmt->bindValue(':lastName', $formateur->lastName);
        $stmt->bindValue(':description', $formateur->description);
        $stmt->bindValue(':photo', $formateur->photo);
        $stmt->bindValue(':id', $formateur->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM formateur WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
    public function getCount()
    {
        $sql = "SELECT COUNT(*) FROM formateur";
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
    }

}
