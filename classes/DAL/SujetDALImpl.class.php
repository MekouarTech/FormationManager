<?php

require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';

class SujetDALImpl implements ISujetDal
{
    private $pdo;

    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function create(Sujet $sujet)
    {
        $sql = "INSERT INTO sujet (name, shortDescription, longDescription, individualBenefit, businessBenefit, logo, domaineId)
                VALUES (:name, :shortDescription, :longDescription, :individualBenefit, :businessBenefit, :logo, :domaineId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $sujet->name);
        $stmt->bindValue(':shortDescription', $sujet->shortDescription);
        $stmt->bindValue(':longDescription', $sujet->longDescription);
        $stmt->bindValue(':individualBenefit', $sujet->individualBenefit);
        $stmt->bindValue(':businessBenefit', $sujet->businessBenefit);
        $stmt->bindValue(':logo', $sujet->logo, PDO::PARAM_LOB);
        $stmt->bindValue(':domaineId', $sujet->domaineId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM sujet WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Sujet(
                $row['id'],
                $row['name'],
                $row['shortDescription'],
                $row['longDescription'],
                $row['individualBenefit'],
                $row['businessBenefit'],
                $row['logo'],
                $row['domaineId']
            );
        }
        return null;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM sujet";
        $stmt = $this->pdo->query($sql);
        $sujets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sujets[] = new Sujet(
                $row['id'],
                $row['name'],
                $row['shortDescription'],
                $row['longDescription'],
                $row['individualBenefit'],
                $row['businessBenefit'],
                $row['logo'],
                $row['domaineId']
            );
        }
        return $sujets;
    }

    public function update(Sujet $sujet)
    {
        $sql = "UPDATE sujet SET name = :name, shortDescription = :shortDescription, longDescription = :longDescription,
                individualBenefit = :individualBenefit, businessBenefit = :businessBenefit, logo = :logo, domaineId = :domaineId
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $sujet->name);
        $stmt->bindValue(':shortDescription', $sujet->shortDescription);
        $stmt->bindValue(':longDescription', $sujet->longDescription);
        $stmt->bindValue(':individualBenefit', $sujet->individualBenefit);
        $stmt->bindValue(':businessBenefit', $sujet->businessBenefit);
        $stmt->bindValue(':logo', $sujet->logo, PDO::PARAM_LOB);
        $stmt->bindValue(':domaineId', $sujet->domaineId, PDO::PARAM_INT);
        $stmt->bindValue(':id', $sujet->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM sujet WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function getCount()
    {
        $sql = "SELECT COUNT(*) FROM sujet";
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
    }

}
?>