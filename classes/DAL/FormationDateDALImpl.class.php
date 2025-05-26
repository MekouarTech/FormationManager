<?php

require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';

class FormationDateDALImpl implements IFormationDateDal
{
    private $pdo;

    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function create(FormationDate $formationDate)
    {
        $sql = "INSERT INTO formationdate (date, formationId) 
                VALUES (:date, :formationId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':date', $formationDate->date);
        $stmt->bindValue(':formationId', $formationDate->formationId);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM formationdate WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new FormationDate($row['id'], $row['date'], $row['formationId']);
        }
        return null;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM formationdate";
        $stmt = $this->pdo->query($sql);
        $dates = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dates[] = new FormationDate($row['id'], $row['date'], $row['formationId']);
        }
        return $dates;
    }

    public function update(FormationDate $formationDate)
    {
        $sql = "UPDATE formationdate 
                SET date = :date, formationId = :formationId 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':date', $formationDate->date);
        $stmt->bindValue(':formationId', $formationDate->formationId);
        $stmt->bindValue(':id', $formationDate->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM formationdate WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
    public function getCount()
    {
        $sql = "SELECT COUNT(*) FROM formationdate";
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
    }

}
?>