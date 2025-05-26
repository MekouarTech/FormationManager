<?php

require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';

class DomaineDALImpl implements IDomaineDal
{
    private $pdo;

    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function create(Domaine $domaine)
    {
        $sql = "INSERT INTO domaine (name, description) VALUES (:name, :description)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $domaine->name);
        $stmt->bindValue(':description', $domaine->description);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM domaine WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Domaine($row['id'], $row['name'], $row['description']);
        }
        return null;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM domaine";
        $stmt = $this->pdo->query($sql);
        $domaines = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $domaines[] = new Domaine($row['id'], $row['name'], $row['description']);
        }
        return $domaines;
    }

    public function update(Domaine $domaine)
    {
        $sql = "UPDATE domaine SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $domaine->name);
        $stmt->bindValue(':description', $domaine->description);
        $stmt->bindValue(':id', $domaine->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM domaine WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
    public function getCount()
    {
        $sql = "SELECT COUNT(*) FROM domaine";
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
    }

}
?>