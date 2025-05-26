<?php

require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';

class FormationDALImpl implements IFormationDal
{
    private $pdo;

    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function create(Formation $formation)
    {
        $sql = "INSERT INTO formation (price, mode, coursId, formateurId, villeId) 
                VALUES (:price, :mode, :coursId, :formateurId, :villeId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':price', $formation->price);
        $stmt->bindValue(':mode', $formation->mode);
        $stmt->bindValue(':coursId', $formation->coursId);
        $stmt->bindValue(':formateurId', $formation->formateurId);
        $stmt->bindValue(':villeId', $formation->villeId);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM formation WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Formation($row['id'], $row['price'], $row['mode'], $row['coursId'], $row['formateurId'], $row['villeId']);
        }
        return null;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM formation";
        $stmt = $this->pdo->query($sql);
        $formations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $formations[] = new Formation($row['id'], $row['price'], $row['mode'], $row['coursId'], $row['formateurId'], $row['villeId']);
        }
        return $formations;
    }

    public function update(Formation $formation)
    {
        $sql = "UPDATE formation 
                SET price = :price, mode = :mode, coursId = :coursId, 
                    formateurId = :formateurId, villeId = :villeId 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':price', $formation->price);
        $stmt->bindValue(':mode', $formation->mode);
        $stmt->bindValue(':coursId', $formation->coursId);
        $stmt->bindValue(':formateurId', $formation->formateurId);
        $stmt->bindValue(':villeId', $formation->villeId);
        $stmt->bindValue(':id', $formation->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM formation WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function searchFormations($search = '', $domaineId = '', $sujetId = '', $coursId = '')
{
    $sql = "SELECT f.* FROM formation f 
            INNER JOIN cours c ON f.coursId = c.id
            INNER JOIN sujet s ON c.sujetId = s.id
            INNER JOIN domaine d ON s.domaineId = d.id
            WHERE 1=1";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND c.name LIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    if (!empty($domaineId)) {
        $sql .= " AND d.id = :domaineId";
        $params[':domaineId'] = $domaineId;
    }

    if (!empty($sujetId)) {
        $sql .= " AND s.id = :sujetId";
        $params[':sujetId'] = $sujetId;
    }

    if (!empty($coursId)) {
        $sql .= " AND c.id = :coursId";
        $params[':coursId'] = $coursId;
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    $formations = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $formations[] = new Formation(
            $row['id'],
            $row['price'],
            $row['mode'],
            $row['coursId'],
            $row['formateurId'],
            $row['villeId']
        );
    }

    return $formations;
}


public function getCount()
{
    $sql = "SELECT COUNT(*) FROM formation";
    $stmt = $this->pdo->query($sql);
    return (int)$stmt->fetchColumn();
}

}
