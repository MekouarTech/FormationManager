<?php

require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';

class PaysDALImpl implements IPaysDal
{
    private $pdo;

    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function create(Pays $pays)
    {
        $sql = "INSERT INTO pays (value) VALUES (:value)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $pays->value);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM pays WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Pays($row['id'], $row['value']);
        }
        return null;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM pays";
        $stmt = $this->pdo->query($sql);
        $paysList = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $paysList[] = new Pays($row['id'], $row['value']);
        }
        return $paysList;
    }

    public function update(Pays $pays)
    {
        $sql = "UPDATE pays SET value = :value WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $pays->value);
        $stmt->bindValue(':id', $pays->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM pays WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getVillesByPaysId($paysId)
    {
        $sql = "SELECT * FROM ville WHERE paysId = :paysId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':paysId', $paysId, PDO::PARAM_INT);
        $stmt->execute();

        $villes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $villes[] = new Ville($row['id'], $row['value'], $row['paysId']);
        }
        return $villes;
    }

    
    public function getCount()
    {
        $sql = "SELECT COUNT(*) FROM pays";
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
    }

}
?>