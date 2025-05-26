<?php

require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';

class VillesDALImpl implements IVilleDal
{
    private $pdo;

    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function create(Ville $ville)
    {
        $sql = "INSERT INTO Ville (value, paysId) VALUES (:value, :paysId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $ville->value);
        $stmt->bindValue(':paysId', $ville->paysId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM Ville WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Ville($row['id'], $row['value'], $row['paysId']);
        }
        return null;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM Ville";
        $stmt = $this->pdo->query($sql);
        $villes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $villes[] = new Ville($row['id'], $row['value'], $row['paysId']);
        }
        return $villes;
    }

    public function update(Ville $ville)
    {
        $sql = "UPDATE Ville SET value = :value, paysId = :paysId WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $ville->value);
        $stmt->bindValue(':paysId', $ville->paysId, PDO::PARAM_INT);
        $stmt->bindValue(':id', $ville->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Ville WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /*public function getPaysById($id)
    {
        $sql = "SELECT p.* FROM Ville v JOIN Pays p ON v.paysId = p.id WHERE v.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Pays($row['id'], $row['value']);
        }
        return null;
    }*/

    public function getPaysById($id)
    {
        $sql = "SELECT * FROM Pays WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row) {
            return new Pays($row['id'], $row['value']);
        }
        return null;
}


public function getCount()
{
    $sql = "SELECT COUNT(*) FROM ville";
    $stmt = $this->pdo->query($sql);
    return (int)$stmt->fetchColumn();
}

}
?>